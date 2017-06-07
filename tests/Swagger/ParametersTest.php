<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 07/06/17
 * Time: 14:55
 */

namespace Wollanup\Api\Swagger\Test;

use Eukles\Container\Container;
use Eukles\Container\ContainerInterface;
use Eukles\RouteMap\RouteMapAbstract;
use Eukles\RouteMap\RouteMapInterface;
use Eukles\Service\Router\RouteInterface;
use FastRoute\RouteParser\Std;
use PHPUnit\Framework\TestCase;
use Wollanup\Api\Swagger\Operation;
use Wollanup\Api\Swagger\Parameters;

class ParametersTest extends TestCase
{
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var Operation
     */
    protected $operation;
    /**
     * @var RouteInterface
     */
    protected $route;
    /**
     * @var RouteMapInterface
     */
    protected $routeMap;
    
    public function mock($pattern, $method)
    {
        $this->route     = $this->routeMap->get($pattern)
            ->setPackage('foo')
            ->setActionClass(FooAction::class)
            ->setActionMethod($method);
        $parser          = new Std();
        $routesPattern   = $parser->parse($this->route->getPattern());
        $r               = new \ReflectionMethod(FooAction::class, $method);
        $this->operation = new Parameters($r, $this->route, $routesPattern[0]);
        
        return $this->operation;
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->container = new Container();
        
        /** @var RouteMapInterface $routeMap */
        $this->routeMap = $this->getMockForAbstractClass(RouteMapAbstract::class, [$this->container]);
    }
    
    public function testParamInPathIsNotAddedFromMethod()
    {
        $params = $this->mock("/foo/{id:[0-9+]}", 'getById');
        $this->assertTrue(count($params->jsonSerialize()) === 1);
    }
}
