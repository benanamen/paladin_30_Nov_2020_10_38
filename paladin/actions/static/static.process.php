<?php
/*
	static.process.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

function process_action($db, &$data) {

	if (!($page = Request::value(1))) {
		$page = Request::value();
		if (!$page || ($page == 'static')) $page = 'default';
	}
	
	$data = [
		'contentFilePath' => 'actions/static/pages/%s/%s',
		'pageName' => $page
	];
	
	$dataSources = 0;
	$filePath = 'actions/static/pages/' . $page . '/' . $page;
	
	if (Settings::loadFromIni($filePath)) $dataSources++;
	
	$data['pageTitle'] == Settings::get('pageTitle');
	
	if (file_exists($fn = $filePath . '.process.php')) {
		$dataSources++;
		Load::isolate($fn);
		process_static($db, $data);
	}
	
	if (!$dataSources) Bomb::http(404);
	
	return $data;
	
} // process_action