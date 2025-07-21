<?php

declare(strict_types=1);

namespace MeepozZza\tests;

use MeepozZza\CyrillicCurrencyConverter\Converter;
use PHPUnit\Framework\Attributes\Test;

class ConverterTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function basic_numbers()
    {
        $converter = new Converter(true);

        $this->assertEquals('ноль рублей 00 копеек', $converter->convertNumberToString(0));
        $this->assertEquals('один рубль 00 копеек', $converter->convertNumberToString(1));
        $this->assertEquals('два рубля 00 копеек', $converter->convertNumberToString(2));
        $this->assertEquals('пять рублей 00 копеек', $converter->convertNumberToString(5));
    }

    #[Test]
    public function tens_and_hundreds()
    {
        $converter = new Converter(true);

        $this->assertEquals('десять рублей 00 копеек', $converter->convertNumberToString(10));
        $this->assertEquals('двенадцать рублей 00 копеек', $converter->convertNumberToString(12));
        $this->assertEquals('сто рублей 00 копеек', $converter->convertNumberToString(100));
        $this->assertEquals('двести тридцать четыре рубля 00 копеек', $converter->convertNumberToString(234));
    }

    #[Test]
    public function thousands()
    {
        $converter = new Converter(true);

        $this->assertEquals('одна тысяча рублей 00 копеек', $converter->convertNumberToString(1000));
        $this->assertEquals('две тысячи рублей 00 копеек', $converter->convertNumberToString(2000));
        $this->assertEquals('пять тысяч рублей 00 копеек', $converter->convertNumberToString(5000));
        $this->assertEquals('сто тысяч рублей 00 копеек', $converter->convertNumberToString(100000));
        $this->assertEquals('шестьсот тысяч рублей 00 копеек', $converter->convertNumberToString(600000));
    }

    #[Test]
    public function millions()
    {
        $converter = new Converter(true);

        $this->assertEquals('один миллион рублей 00 копеек', $converter->convertNumberToString(1000000));
        $this->assertEquals('два миллиона рублей 00 копеек', $converter->convertNumberToString(2000000));
        $this->assertEquals('пять миллионов рублей 00 копеек', $converter->convertNumberToString(5000000));
    }

    #[Test]
    public function fractions()
    {
        $converter = new Converter(true);

        $this->assertEquals('ноль рублей 99 копеек', $converter->convertNumberToString(0.99));
        $this->assertEquals('один рубль 01 копейка', $converter->convertNumberToString(1.01));
        $this->assertEquals('один рубль 02 копейки', $converter->convertNumberToString(1.02));
        $this->assertEquals('два рубля 50 копеек', $converter->convertNumberToString(2.50));
        $this->assertEquals('сто рублей 99 копеек', $converter->convertNumberToString(100.99));
    }

    #[Test]
    public function edge_cases()
    {
        $converter = new Converter(true);

        $this->assertEquals('один рубль 00 копеек', $converter->convertNumberToString(1.00));
        $this->assertEquals('одна тысяча один рубль 00 копеек', $converter->convertNumberToString(1001));
        $this->assertEquals('две тысячи два рубля 00 копеек', $converter->convertNumberToString(2002));
        $this->assertEquals('один миллион одна тысяча один рубль 00 копеек', $converter->convertNumberToString(1001001));
    }

    #[Test]
    public function large_numbers()
    {
        $converter = new Converter(true);

        $this->assertEquals(
            'один миллиард двести тридцать четыре миллиона пятьсот шестьдесят семь тысяч восемьсот девяносто рублей 12 копеек',
            $converter->convertNumberToString(1234567890.12)
        );
    }
}
