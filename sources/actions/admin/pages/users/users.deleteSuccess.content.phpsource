<?php
/*
	users.deleteSuccess.content.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function content_users($data) {
	
	template_section(
		'#userDeleted.splash',
		Lang::getByName('deleteSuccessTitle', 'adminUser'),
		sprintf(
			Lang::getByName('deleteSuccessDesc', 'adminUser'),
			htmlspecialchars($_POST['id'])
		)
	);

} // content_users