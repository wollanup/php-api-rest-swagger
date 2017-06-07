<?php

namespace Wollanup\Api\Swagger\Parameter\Pagination;

use Eukles\Service\Pagination\PaginationInterface;
use Wollanup\Api\Swagger\Parameter;

/**
 * Class Page
 *
 * @package Wollanup\Api\Swagger\Parameter\Pagination
 */
class PaginationPage extends Parameter
{
    
    protected $default = PaginationInterface::DEFAULT_PAGE;
    protected $description = "The number of the current page";
    protected $name = 'page';
    protected $required = false;
    protected $type = "integer";
}
