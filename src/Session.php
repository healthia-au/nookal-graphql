<?php
	/** @noinspection PhpUnused */
	
	namespace Healthia\Nookal\GraphQL;
	
	/**
	 * Represents a GraphQL session.
	 */
	class Session
	{
		const BaseUrl = 'https://au-apiv3.nookal.com/graphql';
		
		private AccessToken $token;
		private string $baseUrl;
		
		/**
		 * Initialises a session using an OAuth token.
		 * @param AccessToken $token
		 * @param string $baseUrl
		 */
		public function __construct(AccessToken $token, string $baseUrl = self::BaseUrl)
		{
			$this->token = $token;
			$this->baseUrl = $baseUrl;
		}
		
		/**
		 * Gets the session token.
		 * @return AccessToken
		 */
		public function getAccessToken(): AccessToken
		{
			return $this->token;
		}
	}
