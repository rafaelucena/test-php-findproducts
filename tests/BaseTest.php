<?php

namespace Recruitment\Tests;

use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @param object $object
     * @param string $methodName
     * @param array $methodParameters
     * @return void
     */
    protected function callMethod(object $object, string $methodName, array $methodParameters)
    {
        $class = new \ReflectionClass(get_class($object));
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $methodParameters);
    }
}