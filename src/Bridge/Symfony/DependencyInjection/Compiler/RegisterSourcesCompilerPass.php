<?php

namespace Brouzie\Specificator\Bridge\Symfony\DependencyInjection\Compiler;

//TODO: does it needed?
class RegisterSourcesCompilerPass
{
    public function process()
    {
        foreach ($container->getParameter('brouzie_specificator.sources') as $name => $config) {

        }
    }
}
