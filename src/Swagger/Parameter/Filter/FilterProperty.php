<?php

namespace Wollanup\Api\Swagger\Parameter\Filter;

use Wollanup\Api\Swagger\Parameter;

/**
 * Class Property
 *
 * @package Wollanup\Api\Swagger\Parameter\Filter
 */
class FilterProperty extends Parameter
{
    
    protected $description = 'Filter on this property';
    protected $name = 'filter[0][property]';
    protected $required = false;
    protected $type = "string";
}
