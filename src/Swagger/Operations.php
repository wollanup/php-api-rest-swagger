<?php

namespace Wollanup\Api\Swagger;

use Eukles\RouteMap\RouteMapInterface;
use Eukles\Service\Router\RouteInterface;
use Eukles\Service\Router\RouterInterface;
use Eukles\Util\DataIterator;
use FastRoute\RouteParser\Std;

/**
 * Class Operations
 *
 * @property  Operation[] $data
 * @package Wollanup\Api\Swagger
 */
class Operations extends DataIterator implements \JsonSerializable
{
    
    /**
     * @var Definitions
     */
    protected $definitions;
    
    public function __construct(Definitions $definitions)
    {
        $this->definitions = $definitions;
    }
    
    /**
     * Parameters constructor.
     *
     * @param RouterInterface $router
     *
     * @return $this
     */
    public function buildAll(RouterInterface $router = null)
    {
        /** @var RouteMapInterface $routeMap */
        foreach ($router->getRoutesMap() as $routeMap) {
            /** @var RouteInterface $route */
            $tags = [TagHelper::buildTagName($routeMap)];
            foreach ($routeMap as $route) {
                $this->buildOperation($route, $tags, $this->definitions);
            }
        }
        
        return $this;
    }
    
    /**
     * @param RouteInterface $route
     *
     * @param array          $tags
     *
     * @param Definitions    $definitions
     */
    public function buildOperation(RouteInterface $route, array $tags, Definitions $definitions)
    {
        $parser        = new Std();
        $routesPattern = $parser->parse($route->getPattern());
        if (empty($routesPattern)) {
            throw new \InvalidArgumentException('Unable to parse route');
        }
        foreach ($routesPattern as $routePattern) {
            $operation = new Operation($route, $routePattern, $definitions);
            if (count($this->data)) {
                /** @var Operation $previousOp */
                $previousOp = $this->data[count($this->data) - 1];
                if ($previousOp->getPath() . '/' === $operation->getPath()) {
                    continue;
                }
            }
            if ($operation->isInternal()) {
                continue;
            }
            $operation->setTags($tags);
            
            $this->data[] = $operation;
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
    public function jsonSerialize()
    {
        $operations = [];
        
        foreach ($this->data as $operation) {
            $operations = array_merge($operations, $operation->jsonSerialize());
        }
        ksort($operations);
        
        return $operations;
    }
}
