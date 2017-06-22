<?php

namespace Wollanup\Api\Swagger;

use Eukles\Container\ContainerInterface;
use Eukles\Entity\EntityRequestInterface;
use Eukles\Service\Router\RouteInterface;
use Eukles\Service\Router\RouterInterface;
use Eukles\Util\DataIterator;
use Wollanup\Api\Swagger\Definition\DefinitionModelAdd;
use Wollanup\Api\Swagger\Definition\DefinitionModelRead;

/**
 * Class Parameters
 *
 * @property  Definition[] $data
 * @package Wollanup\Api\Swagger
 */
class Definitions extends DataIterator implements \JsonSerializable
{
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var Definition[]
     */
    protected $pool = [];
    
    /**
     * Definitions constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * Definitions constructor.
     *
     * @param RouterInterface $router
     *
     * @return $this
     */
    public function buildAll(RouterInterface $router = null)
    {
        foreach ($router->getRoutesMap() as $routeMap) {
            /** @var RouteInterface $route */
            foreach ($routeMap as $route) {
                $this->buildDefinition($route);
            }
        }
        
        return $this;
    }
    
    /**
     * @param RouteInterface $route
     */
    public function buildDefinition(RouteInterface $route)
    {
        if ($route->isMakeInstance()) {
            $className = $route->getRequestClass();
            if (!isset($this->pool[$className])) {
                $this->pool[$className] = true;
                /** @var EntityRequestInterface $requestInstance */
                $requestInstance = new $className($this->container);
    
                $model                         = new DefinitionModelRead($requestInstance);
                $this->data[$model->getName()] = $model;
                
                $modelAdd                         = new DefinitionModelAdd($requestInstance);
                $this->data[$modelAdd->getName()] = $modelAdd;
            }
        }
    }
    
    /**
     *
     * @param RouteInterface $route
     * @param string         $suffix
     *
     * @return Definition
     */
    public function getDefinition($route, $suffix = '')
    {
        return $this->data[$route->getRequestClass() . $suffix];
    }
    
    /**
     * @param RouteInterface $route
     *
     * @return bool
     */
    public function hasDefinition(RouteInterface $route)
    {
        return isset($this->pool[$route->getRequestClass()]);
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
