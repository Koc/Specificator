<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Brouzie\Specificator\Bridge\Doctrine\ORM\ResultBuilder\ResultStageResultBuilder;
use Brouzie\Specificator\Result;
use Brouzie\Specificator\Specification;
use Brouzie\Specificator\SpecificationExecutor;
use Brouzie\Specificator\Subscriber\PaginationMapperLocator;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class DoctrineOrmSpecificationExecutor implements SpecificationExecutor
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
        DoctrineOrmFilterMapper $filterMapper,
        DoctrineOrmSortOrderMapper $sortOrderMapper,
        DelegatingPaginationMapper $paginationMapperLocator,
        DoctrineOrmResultBuilderLocator $resultBuilderLocator
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->entityClass = $entityClass;
        $this->filterMapper = $filterMapper;
        $this->sortOrderMapper = $sortOrderMapper;
        $this->paginationMapperLocator = $paginationMapperLocator;
        $this->resultBuilderLocator = $resultBuilderLocator;
    }

    public function execute(Specification $specification, string $resultItemClass): Result
    {
        $queryBuilder = $this->createQueryBuilder();

        $resultBuilder = $this->resultBuilderLocator->getResultBuilder($resultItemClass);
        $resultBuilder->modifyQuery($queryBuilder);

        $this->mapFilters($specification, $queryBuilder);
        $this->mapSortsOrders($specification, $queryBuilder);

        $pagination = $specification->getPagination();
        $paginationMapper = $this->paginationMapperLocator->getPaginationMapper($pagination);
        $paginationMapper->sliceQuery($pagination, $queryBuilder);

        $queryResult = $queryBuilder->getQuery()->getResult();

        $itemsCount = 0;
        if ($paginationMapper instanceof DoctrineOrmCountablePaginationMapper) {
            $itemsCount = $paginationMapper->getItemsCount($queryBuilder);
        }

        $items = $this->hydrateItems($resultBuilder, $queryResult);

        return new Result($items, $itemsCount, []);
    }

    private function createQueryBuilder(): QueryBuilder
    {
        $entityManager = $this->managerRegistry->getManagerForClass($this->entityClass);
        /** @var EntityRepository $entityRepository */
        $entityRepository = $entityManager->getRepository($this->entityClass);

        return $entityRepository->createQueryBuilder('entity');
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

    private function hydrateItems(DoctrineOrmResultBuilder $resultBuilder, $queryResult): array
    {
        $items = [];
        foreach ($resultBuilder->hydrateItems($queryResult) as $item) {
            $items[] = $item;
        }

        return $items;
    }
}
