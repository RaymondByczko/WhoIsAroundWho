<?php
	namespace WhoIsAroundWho;
	require 'vendor/autoload.php';

	\Logger::configure('config/config.xml');

	/**
	 * ICacheWorkhorse provides an interface to which specific objects
	 * can implement cache workings.  It depends on the situation (development,
	 * shared hosting, etc).
	 */
	interface ICacheWorkhorse
	{
	}
?>	
