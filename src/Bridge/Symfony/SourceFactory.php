<?php

namespace Brouzie\Specificator\Bridge\Symfony;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface SourceFactory
{
    public function registerSourceConfig(NodeDefinition $builder);

    public function registerSource(ContainerBuilder $container, $id, $config);
}
