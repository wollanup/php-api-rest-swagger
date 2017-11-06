<?php

namespace Wollanup\Api\Swagger;

/**
 * Class SchemaHelper
 *
 * @package Wollanup\Api\Swagger
 */
class SchemaHelper
{

    /**
     * @param $className
     *
     * @return array
     */
    public static function build($className)
    {
        return [
            '$ref' => '#/definitions/' . str_replace('\\', '/', $className),
        ];
    }
}
