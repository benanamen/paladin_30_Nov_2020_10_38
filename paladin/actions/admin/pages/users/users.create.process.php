<?php
/*
	users.create.process.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function adminUser_create_process($db, &$data) {

	$data['pageName'] = 'users';

	if (array_key_exists('userEditForm_hash', $_POST)) { // from Form
		
		if (!Hash::matchPost('userEditForm')) Bomb::lang('invalidEditHash');
		
		if (
			!empty($_POST['username']) &&
			!empty($_POST['password']) &&
			!empty($_POST['contact_email'])
		) { // create
		
			$db->prepExec([
				$_POST['name'] ?? $_POST['username'],
				$_POST['password'],
				hash(PASSWORD_ALGO, $_POST['username']),
				$_POST['contact_email']
			], 'users_create', 'admin');
			
			if (!empty($_POST['permissions'])) {
				require_once('actions/admin/pages/users/internal_setPermissions.php');
				setUserPermissions(
					$db,
					$db->lastInsertId(),
					$_POST['permissions']
				);
			}
			
			$data['contentFilePath'] = 'actions/admin/pages/%s/%s.createSuccess';
			return;
			
		} // create 
		
		$Settings::set([
			'title' => '@createUserErrorTitle_adminUser',
			'text' => '@createUserErrorEmptyFields_adminUser'
		], notice);
		
	} // from form
	
	$data['contentFilePath'] = 'actions/admin/pages/%s/%s.create';
	Template::load('forms', 'admin_user_edit');
	
} // adminUser_create_process
