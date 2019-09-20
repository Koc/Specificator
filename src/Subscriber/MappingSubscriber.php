<?php

namespace Brouzie\Specificator\Subscriber;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface MappingSubscriber
{
    /**
     * @return AggregationSubscriber[]|FilterSubscription[]|ResultSubscription[]|SortOrderSubscription[]
     */
    public static function getSubscriptions(): iterable;
}
