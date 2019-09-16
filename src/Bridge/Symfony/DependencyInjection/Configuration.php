<?php

namespace Brouzie\Specificator\Bridge\Symfony\DependencyInjection;

use Brouzie\Specificator\Bridge\Symfony\SourceFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private $factories;

    /**
     * @param SourceFactory[] $factories
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('brouzie_specificator');
        $rootNode = $builder->getRootNode();
        //TODO: add sources (source: doctrine, config: {entity: App\Product\Domain\AggregateRoot\Product}

        foreach ($this->factories as $factory) {
            $factory->registerSourceConfig($rootNode);
        }
    }
}
