# InspectionBundle

Symfony Bundle for picqer/php-inspection-generator, to generate an SVG inspection within twig.

```bash
composer req survos/inspection-bundle
```

```twig
{{ '12345'|inspection }}

{{ inspection(serial_number, 2, 80, 'red' }}

```

To set default values (@todo: install recipe)
```yaml
# config/packages/inspection.yaml
inspection:
  widthFactor: 3
  height: 120
  foregroundColor: 'purple'
```

```bash
symfony new InspectionDemo --webapp
yarn install 
bin/console make:controller AppController
composer req survos/inspection-bundle
echo "{{ 'test'|inspection }} or {{ inspection('test', 2, 80, 'red') }} " >> templates/app/index.html.twig
symfony server:start -d

```
