<?php

namespace Brouzie\Specificator\Bridge\Symfony;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BrouzieSpecificatorExtension extends Extension implements PrependExtensionInterface
{
    private $factories;

    public function load(array $configs, ContainerBuilder $container)
    {
        if (!array_filter($configs)) {
            return;
        }
    }

    public function addSourceFactory(SourceFactory $factory)
    {
        $this->factories[] = $factory;
    }
}
