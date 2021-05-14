<?php

trait TestTrait
{
    protected function callMethod(object $service, string $method, array $parameters = [])
    {
        $reflector = new ReflectionObject($service);
        $target = $reflector->getMethod($method);
        $target->setAccessible(true);

        return $target->invoke($service, ...$parameters);
    }

    protected function setProperty(object $item, string $property, $value)
    {
        $reflector = new ReflectionObject($item);
        $target = $reflector->getProperty($property);
        $target->setAccessible(true);
        $target->setValue($item, $value);
    }
}