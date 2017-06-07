<?php

namespace Wollanup\Api\Swagger\Parameter\Pagination;

use Eukles\Service\Pagination\PaginationInterface;
use Wollanup\Api\Swagger\Parameter;

/**
 * Class Limit
 *
 * @package Wollanup\Api\Swagger\Parameter\Pagination
 */
class PaginationLimit extends Parameter
{
    
    protected $default = PaginationInterface::DEFAULT_LIMIT;
    protected $description = "The number of items per page";
    protected $name = 'limit';
    protected $required = false;
    protected $type = "integer";
}
