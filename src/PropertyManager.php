<?php

namespace YQService\oem;

use DateTime;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class PropertyManager
{
    /**
     * @var Metadata[]
     */
    protected static $metadata = [];

    /**
     * @throws Exception
     */
    public static function mapData(string $className, array $constructorParams, array $data, bool $debug)
    {
        $meta = self::getClassMeta($className);
        if ($meta->classFactory) {
            $object = $meta->classFactory->invoke(null, $data);
            $meta = self::getClassMeta(get_class($object));
        } else {
            $object = new $className($constructorParams);
        }

        foreach ($data as $fieldName => $value) {
            $fieldType = $meta ? @$meta->fields[$fieldName] : null;
            if (!$fieldType) {
                if ($debug)
                    throw new Exception('Unable to map field ' . $fieldName . ' to class ' . $className);
            } else {
                $object->$fieldName = self::getFieldValue($fieldType, $constructorParams, $value, $debug);
            }
        }

        return $object;
    }

    private static function getClassMeta(string $className): ?Metadata
    {
        if (!array_key_exists($className, self::$metadata)) {
            self::fillData($className);
        }
        return self::$metadata[$className];
    }

    /**
     * @param string $className
     * @throws ReflectionException
     */
    private
    static function fillData(string $className): void
    {
        $meta = null;
        if (class_exists($className)) {
            $meta = new Metadata();
            $reflection = new ReflectionClass($className);
            foreach ($reflection->getProperties() as $property) {
                $comment = $property->getDocComment();
                $meta->fields[$property->getName()] = self::parseType($comment);
                if (strpos($comment, '@KeyField') !== false) {
                    $meta->keyField = $property->getName();
                }
            }

            $methods = $reflection->getMethods(ReflectionMethod::IS_STATIC | ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                if ($method->getName() == 'classFactory') {
                    $meta->classFactory = $method;
                    break;
                }
            }
        }

        self::$metadata[$className] = $meta;
    }

    private
    static function parseType($comment)
    {
        $count = preg_match_all('/@var ([\d\w\[\]]+)/', $comment, $matches);
        if ($count) {
            return $matches[1][0];
        }

        return false;
    }

    /**
     * @param string $fieldType
     * @param mixed $fieldValue
     * @return mixed
     * @throws Exception
     */
    protected static function getFieldValue(string $fieldType, array $constructorParams, $fieldValue, bool $debug)
    {
        if (substr($fieldType, -2) == '[]') {
            if (is_array($fieldValue)) {
                $result = [];
                $subType = substr($fieldType, 0, -2);
                $subClassName = self::getClassName($subType);
                $subMeta = $subClassName !== null ? self::getClassMeta($subClassName) : null;
                $keyField = $subMeta !== null ? $subMeta->keyField : null;
                foreach ($fieldValue as $key => $subValue) {
                    $subValueObject = self::getFieldValue($subType, $constructorParams, $subValue, $debug);
                    $arrayKey = $keyField != null ? $subValueObject->$keyField : $key;
                    if ($arrayKey === null) {
                        throw new Exception('Key ' . $keyField . ' is null of type ' . $fieldType);
                    }
                    if ($debug && array_key_exists($arrayKey, $result)) {
                        throw new Exception('Array item with key ' . $arrayKey . ' already exists for ' . $fieldType);
                    }
                    $result[$arrayKey] = $subValueObject;
                }
                return $result;
            } else {
                throw new Exception('Field ' . $fieldType . ' should be array');
            }
        } else {
            switch ($fieldType) {
                case 'string':
                    return (string)$fieldValue;
                case 'bool':
                case 'boolean':
                    return (bool)$fieldValue;
                case 'int':
                    return (int)$fieldValue;
                case 'float':
                    return (float)$fieldValue;
                case 'array':
                    return (array)$fieldValue;
                case 'DateTime':
                    $fieldValue = $fieldValue / 1000;
                    if ($fieldValue) {
                        $timestamp = new DateTime(null);
                        $timestamp->setTimestamp($fieldValue);
                        return $timestamp;
                    } else {
                        return null;
                    }
                default:
                    $className = self::getClassName($fieldType);
                    if ($className) {
                        return self::mapData($className, $constructorParams, $fieldValue, $debug);
                    } else {
                        throw new Exception('Unable to map field with type ' . $fieldType);
                    }
            }
        }
    }

    private static function getClassName(string $fieldType): ?string
    {
        if (class_exists('YQService\oem\Response\\' . $fieldType)) {
            return 'YQService\oem\Response\\' . $fieldType;
        }

        return null;
    }

}