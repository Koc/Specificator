<?php

namespace Brouzie\Specificator\Bridge\Symfony;

use Brouzie\Specificator\Bridge\Symfony\SourceFactory\DoctrineOrmSourceFactory;
use Brouzie\Specificator\Bridge\Symfony\DependencyInjection\BrouzieSpecificatorExtension;
use Brouzie\Specificator\Bridge\Symfony\SourceFactory\ElasticaSourceFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class BrouzieSpecificatorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $this->getExtension($container);
        $extension->addSourceFactory('doctrine_orm', new DoctrineOrmSourceFactory());
        $extension->addSourceFactory('elastica', new ElasticaSourceFactory());
    }

    private function getExtension(ContainerBuilder $container): BrouzieSpecificatorExtension
    {
        return $container->getExtension('brouzie_specificator');
    }
}
