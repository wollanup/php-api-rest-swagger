<?php

namespace Wollanup\Api\Swagger;

class Responses implements \JsonSerializable
{
    
    /**
     * @var array|null
     */
    protected $schema;
    
    public function __construct(\ReflectionMethod $r)
    {
        if ($r->hasReturnType()) {
            $type = $r->getReturnType()->__toString();
            if (class_exists($type)) {
                $this->schema = SchemaHelper::build($type);
            } else {
//                $this->type = TypeHelper::determine($type);
            }
        }
////        \Core\Util\Debug::dump($r->getReturnType());
//        $comment = $r->getDocComment();
//        if (!$comment) {
//            return;
//        }
//
//        $factory = DocBlockFactory::createInstance();
//        if ($r) {
//            $docBlock = $factory->create($r);
//            $return   = $docBlock->getTagsByName('return');
////            \Core\Util\Debug::dump($return);
//        }
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
        $responses = [
            "default" => [
                "description" => "response",
            ],
        ];
        if ($this->schema) {
            $responses['default']['schema'] = $this->schema;
        }
        
        return $responses;
    }
}
