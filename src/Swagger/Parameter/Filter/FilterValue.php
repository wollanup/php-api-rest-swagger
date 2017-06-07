<?php

namespace Wollanup\Api\Swagger\Parameter\Filter;

use Wollanup\Api\Swagger\Parameter;

/**
 * Class Value
 *
 * @package Wollanup\Api\Swagger\Parameter\Filter
 */
class FilterValue extends Parameter
{
    
    protected $description = 'Filter by this value';
    protected $name = 'filter[0][value]';
    protected $required = false;
    protected $type = "string";
}
