<?php

namespace App\Service\MacronutrientsManagers;

use Exception;

class UnitConverter
{
    private const CUP_TO_GRAMS = 250;
    private const TBSP_TO_GRAMS = 15;
    private const TSP_TO_GRAMS = 5;
    private const PINCH_TO_GRAMS = 0.4;

    public static function convertToGrams($amount, $unit){
        switch($unit){
            case 'ml':
            case 'g':
                return $amount;
            case 'cup':
                return $amount * self::CUP_TO_GRAMS;
            case 'tbsp':
                return $amount * self::TBSP_TO_GRAMS;
            case 'tsp':
                return $amount * self::TSP_TO_GRAMS;
            case 'pinch':
                return $amount * self::PINCH_TO_GRAMS;
            default:
                throw new Exception('Invalid unit:'.$unit);
        }
    }



}