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
class Fields extends Parameter
{

    protected $description
        = "Restrict fields in answer." . PHP_EOL . PHP_EOL .
        'Comma separated list of properties' . PHP_EOL . PHP_EOL .
        '```' . PHP_EOL .
        'fields=name,id' . PHP_EOL .
        '```' . PHP_EOL . PHP_EOL .
        'will output:' . PHP_EOL . PHP_EOL .
        '```json' . PHP_EOL .
        '[{"name":"first","id":1},{"name":"second","id":2}]' . PHP_EOL . PHP_EOL .
        '```' . PHP_EOL . PHP_EOL .
        'If only one field is asked, return will be an array of values instead of object' . PHP_EOL . PHP_EOL .
        '```' . PHP_EOL .
        'fields=name' . PHP_EOL .
        '```' . PHP_EOL . PHP_EOL .
        'will output:' . PHP_EOL . PHP_EOL .
        '```json' . PHP_EOL .
        '["first","second"]' . PHP_EOL . PHP_EOL .
        '```' . PHP_EOL . PHP_EOL;
    protected $name = 'fields';
    protected $required = false;
    protected $type = "string";
}
