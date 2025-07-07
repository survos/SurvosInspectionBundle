<?php

namespace Survos\InspectionBundle\Controller;

use cebe\openapi\spec\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Attribute\Route;

class InspectionController extends AbstractController
{
    #[Route('/analysis/json-ld', name: 'inspection_analysis_jsonld')]
    public function jsonld(ParameterBagInterface $bag): Response
    {

        // bin/console api:openapi:export -o openapi.json
        // https://api-platform.com/docs/core/openapi/ for more options
        $fn = $bag->get('kernel.project_dir') . '/openapi.json';

        //if file does not exist, return a 200 with an empty text response
        if (!file_exists($fn)) {
            return new Response('', 200, ['Content-Type' => 'application/json']);
        }

//        $openapi = Reader::readFromJsonFile($fn);
//        $spec = $openapi->getSerializableData();

        $data = json_decode(file_get_contents($fn));
        $data = json_decode(json_encode($data), true);
//        dd($data);
        // get all the entity classes that implement some core class

//        $spec = $openapi->getSerializableData();
//        dd($spec->components->schemas);
        /** @var Schema $schema */
//        foreach ($openapi->components->schemas as $schema) {
//            dd($schema); // , $schema->getDescription());
//            dump($openapi, $schema, $schema->description, $schema->example, $schema->getBaseDocument());
//            $schemaProperties = $accessor->getValue($schema, 'properties');
//            dd($schemaProperties, $schema, $details); // , $class, $details[$class]);
//        }

        return $this->render('@SurvosInspection/entities.html.twig', [
            'openapi' => $openapi??null,
            'spec' => $data
//            'schemas' => $openapi->getSerializableData()->components->schemas, // $openapi->components->schemas,
        ]);
    }

    #[Route('/analysis/formio', name: 'inspection_analysis_formio')]
    public function formio(): Response
    {
        return $this->render('analysis/formio.html.twig', [
        ]);

    }


    #[Route('/analysis/reflection', name: 'inspection_reflection')]
    public function index(
        #[MapQueryParameter] string $fullClass
    ): Response
    {

//        $classes = ClassFinder::getClassesInNamespace('App\Entity\Lls');
//        $classes = [\App\Entity\Transcript::class];
        $classes = [$fullClass];

        $details = array_reduce($classes, function ($carry, $class) {
//            $em = $registry->getManagerForClass($class);
//            /** @var EventManager $evm */
//            $evm = $em->getEventManager();
//            $eventArgs = new LoadClassMetadataEventArgs($class, $em);
//            $evm->dispatchEvent(Events::loadClassMetadata, $eventArgs);
//            dd($eventArgs);
//
            $carry[$class] = new \ReflectionClass($class);
            return $carry;
        }, []);
        $accessor = new PropertyAccessor();
        return $this->render('@SurvosInspection/reflection.html.twig', [
            'classes' => $details,
        ]);

    }

}
