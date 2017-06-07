<?php

namespace Wollanup\Api\Swagger;

use Eukles\RouteMap\RouteMapInterface;

/**
 * Class TagHelper
 *
 * @package Wollanup\Api\Swagger
 */
class TagHelper
{
    
    public static function buildTagName(RouteMapInterface $routeMap)
    {
        $prefix = 'Core ';
        if ($routeMap->getPrefix()) {
            $prefix = ucfirst($routeMap->getPrefix()) . ' ';
        }
        
        return $prefix . ucfirst($routeMap->getPackage());
    }
}
