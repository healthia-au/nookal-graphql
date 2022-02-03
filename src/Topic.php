<?php
	namespace Healthia\Nookal\GraphQL;
	
	/**
	 * Represents a series of endpoints related to a single topic.
	 */
	class Topic
	{
		protected Session $session;
		
		/**
		 * Initialises the topic.
		 * @param Session $session
		 */
		public function __construct(Session $session)
		{
			$this->session = $session;
		}
	}
