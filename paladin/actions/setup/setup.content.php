<?php
/*
	setup.content.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function content_setup($data) {

	$sectionDSS = template_section('.splash', 'Setup Login');

	Template::load('forms');

	$formDSS = template_formHeader('#stage0', 'setup');
	
	echo '
			
			<fieldset>
				<h3>Setup</h3>
				<p>
					Use the password you created in the user config file to start the setup.
				</p>
				<label>
					Setup Password:<br>
					<input type="password" name="password" required><br>
				</label>
				<br>
			</fieldset>
			
			
			<fieldset>
				<h3>Administrator</h3>
				<p>
					Create your account
				</p>
				<label>
					Admin Username:<br>
					<input type="text" name="admin_username" required><br>
				</label>
				<label>
					Admin Password:<br>
					<input type="password" name="admin_password" required><br>
				</label>
				<br>
			</fieldset>';
			
	template_formFooter($formDSS, 'Submit', [ 'stage' => '0' ]);
	
	template_sectionFooter($sectionDSS);
	
} // action_content