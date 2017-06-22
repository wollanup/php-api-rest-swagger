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
     * @param \ReflectionMethod    $r
     * @param \ReflectionParameter $param
     * @param RouteInterface       $route
     */
    public function __construct(\ReflectionMethod $r, \ReflectionParameter $param, RouteInterface $route)
    {
        $this->name     = $param->getName();
        $this->required = !$param->isOptional();
        $this->default  = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null;
        if ($param->getClass() !== null) {
            $class = $param->getClass();
            
            // TODO test make collection
            if ($class->implementsInterface(UploadedFileInterface::class)) {
                $this->fileUpload = true;
                $this->setIn(self::IN_FORM_DATA)
                    ->setType('file')
                    ->setDescription('File upload');
            } else {
                if ($route->isMakeInstance()) {
                    $this->setIn(self::IN_BODY);
                    $suffix = "";
                    if ($route->isMakeInstanceCreate()) {
                        $suffix = DefinitionModelAdd::SUFFIX;
                    }
                    $this->setSchema($route->getRequestClass() . $suffix);
                    $shortName = substr(strrchr($this->schema, '\\'), 1);
                    $this->setDescription("{$shortName} object");
                }
            }
        } elseif (PHP_VERSION_ID > 70000) {
            $reflectionType = $param->getType();
            if ($reflectionType) {
                $this->type = TypeHelper::determine($param->getType()->__toString());
            }
        }
    }
}
