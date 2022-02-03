<?php
	
	namespace Healthia\Nookal\GraphQL\Contracts;
	
	use Throwable;
	
	/**
	 * Defines the interface to convert a GraphQL response into the appropriate output.
	 */
	interface ResponseConverterInterface
	{
		/**
		 * Converts the response data into the appropriate output.
		 * @param string $responseBody
		 * @return mixed
		 * @throws Throwable
		 */
		function convertResponse(string $responseBody);
	}
