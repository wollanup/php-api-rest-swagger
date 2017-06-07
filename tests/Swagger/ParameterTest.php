<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 07/06/17
 * Time: 14:55
 */

namespace Wollanup\Api\Swagger\Test;

use PHPUnit\Framework\TestCase;
use Wollanup\Api\Swagger\Parameter;

class ParameterTest extends TestCase
{
    
    public function testDefault()
    {
        $p = new Parameter();
        $p->setDefault('test');
        $this->assertSame('test', $p->jsonSerialize()['default']);
    }
    
    public function testDescription()
    {
        $p = new Parameter();
        $p->setDescription('description');
        $this->assertSame('description', $p->jsonSerialize()["description"]);
    }
    
    public function testNoDefault()
    {
        $p = new Parameter();
        $this->assertArrayNotHasKey('default', $p->jsonSerialize());
    }
    
    public function testRequired()
    {
        $p = new Parameter();
        $p->setRequired(true);
        $this->assertTrue($p->jsonSerialize()["required"]);
    }
    
    public function testSchemaWhenParamIsInBody()
    {
        $p = new Parameter();
        $p->setSchema('schema');
        $this->assertArrayNotHasKey('schema', $p->jsonSerialize());
        $p->setIn('body');
        $this->assertArrayHasKey('schema', $p->jsonSerialize());
    }
    
    public function testTypeArray()
    {
        $p = new Parameter();
        $p->setType('array');
        $this->assertArrayHasKey('items', $p->jsonSerialize());
        $this->assertSame('string', $p->jsonSerialize()["items"]["type"]);
    }
}
