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
			$log = \Logger::getLogger("myAppender");
			$log->info('CacheUtilty::get:start');
			$gotThis = $workhorse->get($findThis);
			$log->info('CacheUtilty::get:gotThis='.$gotThis);
			return $gotThis;
		}

	}
?>
