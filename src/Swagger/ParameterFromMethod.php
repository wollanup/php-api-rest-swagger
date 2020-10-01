<?php

namespace Wollanup\Api\Swagger;

use Eukles\Service\Router\RouteInterface;
use Psr\Http\Message\UploadedFileInterface;
use ReflectionMethod;
use ReflectionParameter;
use Wollanup\Api\Swagger\Definition\DefinitionModelAdd;
use Wollanup\Api\Swagger\Definition\DefinitionModelChange;

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
     * @param ReflectionMethod $r
     * @param ReflectionParameter $param
     * @param RouteInterface $route
     */
    public function __construct(
        ReflectionMethod $r,
        ReflectionParameter $param,
        RouteInterface $route
    ) {
        $this->name = $param->getName();
        $this->required = ($param->isOptional()
                || $param->isDefaultValueAvailable()) === false;
        $this->default = $param->isDefaultValueAvailable()
            ? $param->getDefaultValue() : null;

        if ($param->getClass() !== null) {
            $class = $param->getClass();

            // TODO test make collection
            if ($class->implementsInterface(UploadedFileInterface::class)) {
                $this->fileUpload = true;
                $this->setIn(self::IN_FORM_DATA)
                    ->setType('file')
                    ->setDescription('File upload');
            } else {
                if ($route->hasEntity($param->getName())) {
                    $config = $route->getEntityConfig($param->getName());
                    $this->setIn(self::IN_BODY);
                    $suffix = "";
                    // POST
                    if ($config->isTypeCreate()) {
                        $suffix = DefinitionModelAdd::SUFFIX;
                    }
                    // PATCH
                    if ($config->isTypeFetch() && $route->getVerb() === 'PATCH') {
                        $suffix = DefinitionModelChange::SUFFIX;
                    }
                    $this->setSchema($config->getEntityRequest() . $suffix);
                    $shortName = substr(strrchr($this->schema, '\\'), 1);
                    $this->setDescription("{$shortName} object");
                } else {
                    $this->setIn(self::IN_BODY);
                    $this->setName($this->name);
                    $this->setType("object");
                    $this->setRequired($this->required);
                    $this->setDescription($this->name . " object");
                }
            }
        } elseif (PHP_VERSION_ID > 70000) {
            $reflectionType = $param->getType();
            if ($reflectionType) {
                $this->type = TypeHelper::determine($param->getType()
                    ->__toString());
            }
        }
    }
}
