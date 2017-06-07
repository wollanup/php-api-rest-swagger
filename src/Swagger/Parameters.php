<?php

namespace Wollanup\Api\Swagger;

use Eukles\Service\Pagination\PaginationInterface;
use Eukles\Service\QueryModifier\QueryModifierInterface;
use Eukles\Service\Router\RouteInterface;
use Eukles\Util\DataIterator;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Wollanup\Api\Swagger\Parameter\Filter\FilterOperator;
use Wollanup\Api\Swagger\Parameter\Filter\FilterProperty;
use Wollanup\Api\Swagger\Parameter\Filter\FilterValue;
use Wollanup\Api\Swagger\Parameter\Pagination\PaginationLimit;
use Wollanup\Api\Swagger\Parameter\Pagination\PaginationPage;
use Wollanup\Api\Swagger\Parameter\Sort\SortDirection;
use Wollanup\Api\Swagger\Parameter\Sort\SortProperty;

/**
 * Class Parameters
 *
 * @property  Parameter[] $data
 * @package Wollanup\Api\Swagger
 */
class Parameters extends DataIterator implements \JsonSerializable
{
    
    /**
     * @var bool
     */
    protected $fileUpload = false;
    /**
     * @var array
     */
    protected $pathParameters = [];
    
    /**
     * Parameters constructor.
     *
     * @param \ReflectionMethod $r
     * @param RouteInterface    $route
     * @param array             $routePattern
     */
    public function __construct(\ReflectionMethod $r, RouteInterface $route, array $routePattern)
    {
        $this->buildPathParameters($routePattern);
        foreach ($r->getParameters() as $param) {
            $class = $param->getClass();
            // When param is already present in path, just skip it
            if (in_array($param->getName(), $this->pathParameters)) {
                continue;
            }
            // When param is a fetched resource instance, just skip it
            
            if ($class && $class->implementsInterface(ActiveRecordInterface::class) && $route->isMakeInstanceFetch()) {
                continue;
            }
            if ($class) {
                
                if ($class->implementsInterface(QueryModifierInterface::class)) {
                    $this->data[] = new SortProperty;
                    $this->data[] = new SortDirection;
                    $this->data[] = new FilterProperty();
                    $this->data[] = new FilterOperator();
                    $this->data[] = new FilterValue();
                    continue;
                }
                if ($class->implementsInterface(PaginationInterface::class)) {
                    $this->data[] = new PaginationPage;
                    $this->data[] = new PaginationLimit;
                    continue;
                }
            }
            $parameter    = new ParameterFromMethod($param, $route);
            $this->data[] = $parameter;
            
            if ($parameter->isFileUpload()) {
                $this->fileUpload = true;
            }
        }
    }
    
    /**
     * @param array $routePattern
     */
    public function buildPathParameters(array $routePattern)
    {
        foreach ($routePattern as $segment) {
            if (is_array($segment)) {
                $typeRegExp = "#({$segment[1]})#";
                $parameter  = new Parameter;
                $parameter->setName($segment[0])
                    ->setType((preg_match($typeRegExp, 1) && !preg_match($typeRegExp, 'a')) ? "integer" : "string")
                    ->setIn('path');
                $this->data[]           = $parameter;
                $this->pathParameters[] = $segment[0];
            }
        }
    }
    
    /**
     * @return bool
     */
    public function hasFileUpload()/*: bool*/
    {
        return $this->fileUpload;
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
        foreach ($this->data as $parameter) {
            $return[] = $parameter->jsonSerialize();
        }
        
        return $return;
    }
}
