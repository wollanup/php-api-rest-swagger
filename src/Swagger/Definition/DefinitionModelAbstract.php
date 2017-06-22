<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 05/06/17
 * Time: 13:32
 */

namespace Wollanup\Api\Swagger\Definition;

use Propel\Runtime\Map\TableMap;
use Wollanup\Api\Swagger\Definition;
use Wollanup\Api\Swagger\Parameter;

/**
 * Class DefinitionModel
 *
 * @package Wollanup\Api\Swagger
 */
abstract class DefinitionModelAbstract extends Definition implements \JsonSerializable
{
    
    protected $modelProperties;
    protected $type = 'object';
    
    
    /**
     * @param TableMap $tableMap
     */
    public function buildProperties(TableMap $tableMap)
    {
        foreach ($this->modelProperties as $property) {
            $columnMap = $tableMap->getColumnByPhpName($property);
            $param     = new Parameter();
            if ($columnMap->isForeignKey()) {
                $param->setDescription("Related " . $columnMap->getRelation()->getForeignTable()->getPhpName() . "ID");
            } else {
                $param->setDescription("{$property} field");
            }
            $param->setName(lcfirst($property));
            $param->setType($this->convertType($columnMap->getType()));
            $param->setRequired($columnMap->isNotNull());
            if ($columnMap->getDefaultValue()) {
                $param->setDefault($columnMap->getDefaultValue());
            }
            # Array
            if ($param->isTypeArray()) {
                $param->setItems(["type" => "string"]);
                if ($columnMap->getValueSet()) {
                    $param->setEnum($columnMap->getValueSet());
                }
            }
            
            # Enum
            if ($columnMap->getValueSet()) {
                $param->setEnum($columnMap->getValueSet());
            }
    
            $this->properties[lcfirst($property)] = $param;
        }
    }
    
    /**
     * @param $propelType
     *
     * @return string
     */
    public function convertType($propelType)
    {
        switch ($propelType) {
            case "VARCHAR":
            case "LONGVARCHAR":
            case "TIMESTAMP":
            case "OBJECT":
                return "string";
                break;
            case "BOOLEAN":
                return "boolean";
                break;
            case "TINYINT":
            case "SMALLINT":
            case "INTEGER":
                return "integer";
                break;
            case "ENUM":
                return "array";
                break;
            default:
                return $propelType;
        }
    }
    
    /**
     * @return array
     */
    function jsonSerialize()
    {
        return [
            $this->name => [
                "type"       => "object",
                "properties" => $this->properties,
            ],
        ];
    }
}