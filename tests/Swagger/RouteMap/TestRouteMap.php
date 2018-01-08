<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 08/01/18
 * Time: 15:49
 */

namespace Wollanup\Api\Swagger\Test\RouteMap;

use Eukles\RouteMap\RouteMapAbstract;
use Wollanup\Api\Swagger\Test\FooAction;

class TestRouteMap extends RouteMapAbstract
{

    /**
     * Routes
     *
     * ```
     * $this->add('GET', '/{id:[0-9]+}')
     *     ->setRoles(['user',])
     *     ->setActionClass(OtherClass::class)
     *     ->setActionMethod('get');
     *```
     *
     * @return mixed
     */
    protected function initialize()
    {
        return $this->get("/")
            ->setActionClass(FooAction::class)
            ->setActionMethod("get");

//        return $this->get("/")
//            ->fetchEntity(EntityFactoryConfig::create()
//                ->setEntityRequest(Request)
//            )
//            ->setActionClass(FooAction::class)
//            ->setActionMethod("get");
    }
}
