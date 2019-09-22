<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Brouzie\Specificator\SpecificationExecutor;
use Brouzie\Specificator\Result;
use Brouzie\Specificator\Specification;
use Elastica\Query;
use Elastica\ResultSet;
use Elastica\SearchableInterface;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class ElasticaSpecificationExecutor implements SpecificationExecutor
{
    private $index;
    private $filterMapper;
    private $sortOrderMapper;
    private $paginationMapperLocator;
    private $resultBuilderLocator;

    public function __construct(
        SearchableInterface $index,
        ElasticaFilterMapper $filterMapper,
        ElasticaSortOrderMapper $sortOrderMapper,
        DelegatingPaginationMapper $paginationMapperLocator,
        ElasticaResultBuilderLocator $resultBuilderLocator
    ) {
        $this->index = $index;
        $this->filterMapper = $filterMapper;
        $this->sortOrderMapper = $sortOrderMapper;
        $this->paginationMapperLocator = $paginationMapperLocator;
        $this->resultBuilderLocator = $resultBuilderLocator;
    }

    public function execute(Specification $specification, string $resultItemClass): Result
    {
        $query = $this->createQuery();

        $resultBuilder = $this->resultBuilderLocator->getResultBuilder($resultItemClass);
        $resultBuilder->modifyQuery($query, $specification);

        $this->mapFilters($specification, $query->getQuery());
        $this->mapSortsOrders($specification, $query);

        $pagination = $specification->getPagination();
        $paginationMapper = $this->paginationMapperLocator->getPaginationMapper($pagination);
        $paginationMapper->sliceQuery($pagination, $query);

        $resultSet = $this->index->search($query);

        $items = $this->hydrateItems($resultBuilder, $resultSet);

        //TODO: provide support of the aggregations (query, hydrate)

        return new Result($items, $paginationMapper->getResultsCount($resultSet), []);
    }

    private function createQuery(): Query
    {
        $query = new Query();
        $query->setQuery(new Query\BoolQuery());

        return $query;
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

    private function hydrateItems(ElasticaResultBuilder $resultBuilder, $resultSet): array
    {
        $items = [];
        foreach ($resultBuilder->hydrateItems($resultSet) as $item) {
            $items[] = $item;
        }

        return $items;
    }
}
