<?php

namespace Recruitment\Tests;

use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /** @var array */
    protected $source = [
        'products' => [],
        'rules' => [],
        'found' => [],
        'matched' => [],
    ];

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->source['products'] = json_decode(file_get_contents(__DIR__ . '/../mocks/products.json'), true);
        $this->source['rules'] = json_decode(file_get_contents(__DIR__ . '/../mocks/rule.json'), true);
        $this->source['found'] = json_decode(file_get_contents(__DIR__ . '/../mocks/found.json'), true);
        $this->source['matched'] = json_decode(file_get_contents(__DIR__ . '/../mocks/matched.json'), true);
    }

    /**
     * @param object $object
     * @param string $methodName
     * @param array $methodParameters
     * @return mixed
     */
    protected function callMethod(object $object, string $methodName, array $methodParameters)
    {
        $class = new \ReflectionClass(get_class($object));
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $methodParameters);
    }

    public function testIfSourceArrayIsValid()
    {
        $this->assertNotEmpty($this->source['products']);
        $this->assertNotEmpty($this->source['rules']);
    }
}