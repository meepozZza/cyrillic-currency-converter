<?php

declare(strict_types=1);

namespace MeepozZza\CyrillicCurrencyConverter;

class Converter
{
    protected const NULL = 'ноль';

    protected const UNITS = [
        ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
    ];

    protected const TEENS = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];

    protected const TENS = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];

    protected const HUNDREDS = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];

    protected const CURRENCY_UNITS = [
        ['копейка', 'копейки', 'копеек', 1],
        ['рубль', 'рубля', 'рублей', 0],
        ['тысяча', 'тысячи', 'тысяч', 1],
        ['миллион', 'миллиона', 'миллионов', 0],
        ['миллиард', 'милиарда', 'миллиардов', 0],
    ];

    public function __construct(protected bool $keepRemainderAsNumber = false)
    {
    }

    /**
     * Convert number to string.
     *
     * @param string|int|float $number
     * @return string
     */
    public function convertNumberToString(string|int|float $number): string
    {
        [$rubbles, $kopecks] = explode('.', sprintf('%015.2f', floatval($number)));

        $out = [];

        $this->convertRubbles($rubbles, $out);
        $this->convertKopecks($kopecks, $out);

        return trim(preg_replace('/ {2,}/', ' ', implode(' ', $out)));
    }

    protected function convertRubbles(string $rubbles, array &$out): void
    {
        if (intval($rubbles) > 0) {
            foreach (str_split($rubbles, 3) as $key => $rubble) {
                if (! intval($rubble)) {
                    continue;
                }

                $currencyUnitKey = count(static::CURRENCY_UNITS) - $key - 1;
                $gender = static::CURRENCY_UNITS[$currencyUnitKey][3];
                [$rubbleUnitOne, $rubbleUnitTwo, $rubbleUnitThree] = array_map('intval', str_split($rubble));
                $out[] = static::HUNDREDS[$rubbleUnitOne];

                if ($rubbleUnitTwo > 1) {
                    $out[] = static::TENS[$rubbleUnitTwo].' '.static::UNITS[$gender][$rubbleUnitThree];
                } else {
                    $out[] = $rubbleUnitTwo > 0 ? static::TEENS[$rubbleUnitThree] : static::UNITS[$gender][$rubbleUnitThree];
                }

                if ($currencyUnitKey > 1) {
                    $out[] = $this->morphCurrencyWord((int) $rubble, $currencyUnitKey);
                }
            }
        } else {
            $out[] = static::NULL;
        }

        $out[] = $this->morphCurrencyWord(intval($rubbles), 1);
    }

    protected function convertKopecks(string $kopecks, array &$out): void
    {
        if (intval($kopecks) > 0) {
            foreach (str_split($kopecks, 2) as $kopeck) {
                if (! intval($kopeck)) {
                    continue;
                }
                if ($this->keepRemainderAsNumber) {
                    $out[] = $kopeck;
                } else {
                    $gender = static::CURRENCY_UNITS[0][3];
                    [$kopeckUnitOne, $kopeckUnitTwo] = array_map('intval', str_split($kopeck));
                    if ($kopeckUnitOne > 1) {
                        $out[] = static::TENS[$kopeckUnitOne].' '.static::UNITS[$gender][$kopeckUnitTwo];
                    } else {
                        $out[] = $kopeckUnitOne > 0 ? static::TEENS[$kopeckUnitTwo] : static::UNITS[$gender][$kopeckUnitTwo];
                    }
                }

                $out[] = $this->morphCurrencyWord((int) $kopeck, 0);
            }
        } else {
            $out[] = $this->keepRemainderAsNumber ? '00' : static::NULL;
            $out[] = $this->morphCurrencyWord(0, 0);
        }
    }

    protected function morphCurrencyWord(int $number, int $currencyUnitKey): string
    {
        $number = abs($number) % 100;

        if ($number > 10 && $number < 20) {
            return static::CURRENCY_UNITS[$currencyUnitKey][2];
        }

        $number %= 10;

        if ($number > 1 && $number < 5) {
            return static::CURRENCY_UNITS[$currencyUnitKey][1];
        }

        if ($number == 1) {
            return static::CURRENCY_UNITS[$currencyUnitKey][0];
        }

        return static::CURRENCY_UNITS[$currencyUnitKey][2];
    }
}
