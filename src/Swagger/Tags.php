<?php

namespace Wollanup\Api\Swagger;

use Eukles\RouteMap\RouteMapInterface;
use Eukles\Service\Router\RouterInterface;
use Eukles\Util\DataIterator;

/**
 * Tags Operations
 *
 * @property  Operation[] $data
 * @package Wollanup\Api\Swagger
 */
class Tags extends DataIterator implements \JsonSerializable
{
    
    /**
     * Parameters constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router = null)
    {
        if ($router) {
            /** @var RouteMapInterface $routeMap */
            foreach ($router->getRoutesMap() as $routeMap) {
                $this->buildTag($routeMap);
            }
        }
    }
    
    /**
     * @param RouteMapInterface $routeMap
     */
    public function buildTag(RouteMapInterface $routeMap)
    {
        // TODO write some info somewhere in Route Map / Package File ?
        $name                                = TagHelper::buildTagName($routeMap);
        $this->data[$routeMap->getPackage()] = [
            'name' => $name,
//        NOT USED in swagger-ui 2.x : UGLY   'description' => "Operations on " . $name,
        ];
    }
    
    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        ksort($this->data);
        
        return array_values($this->data);
    }
}
