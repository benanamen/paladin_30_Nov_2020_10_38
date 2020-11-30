<?php
/*
	httpError.php
	30 Nov 2020 10:38 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

/*
	ASSUMES
		$code == HTTP error code number
*/

if (empty($code)) $code = 501;

$langText = Lang::get('@' . $code . '_httpErrors');
if (
	substr($langText, 0, 14) === '<strong style='
) $langText = Lang::get('@unknown_httpErrors');

$codeText = $code . ' - ' . $langText;

http_response_code($code);
Settings::set($codeText, 'pageTitle');

template_header();

template_section(
	'#httpError.splash',
	$codeText
);

echo '
						<p>
							', sprintf(
								Lang::get('@requestNotServed_httpErrors'), 
								htmlspecialchars($_SERVER['REQUEST_URI'])
							), '
						</p>';
						
template_sectionFooter('httpError');
	
template_footer();

die();