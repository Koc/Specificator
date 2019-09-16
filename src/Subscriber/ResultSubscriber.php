<?php

namespace Brouzie\Specificator\Subscriber;

interface ResultSubscriber
{
    /**
     * @return ResultSubscription[]
     */
    public static function getSubscribedResultItems(): iterable;
}
