<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Packages\Configuration;

/**
 * Class ComponentDirectoryNotSetException
 * @package BComeSafe\Packages\Configuration
 */
class ComponentDirectoryNotSetException extends \Exception
{
}

/**
 * Class ComponentNamespaceNotSetException
 * @package BComeSafe\Packages\Configuration
 */
class ComponentNamespaceNotSetException extends \Exception
{
}

/**
 * Class ComponentNotFoundException
 * @package BComeSafe\Packages\Configuration
 */
class ComponentNotFoundException extends \Exception
{
}

/**
 * Class ComponentExtractor
 * @package BComeSafe\Packages\Configuration
 */
class ComponentExtractor implements Contracts\ExtractorContract
{

    /**
     * @var null
     */
    protected $directory = null;
    /**
     * @var null
     */
    protected $namespace = null;

    /**
     * @param $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return null
     */
    public function getNamespace()
    {
        return 'BComeSafe\\Packages\\' . $this->namespace;
    }

    /**
     * @return null
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return array
     * @throws ComponentDirectoryNotSetException
     * @throws ComponentNamespaceNotSetException
     */
    public function getIntegrations()
    {

        $this->checkDirectoryAndNamespace();

        $components = [];
        $files = \File::allFiles($this->getDirectory());

        foreach ($files as $file) {
            $class = $file->getBasename('.php');

            $reflector = new \ReflectionClass($this->getNamespace() . $class);
            $component = $reflector->getDefaultProperties();
            ;
            $components[str_replace('Integration', '', $class)] = $component['label'];
        }
        return $components;
    }

    /**
     * @param $component
     * @return mixed
     * @throws ComponentDirectoryNotSetException
     * @throws ComponentNamespaceNotSetException
     * @throws ComponentNotFoundException
     */
    public function getIntegration($component)
    {
        $this->checkDirectoryAndNamespace();

        $class = $this->getNamespace() . $component . 'Integration';

        if (!class_exists($class)) {
            throw new ComponentNotFoundException('Component ' . $class . ' not found.');
        }

        $component = new $class();

        return $component;
    }

    /**
     * @throws ComponentDirectoryNotSetException
     * @throws ComponentNamespaceNotSetException
     */
    protected function checkDirectoryAndNamespace()
    {
        if ($this->directory === null) {
            throw new ComponentDirectoryNotSetException('Component directory not set.');
        }
        if ($this->namespace === null) {
            throw new ComponentNamespaceNotSetException('Component directory not set.');
        }
    }
}
