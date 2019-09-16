<?php

namespace Brouzie\Specificator\Bridge\Doctrine\ORM;

use Brouzie\Specificator\Bridge\Symfony\SourceFactory;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineOrmSourceFactory implements SourceFactory
{
    public function registerSourceConfig(NodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('entity')->notNull()->end()
            ->end();
    }

    public function registerSource(ContainerBuilder $container, $id, $config)
    {
        // register definition
    }
}
