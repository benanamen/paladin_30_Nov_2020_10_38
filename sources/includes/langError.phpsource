<?php
/*
	langError.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

/*
	ASSUMES
		$data
		[ $printFdata ]
		
	if $data is array, assume each is a Lang lookup
	Otherwise is prefix for constructed Lang lookup
	
	The optional $printFdata is an Array that will be plugged
	into the language string via vsprintf.
	
*/

if (!defined('DB_TABLES_EXIST')) define('DB_TABLES_EXIST', false);

if (class_exists('Extras')) Extras::clear();

template_header([
	'pageTitle' => Lang::getByName('title', 'errors')
]);

if (is_array($data)) {
	$title = Lang::get($data[0]);
	$desc = Lang::get($data[1]);
} else {
	$title = Lang::getByName($data . 'Title', 'errors');
	$desc = Lang::getByName($data . 'Desc', 'errors');
}

if ($printfData) $desc = vsprintf($desc, $printfData);

error_log(Lang::getByName('title', 'errors') . '
	' . $title . '
	' . $desc . '
	HTTP_X_FORWARDED_FOR : ' . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'none') . '
	REMOTE_ADDR : ' . ($_SERVER['REMOTE_ADDR'] ?? 'none') . '
	HTTP_USER_AGENT : ' . ($_SERVER['HTTP_USER_AGENT'] ?? 'none')
);

template_section(
	'#paladin_error.splash',
	Lang::getByName('title', 'errors')
);

if (
	DB_TABLES_EXIST &&
	User::hasPermission('admin')
) echo '
						<h3>', $title, '</h3>
						<p>', $desc, '</p>
						<dl class="errorList">
							<dt>HTTP_X_FORWARDED_FOR</dt>
							<dd>', $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 'none', '</dd>
							<dt>REMOTE_ADDR</dt>
							<dd>', $_SERVER['REMOTE_ADDR'] ?? 'none', '</dd>
							<dt>HTTP_USER_AGENT</dt>
							<dd>', $_SERVER['HTTP_USER_AGENT'] ?? 'none', '</dd>
						</dl>';

else echo '
						<p>
							', Lang::get('@systemError_errors'), '
						</p><p>
							', Lang::get('@errorHasBeenLogged_errors'), '
						</p>';
						
template_sectionFooter('#paladin_error');
				
template_footer();

die();