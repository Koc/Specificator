<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Brouzie\Specificator\Locator\FilterMapperLocator;
use Brouzie\Specificator\Locator\PaginationMapperLocator;
use Brouzie\Specificator\Locator\ResultBuilderLocator;
use Brouzie\Specificator\Locator\SortOrderMapperLocator;
use Brouzie\Specificator\QueryRepository;
use Brouzie\Specificator\Result;
use Brouzie\Specificator\Specification;
use Elastica\Query;
use Elastica\ResultSet;
use Elastica\SearchableInterface;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class ElasticaQueryRepository implements QueryRepository
{
    private $index;

    private $filterMapperLocator;

    private $sortOrderMapperLocator;

    private $paginationMapperLocator;

    private $resultBuilderLocator;

    public function __construct(
        SearchableInterface $index,
        FilterMapperLocator $filterMapperLocator,
        SortOrderMapperLocator $sortOrderMapperLocator,
        PaginationMapperLocator $paginationMapperLocator,
        ResultBuilderLocator $resultBuilderLocator
    ) {
        $this->index = $index;
        $this->filterMapperLocator = $filterMapperLocator;
        $this->sortOrderMapperLocator = $sortOrderMapperLocator;
        $this->paginationMapperLocator = $paginationMapperLocator;
        $this->resultBuilderLocator = $resultBuilderLocator;
    }

    public function query(Specification $specification, string $resultItemClass): Result
    {
        $query = new Query();
        $boolQuery = new Query\BoolQuery();
        $query->setQuery($boolQuery);

        $this->mapFilters($specification, $boolQuery);
        $this->mapSortsOrders($specification, $query);
        $this->mapPagination($specification, $query);

        $resultSet = $this->index->search($query);

        return $this->buildResult($resultSet, $resultItemClass);
    }

    private function mapFilters(Specification $specification, Query\BoolQuery $boolQuery): void
    {
        foreach ($specification->getFilters() as $filter) {
            /** @var FilterMapper $filterMapper */
            $filterMapper = $this->filterMapperLocator->getFilterMapper($filter);
            $filterMapper($filter, $boolQuery);
        }
    }

    private function mapSortsOrders(Specification $specification, Query $query): void
    {
        foreach ($specification->getSortOrders() as $sortOrder) {
            /** @var SortOrderMapper $sortOrderMapper */
            $sortOrderMapper = $this->sortOrderMapperLocator->getSortOrderMapper($sortOrder);
            $sortOrderMapper($sortOrder, $query);
        }
    }

    private function mapPagination(Specification $specification, Query $query): void
    {
        $pagination = $specification->getPagination();
        /** @var PaginationMapper $paginationMapper */
        $paginationMapper = $this->paginationMapperLocator->getPaginationMapper($pagination);
        $paginationMapper($pagination, $query);
    }

    private function buildResult(ResultSet $resultSet, string $resultItemClass): Result
    {
        /** @var ResultBuilder $resultBuilder */
        $resultBuilder = $this->resultBuilderLocator->getResultBuilder($resultItemClass);

        $items = [];
        foreach ($resultSet->getResults() as $elasticaResultItem) {
            $items[] = $resultBuilder($elasticaResultItem);
        }

        return new Result($items, $resultSet->getTotalHits());
    }
}
