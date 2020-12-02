<?php
/*
	admin.template.php
	02 Dec 2020 14:27 GMT
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
									<form action="', ROOT_HTTP, 'admin" method="post">
										<button
											name="admin[', $control, ']"
											value="', $value, '"
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

	echo '
							<ul class="formButtons">';
				
	foreach ($data['adminControls'] as $name => $controlInfo) {
	
		echo '
								<li>';
					
		template_adminControl(
			$name,
			$controlInfo['title'],
			$controlInfo['value'] ?? 1,
			$controlInfo['hiddens'] ?? []
		);
		
		echo '
							</li>';
		
	}
	
	echo '
						<!-- #adminPanel --></ul>';
			
} // template_adminPanel