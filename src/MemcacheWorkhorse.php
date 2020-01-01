<?php
	namespace WhoIsAroundWho;
	require 'vendor/autoload.php';

	use \WhoIsAroundWho\ICacheWorkhorse as ICacheWorkhorse;

	\Logger::configure('config/config.xml');

	/**
	 * MemcacheWorkhorse implements ICacheworkhorse.  It implements a Memcache
	 * way of doing things. 
	 */
	class MemcacheWorkhorse implements ICacheWorkhorse
	{
		public function get($findThis)
		{
			$memcache = new \Memcache;
			$memcache->connect('127.0.0.1', 11211) or die ("Could not connect to Memcache");
			$foundHere = $memcache->get($findThis);
			if ($foundHere === FALSE)
			{
				$foundHere = \WhoIsAroundWho\JSONArchiveApi::find($findThis, 'lead_paragraph');
				$memcache->set($findThis, $foundHere, 0, 600);
			}
			return $foundHere;

		}
	}
?>
