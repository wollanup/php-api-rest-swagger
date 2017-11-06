<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 05/06/17
 * Time: 13:32
 */

namespace Wollanup\Api\Swagger\Definition;

use Eukles\Entity\EntityRequestInterface;

/**
 * Class DefinitionModel
 *
 * @package Wollanup\Api\Swagger
 */
class DefinitionModelRead extends DefinitionModelAbstract
{

    /**
     * DefinitionModel constructor.
     *
     * @param EntityRequestInterface $entityRequest
     */
    public function __construct(EntityRequestInterface $entityRequest)
    {
        $this->modelProperties = $entityRequest->getExposedProperties();
        $this->name            = str_replace('\\', '/',
            get_class($entityRequest));
        $this->buildProperties($entityRequest->getTableMap());
    }
}
