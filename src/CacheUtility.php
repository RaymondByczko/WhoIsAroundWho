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
		/**
		 * get Gets the elements where findThis is found, using the capability
		 * of a object implementing ICacheWorkhorse.
		 */
		public function get($findThis, ICacheWorkhorse $workhorse) 
		{
			$log = \Logger::getLogger("myAppender");
			$log->info('CacheUtilty::get:start');
			$gotThis = $workhorse->get($findThis);
			$log->info('CacheUtilty::get:gotThis='.$gotThis);
			return $gotThis;
		}

		/**
		  * productCacheWorkhorse reads from the env variable WHOSITE_CACHEMETHOD and determines
		  * what type of caching to use, if any.  If it cannot be determined, it defaults to
		  * the null cache workhorse, which is basically means no caching is utilized.
		 */
		public function produceCacheWorkhorse()
		{
			$objWorkhorse = NULL;
			$envWC = getenv('WHOSITE_CACHEMETHOD');
			if ($envWC == FALSE)
			{
				$objWorkhorse = new \WhoIsAroundWho\NullCacheWorkhorse();
				return $objWorkhorse;
			}
			switch ($envWC)
			{
			case 'MEMCACHE':
				$objWorkhorse = new \WhoIsAroundWho\MemcacheWorkhorse();
				break;
			case 'STASHSQLITE':
				$objWorkhorse = new \WhoIsAroundWho\StashSqliteCacheWorkhorse();
				break;
			case 'NULLCACHE':
				$objWorkhorse = new \WhoIsAroundWho\NullCacheWorkhorse();
				break;
			default:
				$objWorkhorse = new \WhoIsAroundWho\NullCacheWorkhorse();
			}
			return $objWorkhorse;
		}

	}
?>
