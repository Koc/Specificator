<?php

namespace Brouzie\Specificator\Bridge\Symfony\SourceFactory;

use Brouzie\Specificator\Bridge\Symfony\SourceFactory;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ElasticaSourceFactory implements SourceFactory
{
    public function registerSourceConfig(NodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('searchable_id')->notNull()->end()
            ->end();
    }

    public function registerSource(ContainerBuilder $container, $id, $config)
    {
        // register definition
    }
}
