<?php

namespace Brouzie\Specificator\Locator;

interface ResultSubscriber
{
    public const STAGE_QUERY = 'query';

    public const STAGE_RESULT = 'result';

    /**
     *     return [
     *         ProductListItem::class => [
     *             self::STAGE_QUERY => 'queryProductListItem',
     *         ],
     *         ProductDetailItem::class => [
     *             self::STAGE_QUERY => 'queryProductDetailItem',
     *             self::STAGE_RESULT => 'buildProductDetailItem',
     *         ],
     *     ]
     *
     * @return array
     */
    public static function getSubscribedResultItems(): array;
}
