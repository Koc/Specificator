<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Query;
use Elastica\ResultSet;

class DelegatingResultBuilder implements ResultBuilder
{
    private $queryStage;
    private $hydrator;

    public function __construct(?callable $queryStage, callable $hydrator)
    {
        $this->queryStage = $queryStage;
        $this->hydrator = $hydrator;
    }

    public function applyQueryStage(Query $query): void
    {
        if (null !== $this->hydrator) {
            ($this->hydrator)($query);
        }
    }

    public function hydrateItems(ResultSet $resultSet): iterable
    {
        foreach (($this->hydrator)($resultSet) as $item) {
            yield $item;
        }
    }
}
