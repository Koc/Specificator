<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Brouzie\Specificator\Bridge\Elastica\Paginator\QueryStageSlicer;
use Brouzie\Specificator\QueryRepository;
use Brouzie\Specificator\Result;
use Brouzie\Specificator\Specification;
use Brouzie\Specificator\Subscriber\FilterMapperLocator;
use Brouzie\Specificator\Subscriber\PaginationMapperLocator;
use Brouzie\Specificator\Subscriber\SortOrderMapperLocator;
use Elastica\Query;
use Elastica\ResultSet;
use Elastica\SearchableInterface;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class ElasticaQueryRepository implements QueryRepository
{
    private $index;
    private $filterMapper;
    private $sortOrderMapper;
    private $paginationMapperLocator;
    private $resultBuilderLocator;

    public function __construct(
        SearchableInterface $index,
        FilterMapper $filterMapper,
        SortOrderMapper $sortOrderMapper,
        PaginationMapperLocator $paginationMapperLocator,
        ResultBuilderLocator $resultBuilderLocator
    ) {
        $this->index = $index;
        $this->filterMapper = $filterMapper;
        $this->sortOrderMapper = $sortOrderMapper;
        $this->paginationMapperLocator = $paginationMapperLocator;
        $this->resultBuilderLocator = $resultBuilderLocator;
    }

    public function query(Specification $specification, string $resultItemClass): Result
    {
        $resultBuilder = $this->resultBuilderLocator->getResultBuilder($resultItemClass);

        $query = $this->createQuery();
        $resultBuilder->applyQueryStage($query);

        $this->mapFilters($specification, $query->getQuery());
        $this->mapSortsOrders($specification, $query);
        $this->mapPagination($specification, $query);

        $resultSet = $this->index->search($query);

        $items = [];
        foreach ($resultBuilder->hydrateItems($resultSet) as $item) {
            $items[] = $item;
        }

        //TODO: provide support of the aggregations (query, hydrate)

        return new Result($items, $resultSet->getTotalHits(), []);
    }

    private function mapFilters(Specification $specification, Query\BoolQuery $boolQuery): void
    {
        foreach ($specification->getFilters() as $filter) {
            $this->filterMapper->mapFilter($filter, $boolQuery);
        }
    }

    private function mapSortsOrders(Specification $specification, Query $query): void
    {
        foreach ($specification->getSortOrders() as $sortOrder) {
            $this->sortOrderMapper->mapSortOrder($sortOrder, $query);
        }
    }

    private function mapPagination(Specification $specification, Query $query): void
    {
        $pagination = $specification->getPagination();
        /** @var QueryStageSlicer $paginationMapper */
        $paginationMapper = $this->paginationMapperLocator->getPaginationMapper($pagination);
        $paginationMapper($pagination, $query);
    }

    private function createQuery(): Query
    {
        $query = new Query();
        $query->setQuery(new Query\BoolQuery());

        return $query;
    }
}
