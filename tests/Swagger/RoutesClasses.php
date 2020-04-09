<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 18/01/17
 * Time: 16:58
 */

namespace Wollanup\Api\Swagger\Test;

use Core\Util\IO;
use Eukles\Container\ContainerInterface;
use Eukles\RouteMap\RouteMapInterface;
use Eukles\Service\RoutesClasses\RoutesClassesInterface;
use Nette\Reflection\AnnotationsParser;
use Wollanup\Api\Swagger\Test\RouteMap\TestRouteMap;
use Wollanup\Api\Util\DataIterator;

/**
 * Class RoutesClasses
 *
 * @package Ged\Service
 */
class RoutesClasses extends DataIterator implements RoutesClassesInterface
{

    /**
     * @var RouteMapInterface[]
     */
    protected $classes = [];

    /**
     * RoutesClasses constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->data[] = new TestRouteMap($container);
    }
}
