<?php
/*
	admin.process.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/
/*
	admin.process.php
	30 Nov 2020 04:00 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function process_action($db, &$data) {

	if (!User::hasPermission('admin')) Bomb::http('404');
	
	if (!class_exists('Extras')) Load::isolate('libs/extras.lib.php');
	Extras::add('admin', Lang::getByName('adminControlPanelTitle', 'admin'));
	
	$data = [
		'contentFilePath' => 'actions/%s/%s',
		'pageName' => 'admin',
		'pageTitle' => 'Admin',
		'adminControls' => []
	];
	
	$pages = glob('actions/admin/pages/*', GLOB_ONLYDIR);
	foreach ($pages as $page) {
		$name = pathinfo($page, PATHINFO_BASENAME);
		if (file_exists(
			$fn = $page . '/'. $name . '.controls.ini.php')
		) $data['adminControls'][$name] = parse_ini_file($fn, true);
	}
	
	if (!empty($_POST['admin'])) {
		if (is_array($_POST['admin'])) {
			$name = key($_POST['admin']);
			$data['admin_action'] = $_POST['admin'][$name];
		} else $name = $_POST['admin'];
		if (
			!safeName($name) ||
			!is_dir('actions/admin/pages/' . $name)
		) Bomb::lang('unsafeAdminAction');
		$data['contentFilePath'] = 'actions/admin/pages/%s/%s';
		$data['pageName'] = $name;
		$data['pageTitle'] = Lang::getByName(
			$data['adminControls'][$name]['title'] ?? 'defaultPageTitle',
			'admin'
		);
		Load::isolate(
			'actions/admin/pages/' . $name . '/' . $name . '.process.php'
		);
		admin_subProcess($db, $data);
	}	

	Template::load('admin');
	
} // process_action