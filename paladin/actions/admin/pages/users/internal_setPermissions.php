<?php
/*
	internal_setPermissions.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function setUserPermissions($db, $id, $source) {
	$stmt = $db->prepare('users_setPermissions', 'admin');
	foreach (USER_PERMISSIONS as $userPermission) {
		if (array_key_exists($userPermission, $source)) $stmt->execute([
			$id,
			$userPermission,
			$source[$userPermission]
		]);
	}
} // setUserPermissions
