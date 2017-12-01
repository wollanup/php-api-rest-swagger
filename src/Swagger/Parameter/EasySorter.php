<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 06/06/17
 * Time: 11:29
 */

namespace Wollanup\Api\Swagger\Parameter;

use Wollanup\Api\Swagger\Parameter;

/**
 * Class Property
 *
 * @package Wollanup\Api\Swagger\Parameter\Sort
 */
class EasySorter extends Parameter
{

    protected $description
        = "Sort results by properties." . PHP_EOL . PHP_EOL .
        'Comma separated list of properties' . PHP_EOL . PHP_EOL .
        '- ASC  : `+property` or `property`' . PHP_EOL .
        '- DESC : `-property`' . PHP_EOL . PHP_EOL .
        '```' . PHP_EOL .
        'sort=-id,name' . PHP_EOL .
        '```' . PHP_EOL;
    protected $name = 'sort';
    protected $required = false;
    protected $type = "string";
}
