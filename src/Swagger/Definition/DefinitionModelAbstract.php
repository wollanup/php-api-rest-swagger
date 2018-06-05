<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 05/06/17
 * Time: 13:32
 */

namespace Wollanup\Api\Swagger\Definition;

use Propel\Runtime\Map\ColumnMap;
use Propel\Runtime\Map\TableMap;
use Wollanup\Api\Swagger\Definition;
use Wollanup\Api\Swagger\Parameter;

/**
 * Class DefinitionModel
 *
 * @package Wollanup\Api\Swagger
 */
abstract class DefinitionModelAbstract
    extends Definition
    implements \JsonSerializable
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

            if ($this->hookBuildBefore($columnMap, $param) === false) {
                continue;
            };

            if ($columnMap->isForeignKey()) {
                # Use try because "$columnMap->getRelation()" can fail with some polymorphic relations
                try {
                    $param->setDescription(
                        "Foreign key to " . $columnMap->getRelation()->getForeignTable()->getPhpName() . "ID"
                    );
                } catch (\Throwable $e) {
                    $param->setDescription("Foreign key");
                }
            } else {
                $param->setDescription("{$property} field");
            }
            $param->setName(lcfirst($property));
            $param->setType($this->convertType($columnMap->getType()));
            if ($param->isTypeString()) {
                $param->setFormat($this->convertFormat($columnMap->getType()));
            }
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

            if ($this->hookBuildAfter($columnMap, $param) === false) {
                continue;
            };

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
            case "OBJECT":
            case "CHAR":
            case "DATE":
            case "TIMESTAMP":
                return "string";
                break;
            case "BOOLEAN":
                return "boolean";
                break;
            case "TINYINT":
            case "SMALLINT":
            case "BIGINT":
            case "INTEGER":
                return "integer";
                break;
            case "BIGINT":
                return "number";
                break;
            case "ENUM":
                return "array";
                break;
            default:
                return $propelType;
        }
    }

    /**
     * @param $propelType
     *
     * @return string
     */
    public function convertFormat($propelType)
    {
        switch ($propelType) {
            case "DATE":
                return "date";
            case "TIMESTAMP":
                return "date-time";
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

    /**
     * @param ColumnMap $columnMap
     * @param Parameter $param
     */
    protected function hookBuildAfter(ColumnMap $columnMap, Parameter $param)
    {

    }

    /**
     * @param ColumnMap $columnMap
     * @param Parameter $param
     */
    protected function hookBuildBefore(ColumnMap $columnMap, Parameter $param)
    {

    }
}
