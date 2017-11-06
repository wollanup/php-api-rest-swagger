<?php

namespace Wollanup\Api\Swagger;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Wollanup\Api\Swagger\Definition\DefinitionModelSend;

class Responses implements \JsonSerializable
{

    /**
     * @var string
     */
    protected $contentType = 'application/json';
    /**
     * @var array|null
     */
    protected $schema = ['type' => 'object'];

    public function __construct(\ReflectionMethod $r)
    {
        if (PHP_VERSION_ID > 70000) {
            if ($r->hasReturnType()) {
                $type = $r->getReturnType()->__toString();
                if (class_exists($type)) {
                    $rc = new \ReflectionClass($r->getReturnType()
                        ->__toString());
                    if ($rc->implementsInterface(ActiveRecordInterface::class)) {
                        $this->schema = SchemaHelper::build($type
                            . DefinitionModelSend::SUFFIX);
                    } else {
                        $this->schema = SchemaHelper::build($type);
                    }
                } else {
                    $this->schema['type'] = TypeHelper::determine($type);
                }
            }
        }
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $responses = [
            "default" => [
                "description" => "OK",
                /**
                 * content => application/json is for V3, we use V2
                 */
//                "content"     => [
//                    $this->contentType => [
                'schema'      => $this->schema
//                    ],
//                ],
            ],
//            '4XX' => [
//                "description" => "Error",
//            ],
//            '5XX' => [
//                "description" => "Unexpected error",
//            ],
        ];

        return $responses;
    }
}
