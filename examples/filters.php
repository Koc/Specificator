<?php

namespace App\Query\Http\Request;

use App\Query\Http\SpecificationBuilder\LimitOffsetPaginatedRequest;

class GetProductsFilter
{
    public $sku;

    public $priceFrom;

    public $priceTo;
}

class GetProductsQuery implements LimitOffsetPaginatedRequest
{
    /**
     * @var GetProductsFilter
     */
    public $filter;

    public $sort = [];

    public $limit = 20;

    public $offset = 0;

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}

namespace App\Query\Http\SpecificationBuilder;

use App\Query\Http\Request\GetProductsQuery;
use App\Query\Specification\Filter\PriceRange;
use App\Query\Specification\Filter\Sku;
use App\Query\Specification\SortOrder\Latest;
use App\Query\Specification\SortOrder\Price;
use App\Query\Specification\SortOrder\Sale;
use Brouzie\Specificator\Pagination\LimitOffsetPagination;
use Brouzie\Specificator\SortOrder\SupportsInverse;
use Brouzie\Specificator\Specification;

class FilterSpecificationBuilder
{
    public function __invoke(Specification $specification, GetProductsQuery $request): void
    {
        if ($request->filter->sku) {
            $specification->addFilter(new Sku($request->filter->sku));
        }

        if ($request->filter->priceFrom || $request->filter->priceTo) {
            $specification->addFilter(new PriceRange($request->filter->priceTo, $request->filter->priceTo));
        }
    }
}

interface SortedPaginationRequest
{
    public function getSortOrders(): array;
}

class SortOrderSpecificationBuilder
{
    private const SORT_ORDERS_MAPPING = [
        'sale' => Sale::class,
        'latest' => Latest::class,
        'price' => Price::class,
    ];

    public function __invoke(Specification $specification, SortedPaginationRequest $request): void
    {
        foreach ($request->getSortOrders() as $sortOrder => $direction) {
            if (!isset(static::SORT_ORDERS_MAPPING[$sortOrder])) {
                throw new \OutOfBoundsException();
            }

            $sortOrderClass = static::SORT_ORDERS_MAPPING[$sortOrder];
            if (is_a($sortOrderClass, SupportsInverse::class)) {
                $sortOrderObject = $sortOrderClass::create('DESC' === $direction);
            } else {
                $sortOrderObject = new $sortOrderClass();
            }

            $specification->addSortOrder($sortOrderObject);
        }
    }
}

interface LimitOffsetPaginatedRequest
{
    public function getLimit(): int;

    public function getOffset(): int;
}

class PaginationSpecificationBuilder
{
    public function __invoke(Specification $specification, LimitOffsetPaginatedRequest $request): void
    {
        $specification->setPagination(new LimitOffsetPagination($request->getLimit(), $request->getOffset()));
    }
}

namespace App\Query\Specification\Filter;

class Sku
{
    private $sku;

    public function __construct(string $sku)
    {
        $this->sku = $sku;
    }

    public function getSku(): string
    {
        return $this->sku;
    }
}

class PriceRange
{
    private $from;

    private $to;

    public function __construct(int $from, int $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function getTo(): int
    {
        return $this->to;
    }
}

namespace App\Query\Specification\SortOrder;

use Brouzie\Specificator\SortOrder\SupportsInverse;

class Price implements SupportsInverse
{
    private $inverse;

    public function __construct(bool $inverse)
    {
        $this->inverse = $inverse;
    }

    public static function create(?bool $inverse)
    {
        return new self($inverse ?? false);
    }

    public function isInverse(): bool
    {
        return $this->inverse;
    }
}

class Sale
{
    //
}

class Latest
{
    //
}

namespace App\Query\Specification\Aggregation;

class CategoryIdAggregation
{
    private $limit;

    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}

namespace App\Query\Result;

class ProductItem
{
    private $id;

    private $name;

    private $sku;

    private $qty;

    public function __construct(int $id, string $name, string $sku, int $qty)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sku = $sku;
        $this->qty = $qty;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getQty(): int
    {
        return $this->qty;
    }
}

class ProductCategoryAggregationResult
{
    private $items;

    public function __construct(ProductCategoryAggregationItem ...$items)
    {
        $this->items = $items;
    }

