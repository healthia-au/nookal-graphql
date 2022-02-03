<?php
	
	namespace Healthia\Nookal\GraphQL\Contracts;
	
	/**
	 * Defines the interface to a class convertible from an array.
	 */
	class ArrayConvertible
	{
		const StringType = 'string';
		const IntegerType = 'integer';
		const FloatType = 'float';
		const DoubleType = 'double';
		const BooleanType = 'boolean';
		
		/**
		 * Specifies the class names for each property name.
		 */
		protected const ArrayPropertyTypes = [];
		
		/**
		 * If a property is an array type, this specifies
		 * the class to convert each element to.
		 * @param string $name The name of the property.
		 * @return string|null
		 */
		public function getArrayPropertyClass(string $name): ?string
		{
			return static::ArrayPropertyTypes[$name] ?? null;
		}
		
		/**
		 * Returns whether a property's value should be
		 * @param string $name The name of the property.
		 * @return bool
		 */
		function hasPropertyConverter(string $name): bool
		{
			return false;
		}
		
		/**
		 * Converts a property's value using a custom conversion.
		 * @param string $name The name of the property.
		 * @param mixed $value The value to be converted.
		 * @return mixed
		 */
		function convertValueForProperty(string $name, $value)
		{
			return $value;
		}
	}
