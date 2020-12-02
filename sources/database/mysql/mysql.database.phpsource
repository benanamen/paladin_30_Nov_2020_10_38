<?php
/*
	mysql.database.php
	02 Dec 2020 14:27 GMT
	Paladin X.4 (Squire 4)
	Jason M. Knight, Paladin Systems North
*/

final class Database extends PDO {

	private
		$authorizations = [],
		$queries = [];
		
	// START PUBLIC OVERLOADS

		public function __construct(
			$dsn,
			$username,
			$password,
			$options = [],
			$tablePrefix = false
		) {
		
			Bomb::ifStarted('Database');
			
			$options[PDO::ATTR_STATEMENT_CLASS] = [ 'DatabaseStatement', [ $this ] ];
			
			try {
				parent::__construct($dsn, $username, $password, $options);
			} catch (PDOException $e) {
				Bomb::lang('Database Connection Error', $e );
			}
			
			define('DB_TABLE_PREFIX', $tablePrefix ? $tablePrefix . '_' : '');
			define('DB_QUERY_DIR', 'database/' . SQL_ENGINE . '/queries/');
			
		} // Database::__construct
		
		public function exec($name, $module = 'common', $tableName = false) {
			return $this->dryStatement('exec', $name, $module, $tableName);
		} // Database::exec
		
		public function prepare($name, $module = 'common', $tableName = false) {
			return $this->dryStatement('prepare', $name, $module, $tableName);
		} // Database::prepare
		
		public function query($name, $module = 'common', $tableName = false) {
			return $this->dryStatement('query', $name, $module, $tableName);
		} // Database::query
		
	// END PUBLIC OVERLOADS
	
	// START PUBLIC EXTENSIONS
	
		public function prepExec($data = [], $name, $module = 'common', $tableName = false) {
			$stmt = $this->prepare($name, $module, $tableName);
			$stmt->execute($data);
			return $stmt;
		} // Database::prepExec
		
		public function rowCount($tableName) {
			$stmt = $this->query('rowCount', 'common', $tableName);
			return $stmt->fetchColumn();
		} // Database::rowCount
		
		public function tableExists($name) {
			$stmt = $this->query('tableExists', 'common', $name);
			return $stmt->fetch();
		} // Database::tableExists
		
	// END PUBLIC EXTENSIONS
	
	// START PRIVATE EXTENSIONS

		private function auth($name, $module) {
		
			if ($module === 'common') return true;
			
			if (!array_key_exists($module, $this->authorizations)) {
				if (file_exists(
					$fn = DB_QUERY_DIR . $module . '/' . $module . '.auth.ini.php'
				)) $this->authorizations[$module] = parse_ini_file($fn);
				else return true;
			}
			
			return (
				($this->authorizations[$module][$name] ?? 0) ||
				($this->authorizations[$module]['all'] ?? 0)
			) !== 0;
			
		} // Database::auth
		
		private function dryStatement($method, $name, $module, $tableName) {
			try {
				$stmt = parent::$method($this->namedQuery($name, $module, $tableName));
			} catch (PDOException $e) {
				$errorMessage = $e->getMessage();
				echo $errorMessage, '<br>wtf<br>';
			}
			if (
				$method !== 'exec' &&
				!empty($stmt) &&
				($stmt->errorCode() > 0)
			) $errorMessage = $stmt->errorInfo()[2];
			if (empty($errorMessage)) return $stmt;
			Bomb::lang(
				'db' . ucFirst($method) . 'Error',
				[ $name, $module, $errorMessage ]
			);
		} // Database::dryStatement
		
		private function namedQuery($name, $module = "common", $tableName = false) {
		
			if (!$this->auth($name, $module)) Bomb::lang(
				'unauthorizedQuery', [ $name, $module ]
			);
			
			if (!array_key_exists($module, $this->queries)) {
				$this->queries[$module] = file_exists(
					$fn = DB_QUERY_DIR . $module . '/' . $module . '.queries.ini.php'
				) ?  parse_ini_file($fn) : [];
			}
			
			if (!array_key_exists($name, $this->queries[$module])) {
				if (file_exists(
					$fn = DB_QUERY_DIR . $module . '/' . $module . '.' . $name . '.sql'
				)) $this->queries[$module][$name] = file_get_contents($fn);
				else Bomb::lang('queryNotFound', [ $name, $module ]);
			}
			
			$query = str_replace(
				'!PREFIX!',
				DB_TABLE_PREFIX,
				$this->queries[$module][$name]
			);
			
			if ($tableName) {
				if (safeName($tableName)) {
					$query = str_replace('!TABLE!', $tableName, $query);
				} else Bomb::lang('invalidTableName', [ $tableName ]);
			}
			
			return $query;
			
		} // Database::namedQuery
		
	// END PRIVATE EXTENSIONS
	
} // Database

class DatabaseStatement extends PDOStatement {

	private
		$pdo,
		$locked = false;
	
	protected final function __construct($pdo) {
		$this->pdo = $pdo;
	} // DatabaseStatement:__construct
	
	public final function execute($input_parameters = Null) {
		if ($this->locked) Bomb::lang('executeLockedStmt');
		return parent::execute($input_parameters);
	} // DatabaseStatement::execute
	
	public final function lock() {
		$this->locked = true;
	}
	
} // DatabaseStatement