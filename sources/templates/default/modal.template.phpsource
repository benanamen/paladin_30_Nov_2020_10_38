<?php
/*
	modal.template.php
	30 Nov 2020 10:38 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function template_modalHeader($id, $title) {

	echo '

	<div id="', $id, '" class="modal">
		<a href="#" class="modalClose" hidden aria-hidden="true"></a>
		<div><section>
			<a href="#" class="modalClose" hidden aria-hidden="true"></a>
			<h2>', Lang::get($title), '</h2>';
		
} // template_modalHeader

function template_modalFooter($id) {
	
	echo '
		</section></div>
	<!-- #', $id, '.modal --></div>';

} // template_modalFooter

function template_modalFormHeader($id, $title, $action, $method = 'POST') {

	echo '

	<form action="', $action, '" method="', $method, '" id="', $id, '" class="modal">
		<a href="#" class="modalClose" hidden aria-hidden="true"></a>
		<div><section>
			<a href="#" class="modalClose" hidden aria-hidden="true"></a>
			<h2>', Lang::get($title), '</h2>';
	
} // template_modalFormHeader

function template_modalFormFooter($id) {
	
	echo '
		</section></div>
	<!-- #', $id, '.modal --></form>';

} // template_modalFormFooter

function template_modalInclude($data, $id, $type = 'modal') {
	$modalFilePrefix = 'modals/' . $id . '.' . $type . '.';
	if (file_exists($modalFile = $modalFilePrefix . 'php')) {
		safeInclude($modalFile);
		($id . '_modalRun')($data, $id);
	}
	if (file_exists($modalFile = $modalFilePrefix . 'static')) {
		readFile($modalFile);
	}
}
