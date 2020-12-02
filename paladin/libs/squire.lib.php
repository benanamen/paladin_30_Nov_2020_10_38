<?php
/*
	squire.lib.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

/*
	Squire is a PHP library / foundation for building semi-static
	"Poor Man's CMS" systems, that can also be used in the creation
	of database driven sites.

	squire.lib should be included early as you can so that the following
	"setup" block can start compression making it safe to header() or
	$_COOKIE until blue in the face, as well as get  sessions, http headers,
	and so forth squared away. Much less EVERYTHING relies on the functions
	and objects present.
*/

/* *** START SYSTEM SETUP *** */

foreach (['gzip', 'x-gzip', 'x-compress'] as $type) {
	if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], $type) !== false) {
		define('OB_HANDLER', 'ob_gzhandler');
		break;
	}
}
if (!defined('OB_HANDLER')) define('OB_HANDLER', null);
ob_start(OB_HANDLER);
ob_implicit_flush(0);
if (OB_HANDLER) header('Content-Encoding: ' . $type);
register_shutdown_function(
	function() {
		ob_end_flush();
	}
);

session_start();
session_regenerate_id();

Settings::loadFromIni('default', 'user');

if (!defined('HTTP_ENCODING')) define('HTTP_ENCODING', 'utf-8');
header('Content-Type: text/html; charset=' . HTTP_ENCODING);
header('X-Frame-Options: DENY');

define('SCRIPT_PATH',cleanPath(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME)));
define('ROOT_HTTP', '/' . SCRIPT_PATH . (SCRIPT_PATH == '' ? '' : '/'));

/*
	*** END SYSTEM SETUP ***

	*** START GLOBAL FUNCTIONS ***
*/

/*
	action is called by our main() or IIFE to handle action processing
*/
function action($db) {

	Bomb::ifStarted('action');

	$action = Request::value();
	if (!$action) $action = 'static';
	if (!is_dir('actions/' . $action)) Bomb::http(404);
	define('ACTION', $action);
	
	$actionPath = 'actions/' . ACTION . '/' . ACTION;
	Settings::loadFromIni($actionPath);
	Load::isolate($actionPath . '.process.php');
	process_action($db, $data);
	if (class_exists('Extras')) Extras::process($db, $data);
	
	template_header($data);
	if (!empty($data['contentFilePath'])) Load::content(
		$data['contentFilePath'],
		$data['pageName'],
		$data
	);
	if (!empty($data['contentFunction'])) $data['contentFunction']($data);
	template_footer($data);
	
} // action

function cleanString($string) {
	return htmlspecialchars(strip_tags($string));
} // cleanString

function cleanPath($path) {
	return trim(str_replace(['\\', '%5C'], '/', $path), '/');
} // cleanPath

function safeName($name) {
	return $name === preg_replace('/[^a-z0-9_]/', '', $name);
} // safeName

function uriLocalize($uri) {
	if (
		substr($uri, 0, 4) == 'http' ||
		substr($uri, 0, 1) == '#'
	) return $uri;
	return ROOT_HTTP . $uri;
} // uriLocalize

/*
	*** END GLOBAL FUNCTIONS ***

	*** START STATIC OBJECTS ***
*/

/*
	Bombs are fatal errors, execution will end after message
	is logged and displayed.
*/

final class Bomb {

	public static function http($code) {
		include('includes/httpError.php');
	} // Bomb::http

	public static function lang($data, $printfData = false) {
		include('includes/langError.php');
	} // Bomb::lang
	
	public static function ifStarted($name) {
		$name = 'PALADIN_STARTED_' . strtoupper($name);
		if (defined($name)) self::lang('alreadyStarted', [ $name ]);
		define($name, 1);
	} // Bomb::ifStarted

} // Bomb

/*
	Hash is used to generate and validate form/session hashes
*/

final class Hash {

	public static function create($name) {
		return $_SESSION[$name . '_hash'] = bin2hex(random_bytes(24));
	} // Hash::create
	
	public static function destroy($name) {
		$_SESSION[$name . '_hash'] = '';
	} // Hash::destroy
	
	public static function match($name, $hash, $destroy = true) {
		$name .= '_hash';
		if (!empty($_SESSION[$name])) {
			$result = $_SESSION[$name] == $hash;
			if ($destroy) unset($_SESSION[$name]);
			return $result;
		}
		return false;
	} // Hash::match

