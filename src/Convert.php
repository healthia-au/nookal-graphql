<?php
	
	namespace Healthia\Nookal\GraphQL;
	
	use Healthia\Nookal\GraphQL\Contracts\IClassConvertible;
	use ReflectionClass;
	use ReflectionNamedType;
	use ReflectionProperty;
	use Throwable;
	
	/**
	 * Provides methods to convert data between formats.
	 */
	class Convert
	{
		private static array $properties = [];
		
		/**
		 * Enumerates a class' properties.
		 * @param string $className
		 * @return ReflectionNamedType[]
		 * @throws Throwable
		 */
		private static function enumerateClass(string $className): array
		{
			if (!isset(self::$properties[$className]))
			{
				$class = new ReflectionClass($className);
				$properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
				$props = [];
				
				foreach($properties as $property)
					$props[$property->getName()] = $property->getType();
				
				self::$properties[$className] = $props;
			}
			
			return self::$properties[$className];
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
			$properties = self::enumerateClass($class);
			
			/** @var IClassConvertible $object */
			$object = new $class();
			$isConvertible = $object instanceof IClassConvertible;
			
			foreach($item as $key=>$value)
			{
				$type = $properties[$key];
				
				if ($isConvertible && $object->useCustomConverter($key))
				{
					$value = $object->convertProperty($key, $value);
				}
				else if (is_array($value) && $type instanceof ReflectionNamedType && !$type->isBuiltin())
				{
					$className = $type->getName();
					if (isset(self::$properties[$className]) || class_exists($className))
						$value = self::arrayToClass($value, $className);
				}
				
				$object->{$key} = $value;
			}
			
			return $object;
		}
		
		/**
		 * Converts an array of items to an array of classes.
		 * @param array $items
		 * @param string $class
		 * @return array
		 * @throws Throwable
		 */
		public static function arraysToClassArray(array $items, string $class): array
		{
			return array_map(fn($item) => self::arrayToClass($item, $class), $items);
		}
	}
