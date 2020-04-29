<?php

namespace Wollanup\Api\Swagger;

use BadMethodCallException;
use Eukles\Service\Router\RouteInterface;
use JsonSerializable;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class Operation
 *
 * @package Wollanup\Api\Swagger
 */
class Operation implements JsonSerializable
{

    /**
     * @var array
     */
    protected $consumes = ['application/json'];
    /**
     * @var bool
     */
    protected $deprecated = false;
    /**
     * @var
     */
    protected $description = "";
    /**
     * @var bool
     */
    protected $internal = false;
    /**
     * @var string
     */
    protected $operationId;
    /**
     * @var Parameters
     */
    protected $parameters = null;
    /**
     * @var string
     */
    protected $path = "/";
    /**
     * @var array
     */
    protected $produces = ['application/json'];
    /**
     * @var []
     */
    protected $responses;
    /**
     * @var array
     */
    protected $routePattern;
    /**
     * @var string
     */
    protected $summary = "";
    /**
     * @var array
     */
    protected $tags = [];
    /**
     * @var string
     */
    protected $verb = "get";

    /**
     * Operation constructor.
     *
     * @param RouteInterface $route
     * @param array          $routePattern
     * @param Definitions    $definitions
     */
    public function __construct(RouteInterface $route, array $routePattern, Definitions $definitions)
    {

        $this->routePattern = $routePattern;
        $this->deprecated   = $route->isDeprecated();
        $this->path         = $this->buildSwaggerStyleRoutePattern($routePattern);
        $this->verb = strtolower($route->getVerb());
        $this->operationId = $route->getIdentifier();

        $class = $route->getActionClass();
        $rClass = new ReflectionClass($class);

        $method  = $route->getActionMethod();
        if ($rClass->hasMethod($method) === false) {
            throw new BadMethodCallException("Method {$method} Not Found in class {$rClass->getName()}");
        }
        $rMethod = $rClass->getMethod($method);

        $this->buildCommentData($rMethod);

        $this->parameters = new Parameters($rMethod, $route, $this->routePattern, $definitions);

        if ($this->parameters->hasFileUpload()) {
            $this->consumes = ['multipart/form-data'];
        }

        $this->responses = new Responses($rMethod, $route, $this->routePattern);
    }

    /**
     * @param ReflectionMethod $r
     */
    public function buildCommentData(ReflectionMethod $r)
    {
        if (!$r->getDocComment()) {
            return;
        }
        $docBlock = DocBlockFactory::createInstance()->create($r->getDocComment());
        if ($docBlock->getTagsByName('internal')) {
            $this->internal = true;
        }
        if ($docBlock->getTagsByName('deprecated')) {
            $this->deprecated = true;
        }
        $this->summary     = $docBlock->getSummary();
        $this->description = $docBlock->getDescription()->render();
    }

    /**
     * Transform pattern to be swagger compatible (removes placeholder types, optional parts)
     *
     * @param array $routePattern
     *
     * @return string
     */
    public function buildSwaggerStyleRoutePattern(array $routePattern)
    {
        $path = "";
        foreach ($routePattern as $segment) {
            # Array : Placeholder segment {0:"myPathParam",1:"[0-9+]"}
            # String : Simple path segment
            $path .= is_array($segment) ? ('{' . $segment[0] . '}') : $segment;
        }

        return $path;
    }

    /**
     * @return array
     */
    public function getConsumes()/*: array*/
    {
        return $this->consumes;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getOperationId()
    {
        return $this->operationId;
    }

    /**
     * @return Parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getPath()/*: string*/
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getProduces()/*: array*/
    {
        return $this->produces;
    }

    /**
     * @return mixed
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @return array
     */
    public function getRoutePattern()/*: array*/
    {
        return $this->routePattern;
    }

    /**
     * @return string
     */
    public function getSummary()/*: string*/
    {
        return $this->summary;
    }

    /**
     * @return array
     */
    public function getTags()/*: array*/
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     *
     * @return Operation
     */
    public function setTags(array $tags)/*: Operation*/
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return string
     */
    public function getVerb()/*: string*/
    {
        return $this->verb;
    }

    /**
     * @return bool
     */
    public function isDeprecated()/*: bool*/
    {
        return $this->deprecated;
    }

    /**
     * @return bool
     */
    public function isInternal()/*: bool*/
    {
        return $this->internal;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            $this->getPath() => [
                $this->getVerb() => [
                    "deprecated"  => $this->isDeprecated(),
                    "tags"        => $this->getTags(),
                    "operationId" => $this->getOperationId(),
                    "description" => $this->getDescription(),
                    "summary"     => $this->getSummary(),
                    "parameters"  => $this->getParameters(),
                    "responses"   => $this->getResponses(),
                    "security"    => [],
                    "produces"    => $this->getProduces(),
                    "consumes"    => $this->getConsumes(),
                ],
            ],
        ];
    }
}
