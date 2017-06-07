<?php

namespace Wollanup\Api\Swagger;

use Eukles\Service\Router\RouteInterface;
use Psr\Http\Message\UploadedFileInterface;
use Wollanup\Api\Swagger\Definition\DefinitionModelAdd;

/**
 * Class MethodParameter
 *
 * @package Wollanup\Api\Swagger
 */
class ParameterFromMethod extends Parameter
{
    
    /**
     * Parameter constructor.
     *
     * @param \ReflectionParameter $param
     * @param RouteInterface       $route
     */
    public function __construct(\ReflectionParameter $param, RouteInterface $route)
    {
        $this->name     = $param->getName();
        $this->required = !$param->isOptional();
        $this->default  = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
        if ($param->getClass() !== null) {
            if ($route->isMakeInstanceCreate()) {
                $this->in = 'body';
            }
            $class = $param->getClass();
            if ($route->isMakeInstance()) {
                $suffix = "";
                if ($route->isMakeInstanceFetch()) {
                    $suffix = DefinitionModelAdd::SUFFIX;
                }
                $this->schema = SchemaHelper::build($param->getClass()->getName() . $suffix);
            }
            
            // TODO test make collection
            if ($class->implementsInterface(UploadedFileInterface::class)) {
                $this->fileUpload = true;
                $this->in         = 'formData';
                $this->type       = 'file';
            }
        } else {
            $reflectionType = $param->getType();
            if ($reflectionType) {
                $this->type = TypeHelper::determine($param->getType()->__toString());
            }
        }
    }
}
