<?php

namespace Brouzie\Specificator\Bridge\Symfony;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
interface SourceFactory
{
    public function defineConfig(NodeDefinition $builder): void;

    public function registerSource(ContainerBuilder $container, string $id, array $config): void;
}
