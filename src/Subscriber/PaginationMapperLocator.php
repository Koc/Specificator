<?php

namespace Brouzie\Specificator\Subscriber;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 * @deprecated Create DelegatingPaginationMapper which uses ContainerInterface from Psr-11.
 */
interface PaginationMapperLocator
{
    public function getPaginationMapper(object $pagination): callable;
}
