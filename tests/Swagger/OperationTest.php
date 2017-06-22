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
use Wollanup\Api\Swagger\Definitions;
use Wollanup\Api\Swagger\Operation;

class OperationTest extends TestCase
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
        $this->operation = new Operation($this->route, $routesPattern[0], new Definitions($this->container));
        
        return $this->operation;
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->container = new Container();
        
        /** @var RouteMapInterface $routeMap */
        $this->routeMap = $this->getMockForAbstractClass(RouteMapAbstract::class, [$this->container]);
    }
    
    public function testDeprecatedOperation()
    {
        $operation = $this->mock("", 'deprecated');
        
        $this->assertTrue($operation->isDeprecated());
    }
    
    public function testGetRoutePattern()
    {
        $operation = $this->mock("", 'deprecated');
        
        $this->assertSame(["/"], $operation->getRoutePattern());
    }
    
    public function testInternalOperation()
    {
        $operation = $this->mock("", 'internal');
        
        $this->assertTrue($operation->isInternal());
    }
    
    public function testNoCommentOperation()
    {
        $operation = $this->mock("/foo/{id:[0-9+]}", 'noComment');
        
        $this->assertSame('', $operation->getSummary());
        $this->assertSame('', $operation->getDescription());
    }
    
    public function testOperation()
    {
        $operation = $this->mock("/foo/{id:[0-9+]}", 'get');
        
        $this->assertSame('Get method summary', $operation->getSummary());
        $this->assertSame('Get method description', $operation->getDescription());
        $this->assertSame('/foo/{id}', $operation->getPath());
        $this->assertSame('get', $operation->getVerb());
    }
    
    public function testSetTags()
    {
        $operation = $this->mock("", 'deprecated');
        $operation->setTags(['tag']);
        $this->assertSame(['tag'], $operation->getTags());
    }
    
    public function testUploadTags()
    {
        $operation = $this->mock("", 'upload');
        $operation->setTags(['tag']);
        $this->assertSame(['tag'], $operation->getTags());
    }
}
