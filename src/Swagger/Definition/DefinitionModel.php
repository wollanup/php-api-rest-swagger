<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 05/06/17
 * Time: 13:32
 */

namespace Wollanup\Api\Swagger\Definition;

use Eukles\Entity\EntityRequestInterface;
use Propel\Runtime\Map\TableMap;
use Wollanup\Api\Swagger\Definition;

/**
 * Class DefinitionModel
 *
 * @package Wollanup\Api\Swagger
 */
class DefinitionModel extends Definition implements \JsonSerializable
{
    
    protected $modelProperties;
    protected $type = 'object';
    
    /**
     * DefinitionModel constructor.
     *
     * @param EntityRequestInterface $entityRequest
     */
    public function __construct(EntityRequestInterface $entityRequest)
    {
        $this->modelProperties = $entityRequest->getExposedProperties();
        $this->name            = get_class($entityRequest->instantiateActiveRecord());
        $this->buildProperties($entityRequest->getTableMap());
    }
    
    /**
     * @param TableMap $tableMap
     */
    public function buildProperties(TableMap $tableMap)
    {
        foreach ($this->modelProperties as $property) {
            $columnMap = $tableMap->getColumnByPhpName($property);
            
            $propertyData = [
                "type" => $this->convertType($columnMap->getType()),
            ];
            
            # Default
            $defaultValue = $columnMap->getDefaultValue();
            if ($defaultValue) {
                $propertyData["default"] = $columnMap->getDefaultValue();
            }
            
            # Array
            if ($propertyData["type"] === "array") {
                $propertyData["items"] = ["type" => "string"];
                if ($columnMap->getValueSet()) {
                    $propertyData["enum"] = $columnMap->getValueSet();
                }
            }
            
            # Enum
            if ($columnMap->getValueSet()) {
                $propertyData["items"]["enum"] = $columnMap->getValueSet();
            }
            
            $this->properties[lcfirst($property)] = $propertyData;
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
