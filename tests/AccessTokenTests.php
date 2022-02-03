<?php
	namespace Healthia\Nookal\Tests;
	
	use Exception;
	use Healthia\Nookal\GraphQL\AccessToken;
	use Throwable;
	
	class AccessTokenTests extends TestCase
	{
		function testAccessTokenCanBeSerialized()
		{
			$token = new AccessToken(sha1('Hello, world!'), time());
			
			$serialized = serialize($token);
			
			/** @var AccessToken $deserialized */
			$deserialized = unserialize($serialized);
			
			$this->assertEquals($token->getAccessToken(), $deserialized->getAccessToken());
			$this->assertEquals($token->getExpiryTimestamp(), $deserialized->getExpiryTimestamp());
		}
		
		/**
		 * @throws Throwable
		 */
		function testAccessTokenCanBeSuccessfullyFetched()
		{
			$token = AccessToken::fetch($this->basicKey);
			
			$this->assertFalse($token->hasExpired());
		}
		
		/**
		 * @throws Throwable
		 */
		function testInvalidAccessTokenThrowsException()
		{
			$this->expectException(Exception::class);
			
			AccessToken::fetch('HELLOWORLD');
		}
	}
