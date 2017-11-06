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
     * @var Parameter[]
     */
    protected $properties = [];
    /**
     * @var string
     */
    protected $type = 'object';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Definition
     */
    public function setName(string $name): Definition
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Parameter[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Definition
     */
    public function setType(string $type): Definition
    {
        $this->type = $type;

        return $this;
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
