<?php
/*
	admin_users.template.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function template_adminUserHeader() {

	template_section(
		'#admin_userList',
		Lang::getByName('caption', 'adminUser')
	);

	echo '
		<form action="', ROOT_HTTP, 'admin" method="post">
			<button name="admin[users]" value="create">Create New User</button>
		</form>
		<table>
			<thead>
				<tr>
					<th scope="col">', Lang::getByName('id', 'adminUser'), '</th>
					<th scope="col">', Lang::getByName('username', 'adminUser'), '</th>
					<th scope="col">', Lang::getByName('created', 'adminUser'), '</th>
					<th scope="col">', Lang::getByName('lastAccess', 'adminUser'), '</th>
					<th scope="col">', Lang::getByName('controls', 'adminUser'), '</th>
				</tr>
			</thead><tbody>';
			
	define('ADMIN_USERACTIONHASH', Hash::create('admin_userAction'));
	
} // template_adminUserHeader

function template_adminUserLine($userData) {

	echo '
				<tr>
					<td>', $userData['id'], '</td>
					<th scope="row">', $userData['username'], '</th>
					<td>', $userData['created'], '</td>
					<td>', $userData['last_access'], '</td>
					<td>
						<form action="', ROOT_HTTP, 'admin" method="post">
							<fieldset>';
					
	if ($userData['id'] > 1) echo '
								<button name="admin[users]" value="delete">Delete</button>';
							
	echo '
								<button name="admin[users]" value="edit">Edit</button>
								<input
									type="hidden"
									name="id"
									value="', $userData['id'], '"
								>
								<input
									type="hidden"
									name="admin_userAction_hash"
									value="', ADMIN_USERACTIONHASH, '"
								>
							</fieldset>
						</form>
					</td>
				</tr>';
				
} // template_adminUserLine

function template_adminUserFooter() {

	echo '
			</tbody>
		</table>';
		
		template_sectionFooter('#admin_userList');
		
} // template_adminUserFooter