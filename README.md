Evaluator
==============

[![Build Status](https://travis-ci.org/elepunk/evaluator.svg?branch=master)](https://travis-ci.org/elepunk/evaluator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/elepunk/evaluator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/elepunk/evaluator/?branch=master)
[![Latest Unstable Version](https://poser.pugx.org/elepunk/evaluator/v/unstable.svg)](//packagist.org/packages/elepunk/evaluator)
[![License](https://poser.pugx.org/elepunk/evaluator/license.svg)](https://packagist.org/packages/elepunk/evaluator)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6dda2ef1-b8fb-403f-a9c3-f01d1623aa6c/mini.png)](https://insight.sensiolabs.com/projects/6dda2ef1-b8fb-403f-a9c3-f01d1623aa6c)

A Laravel package and Orchestra extension for [symfony/expression-language](http://symfony.com/doc/current/components/expression_language/index.html) component.

## Installation

To install simply add into your ```composer.json``` file.

```json
{
    "require": {
        "elepunk/evaluator": "0.1.*@dev"
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

echo Evaluator::evaluate('test', $test); //this will return true
```

You can also specify a callback when the it passes the evaluation

```php
Evaluator::ifTrue('test', $test, function ($t) {
   ...
});
```

For supported expressions, visit the [Symfony Expression Language Component](http://symfony.com/doc/current/components/expression_language/index.html).
