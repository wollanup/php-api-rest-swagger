<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 06/11/17
 * Time: 14:35
 */

namespace Wollanup\Api\Swagger\Definition\Propel;

class PropelModelPager implements \JsonSerializable
{

    /**
     * @return string
     */
    public function getName()
    {
        return str_replace('\\', '/',
            \Propel\Runtime\Util\PropelModelPager::class);
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            str_replace('\\', '/',
                \Propel\Runtime\Util\PropelModelPager::class) => [
                "type"       => "object",
                "properties" => [
                    "haveToPaginate" => ['type' => 'boolean'],
                    "page"           => ['type' => 'integer', "default" => 1],
                    "firstPage"      => ['type' => 'integer', "default" => 1],
                    "lastPage"       => ['type' => 'integer', "default" => 1],
                    "total"          => ['type' => 'integer', "default" => 1],
                    "first"          => ['type' => 'integer', "default" => 1],
                    "last"           => ['type' => 'integer', "default" => 1],
//                    "links"          => ['type'=> 'array'],
                    "limit"          => ['type' => 'integer', "default" => 20],
//                    "data"           => ['type'=> 'array'],

                ],
            ],
        ];
    }
}
