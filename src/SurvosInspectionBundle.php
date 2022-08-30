<?php

namespace Survos\InspectionBundle;

use ApiPlatform\Api\IriConverterInterface;
use Survos\InspectionBundle\Twig\TwigExtension;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\WebpackEncoreBundle\Twig\StimulusTwigExtension;
use Twig\Environment;

class SurvosInspectionBundle extends AbstractBundle
{

    protected string $extensionAlias = 'survos_inspection';

    /** @param array<mixed> $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
            $definition = $builder
                ->setDefinition('survos.inspection_twig', new Definition(TwigExtension::class))
                ->addTag('twig.extension')
                ->setPublic(false)
            ;
//                dd($reference);
//        $reference = new Reference('api_platform.iri_converter.legacy');
//        $definition->setArgument('$iriConverter', $reference);
        $reference = new Reference('api_platform.iri_converter');
        $definition->setArgument('$iriConverter', $reference);
//                if (!$reference->getInvalidBehavior()) {
//                    dd($reference);
//                    $definition->setArgument('$iriConverter', $reference);
//                } else {
//                    $reference = new Reference('api_platform.iri_converter.legacy');
//                    $definition->setArgument('$iriConverter', $reference);
//                }
//                dd($reference);
        if (class_exists(Environment::class) && class_exists(IriConverterInterface::class)) {

        }
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        // since the configuration is short, we can add it here
        $definition->rootNode()
            ->children()
            ->booleanNode('debug')->defaultValue(false)->end()
            ?->end();
        ;
    }

}
