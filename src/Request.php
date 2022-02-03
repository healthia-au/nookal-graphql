<?php
	namespace Healthia\Nookal\GraphQL;
	
	use Exception;
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\GuzzleException;
	use Psr\Http\Message\ResponseInterface;
	
	/**
	 * Represents a GraphQL query or mutation request.
	 */
	class Request
	{
		private Client $client;
		private string $postContent;
		private ?ResponseInterface $response = null;
		
		/**
		 * Initialises a new request.
		 * @param Client $client
		 * @param string $graphQL
		 * @param array $variables
		 */
		public function __construct(Client $client, string $graphQL, array $variables)
		{
			$this->client = $client;
			$this->postContent = json_encode([
				'query' => $graphQL,
				'variables' => $variables
			]);
		}
		
		/**
		 * Sends the request.
		 * @return self
		 * @throws GuzzleException
		 */
		public function send(): self
		{
			$this->response = $this->client->post('', [
				'body' => $this->postContent
			]);
			
			return $this;
		}
		
		/**
		 * Determines if the request succeeded.
		 * @return bool
		 */
		public function isOkay(): bool
		{
			return $this->response && $this->response->getStatusCode() === 200;
		}
		
		/**
		 * Gets the response as JSON data.
		 * @return array
		 * @throws Exception
		 */
		public function getResponseData(): array
		{
			if (!$this->response)
				throw new Exception('The request has not been sent yet.');
			return json_decode($this->response->getBody(), true);
		}
	}
