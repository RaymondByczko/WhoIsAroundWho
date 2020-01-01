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
			// throw new \Exception('StashSqliteCacheWorkhorse not implemented');
			$driver = new \Stash\Driver\Sqlite();
			$options = array('path'=> 'who-sql-dir');
			$driver->setOptions($options);

			$pool = new \Stash\Pool($driver);

			$item = $pool->getItem($findThis);

			$foundHere = $item->get();

			//
			// Check to see if the data was a miss.
			if($item->isMiss())
			{
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
