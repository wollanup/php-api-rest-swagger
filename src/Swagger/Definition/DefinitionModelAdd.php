<?php

namespace Wollanup\Api\Swagger\Definition;

use Eukles\Entity\EntityRequestInterface;

/**
 * Class DefinitionModel
 *
 * @package Wollanup\Api\Swagger
 */
class DefinitionModelAdd extends DefinitionModel
{
    
    const  SUFFIX = 'Add';
    
    /**
     * DefinitionModel constructor.
     *
     * @param EntityRequestInterface $entityRequest
     */
    public function __construct(EntityRequestInterface $entityRequest)
    {
        $this->modelProperties = $entityRequest->getWritableProperties();
        $this->name            = get_class($entityRequest->instantiateActiveRecord()) . self::SUFFIX;
        $this->buildProperties($entityRequest->getTableMap());
    }
}
