<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Libraries;

use Imagick;

class MapProcessor
{
    const TILE_WIDTH = 256;
    const TILE_HEIGHT = 256;

    protected $image;
    protected $files;

    protected $baseDir;

    protected $mapDirectory = 'maps';
    protected $tileDirectory = 'tiles';

    protected $maxZoomLevel = 0;

    protected $zoomLevels = [
        256,    //0 level
        512,    //1 level
        1024,   //2 level
        2048,   //3 level
        4096,   //4 level
        8192,   //5 level
        16384,  //6 level
    ];

    protected $zoomedImages = [];

    public function __construct($school_id = 1, $campus_id = 1, $building_id, $floor_id, $file, $old)
    {
        $this->file = $file;
        $this->path = 'uploads/maps/'.join('/', [$school_id, $campus_id, $building_id, $floor_id]);
        $this->baseDir = public_path(
            $this->path
        );

        if (!empty($old)) {
            \File::deleteDirectory($this->baseDir . '/' . $old);
        }

        $this->baseDir .= '/'.$this->getFilename();
        $this->path .= '/'.$this->getFilename();

        $this->tileDirectory = $this->baseDir.'/'.$this->tileDirectory;
    }

    public function getFilename()
    {
        return str_replace(['.jpg', '.png', '.gif'], '', $this->file->getFilename());
    }

    public function processMap()
    {
        $this->makeDir($this->baseDir);

        $image = public_path('uploads/maps/'.$this->file->getFilename());

        $this->image = new Image($image);

        $this->setZoomLevels();
        $this->prepare();

        $images = [];
        for ($i = $this->maxZoomLevel; $i >= 0; $i--) {
            $images[$i] = $this->resizeImage($i);
            $this->makeTiles($i);
        }

        return [
            'max_zoom_level' => $this->maxZoomLevel,
            'width' => $images[$this->maxZoomLevel]['width'],
            'height' =>$images[$this->maxZoomLevel]['height'],
            'path' => '/'.$this->path.'/'
        ];
    }

    protected function makeTiles($zoomLevel)
    {
        $image = $this->zoomedImages[$zoomLevel];
        $dimensions = $image->getImageGeometry();

        for ($w = 0; $w < ($dimensions['width'] / self::TILE_WIDTH); $w++) {
            for ($h = 0; $h < ($dimensions['height'] / self::TILE_HEIGHT); $h++) {
                $x = $w * self::TILE_WIDTH;
                $y = $h * self::TILE_HEIGHT;

                $name = $this->tileDirectory.'/'.$zoomLevel.'/'.$w."_".$h.".png";

                $tile = clone $image;
                $tile->cropImage(self::TILE_WIDTH, self::TILE_HEIGHT, $x, $y);

                $this->image->createFixedSizeImage($tile, $name, self::TILE_WIDTH, self::TILE_HEIGHT);
            }
        }

        $image->destroy();
    }

    protected function resizeImage($zoomLevel)
    {
        $width = $this->zoomLevels[$zoomLevel];

        $clone = $this->zoomedImages[$zoomLevel] = clone $this->image;

        $clone->resizeImage($width, $width, Imagick::FILTER_LANCZOS, 1, true);

        $width = ceil($clone->getImageGeometry()['width'] / self::TILE_WIDTH) * self::TILE_WIDTH;
        $height = ceil($clone->getImageGeometry()['height'] / self::TILE_HEIGHT) * self::TILE_WIDTH;

        $name = $this->tileDirectory.'/'.$zoomLevel.'/'.$this->getFilename().'.png';
        $this->image->createFixedSizeImage($clone, $name, $width, $height);

        return ['width' => $width, 'height' => $height];
    }

    protected function prepare()
    {
        $this->makeDir($this->tileDirectory);

        for ($i = $this->maxZoomLevel; $i >= 0; $i--) {
            $this->makeDir($this->tileDirectory.'/'.$i);
        }
    }

    protected function checkZoomLevel($level, $width, $height)
    {
        if (!isset($this->zoomLevels[$level + 1])) {
            return true;
        }

        if (($this->zoomLevels[$level] < $width     || $this->zoomLevels[$level] < $height)
            && ($this->zoomLevels[$level+1] > $width   || $this->zoomLevels[$level] > $height)
        ) {
                return true;
        }

        return false;
    }

    protected function setZoomLevels()
    {
        $size = $this->image->getDimensions();

        $max = 0;

        for ($i=0; $i<count($this->zoomLevels); $i++) {
            if ($this->checkZoomLevel($i, $size['width'], $size['height'])) {
                $max = $i;
                break;
            }
        }

        $this->maxZoomLevel = $max;
    }

    protected function makeDir($dir)
    {
        if (!is_dir($dir) && !mkdir($dir, 0777, true)) {
            throw new Exception('Cannot create'.$dir.'directory');
        }
    }
}
