<?php

namespace Wollanup\Api\Swagger;

use Eukles\Container\ContainerInterface;
use Eukles\Entity\EntityFactoryConfig;
use Eukles\Entity\EntityRequestInterface;
use Eukles\Service\Router\RouteInterface;
use Eukles\Service\Router\RouterInterface;
use JsonSerializable;
use Wollanup\Api\Swagger\Definition\DefinitionModelAdd;
use Wollanup\Api\Swagger\Definition\DefinitionModelChange;
use Wollanup\Api\Swagger\Definition\DefinitionModelRead;
use Wollanup\Api\Swagger\Definition\DefinitionModelSend;
use Wollanup\Api\Swagger\Definition\Propel\PropelModelPager;
use Wollanup\Api\Util\DataIterator;

/**
 * Class Parameters
 *
 * @property  Definition[] $data
 * @package Wollanup\Api\Swagger
 */
class Definitions extends DataIterator implements JsonSerializable
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
        $this->buildExtra();

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
        /** @var EntityRequestInterface[] $classes */
        $classes = [];
        if ($route->hasEntities()) {
            /** @var EntityFactoryConfig $config */
            foreach ($route->getEntities() as $config) {
                $requestEntity = $config->createEntityRequest($this->container->getRequest(), $this->container);
                $classes[] = $requestEntity;
            }
        }
        /** @var EntityRequestInterface $class */
        foreach ($classes as $class) {
            $className = get_class($class);
            if (!isset($this->pool[$className])) {
                $this->pool[$className] = true;

                $model = new DefinitionModelRead($class);

                $this->data[str_replace('\\', '/', $model->getName())] = $model;

                $modelAdd = new DefinitionModelAdd($class);
                $this->data[str_replace('\\', '/', $modelAdd->getName())]
                    = $modelAdd;

                $modelChange = new DefinitionModelChange($class);
                $this->data[str_replace('\\', '/', $modelChange->getName())]
                    = $modelChange;

                $modelSend = new DefinitionModelSend($class);
                $this->data[str_replace('\\', '/', $modelSend->getName())]
                    = $modelSend;
            }
        }
    }

    public function buildExtra()
    {
        $pager = new PropelModelPager();
        // TODO Add properties
        $this->data[$pager->getName()] = $pager;

//        $coll = new Definition();
//        $coll->setName(str_replace('\\', '/', ObjectCollection::class));
//        // TODO Add properties
//        $this->data[$coll->getName()] = $coll;
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
        return $this->data[str_replace('\\', '/',
            $route->getRequestClass() . $suffix)];
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
