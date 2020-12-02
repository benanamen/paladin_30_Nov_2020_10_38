<?php
/*
	forms.template.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function template_formHeader($dss, $action, $post = 'POST', $autoComplete = true) {

	$dss = new Selector($dss);

	echo '

		<form action="', ROOT_HTTP, $action, '" method="', $post, '"';

	if ($dss->id) echo ' id="', $dss->id, '"';
	if ($dss->className) echo ' class="', $dss->className, '"';
	if ($dss->name) echo ' name="', $dss->name, '"';
	if (!$autoComplete) echo ' autocomplete="off"';

	echo '>';

	return $dss;

} // template_formHeader

function template_formFooter($dss, $submitText = false, $hidden = [], $hash = true, $submitAttr = []) {

	if (!is_object($dss)) $dss = new Selector($dss);

	if ($submitText) template_submitsAndHiddens($dss, $submitText, $hidden, $hash, $submitAttr);

	echo '

		';

	if ($dss->id) echo '<!-- #', $dss->id, ' -->';

	echo '</form>';

} // template_formFooter

function template_submitsAndHiddens($dss, $submitText, $hidden = [], $hash = true, $submitAttr = '') {

	if (!is_object($dss)) $dss = new Selector($dss);
	

	echo '

			<div class="submitsAndHiddens">
				<button';
				
	if (!empty($submitAttr)) {
	
		foreach ($submitAttr as $name => $value) echo '
					', $name, '="', htmlspecialchars($value), '"';
		
		echo '
				';
				
	}
	
	echo '>', $submitText, '</button>';

	if ($hash) $hidden[$dss->id . '_hash'] = Hash::create($dss->id);

	foreach ($hidden as $name => $value) echo '
				<input type="hidden" name="', $name, '" value="', $value, '">';

	echo '
			<!-- .submitsAndHiddens --></div>';

} // template_modalFormSubmitsAndHiddens