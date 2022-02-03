<?php
	
	namespace Healthia\Nookal\Tests\Queries;
	
	use GuzzleHttp\Exception\GuzzleException;
	use Healthia\Nookal\GraphQL\Topics\Locations;
	use Healthia\Nookal\Tests\GraphQLTestCase;
	
	class LocationsTests extends GraphQLTestCase
	{
		private Locations $topic;
		
		protected function setUp(): void
		{
			parent::setUp();
			$this->topic = $this->session->locations();
		}
		
		/**
		 * @throws GuzzleException
		 */
		function testLocationsCanBeFetched()
		{
			$request = $this->topic->locations()->send();
			
			$this->assertTrue($request->isOkay());
		}
		
		/**
		 * @throws GuzzleException
		 */
		function testLocationsCanBeFetchedAgain()
		{
			$request = $this->topic->locations(1)->send();
			
			$this->assertTrue($request->isOkay());
		}
	}
