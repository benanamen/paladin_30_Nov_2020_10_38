<?php
/*
	extras.lib.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

final class Extras {

	private static $data = [ [], [] ];
	
	public static function add($name, $value, $secondary = false) {
		self::Remove($name);
		self::$data[$secondary ? 1 : 0][$name] = $value;
	} // Extras::add
	
	public static function addFromArray($data, $secondary = false) {
		foreach ($data as $name => $value) self::add($name, $value, $secondary);
	} // Extras::addFromArray
	
	public static function clear($filter = 3) {
		if ($filter & 1) self::$data[0] = [];
		if ($filter & 2) self::$data[1] = [];
	} // Extras::clear
	
	public static function isEmpty() {
		return empty(self::$data[0]) && empty(self::$data[1]);
	} // Extras::clear
	
	public static function output($data) {
		Template::load('extras');
		if (count(self::$data[0])) template_extrasPart('extras1', self::$data[0], $data);
		if (count(self::$data[1])) template_extrasPart('extras2', self::$data[1], $data);
	} // Extras::output
	
	public static function process($db, &$data) {
		$data['extras'] = [];
		foreach (self::$data as &$data) {
			foreach ($data as $name => $title) {
				$path = 'extras/' . $name . '/' . $name;
				if (file_exists(
					$fn = $path . '.ini.php'
				)) $data['extras'][$name] = parse_ini_file($fn);
				if (file_exists(
					$fn = $path . '.process.php'
				)) {
					Load::isolate($fn);
					('process_' . $name)($db);
				}
			}
		}
	} // Extras::process
	
	public static function remove($name) {
		if (array_key_exists($name, self::$data[0])) unset(self::$data[0][$name]);
		if (array_key_exists($name, self::$data[1])) unset(self::$data[1][$name]);
	} // Extras::remove
	
} // Extras

