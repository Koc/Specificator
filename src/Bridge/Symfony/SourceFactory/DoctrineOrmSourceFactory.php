<?php

namespace Brouzie\Specificator\Bridge\Symfony\SourceFactory;

use Brouzie\Specificator\Bridge\Symfony\SourceFactory;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineOrmSourceFactory implements SourceFactory
{
    private const ENTITY = 'entity';

    public function defineConfig(NodeDefinition $builder): void
    {
        $builder
            ->children()
                ->scalarNode(self::ENTITY)->notNull()->end()
            ->end();
    }

    public function registerSource(ContainerBuilder $container, string $id, array $config): void
    {
        // register definition
        $config[self::ENTITY];
    }
}
