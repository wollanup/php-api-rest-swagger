<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 06/06/17
 * Time: 11:29
 */

namespace Wollanup\Api\Swagger\Parameter\Sort;

use Wollanup\Api\Swagger\Parameter;

/**
 * Class Property
 *
 * @package Wollanup\Api\Swagger\Parameter\Sort
 */
class SortProperty extends Parameter
{
    
    protected $description = 'Sort by this property';
    protected $name = 'sort[property]';
    protected $required = false;
    protected $type = "string";
}
