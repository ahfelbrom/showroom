<?php

namespace AppBundle\DependencyInjection;

use AppBundle\ShowFinder\ShowFinder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ShowFinderCompilerPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{
		// Récupérer la défintion du service ShowFinder afin de lui ajouter les services taggués plus bas
		$showFinderDefinition = $container->findDefinition(ShowFinder::class);

		// Récupérer tous les noms de servies (appelé id dans Symfony) ayant
		// comme tag 'show.finder'.
		$showFinderTaggedServices = $container->findTaggedServiceIds('show.finder');


		// Pour tous les id de services ayant pour tag 'show.finder'
		foreach ($showFinderTaggedServices as $showFinderTaggedServiceId => $showFinderTags) {

			// Créer une référence (représentation d'un service dans Symfony) avec l'id du service taggué
			$serviceReference = new Reference($showFinderTaggedServiceId);

			// Demander à appeler la méthode `addFinder` sur le service AppBundle\ShowFinder\ShowFinder
			// afin d'y injecter le service taggué (soit AppBundle\ShowFinder\OMDBFinder et  AppBundle\ShowFinder\DBFinder).
			$showFinderDefinition->addMethodCall('addFinder', [$serviceReference]);
		}
	}
}