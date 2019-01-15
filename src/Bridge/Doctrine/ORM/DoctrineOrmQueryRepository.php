<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Brouzie\Specificator\Bridge\Doctrine\ORM\ResultBuilder\ResultStageResultBuilder;
use Brouzie\Specificator\Locator\FilterMapperLocator;
use Brouzie\Specificator\Locator\PaginationMapperLocator;
use Brouzie\Specificator\Locator\ResultBuilderLocator;
use Brouzie\Specificator\Locator\SortOrderMapperLocator;
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

    private $filterMapperLocator;

    private $sortOrderMapperLocator;

    private $paginationMapperLocator;

    private $resultBuilderLocator;

    public function __construct(
        ManagerRegistry $managerRegistry,
        string $entityClass,
        FilterMapperLocator $filterMapperLocator,
        SortOrderMapperLocator $sortOrderMapperLocator,
        PaginationMapperLocator $paginationMapperLocator,
        ResultBuilderLocator $resultBuilderLocator
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->entityClass = $entityClass;
        $this->filterMapperLocator = $filterMapperLocator;
        $this->sortOrderMapperLocator = $sortOrderMapperLocator;
        $this->paginationMapperLocator = $paginationMapperLocator;
        $this->resultBuilderLocator = $resultBuilderLocator;
    }

    public function query(Specification $specification, string $resultItemClass): Result
    {
        $resultBuilder = $this->resultBuilderLocator->getResultBuilder($resultItemClass);

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
            /** @var FilterMapper $filterMapper */
            $filterMapper = $this->filterMapperLocator->getFilterMapper($filter);
            $filterMapper($filter, $queryBuilder);
        }
    }

    private function mapSortsOrders(Specification $specification, QueryBuilder $queryBuilder): void
    {
        foreach ($specification->getSortOrders() as $sortOrder) {
            /** @var SortOrderMapper $sortOrderMapper */
            $sortOrderMapper = $this->sortOrderMapperLocator->getSortOrderMapper($sortOrder);
            $sortOrderMapper($sortOrder, $queryBuilder);
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
        $resultBuilder = $this->resultBuilderLocator->getResultBuilder($resultItemClass);

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
}
