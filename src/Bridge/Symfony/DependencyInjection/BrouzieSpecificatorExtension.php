<?php

namespace Brouzie\Specificator\Bridge\Symfony\DependencyInjection;

use Brouzie\Specificator\Bridge\Symfony\SourceFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class BrouzieSpecificatorExtension extends Extension implements PrependExtensionInterface
{
    private $factories;

    public function load(array $configs, ContainerBuilder $container)
    {
        if (!array_filter($configs)) {
            return;
        }
    }

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($this->factories);
    }

    public function addSourceFactory(SourceFactory $factory): self
    {
        $this->factories[] = $factory;

        return $this;
    }
}
