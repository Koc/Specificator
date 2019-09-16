<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Brouzie\Specificator\Bridge\Doctrine\ORM\ResultBuilder\ResultStageResultBuilder;
use Brouzie\Specificator\Bridge\Elastica\ResultBuilder;
use Brouzie\Specificator\Subscriber\PaginationMapperLocator;
use Brouzie\Specificator\Bridge\Elastica\ResultBuilderLocator;
use Brouzie\Specificator\Result;
use Brouzie\Specificator\Specification;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class DoctrineOrmQueryRepository
{
    private $managerRegistry;
    private $entityClass;
    private $filterMapper;
    private $sortOrderMapper;
    private $paginationMapperLocator;
    private $resultBuilderLocator;

    public function __construct(
        ManagerRegistry $managerRegistry,
        string $entityClass,
        FilterMapper $filterMapper,
        SortOrderMapper $sortOrderMapper,
        PaginationMapperLocator $paginationMapperLocator,
        ResultBuilderLocator $resultBuilderLocator
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->entityClass = $entityClass;
        $this->filterMapper = $filterMapper;
        $this->sortOrderMapper = $sortOrderMapper;
        $this->paginationMapperLocator = $paginationMapperLocator;
        $this->resultBuilderLocator = $resultBuilderLocator;
    }

    public function query(Specification $specification, string $resultItemClass): Result
    {
        $resultBuilder = $this->getResultBuilder($resultItemClass);

        $queryBuilder = $this->createQueryBuilder();
        if ($queryStageResultBuilder = $resultBuilder->getQueryStage()) {
            $queryStageResultBuilder($queryBuilder);
        }

        $this->mapFilters($specification, $queryBuilder);
        $this->mapSortsOrders($specification, $queryBuilder);
        $this->mapPagination($specification, $queryBuilder);

        $queryResult = $queryBuilder->getQuery()->getResult();
        $result = $queryResult;
        if ($resultStageResultBuilder = $resultBuilder->getResultStage()) {
            $result = $resultStageResultBuilder($result);
        }

        //TODO: implement pagination count query
        $paginationResult = 0;

        return $this->buildResult($queryResult, $paginationResult, $resultItemClass);
    }

    private function mapFilters(Specification $specification, QueryBuilder $queryBuilder): void
    {
        foreach ($specification->getFilters() as $filter) {
            $this->filterMapper->mapFilter($filter, $queryBuilder);
        }
    }

    private function mapSortsOrders(Specification $specification, QueryBuilder $queryBuilder): void
    {
        foreach ($specification->getSortOrders() as $sortOrder) {
            $this->sortOrderMapper->mapSortOrder($sortOrder, $queryBuilder);
        }
    }

    private function mapPagination(Specification $specification, QueryBuilder $queryBuilder): void
    {
        $pagination = $specification->getPagination();
        /** @var PaginationMapper $paginationMapper */
        $paginationMapper = $this->paginationMapperLocator->getPaginationMapper($pagination);
        $paginationMapper($pagination, $queryBuilder);
    }

    private function buildResult(array $queryResult, int $paginationResult, string $resultItemClass): Result
    {
        /** @var ResultStageResultBuilder $resultBuilder */
        $resultBuilder = $this->getResultBuilder($resultItemClass);

        $items = [];
        foreach ($queryResult as $doctrineResultItem) {
            $items[] = $resultBuilder($doctrineResultItem);
        }

        return new Result($items, $paginationResult);
    }

    private function createQueryBuilder(): QueryBuilder
    {
        $entityManager = $this->managerRegistry->getManagerForClass($this->entityClass);
        /** @var EntityRepository $entityRepository */
        $entityRepository = $entityManager->getRepository($this->entityClass);

        return $entityRepository->createQueryBuilder('entity');
    }

    private function getResultBuilder(string $resultItemClass): ResultBuilder
    {
        return $this->resultBuilderLocator->getResultBuilder($resultItemClass);
    }
}
