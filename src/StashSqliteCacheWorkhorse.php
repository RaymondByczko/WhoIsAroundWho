<?php
	namespace WhoIsAroundWho;
	require 'vendor/autoload.php';

	use \WhoIsAroundWho\ICacheWorkhorse as ICacheWorkhorse;

	\Logger::configure('config/config.xml');

	/**
	 * StashSqliteCacheWorkhorse implements ICacheworkhorse.  It implements a Stash
	 * way of doing things, using sqlite3. 
	 */
	class StashSqliteCacheWorkhorse implements ICacheWorkhorse
	{
		public function get($findThis)
		{
			$log = \Logger::getLogger("myAppender");
			$log->info('StashSqliteCacheWorkhorse::get:start');
			// throw new \Exception('StashSqliteCacheWorkhorse not implemented');
			$driver = NULL;
			try {
			/// $driver = new \Stash\Driver\Sqlite();
			//
			$driver = new \Stash\Driver\Sqlite(array('path'=> 'who-sql-dir'));
			}
			catch (Exception $e)
			{
				echo 'exception caught after new of Sqlite'."\n";
			}
			$log->info('StashSqliteCacheWorkhorse::get:post-construct-Sqlite');
			/// $options = array('path'=> 'who-sql-dir');
			/// $driver->setOptions($options);

			$pool = new \Stash\Pool($driver);
			$log->info('StashSqliteCacheWorkhorse::get:post-pool');
			$item = $pool->getItem($findThis);

			$foundHere = $item->get();

			//
			// Check to see if the data was a miss.
			if($item->isMiss())
			{
				$log = \Logger::getLogger("myAppender");
				$log->info('StashSqliteCacheWorkhorse::get:missed');
				// Let other processes know that this one is rebuilding the data.
				$item->lock();

			 	// Run intensive code
				$foundHere = \WhoIsAroundWho\JSONArchiveApi::find($findThis, 'lead_paragraph');
				// Store the expensive to generate data.
				$pool->save($item->set($foundHere));
			}
			return $foundHere;
		}
	}
?>
