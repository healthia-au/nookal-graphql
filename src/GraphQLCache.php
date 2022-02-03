<?php
	namespace Healthia\Nookal\GraphQL;
	
	/**
	 * Represents the cache of pre-built GraphQL queries and mutations.
	 */
	class GraphQLCache
	{
		private static ?self $main = null;
		
		/** @var string[] $queries */
		private array $queries = [];
		
		/** @var string[] $mutations */
		private array $mutations = [];
		
		/**
		 * Gets the main cache instance.
		 * @return static
		 */
		public static function main(): self
		{
			return self::$main ??= new self();
		}
		
		/**
		 * Gets a query from the cache.
		 * @param string $name The name of the query (not including its file extension or directory).
		 * @return string
		 */
		public function query(string $name): string
		{
			return $this->queries[$name] ??=
				file_get_contents(__DIR__ . "/GraphQL/Queries/$name.graphql");
		}
		
		/**
		 * Gets a mutation from the cache.
		 * @param string $name The name of the mutation (not including its file extension or directory).
		 * @return string
		 */
		public function mutation(string $name): string
		{
			return $this->mutations[$name] ??=
				file_get_contents(__DIR__ . "/GraphQL/Mutations/$name.graphql");
		}
	}
