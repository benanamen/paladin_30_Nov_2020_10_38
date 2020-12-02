<?php
/*
	index.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

include('libs/main.lib.php');

main(
	'mysql:dbname=paladin;host=localhost', // dsn
	'paladin_user',                        // sql username
	'',                                    // sql password
	[
		PDO::ATTR_EMULATE_PREPARES => false,
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	],
	'paladin'                              // auto table prefix
);