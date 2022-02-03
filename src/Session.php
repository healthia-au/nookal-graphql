<?php
	namespace Healthia\Nookal\GraphQL;
	
	use GuzzleHttp\Client;
	use Healthia\Nookal\GraphQL\Topics\Locations;
	
	/**
	 * Represents a GraphQL session.
	 */
	class Session
	{
		const BaseUri = 'https://au-apiv3.nookal.com/graphql';
		const DefaultPageLength = 200;
		
		protected AccessToken $accessToken;
		protected Client $client;
		
		/**
		 * Initialises a session using an OAuth token.
		 * @param AccessToken $accessToken
		 * @param string $baseUri
		 */
		public function __construct(AccessToken $accessToken, string $baseUri = self::BaseUri)
		{
			$this->accessToken = $accessToken;
			$this->client = new Client([
				'base_uri' => $baseUri,
				'headers' => [
		            'Content-Type' => 'application/json',
		            'Authorization' => 'Bearer ' . $accessToken->getAccessToken()
		        ]
			]);
		}
		
		/**
		 * Gets the session token.
		 * @return AccessToken
		 */
		public function getAccessToken(): AccessToken
		{
			return $this->accessToken;
		}
		
		/**
		 * Determines if the access token has expired.
		 * @return bool
		 */
		public function accessTokenHasExpired(): bool
		{
			return $this->accessToken->hasExpired();
		}
		
		/**
		 * Creates a query that can return a response.
		 * @param string $graphQL
		 * @param array $variables
		 * @return Request
		 */
		public function request(string $graphQL, array $variables = []): Request
		{
			return new Request($this->client, $graphQL, $variables);
		}
		
		/**
		 * Gets the Locations topic.
		 * @return Locations
		 */
		public function locations(): Locations
		{
			return new Locations($this);
		}
	}
