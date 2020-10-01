<?php

namespace Wollanup\Api\Swagger;

/**
 * Class TypeHelper
 *
 * @package Wollanup\Api\Swagger
 */
class TypeHelper
{
    
    /**
     * @param $type
     *
     * @return string
     */
    public static function determine(string $type): string
    {
        $type = strtolower($type);
        switch ($type) {
            case 'int':
            case 'float':
                return 'integer';
                break;
            case 'bool':
                return 'boolean';
                break;
            default:
                return $type;
        }
    }
}
