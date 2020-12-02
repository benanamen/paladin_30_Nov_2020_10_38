<?php
/*
	users.list.process.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function adminUser_list_process($db, &$data) {

	if (!Hash::matchPost('ADMIN')) Bomb::lang('adminHashFailed');

	$data['admin_userStmt'] = $db->prepExec([
		0, 10
	], 'users_getUsers', 'admin');
	$data['admin_userStmt']->lock();
	
} // adminUser_list_process