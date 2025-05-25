<?php

namespace src\Components;

use src\Abstracts\Component;

class ComponentMapper
{
    /**
     * Retrieves all components.
     *
     * This method loads the component map from the specified file, iterates over the map,
     * and instantiates each class that exists and is a subclass of the Component class.
     * The instantiated components are then stored in an associative array with their names as keys.
     *
     * @return array An associative array of component names and their instantiated objects.
     */
    public static function getAll(): array
    {
        $components = [];
        $map = require_once SECURE_DIR . "/src/Components/map.php";

        foreach ($map as $name => $class) {
            if (class_exists($class) && is_subclass_of($class, Component::class)) {
                $components[$name] = new $class();
            }
        }
        return $components;
    }
}
