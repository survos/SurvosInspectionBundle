<?php

namespace Survos\InspectionBundle\Twig;

use ApiPlatform\Api\IriConverterInterface;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Symfony\Routing\IriConverter;
use Survos\CoreBundle\Entity\RouteParametersInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use function Symfony\Component\String\u;

//use ApiPlatform\Symfony\Routing\IriConverter

class TwigExtension extends AbstractExtension
{
    public function __construct(
        private IriConverter|null $iriConverter = null,
    )
    {
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            //            new TwigFilter('datatable', [$this, 'datatable'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('api_route', [$this, 'apiCollectionRoute']),
            new TwigFunction('api_item_route', [$this, 'apiItemRoute']),
            new TwigFunction('api_subresource_route', [$this, 'apiCollectionSubresourceRoute']),
//            new TwigFunction('sortable_fields', [$this, 'sortableFields']),
//            new TwigFunction('searchable_fields', [$this, 'searchableFields']),
//            new TwigFunction('search_builder_fields', [$this, 'searchBuilderFields']),
        ];
    }

    public function sortableFields(string $class): array
    {
        assert(class_exists($class), $class);
        $reflector = new \ReflectionClass($class);
        foreach ($reflector->getAttributes() as $attribute) {
            if (!u($attribute->getName())->endsWith('ApiFilter')) {
                continue;
            }
            $filter = $attribute->getArguments()[0];
            if (u($filter)->endsWith('OrderFilter')) {
                $orderProperties = $attribute->getArguments()['properties'];
                return $orderProperties;
            }
        }
        return [];
    }

    public function searchableFields(string $class): array
    {
        $reflector = new \ReflectionClass($class);
        foreach ($reflector->getAttributes() as $attribute) {
            if (!u($attribute->getName())->endsWith('ApiFilter')) {
                continue;
            }
            $filter = $attribute->getArguments()[0];
            if (u($filter)->endsWith('MultiFieldSearchFilter')) {
                return $attribute->getArguments()['properties'];
            }
        }

        return [];
    }

    private function getIriConverter(): IriConverterInterface
    {
        if (!$this->iriConverter) {
            throw new \LogicException("Install api-platform/core >= 2.7");
        }
        return $this->iriConverter;
    }

    public function apiCollectionRoute($entityOrClass, array $context = [])
    {
        $x = $this->iriConverter->getIriFromResource($entityOrClass, operation: new GetCollection(), context: $context);
        return $x;
    }

    public function apiItemRoute($entity)
    {
        $x = $this->getIriConverter()->getIriFromResource($entity);
        return $x;
    }

    public function apiCollectionSubresourceRoute($entityOrClass, RouteParametersInterface $parent)
    {
        //        #[ApiResource(
        //            uriTemplate: '/companies/{companyId}/employees',
        //            uriVariables: [
        //                'companyId' => new Link(fromClass: Company::class, toProperty: 'company'),
        //            ],
        //            operations: [ new GetCollection() ]
        //        )]
        $iri = $this->iriConverter->getIriFromResource($entityOrClass, operation: new GetCollection(), context: $context = [
            'uri_variables' => $parent->getrp(),
        ]);
        return $iri;
    }

    public function searchBuilderFields(string $class, array $normalizedColumns): array
    {
        $columnNumbers = [];
        foreach ($normalizedColumns as $idx => $normalizedColumn) {
            if ($normalizedColumn->searchable) {
                $columnNumbers[] = $idx;
            }
        }
        return $columnNumbers;
    }


}
