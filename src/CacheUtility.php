<?php
	namespace WhoIsAroundWho;
	require 'vendor/autoload.php';

	use WhoIsAroundWho\ICacheWorkhorse as ICacheWorkhorse;

	\Logger::configure('config/config.xml');

	/**
	 * CacheUtility provides for cache depending on circumstance (development, on shared
	 * hosting, etc.
	 */
	class CacheUtility
	{
		public function get($findThis, ICacheWorkhorse $workhorse) 
		{
			$gotThis = $workhorse->get($findThis);
			return $gotThis;
		}

	}
?>
