<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

//------------------------------------------------------------------------------

use AppBundle\DependencyInjection\ShowFinderCompilerPass;

//------------------------------------------------------------------------------


class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ShowFinderCompilerPass);
    }
}