	public static function matchPost($name, $destroy = true) {
		$name .= '_hash';
		if (empty($_SESSION[$name])) return false;
		$hash = $_SESSION[$name];
		if ($destroy) unset($_SESSION[$name]);
		if (empty($_POST[$name])) return false;
		return $_POST[$name] == $hash;
	} // Hash::matchPost
	
} // Hash

/*
	Lang exists to load language strings
*/

final class Lang {

	private static $cache = [];

	public static function get($longName) {

		if (substr($longName, 0, 1) !== '@') return $longName;

		$splode = explode('_', substr($longName, 1), 2);
		$name = $splode[0];
		$module = $splode[1] ?? 'common';
		
		return self::getByName($name, $module);

	} // Lang::__get
	
	public static function getByName($name, $module = "common") {
	
		$lang = Settings::get('lang') ?: 'en';
		
		if (!array_key_exists($module, self::$cache)) {
			if (file_exists(
				$fn = 'lang/' . $lang . '/' . $module . '/' . $module .  '.ini.php'
			)) self::$cache[$module] = parse_ini_file($fn);
			else self::$cache[$module] = [];
		}

		if (
			array_key_exists($name, self::$cache[$module])
		) return self::$cache[$module][$name];

		if (file_exists(
			$fn = 'lang/' . $lang . '/' . $module . '/' .$name . '.txt'
		)) return self::$cache[$module][$name] = file_get_contents($fn);

		error_log(
			'Paladin Error -- Lang::get, key "' . $name .
			'" not found in module "' . $module
		);

		return '<ins style="color:red; font-weight:bold;">LANG[' . $name . '_' . $module . ']</ins>';
	} // Lang::__getByName

} // Lang

/*
	Load contains several different types of "loaders" we use all over the
	codebase.
*/

final class Load {
	
	private static $processLock = false;
	
	public static function content($filePath, $name, $data, $handler = '', $type = 'content') {
		$filePath = self::loadExec($filePath, $name, $data, $handler, $type);
		if (file_exists($fn = $filePath . '.static')) readfile($fn);
	} // Load::content
	
	/*
		Load::isolate is dumbass, but since PHP just LOVES to
		bleed scope all over the place with 1970's style "includes"
		we have to do this JUST to break local scope!
	*/
	public static function isolate($name) {
		include($name);
	} // Load::isolate
	
	private static function loadExec($filePath, $name, &$data, $handler = '', $type) {
		$filePath = sprintf($filePath, $name, $name);
		if (file_exists($fn = $filePath . '.' . $type . '.php')) {
			include_once($fn);
			(
				(empty($type) ? '' : $type . '_') .
				(empty($handler) ? '' : $handler . '_') .
				$name
			)($data);
		}
		return $filePath;
	} // Load::loadExec
	
	public static function process($filePath, $name, &$data, $handler = '') {
		if (self::$processLock) Bomb::lang('processLocked');
		self::loadExec($filePath, $name, $data, $handler, 'process');
	} // Load::process
	
	public static function processLock() {
		self::$processLock = true;
	} // Load::processLock
	
} // Load

final class Modals {

	private static $data = [];

	public static function set($id, $title, $action = false, $method = false) {
		self::$data[$id] = [
			'title' => $title
		];
		if ($action !== false) {
			self::$data[$id]['action'] = $action;
			self::$data[$id]['method'] = $method;
		}
	} // Modals::set

	public static function output($data) {

		if (!count(self::$data)) return;

		Template::load('modal');
		Template::load('forms');

		foreach (self::$data as $id => $mData) {
			if (array_key_exists('action', $mData)) {
				template_modalFormHeader(
					$id, $mData['title'], $mData['action'], $mData['method']
				);
				template_modalInclude($data, $id, 'modalForm');
				template_modalFormFooter($id);
			} else {
				template_modalHeader($id, $mData['title']);
				template_modalInclude($data, $id);
				template_modalFooter($id);
			}
		}

	} // Modals::output

} // Modals

final class Request {

	private static
		$data = false,
		$path = '';

	private static function set() {
		self::$path = parse_url(cleanPath($_SERVER['REQUEST_URI']), PHP_URL_PATH);
		if (strpos(self::$path, '..')) Bomb::lang('invalidURI');
		self::$path = substr(self::$path, strlen(ROOT_HTTP) - 1);
		self::$data = (
			empty(self::$path) ?
			[ Settings::get('default_action') ] :
			explode('/', self::$path)
		);
		foreach (self::$data as &$p) $p = urldecode($p);
	} // Request::set

	public static function value($index = 0) {
		if (!self::$data) self::set();
		return self::$data[$index] ?? false;
	} // Request::value

