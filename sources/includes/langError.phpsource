<?php
/*
	langError.php
	30 Nov 2020 10:38 GMT
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

$data['pageTitle'] = Lang::get('paladinSystemError');

template_header();

if (is_array($data)) {
	$title = Lang::get($data[0]);
	$desc = Lang::get($data[1]);
} else {
	$title = Lang::get('@' . $data . 'Title_errors');
	$desc = Lang::get('@' . $data . 'Desc_errors');
}

if ($printfData) $desc = vsprintf($desc, $printfData);

error_log(Lang::get('@title_errors') . ' - ' . $title . ' - ' . $desc);

template_section(
	'#paladin_error.splash',
	Lang::get('@title_errors')
);

echo '
						<p>
							', Lang::get('@systemError_errors'), '
						</p><p>
							', Lang::get('@errorHasBeenLogged_errors'), '
						</p>';
						
template_sectionFooter('#paladin_error');
				
template_footer();

die();