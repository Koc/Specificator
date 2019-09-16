<?php

namespace Brouzie\Specificator\Subscriber;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface FilterSubscriber
{
    /**
     *     return [
     *         PriceRangeFilter::class => 'mapPriceRangeFilter',
     *         StockFilter::class => 'mapStockFilter',
     *     ]
     *
     * @return array
     */
    public static function getSubscribedFilters(): array;
}
