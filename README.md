Evaluator
==============

[![Build Status](https://travis-ci.org/elepunk/evaluator.svg?branch=master)](https://travis-ci.org/elepunk/evaluator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/elepunk/evaluator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/elepunk/evaluator/?branch=master)
[![Coverage Status](https://coveralls.io/repos/elepunk/evaluator/badge.png?branch=master)](https://coveralls.io/r/elepunk/evaluator?branch=master)
[![License](https://poser.pugx.org/elepunk/evaluator/license.svg)](https://packagist.org/packages/elepunk/evaluator)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6dda2ef1-b8fb-403f-a9c3-f01d1623aa6c/mini.png)](https://insight.sensiolabs.com/projects/6dda2ef1-b8fb-403f-a9c3-f01d1623aa6c)

A Laravel package and Orchestra extension for [symfony/expression-language](http://symfony.com/doc/current/components/expression_language/index.html) component.

## Installation

Simpy update the ```composer.json``` file and run ```composer install```.

```json
"require": {
	"elepunk/evaluator": "1.0.*"
}
```

## Quick Installation

```composer require "elepunk/evaluator=1.0.*"```

## Setup

If you are using Orchestra Platform, you can simply enable the extension or add the service provider.

```php
'providers' => [
	'Elepunk\Evaluator\EvaluatorServiceProvider'
];
```

## Adapter

This package provide Orchesta Memory as the default driver.

## How To Use

### Evaluating an expression

```php
use Elepunk\Evaluator\Facades\Evaluator;

$test = [
    'foo' => 10,
    'bar' => 5
];

echo Evaluator::evaluate('foo > bar', $test); //this will return true
```

You can also save the expression rule.

```php
use Elepunk\Evaluator\Facades\Evaluator;

$test = [
    'foo' => 10,
    'bar' => 5
];

Evaluator::expression()->add('test', 'foo > bar');

echo Evaluator::evaluate('test', $test); //this will return true
```

For supported expressions, visit the [Symfony Expression Language Component](http://symfony.com/doc/current/components/expression_language/index.html).

### Condition

Let say we want to implement 10% tax to our collection.

```php
use Elepunk\Evaluator\Facades\Evaluator;

$item = [
    'price' => 100
];

$condition = [
    'target' => 'price',
    'action' => '10%',
    'rule' => 'price > 50'
];

Evaluator::expression()->add('tax', $condition);

$calculated = Evaluator::condition('tax', $item);
```

Item with multiplier.

```php
$item = [
	'price' => 50,
	'quantity' => 2
];

$condition = [
    'target' => 'price',
    'action' => '10%',
    'rule' => 'price > 50',
    'multiplier' => 'quantity'
];

Evaluator::expression()->add('tax', $condition);

$calculated = Evaluator::condition('tax', $item);
```
