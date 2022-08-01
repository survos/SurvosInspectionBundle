# InspectionBundle

Symfony Bundle to inspect doctrine entities, to expose routes and searchable/sortable fields as defined by ApiPlatform

```bash
composer req survos/inspection-bundle
```

```twig

{% set class = 'App\Entity\Book' %}
{{ inspect(book) }}

See grid-demo for an example

