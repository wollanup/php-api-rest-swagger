<?php

namespace Wollanup\Api\Swagger\Parameter\Filter;

use Wollanup\Api\Swagger\Parameter;

/**
 * Class Value
 *
 * @package Wollanup\Api\Swagger\Parameter\Filter
 */
class FilterOperator extends Parameter
{
    
    protected $description = 'Operator used to filter results. Default is "=". Standard SQL operators are available';
    protected $name = 'filter[0][operator]';
    protected $required = false;
    protected $type = "string";
}
