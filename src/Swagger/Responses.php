<?php

namespace Wollanup\Api\Swagger;

use Eukles\Service\Router\HttpStatus;
use Eukles\Service\Router\RouteInterface;
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
    protected $responses
        = [
            "default" => [
                "description" => "OK",
                /**
                 * content => application/json is for V3, we use V2
                 */
//                "content"     => [
//                    $this->contentType => [
                'schema'      => ['type' => 'object']
//                    ],
//                ],
            ],
        ];

    public function __construct(\ReflectionMethod $r, RouteInterface $route)
    {

        /** @var HttpStatus $status */
        $statuses = $route->getStatuses();
        if (empty($statuses)) {
            if ($r->hasReturnType()) {
                $schema = $this->buildSchemaFromReturnType($r->getReturnType()->__toString());

                $this->responses["default"]["schema"] = $schema;
            }
        } else {
            foreach ($statuses as $status) {
                $schema = ['type' => 'object'];
                if ($status->isMainSuccess()) {
                    if (isset($this->responses['default'])) {
                        unset($this->responses['default']);
                    }
                    if ($r->hasReturnType()) {
                        $schema = $this->buildSchemaFromReturnType($r->getReturnType()->__toString());
                    }
                }

                $this->responses[$status->getStatus()] = [
                    "description" => $status->getDescription(),
                    "schema"      => $schema,
                ];
            }
        }
    }

    public function buildSchemaFromReturnType($type)
    {
        if (class_exists($type)) {
            $rc = new \ReflectionClass($type);
            if ($rc->implementsInterface(ActiveRecordInterface::class)) {
                $schema = SchemaHelper::build($type . DefinitionModelSend::SUFFIX);
            } else {
                $schema = SchemaHelper::build($type);
            }
        } else {
            $schema['type'] = TypeHelper::determine($type);
        }

        return $schema;
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


        return $this->responses;
    }
}
