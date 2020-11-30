<?php
/*
	main.lib.php
	30 Nov 2020 10:38 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

include('libs/squire.lib.php');
include('libs/user.lib.php');

define(
	'TEMPLATE_PATH',
	'templates/' . (Settings::get('template') ?: 'default') . '/'
);

Template::load('common');

function main($dsn, $username, $password, $options, $tablePrefix) {

	Bomb::ifStarted('main');
	
	define('SQL_ENGINE', strstr($dsn, ':', true));
	Load::isolate('database/' . SQL_ENGINE . '/' . SQL_ENGINE . '.database.php');
	$db = new Database($dsn, $username, $password, $options, $tablePrefix);
	
	if (!$db->tableExists('users')) {
		define('DB_TABLES_EXIST', false);
		if (Request::value() !== 'setup') Bomb::lang('dbTablesMissing');
	} else define('DB_TABLES_EXIST', true);
	
	if (DB_TABLES_EXIST) {
		if (!empty($_REQUEST['logout'])) $_SESSION['user'] = null;
		User::init($db);
		if (User::get('id') < 0) Modals::set(
			'login', '@title_loginModal', htmlspecialchars($_SERVER['REQUEST_URI']), 'POST'
		); else Modals::set('userMenu', User::get('name'));
	}
	
	action($db);

} // main
