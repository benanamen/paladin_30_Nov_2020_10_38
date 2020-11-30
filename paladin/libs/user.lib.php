<?php
/*
	user.lib.php
	30 Nov 2020 10:38 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

final class User {

	private static
		$db,
		$data = [],
		$permissionStmt,
		$permissions = [];
		
	// START PUBLIC METHODS
	
		public static function init($db) {
		
			self::$db = $db;
			self::$permissionStmt = $db->prepare('hasPermission', 'user');
			
			if (
				!empty($_SESSION['user']) &&
				($_SESSION['user']['id'] >= 0)
			) {
				self::$data = $_SESSION['user'];
				self::touch(); // eew. Do that in private
				return;
			}
			
			if (!empty($_POST['login_hash'])) {
				if (Hash::matchPost('login')) {
					if (
						!empty($_POST['username'])  &&
						!empty($_POST['password'])
					) {
						$stmt = $db->prepExec([
							$_POST['username'],
							hash('sha256', $_POST['password'])
						], 'login', 'user');
						if (self::$data = $stmt->fetch()) {
							$_SESSION['user'] = self::$data;
							// when I think about you I...
							self::touch();
							return;
						}
						Settings::set([
							'title' => '@loginErrorTitle_loginErrors',
							'text' =>  '@invalidUsernameOrPassword_loginErrors',
							'link' => '#login',
							'linkText' => 'Please Try Again'
						], 'notice');
					} else Settings::set([
						'title' => '@loginErrorTitle_loginErrors',
						'text' => '@emptyUsernameOrPassword_loginErrors',
						'link' => '#login',
						'linkText' => 'Please Try Again'
					], 'notice');
				} else Settings::set([
					'title' => '@loginErrorTitle_loginErrors',
					'text' => '@hashFailure_loginErrors',
					'link' => '#login',
					'linkText' => 'Please Try Again'
				], 'notice');
			}
			
			$_SESSION['user'] = self::$data = [
				'id' => -1,
				'name' => 'guest'
			];
			
		} // User::__construct
		
		public static function get($name) {
			if (array_key_exists($name, self::$data)) return self::$data[$name];
			return false;
		} // User::get
		
		public static function hasPermission($name) {
		
			if (self::$data['id'] === -1) return false;
			
			return (
				(self::cached('admin') === 1) ||
				(self::cached($name) === 1) ||
				(self::filter('admin') === 1) ||
				(self::filter($name) === 1)
			);
			
		} // User::hasPermission
	
	// END PUBLIC METHODS
	
	// START PRIVATE METHODS
	
		private static function filter($name) {
			self::$permissionStmt->execute([ self::$data['id'], $name ]);
			if ($filter = self::$permissionStmt->fetchColumn()) {
				self::$permissions[$name] = $filter;
				return $filter;
			}
			self::$permissions[$name] = 0;
			return false;
		} // User::filter
		
		private static function cached($name) {
			return self::$permissions[$name] ?? false;
		} // User::cached
		
		private static function touch() {
			if (
				array_key_exists('id', self::$data) &&
				(self::$data['id'] !== -1)
			) self::$db->prepExec([ self::$data['id'] ], 'touch', 'user');
		} // User::touch
		
	// END PRIVATE METHODS
	
} // User

