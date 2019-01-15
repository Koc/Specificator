<?php

namespace Brouzie\Specificator\Locator;

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
    public static function getMappedFilters(): array;
}
