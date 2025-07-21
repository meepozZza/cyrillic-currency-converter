currency-converter-php
======================

[![Latest Stable Version](https://poser.pugx.org/meepozzza/cyrillic-currency-converter/v/stable.png)](https://packagist.org/packages/meepozzza/cyrillic-currency-converter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/meepozzza/cyrillic-currency-converter/badges/quality-score.png?s=c4d93ce5c60894c09d2b4f7b1ec97d6956c9b23f)](https://scrutinizer-ci.com/g/meepozzza/cyrillic-currency-converter/)
[![Code Coverage](https://scrutinizer-ci.com/g/meepozzza/cyrillic-currency-converter/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/meepozzza/cyrillic-currency-converter/?branch=main)

Basic converter of floating point numbers to Cyrillic sentences. Rubles and kopecks are supported.

## Getting started
```php
<?php
require 'vendor/autoload.php';

$converter = new MeepozZza\CyrillicCurrencyConverter\Converter();
echo $converter->convertNumberToString('1234.56'); // will print something like 'одна тысяча двести рублей тридцать четыре рубля пятьдесят шесть копеек

$converter = new MeepozZza\CyrillicCurrencyConverter\Converter(true);
echo $converter->convertNumberToString('1234.56'); // will print something like 'одна тысяча двести рублей тридцать четыре рубля 56 копеек

```

## Why the package was created

* At the moment there is no package that correctly outputs floating point numbers to Cyrillic

## Requirements

* PHP version 8.0 or later

## Installation

composer require meepozzza/cyrillic-currency-converter

## Usage

* Use variable $keepRemainderAsNumber in __construct for keep remainder as number
* Call convertNumberToString method of Converter class
