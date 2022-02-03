<?php
	
	namespace Healthia\Nookal\GraphQL;
	
	use Healthia\Nookal\GraphQL\Contracts\ArrayConvertible;
	use ReflectionClass;
	use ReflectionNamedType;
	use ReflectionProperty;
	use Throwable;
	
	/**
	 * Provides methods to convert data between formats.
	 */
	class Convert
	{
		private static array $typedClassProperties = [];
		
		/**
		 * Enumerates a class' type-hinted properties.
		 * @param string $className
		 * @return ReflectionNamedType[]
		 * @throws Throwable
		 */
		private static function enumerateTypedProperties(string $className): array
		{
			if (!isset(self::$typedClassProperties[$className]))
			{
				$class = new ReflectionClass($className);
				$properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
				$props = [];
				
				foreach($properties as $property)
				{
					$type = $property->getType();
					if ($type instanceof ReflectionNamedType)
						$props[$property->getName()] = $type;
				}
				
				self::$typedClassProperties[$className] = $props;
			}
			return self::$typedClassProperties[$className];
		}
		
		/**
		 * Converts an array to a class based on its properties.
		 * @param array $item
		 * @param string $class
		 * @return mixed
		 * @throws Throwable
		 */
		public static function arrayToClass(array $item, string $class)
		{
			$properties = self::enumerateTypedProperties($class);
			
			/** @var ArrayConvertible|mixed $object */
			$object = new $class();
			$isConvertible = $object instanceof ArrayConvertible;
			
			foreach($item as $key=>$value)
			{
				if ($isConvertible && $object->hasPropertyConverter($key))
				{
					$value = $object->convertValueForProperty($key, $value);
				}
				else if (is_array($value)) // represents a potential object or array of objects
				{
					$type = $properties[$key] ?? null;
					
					if ($arrayClass = $object->getArrayPropertyClass($key))
					{
						$value = self::arraysToTypedArray($value, $arrayClass);
					}
					else if ($type instanceof ReflectionNamedType && !$type->isBuiltin())
					{
						$className = $type->getName();
						if (isset(self::$typedClassProperties[$className]) || class_exists($className))
							$value = self::arrayToClass($value, $className);
					}
				}
				
				$object->{$key} = $value;
			}
			
			return $object;
		}
		
		/**
		 * Converts an array of items to an array of a specific type or class.
		 * @param array $items The array of items to convert.
		 * @param string $type The built-in or class name to convert to.
		 * @return array
		 * @throws Throwable
		 */
		public static function arraysToTypedArray(array $items, string $type): array
		{
			switch($type)
			{
				case 'integer':
					return array_map(fn($item) => intval($item), $items);
					
				case 'float':
					return array_map(fn($item) => floatval($item), $items);
					
				case 'double':
					return array_map(fn($item) => doubleval($item), $items);
					
				case 'string':
					return array_map(fn($item) => strval($item), $items);
					
				case 'boolean':
					return array_map(fn($item) => boolval($item), $items);
			}
			return array_map(fn($item) => self::arrayToClass($item, $type), $items);
		}
	}
