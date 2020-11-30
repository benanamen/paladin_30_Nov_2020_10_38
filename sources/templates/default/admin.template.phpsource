<?php
/*
	admin.template.php
	30 Nov 2020 10:38 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function template_adminControl(
	$control,
	$text,
	$value = 1,
	$hiddens = []
) {

	if (!defined('ADMIN_HASH')) define('ADMIN_HASH', Hash::create('ADMIN'));

	echo '
						<form action="', HTTP_ROOT, 'admin' method="post">
							<button
								name="admin[', $control, ']"
								value="', htmlspecialchars($value), '"
							>', lang::getByName($text, 'admin'), '</button>
							<input
								type="hidden"
								name="ADMIN_hash"
								value="', ADMIN_HASH, '"
							>';
			
	foreach ($hiddens as $name => $value) echo '
							<input
								type="hidden"
								name="', $name, '"
								value="', htmlspecialchars($value), '"
							>';
			
	echo '
						</form>';
		
} // template_adminControl

function template_adminPanel($data) {

	if (!is_array($data['adminControls'])) Bomb::lang('@invalidAdminArray_admin');
	
	echo '
			<div id="adminPanel">
				<h2>', Lang::getByName('adminPanel', 'admin'), '</h2>
				<ul id="adminPanel">';
				
	foreach ($data['adminControls'] as $controlInfo) {
	
		echo '
					<li>';
					
		template_adminControl(
			$controlInfo['control'],
			$controlInfo['text'],
			$controlInfo['value'] ?? '',
			$controlInfo['hiddens'] ?? []
		);
		
	}
	
	echo '
			<!-- #adminPanel --></ul>';
			
} // template_adminPanel