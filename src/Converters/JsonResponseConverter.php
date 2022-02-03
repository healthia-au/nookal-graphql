<?php
	namespace Healthia\Nookal\GraphQL\Converters;
	
	use Healthia\Nookal\GraphQL\Contracts\ResponseConverterInterface;
	
	/**
	 * Represents a
	 */
	class JsonResponseConverter implements ResponseConverterInterface
	{
		/**
		 * @inheritDoc
		 */
		function convertResponse(string $responseBody)
		{
			return json_decode($responseBody, true);
		}
	}
