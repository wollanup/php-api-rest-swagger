<?php

namespace Wollanup\Api\Swagger\Definition;

use Eukles\Entity\EntityRequestInterface;
use Propel\Runtime\Map\ColumnMap;

/**
 * Class DefinitionModel
 *
 * @package Wollanup\Api\Swagger
 */
class DefinitionModelChange extends DefinitionModelAdd
{

    const  SUFFIX = 'Change';

    /**
     * DefinitionModel constructor.
     *
     * @param EntityRequestInterface $entityRequest
     */
    public function __construct(EntityRequestInterface $entityRequest)
    {
        $this->modelProperties = $entityRequest->getModifiableProperties();
        $this->modelRequiredProperties
            = $entityRequest->getRequiredWritableProperties();
        $this->name = str_replace(
            '\\',
            '/',
            get_class($entityRequest) . self::SUFFIX
        );
        $this->buildProperties($entityRequest->getTableMap());
    }
}
