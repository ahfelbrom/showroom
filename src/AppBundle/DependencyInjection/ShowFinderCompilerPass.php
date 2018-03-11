<?php

namespace AppBundle\DependencyInjection;

use AppBundle\ShowFinder\ShowFinder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;


class ShowFinderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // récupérer the definition of the service ShowFinder
        $showFinderDefinition = $container->findDefinition(ShowFinder::class);
        // récupération of tous les tagges services qui ont le tag "show.finder"
        $showFinderTaggedServices = $container->findTaggedServiceIds('show.finder');

        // boucle sur ces services
        foreach ($showFinderTaggedServices as $id => $showFinderTaggedService) {
            // création d'une référence a partir de l'id du service (AKA son namespace)
            $service = new Reference($id);
            // appel de la méthod addFinder sur le service ShowFinder afin d'y 
            // injecter le service taggué
            $showFinderDefinition->addMethodCall('addFinder', [$service]);
        }
    }
}