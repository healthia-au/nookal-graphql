<?php
	namespace Healthia\Nookal\GraphQL\Converters;
	
	use Healthia\Nookal\GraphQL\Contracts\ResponseConverterInterface;
	use Healthia\Nookal\GraphQL\Convert;
	use Throwable;
	
	/**
	 * Represents a response converter that converts the result into an object of a specific class.
	 */
	class ObjectResponseConverter extends JsonResponseConverter
	{
		private string $class;
		
		/** @var string[] $keyPath */
		private array $keyPath;
		
		public function __construct(string $class, array $keyPath = [ 'data' ])
		{
			$this->class = $class;
			$this->keyPath = $keyPath;
		}
		
		/**
		 * @inheritDoc
		 * @throws Throwable
		 */
		function convertResponse(string $responseBody)
		{
			$value = value_for_key_path(parent::convertResponse($responseBody), $this->keyPath);
			return Convert::arrayToClass($value, $this->class);
		}
	}
