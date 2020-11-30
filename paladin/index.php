<?php
/*
	index.php
	30 Nov 2020 10:38 GMT
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