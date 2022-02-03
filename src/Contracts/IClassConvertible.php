<?php
	
	namespace Healthia\Nookal\GraphQL\Contracts;
	
	/**
	 * Defines the interface to a class convertible from an array.
	 */
	interface IClassConvertible
	{
		/**
		 * Determines if a property should be converted using the default conversion.
		 * @param string $name The name of the property.
		 * @return bool
		 */
		function useCustomConverter(string $name): bool;
		
		/**
		 * Converts a property's value using a custom conversion.
		 * @param string $name The name of the property.
		 * @param mixed $value The value to be converted.
		 * @return mixed
		 */
		function convertProperty(string $name, $value);
	}
