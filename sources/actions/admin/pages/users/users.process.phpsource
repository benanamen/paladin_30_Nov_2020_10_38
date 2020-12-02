<?php
/*
	users.process.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function admin_subProcess($db, &$data) {

	$action = $data['admin_action'] ?? 'list';
	if ($action == 1) $action = 'list';
	if (
		!safeName($action) ||
		!file_exists(
			$fn = 'actions/admin/pages/users/users.' . $action . '.process.php'
		)
	) Bomb::lang('adminUsersInvalidAction');
	
	Load::isolate($fn);
	$action = 'adminUser_' . $action . '_process';
	$action($db, $data);
	
	Template::load('admin_users');
	
} // admin_subProcess