	public static function getPath() {
		if (count(self::$data) == 0) self::set();
		return self::$path;
	} // Request::getPath

} // Request

class Selector {
	
	private static $splits = [
		'text' => '~',
		'special' => '@',
		'name' => '^',
		'className' => '.',
		'id' => '#'
	];
	
	private $data = [];
	
	public function __construct($dss) {
		foreach (self::$splits as $key => $char) {
			$split = explode($char, $dss, 2);
			$this->data[$key] = $split[1] ?? '';
			$dss = $split[0] ?? '';
		}
		$this->data['tagName'] = $dss;
		if (!empty($this->data['className'])) {
			$this->data['className'] = str_replace('.', ' ', $this->data['className']);
		}
	} // Selector::__construct
	
	public function __get($key) {
		return $this->data[$key] ?? '';
	} // Selector::__get
		
} // Selector

final class Settings {

	private static $data = [];
	
	public static function exists($name, $section = false) {
		return $section ? (
			array_key_exists($section, self::$data) &&
			array_key_exists($name, self::$data[$section])
		) : array_key_exists($name, self::$data);
	} // Settings::exists

	public static function get($name, $section = false) {
		return $section ? (
			(
				array_key_exists($section, self::$data) &&
				array_key_exists($name, self::$data[$section])
			) ? self::$data[$section][$name] : false
		) : (
			array_key_exists($name, self::$data) ?
			self::$data[$name] :
			false
		);
	} // Settings::get

	public static function loadFromIni(...$files) {
		foreach ($files as $filename) {
			if (file_exists(
				$filename .= '.ini.php'
			)) {
				$data = parse_ini_file($filename, true);
				foreach ($data as $key => $value) self::setPair($key, $value);
			} else return false;
		}
		return true;
	} // Settings::loadFromIni
	
	public static function set($value, $name, $section = false) {
		if ($section) self::setPair($section, [ $name => $value ]);
		else self::setPair($name, $value);
	} // Settings::set
	
	private static function addExtra($value, $secondary = false) {
		if (!class_exists('Extras')) Load::isolate('libs/extras.lib.php');
		Extras::addFromArray($value, $secondary);
	} // Settings::addExtra
	
	private static function setPair($key, $value) {
		if (is_array($value)) {
			switch ($key) {
				case 'DEFINE':
					foreach ($value as $dName => $dValue) define($dName, $dValue);
					return;
				case 'modal':
					foreach ($value as $id => $title) Modals::set($id, $title);
					return;
				case 'modalForms':
					foreach ($value as $id => $mData) Modals::set(
						$id, $mData['title'], $mData['action'], $mData['method']
					);
					return;
				case 'extras1':
					self::addExtra($value);
					return;
				case 'extras2':
					self::addExtra($value, true);
					return;
			}
			if (
				array_key_exists($key, self::$data) &&
				is_array(self::$data[$key])
			) {
				self::$data[$key] = array_merge(self::$data[$key], $value);
				return;
			}
		}
		self::$data[$key] = $value;
	} // Settings:setPair
	
} // Settings

final class Template {

	private static
		$path = 'templates/default/',
		$default = 'templates/default/';
		
	public static function set($name) {
	
		if (
			!safeName($name) ||
			!is_dir($name = 'template/' . $name . '/')
		) Bomb::lang('invalidTemplateName');
		
		self::$path = $name;
		
	} // Template::set
	
	public static function load(...$names) {
		foreach ($names as $name) {
			if (!safeName($name)) Bomb::lang('templateLoadFailed', [ $name ]);
			if ($fn = self::resolvePath(
				'php/' . $name . '.template.php'
			)) include_once($fn);
			else Bomb::lang('templateLoadFailed', [ $name ]);
			self::loadCSS($name, 'screen', 'screen,projection,tv');
			self::loadCSS($name, 'print', 'print');
		}
	} // Template::load
	
	public static function loadCSS($name, $ext, $media) {
		$name .= '.' . $ext . '.css';
		if (self::resolvePath('css/' . $name))
			Settings::set($media, $name, 'style');
	} // Template::loadCSS
	
	private static function resolvePath($pathName) {
		return (
			file_exists($fn = self::$path . $pathName) || (
				self::$path === self::$default ?
				false :
				file_exists($fn = self::$default . $pathName)
			)
		) ? $fn : false;
	} // Template::resolvePath
	
} // Template


/* REMOVE FROM HERE DOWN ON DEPLOYMENT */
function debugDump(...$var) {
	foreach ($var as $v) {
		echo '<pre>', var_dump($v), '</pre>';
	}
	die;
} // debugDump