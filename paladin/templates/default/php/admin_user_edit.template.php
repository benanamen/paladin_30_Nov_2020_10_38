<?php
/*
	admin_user_edit.template.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function template_adminUserForm($user, $action, $fields, $submitAttr) {

	$creating = $user['id'] === -1;
	
	if ($creating) $fields['password'] = 'password';

	$sectionDSS = template_section(
		'#userEdit',
		Lang::getByName($user['id'] == -1 ? 'titleCreate' : 'titleEdit', 'userForm')
	);

	$formDSS = template_formHeader('#userEditForm', $action, 'POST', false);

	echo '
			<fieldset class="labelInput">';

	if (!$creating) template_adminUserFormItem(
		'id', 'text', $user, ' disabled'
	);
	
	foreach ($fields as $name => $type)
		template_adminUserFormItem($name, $type, $user);

	echo '
			</fieldset>';
			
	if (array_key_exists('permissionList', $user)) {

		echo '
			<div class="permissions">
				<h3>', Lang::getByName('permissions', 'userForm'), '</h3>';
				
		$langBlock = Lang::getByName('block', 'userForm');
		$langNo = Lang::getByName('not_allowed', 'userForm');
		$langYes = Lang::getByName('allowed', 'userForm');
		
		$admin = ($user['permissionList']['admin'] ?? 0) > 0;
		
		foreach (USER_PERMISSIONS as $permission) {

			$name = 'permissions[' . $permission . ']';
			$value = $user['permissionList'][$permission] ?? 0;
			$disabled = $user['id'] == 1 || (
				$admin &&
				($permission !== 'admin')
			);

			echo '
				<fieldset>
					<legend>', $permission, '</legend>';

			template_adminUserPermissionCheckbox(
				$name, $langBlock , -1, $value < 0, $disabled
			);
			template_adminUserPermissionCheckbox(
				$name, $langNo , 0, $value == 0, $disabled
			);
			template_adminUserPermissionCheckbox(
				$name, $langYes , 1, $value > 0, $disabled
			);

			echo '
				</fieldset>';

		}

		echo '
			<!-- .permissions --></div>';

	}

	template_formFooter(
		$formDSS,
		lang::getByName('submit', 'userForm'),
		$creating ? [] : [ 'id' =>  $user['id'] ],
		true,
		$submitAttr
	);

	template_sectionFooter($sectionDSS);

} // template_adminUserForm

function template_adminUserFormItem($name, $type, $user, $extra = false) {

	$id = htmlspecialchars('user_' . $name);

	echo '
				<div>
					<label for="', $id, '">', lang::getByName($name, 'userForm'), '</label>
					<input
						type="', $type, '"
						id="', $id, '"
						name="', htmlspecialchars($name), '"
						required ';

	if (
		($user['id'] !== -1) &&
		($name !== 'password') &&
		array_key_exists($name, $user)
	) echo '
						value="', htmlspecialchars($user[$name]), '"';
						
	if ($extra) echo '
						', $extra;
	
	echo '
					>
				</div>';

} // template_adminUserFormItem

function template_adminUserPermissionCheckbox($name, $label, $value, $checked, $disabled) {

	echo '
					<label>
						<input type="radio" name="', $name, '" value="', $value, '"', (
							$checked ? ' checked' : ''
						), (
							$disabled ? ' disabled' : '' 
						), '>
						', $label, '
					</label>';

} // template_adminUserFormItem