    /**
     * @return ProductCategoryAggregationItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}

class ProductCategoryAggregationItem
{
    private $categoryId;

    private $productsCount;

    public function __construct(int $categoryId, int $productsCount)
    {
        $this->categoryId = $categoryId;
        $this->productsCount = $productsCount;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getProductsCount(): int
    {
        return $this->productsCount;
    }
}

namespace App\Query\Specification\Schema;

interface Product
{
    public const SKU = 'sku';

    public const PRICE = 'price';
}

namespace App\Query\Specification\Mapper\Elastica;

use App\Query\Result\ProductCategoryAggregationItem;
use App\Query\Result\ProductCategoryAggregationResult;
use App\Query\Result\ProductItem;
use App\Query\Specification\Aggregation\CategoryIdAggregation;
use App\Query\Specification\Filter\PriceRange;
use App\Query\Specification\Filter\Sku;
use App\Query\Specification\Schema\Product;
use Brouzie\Specificator\Locator\FilterSubscriber;
use Brouzie\Specificator\Locator\ResultSubscriber;
use Brouzie\Specificator\QueryRepository;
use Brouzie\Specificator\Specification;
use Doctrine\ORM\QueryBuilder;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use Elastica\Query\Term;

class FilterMapper implements FilterSubscriber
{
    public static function getMappedFilters(): array
    {
        return [
            Sku::class => 'mapSkuFilter',
            PriceRange::class => 'mapPriceFilter',
        ];
    }

    public function mapSkuFilter(Sku $filter, BoolQuery $boolQuery): void
    {
        $boolQuery->addFilter(
            new Term(
                [
                    Product::SKU => $filter->getSku(),
                ]
            )
        );
    }

    public function mapPriceFilter(PriceRange $filter, BoolQuery $boolQuery): void
    {
        $boolQuery->addFilter(
            new Range(
                Product::SKU,
                [
                    'gte' => $filter->getFrom(),
                    'lte' => $filter->getTo(),
                ]
            )
        );
    }
}

class ResultBuilder implements ResultSubscriber
{
    private $inventoryRepository;

    public static function getSubscribedResultItems(): array
    {
        return [
            ProductItem::class => [
                self::STAGE_QUERY => 'queryProductItem',
                self::STAGE_RESULT => 'buildProductItem',
            ],
        ];
    }

    public function __construct(QueryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    public function queryProductItem(QueryBuilder $queryBuilder): void
    {
        $queryBuilder
            ->select('id')
            ->addSelect('sku')
            ->addSelect('name');
    }

    /**
     * @param array[] $result
     *
     * @return ProductItem[]
     */
    public function buildProductItem(array $result): array
    {
        $productIds = array_column($result, 'id');

        $getQtySpecification = new Specification([new ProductIdFilter($productIds)]);
        $qtyItems = $this->inventoryRepository->query($getQtySpecification, ProductQty::class)->getItems();

        $result = [];
        foreach ($result as $row) {
            $id = $row['id'];
            $qty = $qtyItems[$id];
            $result[] = new ProductItem($id, $row['name'], $row['sku'], $qty);
        }

        return $result;
    }
}

class CategoryIdAggregator implements AggregationSubscriber
{
    public static function getSubscribedAggregations(): array
    {
        return [
            CategoryIdAggregation::class => [
                self::STAGE_BUILD_QUERY => 'buildQuery',
                self::STAGE_HYDRATE_RESULT => 'hydrateResult',
            ],
        ];
    }

    public function buildQuery(CategoryIdAggregation $aggregation, Query $query, string $prefix): void
    {
        $agg = (new Terms($prefix.'caregories'))
            ->setField('category_id')
            ->setSize($aggregation->getLimit());

        $query->addAggregation($agg);
    }

    public function hydrateResult(array $aggs): ProductCategoryAggregationResult
    {
        $items = [];
        foreach ($aggs['categories']['buckets'] as $bucket) {
            $items[] = new ProductCategoryAggregationItem($bucket['key'], $bucket['doc_count']);
        }

        return new ProductCategoryAggregationResult(...$items);
    }
}

namespace App\Controller;

use App\Query\Http\Request\GetProductsQuery;
use App\Query\Result\ProductCategoryAggregationResult;
use App\Query\Result\ProductItem;
use App\Query\Specification\Aggregation\CategoryIdAggregation;
use Brouzie\Specificator\Http\SpecificationFactory;
use Brouzie\Specificator\QueryRepository;

class GetProducts
{
    private $specificationFactory;

    private $queryRepository;

    public function __construct(SpecificationFactory $specificationFactory, QueryRepository $queryRepository)
    {
        $this->specificationFactory = $specificationFactory;
        $this->queryRepository = $queryRepository;
    }

    public function __invoke(GetProductsQuery $request): GetProductsResponse
    {
        $specification = $this->specificationFactory->createSpecification($request);
        $specification->addAggregation('category_ids', new CategoryIdAggregation(10));

        $result = $this->queryRepository->query($specification, ProductItem::class);

        /** @var ProductCategoryAggregationResult $categoryIdAggregation */
        $categoryIdAggregation = $result->getAggregation('category_ids');

        return new GetProductsResponse(
            $result->getItems(),
            $result->getTotalItemsCount(),
            $categoryIdAggregation->getItems()
        );
    }
}

class GetProductsResponse
{
    private $items;

    private $totalCount;

    private $categoryIdAggregation;

    /**
     * @param $items
     * @param $totalCount
     * @param $categoryIdAggregation
     */
    public function __construct($items, $totalCount, $categoryIdAggregation)
    {
        $this->items = $items;
        $this->totalCount = $totalCount;
        $this->categoryIdAggregation = $categoryIdAggregation;
    }


}
