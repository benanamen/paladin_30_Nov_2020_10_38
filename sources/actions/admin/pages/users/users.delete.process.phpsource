<?php
/*
	users.delete.process.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function adminUser_delete_process($db, &$data) {

	$data['pageName'] = 'users';
	
	if (
		empty($_POST['id']) ||
		!is_numeric($_POST['id'])
	) Bomb::lang('adminUserInvalidId');
	
	if (!Hash::matchPost('admin_userAction')) Bomb::lang('invalidEditHash');
	
	$stmt = $db->prepExec([ $_POST['id'] ], 'users_delete', 'admin');
	
	if ($stmt->rowCount() === 0) {
		$data['contentFilePath'] = 'actions/admin/pages/%s/%s.deleteFailed';
	} else {
		$db->prepExec([ $_POST['id'] ], 'users_flushPermissions', 'admin');
		$data['contentFilePath'] = 'actions/admin/pages/%s/%s.deleteSuccess';
	}
		
} // adminUser_delete_process
