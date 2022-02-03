<?php
	
	namespace Healthia\Nookal\Tests;
	
	use Healthia\Nookal\GraphQL\AccessToken;
	use Healthia\Nookal\GraphQL\Session;
	
	class GraphQLTestCase extends TestCase
	{
		protected static ?AccessToken $accessToken = null;
		protected ?Session $session = null;
		
		protected function setUp(): void
		{
			parent::setUp();
			
			if (self::$accessToken && self::$accessToken->hasExpired())
				self::$accessToken = null;
			
			self::$accessToken ??= AccessToken::fetch($this->basicKey);
			$this->session ??= self::$accessToken->createSession();
		}
	}
