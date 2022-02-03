<?php
	namespace Healthia\Nookal\GraphQL\Topics;
	
	use Healthia\Nookal\GraphQL\Converters\ObjectResponseConverter;
	use Healthia\Nookal\GraphQL\GraphQLCache;
	use Healthia\Nookal\GraphQL\Objects\LocationLogo;
	use Healthia\Nookal\GraphQL\Request;
	use Healthia\Nookal\GraphQL\Session;
	use Healthia\Nookal\GraphQL\Topic;
	
	/**
	 * Represents the range of methods that can be used to manage an organisation's Locations.
	 */
	class Locations extends Topic
	{
		/**
		 * Queries the locations in the organisation.
		 * @param int $page
		 * @param int $pageLength
		 * @param array $locationIds
		 * @return Request
		 */
		public function locations(
			int $page = 0,
			int $pageLength = Session::DefaultPageLength,
			array $locationIds = []): Request
		{
			return $this->session->request(
				GraphQLCache::main()->query('locations'),
				compact('page', 'pageLength', 'locationIds'));
		}
		
		/**
		 * Queries the locations in the organisation.
		 * @param int $locationId
		 * @return Request
		 */
		public function locationLogo(int $locationId): Request
		{
			return $this->session->request(
				GraphQLCache::main()->query('locations'),
				compact('locationId'),
				new ObjectResponseConverter(LocationLogo::class));
		}
	}
