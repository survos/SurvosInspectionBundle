<?php

namespace Survos\InspectionBundle\Services;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Symfony\Component\Routing\RouterInterface;

class InspectionService
{
    public function __construct(
        private readonly ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private readonly RouterInterface $router,
    ) {
    }

    public function getAllUrlsForResource(string $resourceClass): array
    {
        $urls = [];

        foreach ($this->resourceMetadataCollectionFactory->create($resourceClass) as $resourceMetadata) {
            /** @var HttpOperation $operation */
            foreach ($resourceMetadata->getOperations() as $operation) {
                if ($operation::class == GetCollection::class) {
                    dump($operation->getProvider(), $operation->getName());
                    $urls[] = $this->router->getRouteCollection()->get($operation->getName())->getPath();
                } else {
//                    dump($operation::class);
                }

            }
        }
        dd($urls);

        return $urls;
    }

    public function getMeiliCollectionUrl(string $resourceClass): ?string
    {
        foreach ($this->resourceMetadataCollectionFactory->create($resourceClass) as $resourceMetadata) {
            foreach ($resourceMetadata->getOperations() as $operation) {
                if ($operation::class === GetCollection::class) {
                    $provider = $operation->getProvider();
//                    if ($provider === )
                }
            }

        }

    }
}
