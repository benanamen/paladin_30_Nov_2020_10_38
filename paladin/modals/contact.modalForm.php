<?php
/*
	contact.modalForm.php
	30 Nov 2020 10:38 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function contact_modalRun($data, $id) {

	echo '
		<fieldset>
			<label>
				', Lang::get('@yourName_contactUsModal'), ':<br>
				<input type="text" name="name" required><br>
			</label><label>
				', Lang::get('@yourEmail_contactUsModal'), ':<br>
				<input type="email" name="email" required><br>
			</label><label>
				', Lang::get('@subject_contactUsModal'), ':<br>
				<input type="text" name="subject" required><br>
			</label><label>
				', Lang::get('@message_contactUsModal'), ':<br>
				<textarea name="message" required></textarea><br>
			</label>
		</fieldset>';

	template_submitsAndHiddens('#' . $id, Lang::get('@submit_contactUsModal'));
	
} // contact_modalRun