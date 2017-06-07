<?php

namespace Wollanup\Api\Swagger;

/**
 * Class Definition
 *
 * @package Wollanup\Api\Swagger
 */
class Definition implements \JsonSerializable
{
    
    /**
     * @var string
     */
    protected $name = "";
    /**
     * @var array
     */
    protected $properties = [];
    /**
     * @var string
     */
    protected $type = 'object';
    
    /**
     * @return string
     */
    public function getName()/*: string*/
    {
        return $this->name;
    }
    
    /**
     * @return array
     */
    public function getProperties()/*: array*/
    {
        return $this->properties;
    }
    
    /**
     * @return string
     */
    public function getType()/*: string*/
    {
        return $this->type;
    }
    
    function jsonSerialize()
    {
        return [
            $this->name => [
                "type"       => $this->getType(),
                "properties" => $this->properties,
            ],
        ];
    }
}
