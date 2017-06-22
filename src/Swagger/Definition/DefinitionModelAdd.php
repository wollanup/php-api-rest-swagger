<?php

namespace Wollanup\Api\Swagger\Definition;

use Eukles\Entity\EntityRequestInterface;
use Propel\Runtime\Map\TableMap;

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
        $this->modelProperties         = $entityRequest->getWritableProperties();
        $this->modelRequiredProperties = $entityRequest->getRequiredWritableProperties();
        $this->name                    = get_class($entityRequest) . self::SUFFIX;
        $this->buildProperties($entityRequest->getTableMap());
    }
    
    public function buildProperties(TableMap $tableMap)
    {
        parent::buildProperties($tableMap);
        
        foreach ($this->properties as $name => $param) {
            $param->setRequired(in_array(ucfirst($name), $this->modelRequiredProperties));
        }
    }
}
