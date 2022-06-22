<?php

namespace core;

class DB
{
	public static function connect()
	{
		// $dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', 'localhost', 'IXI');
		// return new \PDO($dsn, 'root', '');

		return $db = new \PDO('mysql:host=localhost;dbname=lotusbd', 'root', '');
	}
}
