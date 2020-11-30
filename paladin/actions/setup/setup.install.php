<?php
/*
	setup.install.php
	30 Nov 2020 10:38 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function install($db) {

	if (!Hash::matchPost('stage0')) Settings::set([
		'title' => '@setupErrorTitle_setup',
		'text' => '@setupErrorHashText_setup'
	], 'notice');

	else if (
		empty($_POST['password']) ||
		empty($_POST['admin_password']) ||
		empty($_POST['admin_username']) ||
		$_POST['password'] !== Settings::get('setupPassword')
	) Settings::set([
		'title' => '@setupErrorTitle_setup',
		'text' => '@setupErrorPasswordText_setup'
	], 'notice');

	if (Settings::exists('notice')) return [
		'contentFilePath' => 'actions/%1/%1'
	];

	$tablePath = 'database/' . SQL_ENGINE . '/queries/setup/';

	$creates = glob($tablePath . '*_table.sql');

	foreach ($creates as $filename) $db->exec(
		substr(pathinfo($filename, PATHINFO_FILENAME), 6),
		'setup'
	);
	
	$db->prepExec([
		$_POST['admin_username'],
		$_POST['admin_username'],
		hash('sha256', $_POST['admin_password'])
	], 'user_insert', 'setup');
	
	$db->prepExec([
		$db->lastInsertId(), 'admin', 1
	], 'user_permission_add', 'setup');

	header('Location: ' . (
		!empty($_SERVER['HTTPS']) ? 'https' : 'http'
	) . '://' . $_SERVER['HTTP_HOST'] . ROOT_HTTP);
	die;

} // install