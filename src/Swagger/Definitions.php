<?php

namespace Wollanup\Api\Swagger;

use Eukles\Container\ContainerInterface;
use Eukles\Entity\EntityRequestInterface;
use Eukles\Service\Router\RouteInterface;
use Eukles\Service\Router\RouterInterface;
use Eukles\Util\DataIterator;
use Wollanup\Api\Swagger\Definition\DefinitionModel;
use Wollanup\Api\Swagger\Definition\DefinitionModelAdd;

/**
 * Class Parameters
 *
 * @property  Definition[] $data
 * @package Wollanup\Api\Swagger
 */
class Definitions extends DataIterator implements \JsonSerializable
{
    
    /**
     * @var array
     */
    protected $pool = [];
    
    /**
     * Definitions constructor.
     *
     * @param ContainerInterface $container
     * @param RouterInterface    $router
     *
     * @return $this
     */
    public function buildAll(ContainerInterface $container, RouterInterface $router = null)
    {
        foreach ($router->getRoutesMap() as $routeMap) {
            /** @var RouteInterface $route */
            foreach ($routeMap as $route) {
                $this->buildDefinition($container, $route);
            }
        }
        
        return $this;
    }
    
    /**
     * @param ContainerInterface $container
     * @param RouteInterface     $route
     */
    public function buildDefinition(ContainerInterface $container, RouteInterface $route)
    {
        if ($route->isMakeInstance()) {
            $className = $route->getRequestClass();
            if (!isset($this->pool[$className])) {
                $this->pool[$className] = true;
                /** @var EntityRequestInterface $requestInstance */
                $requestInstance = new $className($container);
                
                $model                         = new DefinitionModel($requestInstance);
                $this->data[$model->getName()] = $model;
                
                $modelAdd                         = new DefinitionModelAdd($requestInstance);
                $this->data[$modelAdd->getName()] = $modelAdd;
            }
        }
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
        $return = [];
        foreach ($this->data as $definition) {
            $return = array_merge($return, $definition->jsonSerialize());
        }
        
        return $return;
    }
}
