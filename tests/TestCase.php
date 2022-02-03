<?php
	namespace Healthia\Nookal\Tests;
	
	use Dotenv\Dotenv;
	use Exception;
	use Throwable;
	
	class TestCase extends \PHPUnit\Framework\TestCase
	{
		protected Dotenv $dotenv;
		protected string $basicKey;
		
		/**
		 * @throws Throwable
		 */
		protected function setUp(): void
		{
			parent::setUp();
			$this->loadDotEnv();
		}
		
		/**
		 * @throws Throwable
		 */
		private function loadDotEnv(): void
		{
			$this->dotenv = Dotenv::createImmutable(__DIR__ . '/../');
			$this->dotenv->safeLoad();
			
			$this->basicKey = trim($_ENV['GRAPHQL_BASIC_KEY'] ?? '');
			if (empty($this->basicKey))
				throw new Exception('GRAPHQL_BASIC_KEY is not set in [.env].');
		}
		
		
	}
