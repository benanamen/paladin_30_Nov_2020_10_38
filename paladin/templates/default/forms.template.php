<?php
/*
	forms.template.php
	30 Nov 2020 10:38 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function template_formHeader($dss, $action, $post = true) {

	$dss = new Selector($dss);

	echo '

		<form action="', ROOT_HTTP, $action, '" method="', (
			$post ? 'POST' : 'GET'
		), '"';

	if ($dss->id) echo ' id="', $dss->id, '"';
	if ($dss->className) echo ' class="', $dss->className, '"';
	if ($dss->name) echo ' class="', $dss->name, '"';

	echo '>';

	return $dss;

} // template_formHeader

function template_formFooter($dss, $submitText = false, $hidden = [], $hash = true) {

	if (!is_object($dss)) $dss = new Selector($dss);

	if ($submitText) template_submitsAndHiddens($dss, $submitText, $hidden, $hash);

	echo '

		';

	if ($dss->id) echo '<!-- #', $dss->id, ' -->';

	echo '</form>';

} // template_formFooter

function template_submitsAndHiddens($dss, $submitText, $hidden = [], $hash = true) {

	if (!is_object($dss)) $dss = new Selector($dss);

	echo '

			<div class="submitsAndHiddens">
				<button>', $submitText, '</button>';

	if ($hash) $hidden[$dss->id . '_hash'] = Hash::create($dss->id);

	foreach ($hidden as $name => $value) echo '
				<input type="hidden" name="', $name, '" value="', $value, '">';

	echo '
			<!-- .submitsAndHiddens --></div>';

} // template_modalFormSubmitsAndHiddens