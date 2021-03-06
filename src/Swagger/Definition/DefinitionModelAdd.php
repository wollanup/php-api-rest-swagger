<?php

namespace Wollanup\Api\Swagger\Definition;

use Eukles\Entity\EntityRequestInterface;
use Propel\Runtime\Map\ColumnMap;
use Wollanup\Api\Swagger\Parameter;

/**
 * Class DefinitionModel
 *
 * @package Wollanup\Api\Swagger
 */
class DefinitionModelAdd extends DefinitionModelAbstract
{

    const  SUFFIX = 'Add';
    protected $modelRequiredProperties;

    /**
     * DefinitionModel constructor.
     *
     * @param EntityRequestInterface $entityRequest
     */
    public function __construct(EntityRequestInterface $entityRequest)
    {
        $this->modelProperties = $entityRequest->getWritableProperties();
        $this->modelRequiredProperties
                               = $entityRequest->getRequiredWritableProperties();
        $this->name            = str_replace('\\', '/',
            get_class($entityRequest) . self::SUFFIX);
        $this->buildProperties($entityRequest->getTableMap());
    }

    protected function hookBuildAfter(ColumnMap $columnMap, Parameter $param)
    {
        $param->setRequired(in_array(ucfirst($columnMap->getPhpName()),
            $this->modelRequiredProperties));
    }

    /**
     * Skip property in definition if it's an auto increment PK
     *
     * @param ColumnMap $columnMap
     * @param Parameter $param
     * @return bool
     */
    protected function hookBuildBefore(ColumnMap $columnMap, Parameter $param)
    {
        if ($columnMap->isPrimaryKey() && $columnMap->getTable()->isUseIdGenerator()) {
            return false;
        }
        return true;
    }
}
