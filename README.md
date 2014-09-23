Evaluator
==============

A laravel package and orchestra extension for [symfony/expression-language](http://symfony.com/doc/current/components/expression_language/index.html) component.

## Installation

To install simply add into your ```composer.json``` file.

```json
{
    "repositories": [
            {
                "type": "git",
                "url": "https://github.com/elepunk/evaluator"
            }
    ],
    "require": {
        "elepunk/evaluator": "dev-master"
    }
}
```

## Setup

### Setup as Laravel package

Include the service provider into and facade into your ```app/config/app.php``` file.

```php
'providers' => [
    ...
    'Elepunk\Evaluator\EvaluatorServiceProvider',
]
```

```php
'aliases' => [
    ...
    'Evaluator' => 'Elepunk\Evaluator\Facades\Evaluator'
]
```

### Setup as Orchestra extension

Simply activate the ```Elepunk Evaluator``` under orchestra extension page.

## Configuration

Run ```php artisan config:publish elepunk/evaluator``` to publish the configuration file.

## Drivers

By default this package support two drivers
* File (the config file)
* Memory ([Orchestra\Memory](https://github.com/orchestral/memory) component)

### File driver

Expressions are defined inside the configuration file under ```expressions```.

### Memory driver

This requires Orchestra\Memory component if you are not using Orchestra\Platform.

## Usage

Let say we are evaluating the expression ```foo > bar```

### Adding new expression

```php
use Elepunk\Evaluator\Facades\Evaluator;

Evaluator::repository()->add('test', 'foo > bar');
```

```php
use Elepunk\Evaluator\Facades\Evaluator;

$test = [
    'foo' => 10,
    'bar' => 5
];

echo Evaluator::evaluate('test', $test); //this will retun true
```

For supported expressions, visit the [Symfony Expression Language Component](http://symfony.com/doc/current/components/expression_language/index.html).
