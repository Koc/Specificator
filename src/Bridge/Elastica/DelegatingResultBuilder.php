<?php

namespace Brouzie\Specificator\Bridge\Elastica;

use Elastica\Query;
use Elastica\ResultSet;

class DelegatingResultBuilder implements ResultBuilder
{
    private $queryModifier;
    private $hydrator;

    public function __construct(?callable $queryModifier, callable $hydrator)
    {
        $this->queryModifier = $queryModifier;
        $this->hydrator = $hydrator;
    }

    public function modifyQuery(Query $query): void
    {
        if (null !== $this->queryModifier) {
            ($this->queryModifier)($query);
        }
    }

    public function hydrateItems(ResultSet $resultSet): iterable
    {
        foreach (($this->hydrator)($resultSet) as $item) {
            yield $item;
        }
    }
}
