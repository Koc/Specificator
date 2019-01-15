<?php

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

namespace App\Query\Specification\Schema;

interface Product
{
    public const SKU = 'sku';

    public const PRICE = 'price';
}

namespace App\Query\Specification\Mapper\Elastica;

use App\Query\Result\ProductItem;
use App\Query\Specification\Filter\PriceRange;
use App\Query\Specification\Filter\Sku;
use App\Query\Specification\Schema\Product;
use Brouzie\Specificator\Locator\FilterSubscriber;
use Brouzie\Specificator\Locator\ResultSubscriber;
use Brouzie\Specificator\QueryRepository;
use Brouzie\Specificator\Specification;
use Doctrine\ORM\QueryBuilder;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use Elastica\Query\Term;

class FilterMapper implements FilterSubscriber
{
    public static function getMappedFilters(): iterable
    {
        //TODO: automatically determinate all filter mappers using reflection (public methods starts from "map")
        yield Sku::class => 'mapSkuFilter';

        yield PriceRange::class => 'mapPriceFilter';
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

namespace App\Controller;

use App\Query\Result\ProductItem;
use App\Query\Specification\Filter\PriceRange;
use App\Query\Specification\Filter\Sku;
use Brouzie\Specificator\QueryRepository;
use Brouzie\Specificator\Specification;

class GetProducts
{
    private $queryRepository;

    public function __construct(QueryRepository $queryRepository)
    {
        $this->queryRepository = $queryRepository;
    }

    public function __invoke()
    {
        $specification = new Specification();
        if (!empty($_GET['filters']['sku'])) {
            $specification->addFilter(new Sku($_GET['filters']['sku']));
        }

        if (!empty($_GET['filters']['price'])) {
            $specification->addFilter(
                new PriceRange(
                    $_GET['filters']['price']['from'],
                    $_GET['filters']['price']['to']
                )
            );
        }

        return $this->queryRepository->query($specification, ProductItem::class);
    }
}
