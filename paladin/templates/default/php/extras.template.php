<?php
/*
	extras.template.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

/*
	Extras are non-main content like sidebars
*/

function template_extrasPart($id, $extras, $data) {

	echo '

				<div id="', $id, '">';

	foreach ($extras as $name => $title) {
		$dss = '#' . $name;
		template_section($dss, $title);
		Load::content('extras/%s/%s.extra', $name, $data, 'extra');
		template_sectionFooter($dss);
	}

	echo '
				<!-- #', $id, ' --></div>';

} // template_extrasPart
