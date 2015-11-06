<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Libraries;

use Imagick;

class Image extends Imagick
{

    protected $dimensions;
    protected $width;
    protected $height;

    public function __construct($files = null)
    {
        parent::__construct($files);

        $this->dimensions = $this->getImageGeometry();
        $this->width = $this->dimensions['width'];
        $this->height = $this->dimensions['height'];
    }

    public function getDimensions()
    {
        return $this->dimensions;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function createFixedSizeImage($clone, $path, $width, $height)
    {
        $image = new Imagick();
        $image->newImage($width, $height, new \ImagickPixel('transparent'));
        $image->setImageFormat('png');
        $image->setBackgroundColor(new \ImagickPixel('transparent'));
        $image->compositeImage(
            $clone,
            Imagick::COMPOSITE_DEFAULT,
            0,
            0
        );
        $image->writeImage($path);
    }
}
