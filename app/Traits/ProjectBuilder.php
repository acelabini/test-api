<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

trait ProjectBuilder
{
    public static function buildRange($min, $max): string
    {
        return (string) ($min !== null ? ($min == $max ? ($max ?: 0) : "{$min}-{$max}") : $max);
    }

    public static function buildPrice($min, $max): string
    {
        return (string) (
            $min !== null && $min > 0 ?
                (
                    $min == $max ?
                        ($max ? self::shortPrice($max) : 0) :
                        self::shortPrice($min)." - ".self::shortPrice($max)
                ) :
                self::shortPrice($max)
        );
    }

    /**
     * Build project property spaces
     *
     * @param Collection $projectProperty
     * @return array
     */
    public static function buildSpaces(Collection $projectProperty): array
    {
        return [
            'bedrooms' => self::buildRange(
                $projectProperty->min('bedrooms'),
                $projectProperty->max('bedrooms')
            ),
            'bathrooms' => self::buildRange(
                $projectProperty->min('bathrooms'),
                $projectProperty->max('bathrooms')
            ),
            'carSpaces' => self::buildRange(
                $projectProperty->min('car_spaces'),
                $projectProperty->max('car_spaces')
            ),
        ];
    }

    /**
     * Build project property spaces
     *
     * @param Collection $projectProperty
     * @return array
     */
    public static function buildPrices(Collection $projectProperty): array
    {
        $projectProperty = $projectProperty
            ->where('price', '>', 0)
            ->filter(fn($v) => $v->monthly_payment > 0);

        return [
            'price' => self::buildPrice(
                $projectProperty->min('price'),
                $projectProperty->max('price')
            ),
            'monthly' => self::shortPrice($projectProperty->min('monthly_payment')),
            'deposit' => self::shortPrice($projectProperty->min('deposit_payment')),
        ];
    }

    /**
     * Convert amount to readable format
     * K = thousand
     * M = million
     * B = billion
     * T = trillion
     *
     * @param $amount
     * @return string
     */
    public static function shortPrice(int|float $amount): string
    {
        $units = ['', 'K', 'M', 'B', 'T'];
        for ($i = 0; $amount >= 1000; $i++) {
            $amount /= 1000;
        }

        return round($amount, 1) . $units[$i];
    }
}
