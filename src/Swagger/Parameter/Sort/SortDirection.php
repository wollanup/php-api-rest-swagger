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
 * Class Direction
 *
 * @package Wollanup\Api\Swagger\Parameter\Sort
 */
class SortDirection extends Parameter
{
    
    protected $description = 'Sort direction ("DESC" or "ASC").';
    protected $name = 'sort[direction]';
    protected $required = false;
    protected $type = "string";
}
