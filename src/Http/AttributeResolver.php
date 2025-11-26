<?php

namespace SchulIT\CommonBundle\Http;

use ReflectionClass;
use ReflectionException;

/**
 * @internal
 */
class AttributeResolver {

    /**
     * @template T
     * @param class-string<T> $attributeFqcn
     * @param class-string $controllerFqcn
     * @param string $methodFqcn
     * @return T|null
     * @throws ReflectionException
     */
    public function resolve(string $attributeFqcn, string $controllerFqcn, string $methodFqcn): ?object {
        $reflectionClass = new ReflectionClass($controllerFqcn);
        $reflectionMethod = $reflectionClass->getMethod($methodFqcn);

        $attributes = $reflectionMethod->getAttributes($attributeFqcn);

        // Try to fetch attribute from method
        if(count($attributes) === 1) {
            return $attributes[0]->newInstance();
        }

        // Try to fetch attribute from class
        $attributes = $reflectionClass->getAttributes($attributeFqcn);

        if(count($attributes) === 1) {
            return $attributes[0]->newInstance();
        }

        return null;
    }
}
