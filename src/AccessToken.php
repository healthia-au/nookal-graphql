<?php
	/** @noinspection PhpUnused */
	
	namespace Healthia\Nookal\GraphQL;
	
	use DateTime;
	use DateTimeZone;
	use Exception;
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\ClientException;
	
	/**
	 * Represents a GraphQL access token.
	 */
	class AccessToken
	{
		const DefaultTokenUrl = 'https://au-apiv3.nookal.com/oauth/token';
		
		/** @var string Specifies the access token for the session. */
		protected string $accessToken;
		
		/** @var int Specifies the timestamp when  */
		protected int $expiryTimestamp;
		
		/**
		 * Initialises a new access token.
		 * @param string $accessToken
		 * @param int $expiryTimestamp
		 */
		public function __construct(string $accessToken, int $expiryTimestamp)
		{
			$this->accessToken = $accessToken;
			$this->expiryTimestamp = $expiryTimestamp;
		}
		
		/**
		 * Gets the access token.
		 * @return string
		 */
		public function getAccessToken(): string
		{
			return $this->accessToken;
		}
		
		/**
		 * Gets the expiry timestamp.
		 * @return int
		 */
		public function getExpiryTimestamp(): int
		{
			return $this->expiryTimestamp;
		}
		
		/**
		 * Determines if the access token has not yet expired.
		 * @return bool
		 */
		public function hasExpired(): bool
		{
			return time() >= $this->expiryTimestamp;
		}
		
		/**
		 * Determines how much time (in seconds) remains on the access token.
		 * @return int
		 */
		public function timeUntilExpiry(): int
		{
			return time() - $this->expiryTimestamp;
		}
		
		/**
		 * @param string $basicKey
		 * @param string $url
		 * @return AccessToken
		 * @throws Exception
		 */
		public static function fetch(string $basicKey, string $url = self::DefaultTokenUrl): AccessToken
		{
			$client = new Client();
			
			try
			{
				$response = $client->post($url,
				    [
				        'headers' => [
				            'Content-Type' => 'application/x-www-form-urlencoded',
				        	'Authorization' => "Basic $basicKey"
				        ],
				        'body' => '"grant_type":"client_credentials"'
				    ]);
				
				$content = json_decode($response->getBody());
				$accessToken = $content->accessToken ?? null;
				$expiresAt = $content->accessTokenExpiresAt ?? null;
				
				if (!$accessToken || !$expiresAt)
					throw new Exception('Access token response could not be parsed.');
				
				return new self($accessToken,
					(new DateTime($expiresAt, new DateTimeZone('UTC')))->getTimestamp());
			}
			catch(ClientException $exception)
			{
				$response = $exception->getResponse();
				$message = $response->getStatusCode() . ' ' . $response->getReasonPhrase();
				$json = json_decode($response->getBody() ?: '');
				if ($json && $json->message ?? null)
					$message .= ' - ' . $json->message;
				throw new Exception($message, $exception->getCode(), $exception);
			}
		}
	}
