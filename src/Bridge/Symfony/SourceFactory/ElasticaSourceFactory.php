<?php

namespace Brouzie\Specificator\Bridge\Symfony\SourceFactory;

use Brouzie\Specificator\Bridge\Symfony\SourceFactory;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ElasticaSourceFactory implements SourceFactory
{
    private const SEARCHABLE_ID = 'searchable_id';

    public function defineConfig(NodeDefinition $builder): void
    {
        $builder
            ->children()
                ->scalarNode(self::SEARCHABLE_ID)->notNull()->end()
            ->end();
    }

    public function registerSource(ContainerBuilder $container, string $id, array $config): void
    {
        // register definition
        $config[self::SEARCHABLE_ID];
    }
}
