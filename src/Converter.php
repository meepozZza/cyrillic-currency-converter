<?php

declare(strict_types=1);

class Converter
{
    private string $null = 'ноль';

    private array $ten = [
        ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
    ];

    private array $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];

    private array $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];

    private array $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];

    private array $unit = [
        ['копейка', 'копейки', 'копеек', 1],
        ['рубль', 'рубля', 'рублей', 0],
        ['тысяча', 'тысячи', 'тысяч', 1],
        ['миллион', 'миллиона', 'миллионов', 0],
        ['миллиард', 'милиарда', 'миллиардов', 0],
    ];

    public function convertNumberToString(string|int|float $number, bool $keepRemainderAsNumber = false): string
    {
        [$rubbles, $kopecks] = explode('.', sprintf('%015.2f', floatval($number)));

        $out = [];

        if (intval($rubbles) > 0) {
            foreach (str_split($rubbles, 3) as $key => $rubble) {
                if (!intval($rubble)) {
                    continue;
                }

                $uk = count($this->unit) - $key - 1;
                $gender = $this->unit[$uk][3];
                [$i1, $i2, $i3] = array_map('intval', str_split($rubble));
                $out[] = $this->hundred[$i1];

                if ($i2 > 1) {
                    $out[] = $this->tens[$i2] . ' ' . $this->ten[$gender][$i3];
                } else {
                    $out[] = $i2 > 0 ? $this->a20[$i3] : $this->ten[$gender][$i3];
                }
                if ($uk > 1) {
                    $out[] = $this->morphCurrencyWord($rubble, $this->unit[$uk][0], $this->unit[$uk][1], $this->unit[$uk][2]);
                }
            }
        } else {
            $out[] = $this->null;
        }

        $out[] = $this->morphCurrencyWord(intval($rubbles), $this->unit[1][0], $this->unit[1][1], $this->unit[1][2]);

        if (intval($kopecks) > 0) {
            foreach (str_split($kopecks, 2) as $kopeck) {
                if (!intval($kopeck)) {
                    continue;
                }
                $uk = 0;
                if ($keepRemainderAsNumber) {
                    $out[] = $kopeck;
                } else {
                    $gender = $this->unit[$uk][3];
                    [$i1, $i2] = array_map('intval', str_split($kopeck));
                    if ($i1 > 1) {
                        $out[] = $this->tens[$i1] . ' ' . $this->ten[$gender][$i2];
                    } else {
                        $out[] = $i1 > 0 ? $this->a20[$i2] : $this->ten[$gender][$i2];
                    }
                }

                $out[] = $this->morphCurrencyWord($kopeck, $this->unit[$uk][0], $this->unit[$uk][1], $this->unit[$uk][2]);
            }
        } else {
            $out[] = $keepRemainderAsNumber ? '00' : $this->null;
            $out[] = $this->morphCurrencyWord(0, $this->unit[0][0], $this->unit[0][1], $this->unit[0][2]);
        }

        return trim(preg_replace('/ {2,}/', ' ', implode(' ', $out)));
    }

    public function morphCurrencyWord($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20) {
            return $f5;
        }
        $n = $n % 10;
        if ($n > 1 && $n < 5) {
            return $f2;
        }
        if ($n == 1) {
            return $f1;
        }

        return $f5;
    }
}