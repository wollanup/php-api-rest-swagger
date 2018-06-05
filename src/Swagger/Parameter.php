<?php

namespace Wollanup\Api\Swagger;

/**
 * Class Parameter
 *
 * @package Wollanup\Api\Swagger
 */
class Parameter implements \JsonSerializable
{

    const IN_FORM_DATA = 'formData';
    const IN_QUERY = 'query';
    const IN_PATH = 'path';
    const IN_BODY = 'body';
    /**
     * Sets a default value to the parameter.
     * The type of the value depends on the defined type.
     * See http://json-schema.org/latest/json-schema-validation.html#anchor101.
     *
     * @var mixed
     */
    protected $default = null;
    /**
     * @var string
     */
    protected $description = "";
    /**
     * @var null|array
     */
    protected $enum = null;
    /**
     * @var bool
     */
    protected $fileUpload = false;
    /**
     * @var string
     */
    protected $format = "";
    /**
     *  The location of the parameter.
     * Possible values are "query", "header", "path", "formData" or "body".
     *
     * @var string
     */
    protected $in = "query";
    /**
     * @var null|array
     */
    protected $items = null;
    /**
     * The name of the parameter.
     * Parameter names are case sensitive.
     *
     * @var string
     */
    protected $name = "";
    /**
     * @var bool
     */
    protected $required = true;
    /**
     *  The schema defining the type used for the body parameter.
     *
     * @var null
     */
    protected $schema = null;
    /**
     * The type of the parameter.
     * Since the parameter is not located at the request body, it is limited to simple types (that is, not an object).
     * The value MUST be one of "string", "number", "integer", "boolean", "array" or "file".
     * If type is "file", the consumes MUST be either "multipart/form-data" or " application/x-www-form-urlencoded" and the parameter MUST be in "formData".
     *
     * @var null
     */
    protected $type = "string";

    /**
     * Parameter constructor.
     *
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     *
     * @return Parameter
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()/*: string*/
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Parameter
     */
    public function setDescription(/*string*/
        $description
    )/*: Parameter*/
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getEnum()
    {
        return $this->enum;
    }

    /**
     * @param array|null $enum
     *
     * @return Parameter
     */
    public function setEnum(array $enum)
    {
        $this->enum = $enum;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return Parameter
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getIn()/*: string*/
    {
        return $this->in;
    }

    /**
     * @param string $in
     *
     * @return Parameter
     */
    public function setIn(/*string*/
        $in
    )/*: Parameter*/
    {
        $this->in = $in;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array|null $items
     *
     * @return Parameter
     */
    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()/*: string*/
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Parameter
     */
    public function setName(/*string*/
        $name
    )/*: Parameter*/
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param null $schema
     *
     * @return Parameter
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     *
     * @return Parameter
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function hasDefault()
    {
        return $this->default !== null;
    }

    /**
     * @return bool
     */
    public function isFileUpload()/*: bool*/
    {
        return $this->fileUpload;
    }

    /**
     * @return bool
     */
    public function isRequired()/*: bool*/
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return Parameter
     */
    public function setRequired(/*bool*/
        $required
    )/*: Parameter*/
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTypeArray(): bool
    {
        return $this->type === 'array';
    }

    /**
     * @return bool
     */
    public function isTypeString(): bool
    {
        return $this->type === 'string';
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
        $param = [
            "name"        => $this->getName(),
            "in"          => $this->getIn(),
            "description" => $this->getDescription(),
            "required"    => $this->isRequired(),
        ];
        if ($param['in'] === 'body') {
            $param["schema"] = SchemaHelper::build($this->getSchema());
        } else {
            $param["type"] = $this->getType();
            if ($this->hasDefault()) {
                $param["default"] = $this->getDefault();
            }
        }
        if ($this->isTypeArray()) {
            $param["collectionFormat"] = "brackets";
            $param["items"] = [
                "type" => "string", // TODO determine type
            ];
        }
        if ($this->getEnum()) {
            $param['enum'] = $this->getEnum();
        }

        return $param;
    }
}
