<?php
/*
	common.template.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

/*
	template_header
	outputs everything from the DOCTYPE to the <main> tag
*/

function template_header($data = []) {

	if (!defined('TEMPLATE_EXTRAS')) define('TEMPLATE_EXTRAS', (
		class_exists('Extras') &&
		!Extras::isEmpty() &&
		DB_TABLES_EXIST
	));
	
	ob_clean(); // should be NO output prior to this point!

	echo '<!DOCTYPE html><html lang="', (
		Settings::get('lang') ?: 'en'
	), '"><head><meta charset="', (
		Settings::get('encoding') ?: 'utf-8'
	), '">
<meta
	name="viewport"
	content="width=device-width,height=device-height,initial-scale=1"
>
<meta
	http-equiv="X-UA-Compatible"
	content="IE=9"
>';

	template_headSettings('meta');
	template_headSettings('link');

	echo '
<!--[if !IE]>-->';


	foreach (Settings::get('style') as $name => $media) echo '
	<link
		rel="stylesheet"
		href="', ROOT_HTTP, TEMPLATE_PATH, 'css/', $name, '"
		media="', $media, '"
	>';

	echo '
	<link
		rel="stylesheet"
		href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"
		integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ="
		crossorigin="anonymous"
		media="screen,projection,tv"
	>
	<link
		href="//fonts.googleapis.com/css2?family=Poppins" rel="stylesheet"
		media="screen,projection,tv"
	>
<!--<![endif]-->
<title>
	', (
		array_key_exists('pageTitle', $data) ?
		$data['pageTitle'] . ' - ' :
		''
	), Settings::get('siteTitle'), '
</title>
</head><body>

	<input
		type="checkbox"
		id="toggle_darkMode"
		class="toggle remember"
		hidden
		aria-hidden="true"
	>

	<input
		type="checkbox"
		id="toggle_stickyTop"
		class="toggle remember"
		hidden
		aria-hidden="true"
	>

	<div id="fauxBody"><div id="fauxInner">

		<header id="top">
			<h1><a href="', ROOT_HTTP, '">', Settings::get('h1Content'), '</a></h1>';
			
	if (DB_TABLES_EXIST) {
		echo '
			<div id="mainMenu">
				<a href="#" class="modalClose" hidden aria-hidden="true"></a>
				<div><nav>
					<a href="#" class="modalClose" hidden aria-hidden="true"></a>
					<ul>';

		$currentPage = Settings::get('currentPage');

		foreach (Settings::get('mainMenu') as $line) {
			if ($line['text'] == $currentPage) echo '
						<li>
							<em>', $line['text'], '</em>
						</li>';

			else echo '
						<li>
							<a href="', uriLocalize($line['href']), '">
								', $line['text'], '
							</a>
						</li>';
		}

		$userIsGuest = $_SESSION['user']['id'] === -1;

		if ($userIsGuest) template_menuGuestItems();
		else template_menuUserItems();

		echo '
					</ul>
				</nav></div>
			<!-- #mainMenu --></div>

			<a href="#mainMenu" class="mainMenuOpen" hidden aria-hidden="true"></a>

			<label for="toggle_darkMode" class="label_darkMode" hidden aria-hidden="true">
				<i><!-- day/night icon --></i>
				<span>
					Switch to
					<span>Light<span>/</span></span>
					<span>Dark</span>
					Theme
				</span>
			</label>

			<label for="toggle_stickyTop"></label>';

	} // DB_TABLES_EXIST

	echo '

		</header>';

	if (TEMPLATE_EXTRAS) echo '

		<div class="mainGroup">';

	echo '

			<main>

				<!--[if IE ]>
					<h2 style="color:red;">Error, Outdated Browser Detected</h2>
					<p>
						<strong style="color:red;">You are recieving a vanilla version of this page because your browser is a decade or more out of date. For full / proper appearance, please revisit in a modern browser.</strong>
					</p>
				<![endif]-->
';

	if ($notice = Settings::get('notice')) {

		$noticeDSS = template_section('#notice', Lang::get($notice['title']));

		echo '
			<p>
				', Lang::get($notice['text']), '
			</p>';

		if (!empty($notice['link'])) echo '
			<a href="', $notice['link'], '">', Lang::get($notice['linkText']), '</a>';

		template_sectionFooter($noticeDSS);

	}
	
} // template_header

/*
	template_footer
	Creates everything after the <main> tag.
*/


function template_footer($data = []) {

	echo '

			</main>';

	if (TEMPLATE_EXTRAS) {
		Extras::output($data);
		echo '

		<!-- .mainGroup --></div>';
	}

	echo '

		<footer id="bottom">
';

	if (file_exists(
		$fn = 'fragments/footer.content.php'
	)) Load::isolate($fn);
	
	if (file_exists(
		$fn = 'fragments/footer.static'
	)) readfile($fn);

	echo '
		</footer>

	<!-- #fauxInner, #fauxBody --></div></div>';

	if (DB_TABLES_EXIST) Modals::output($data);

	echo '

	<script src="', ROOT_HTTP, TEMPLATE_PATH, 'scripts/default.template.js"></script>

</body></html>';

} // template_footer

/*
	template_headSettings
	Used to create <link> and <meta> inside <head>
*/

function template_headSettings($tag) {

	if (!($data = Settings::get($tag))) return;
	foreach ($data as $name => $fields) {
		echo "\r\n<", $tag;
		foreach ($fields as $key => $value) {
			switch ($key) {
				case 'href':
				case 'src':
					$value = ROOT_HTTP . $value;
					break;
			}
			echo "\r\n\t", $key, '="', htmlspecialchars($value), '"';
		}
		echo "\r\n>";
	}

} // template_headSettings

function template_section(
	$dss,
	$title,
	$content = false,
	$hDepth = 2
 ) {

	$dss = new Selector($dss);

	echo '

					<', $dss->tagName ?: 'section';

	if ($dss->id) echo ' id="', $dss->id, '"';
	if ($dss->className) echo ' class="', $dss->className, '"';
	if ($dss->name) echo ' class="', $dss->name, '"';

	echo '>
						<h', $hDepth, '>', $title, '</h', $hDepth, '>
						<div>';

	if ($content) {
		echo $content;
		template_sectionFooter($dss);
	}

	return $dss;

} // template_section

function template_sectionFooter($dss) {

	if (!is_object($dss)) $dss = new Selector($dss);

	echo '
						</div>
					';

	if (!empty($dss->id)) echo '<!-- #', $dss->id, ' -->';
	echo '</', $dss->tagName ?: 'section', '>';

} // template_sectionFooter

function template_menuGuestItems() {
	echo '
						<li><a href="#login"><i class="fas fa-sign-in-alt"></i> Log In</a></li>
						<li><em><i class="fas fa-user-alt-slash"></i> Guest</em></li>';
} // template_menuGuestItems

function template_menuUserItems() {

	if (User::hasPermission('admin')) echo '
						<li>
							<a href="', ROOT_HTTP, 'admin" class="control">
								<i class="fas fa-tools"></i>
								<span>', Lang::get('admin'), '</span>
							</a>
						</li>';

	echo '
						<li>
							<form
								action="', htmlspecialchars($_SERVER['REQUEST_URI']), '"
								method="post"
								class="menuLogout"
							><button
								name="logout"
								value="1"
							><i class="fas fa-sign-out-alt"></i> Log Out</button></form>
						</li>
						<li><a href="#userOptions"><i class="fas fa-user-cog"></i> ',
							htmlspecialchars($_SESSION['user']['name']),
							'</a></li>';

} // template_menuUserItems
