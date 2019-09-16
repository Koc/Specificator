<?php

namespace Brouzie\Specificator\Bridge\Symfony;

use Brouzie\Specificator\Bridge\Doctrine\ORM\DoctrineOrmSourceFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Konstantin Myakshin <molodchick@gmail.com>
 */
class BrouzieSpecificatorBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('brouzie_specificator');
        $extension->addSourceFactory(new DoctrineOrmSourceFactory());
    }
}
