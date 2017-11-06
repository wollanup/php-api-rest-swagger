<?php

namespace Wollanup\Api\Swagger\Definition;

use Eukles\Entity\EntityRequestInterface;

/**
 * Class DefinitionModel
 *
 * @package Wollanup\Api\Swagger
 */
class DefinitionModelSend extends DefinitionModelAbstract
{

    const  SUFFIX = 'Send';
    /**
     * @var array
     */
    protected $modelRelations = [];

    /**
     * DefinitionModel constructor.
     *
     * @param EntityRequestInterface $entityRequest
     */
    public function __construct(EntityRequestInterface $entityRequest)
    {
        $this->modelProperties = $entityRequest->getExposedProperties();
        $this->modelRelations  = $entityRequest->getExposedRelations();
        $this->name            = str_replace('\\', '/',
            get_class($entityRequest->instantiateActiveRecord())
            . self::SUFFIX);
        $this->buildProperties($entityRequest->getTableMap());
    }

    /**
     * @todo
     * NOT READY YET
     * try to add some related model info
     */
//    public function buildProperties(TableMap $tableMap)
//    {
//        parent::buildProperties($tableMap);
//
//        foreach ($this->modelRelations as $property) {
//            $param     = new Parameter();
//            $param->setDescription("Related " . lcfirst($property));
//            $param->setName(lcfirst($property));
//            $param->setType('object');
//            $this->properties[lcfirst($property)] = $param;
//        }
//    }
}
