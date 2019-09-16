<?php

namespace Brouzie\Specificator\Subscriber;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface FilterSubscriber
{
    /**
     * @return FilterSubscription[]
     */
    public static function getSubscribedFilters(): iterable;
}
