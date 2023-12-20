<?php

namespace Survos\InspectionBundle\Services;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Survos\ApiGrid\State\MeiliSearchStateProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class InspectionService
{
    public function __construct(
        private readonly ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private readonly RouterInterface $router,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function getAllUrlsForResource(string $resourceClass): array
    {
        $urls = [];
        // yikes!  Now the route is here.


        foreach ($this->resourceMetadataCollectionFactory->create($resourceClass) as $resourceMetadata) {
            /** @var HttpOperation $operation */
            foreach ($resourceMetadata->getOperations() as $operation) {
                if ($operation::class == GetCollection::class) {
                    // we want the route, not the path
//                    $urls[$operation->getProvider()] = $operation->getName();
                    $route = $this->router->getRouteCollection()->get($operation->getName());
                    $params = [];
                    $provider = $operation->getProvider();
                    if ($provider === MeiliSearchStateProvider::class) {
                        $params['indexName'] = (new \ReflectionClass($resourceClass))->getShortName();
                    }
                    $url = $this->urlGenerator->generate($operation->getName(), $params);
                    $urls[$operation->getProvider()] = $url;
//                    dd($url, $operation, $route);

//                    $urls[$operation->getProvider()] = ->getPath();
//                } else {
//                    dump($operation::class);
                }

            }
        }
        return $urls;
    }


    public function getMeiliCollectionUrl(string $resourceClass): ?string
    {
        return $this->getAllUrlsForResource($resourceClass)[MeiliSearchStateProvider::class]??null;

        foreach ($this->resourceMetadataCollectionFactory->create($resourceClass) as $resourceMetadata) {
            foreach ($resourceMetadata->getOperations() as $operation) {
                if ($operation::class === GetCollection::class) {
                    $provider = $operation->getProvider();
                    if ($provider === MeiliSearchStateProvider::class) {
                    }
                }
            }

        }

    }
}
