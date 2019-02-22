<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Coordinates;

/**
 * Class Coordinates
 *
 * @package BComeSafe\Packages\Coordinates
 */
class Coordinates
{
    /**
     * @param $feet
     * @param $pixels
     *
     * @return float
     */
    public static function feetToPixels($feet, $pixels)
    {
        if ($feet == 0 || $pixels == 0) {
            return 0;
        }

        $px = round($pixels / $feet, 2);

        return $px;
    }

    /**
     * @param $width
     * @param $widthFeet
     * @param $height
     * @param $heightFeet
     * @param $x
     * @param $y
     *
     * @return array
     */
    public static function convert($width, $widthFeet, $height, $heightFeet, $x, $y)
    {
        $x = round(static::feetToPixels((double)$widthFeet, (double)$width) * (double)$x);
        $y = round(static::feetToPixels((double)$heightFeet, (double)$height) * (double)$y);

        return compact('x', 'y');
    }
}
