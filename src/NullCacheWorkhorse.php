<?php
	namespace WhoIsAroundWho;
	require 'vendor/autoload.php';

	use \WhoIsAroundWho\ICacheWorkhorse as ICacheWorkhorse;

	\Logger::configure('config/config.xml');

	/**
	 * NullCacheWorkhorse implements ICacheworkhorse.  It does not implement
	 * any form of caching.  Accordingly, it is described as the NullCacheWorkhorse.
	 * way of doing things, using sqlite3. 
	 */
	class NullCacheWorkhorse implements ICacheWorkhorse
	{
		public function get($findThis)
		{
			$log = \Logger::getLogger("myAppender");
			$log->info('NullCacheWorkhorse::get:start');
			$foundHere = \WhoIsAroundWho\JSONArchiveApi::find($findThis, 'lead_paragraph');
			return $foundHere;
		}
	}
?>

