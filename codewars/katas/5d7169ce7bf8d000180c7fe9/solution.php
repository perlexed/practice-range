<?php

/**
 * @link https://www.codewars.com/kata/5d7169ce7bf8d000180c7fe9
 * @param string $mystery
 * @throws MysterySolvedException
 * @throws ReflectionException
 */
function solveTheMystery(string $mystery)
{
    $mysteryReflection = new ReflectionClass($mystery);

    $mysteryInstance = !$mysteryReflection->isAbstract()
        ? $mysteryReflection->newInstanceWithoutConstructor()
        : null;

    foreach ($mysteryReflection->getProperties() as $property) {
        if (strpos($property->getDocComment(), '@condition') !== false) {
            if (!$property->isPublic()) {
                $property->setAccessible(true);
            }
            $property->setValue($mysteryInstance, true);
        }
    }

    $targetMethods = [];

    foreach ($mysteryReflection->getMethods() as $method) {
        $docComment = $method->getDocComment();
        if ($docComment !== false && strpos($docComment, '@here') !== false) {
            $targetMethods[] = $method;
        }

    }

    foreach ($targetMethods as $targetMethod) {
        if (!$targetMethod->isPublic()) {
            $targetMethod->setAccessible(true);
        }

        try {
            $methodParams = $targetMethod->getParameters();
            $methodParamsValues = array_map(function($parameter) {
                return true;
            }, $methodParams);

            $result = empty($methodParamsValues)
                ? $targetMethod->invoke($mysteryInstance)
                : $targetMethod->invokeArgs($mysteryInstance, $methodParamsValues);
            if (is_a($result, \Exception::class)) {
                throw $result;
            }
        } catch (\Exception $e) {
            $mysteryException = findMysteryException($e);

            if ($mysteryException) {
                throw $mysteryException;
            }
        }
    }
}

function findMysteryException($exception)
{
    $exceptionClass = get_class($exception);
    if (strpos($exceptionClass, 'MysterySolvedException') !== false) {
        return $exception;
    }

    $previousException = $exception->getPrevious();
    if (!$previousException) {
        return null;
    }

    return findMysteryException($previousException);
}