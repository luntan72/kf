<?php
require_once('kf_object.php');
require_once('dbfactory.php');

class kf_db extends kf_object{
	protected $db = null;
	protected $describes = array();
	protected $inTransaction = false;
	protected $keys = array(); //存放生成Sql时的关键字搜索，主用用于界面高亮

	protected function init($params){
		parent::init($params);
		//设置dsn
		
		if(!empty($this->params['db']))
			$this->setDb($this->params['db']);
	}

	public function setDb($dsn, &$real_db_name = ''){
		if(!is_array($dsn)){
			$dsn = array('dbname'=>$dsn);
		}
		
		$this->set(array('db'=>$dsn['dbname']));
		$this->set(array('dsn'=>$dsn));
// print_r($this->params);		
		$this->db = dbFactory::get($this->params['dsn'], $real_db_name);
		$this->params['real_db_name'] = $real_db_name;
	}
	
	public function freeRes(&$res){
		if(is_object($res)){
			$res->closeCursor();
		}
		unset($res);
	}
	
	function getDB_Table($table, $db){
		$a = explode('.', $table);
		if(count($a) == 2){
			$table = $a[1];
			$db = $a[0];
		}
		if(empty($db))
			$db = $this->params['db'];
		return array($table, $db);
	}
	
	public function quote($str){
		return $this->db->quote($str);
	}
	
	public function query($sql, $params = array(), $db_name = ''){
		// print_r("db_name = $db_name\n");
		if(!empty($db_name))
			$this->setDb($db_name);
		$stmt = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$stmt->execute($params);
		return $stmt;
	}
	
	function update($table_name, $row, $conditions = '', $db_name = ''){
		list($table_name, $db_name) = $this->getDB_Table($table_name, $db_name);
		$this->setDb($db_name);
		$realDbName = $this->get('real_db_name');
		if(empty($conditions))
			$conditions = " 1 ";
		if(!empty($row['id']))
			$conditions = "id=".$row['id']." AND (".$conditions.")";
// print_r("table = $table_name, db = $realDbName, conditions = $conditions\n");		
		return $this->db->update($realDbName.'.'.$table_name, $row, $conditions);
	}
	
	function insert($table_name, $row, $db_name = ''){
		list($table_name, $db_name) = $this->getDB_Table($table_name, $db_name);
		$this->setDb($db_name);
		$realDbName = $this->get('real_db_name');
		$desc = $this->describe($table_name, $db_name);
		$realVP = array();
		foreach($desc as $k=>$v){
			if (isset($row[$v['COLUMN_NAME']]))
				$realVP[$v['COLUMN_NAME']] = $row[$v['COLUMN_NAME']];
		}
		$this->db->insert($realDbName.'.'.$table_name, $realVP);
		return $this->db->lastInsertId();
	}
	
	function insertRows($table_name, $rows, $db_name = ''){
		$count = 0;
		$countOnce = 200;
		list($table_name, $db_name) = $this->getDB_Table($table_name, $db_name);
		$this->setDb($db_name);
		$realDbName = $this->get('real_db_name');
		$keys = implode(',', array_keys($rows[0]));
// print_r($keys);		
		$sql = '';
		$values = array();
		foreach($rows as $valuepair){
			foreach($valuepair as $key=>$value){
				if (is_null($value))
					$valuepair[$key] = 'NULL';
				else
					$valuepair[$key] = $dbAdapter->quote($value);
			}
			$values[] = "(".implode(',', $valuepair).")";
			$count ++;
			if($count == $countOnce){
				if (!empty($values)){
					$sql = "INSERT INTO $table_name ($keys) VALUES ".implode(',', $values);
		// print_r($sql);
					$this->db->query($sql);
				}
				$count = 0;
				$values = array();
			}
		}
		if (!empty($values)){
			$sql = "INSERT INTO $table_name ($keys) VALUES ".implode(',', $values);
// print_r($sql);
			$this->db->query($sql);
		}
		return $this->db->lastInsertId();
	}
	
	function delete($table, $conditions, $db = ''){
		list($table, $db) = $this->getDB_Table($table, $db);
		$this->setDb($db, $realDbName);
		return $this->db->delete($realDbName.".".$table, $conditions);
	}
	
	public function beginTransaction(){
// print_r($this->db);
// print_r($this->params);
// print_r("intran = ".$this->inTransaction);
		if(!empty($this->db) && empty($this->inTransaction)){
// print_r("begin tran\n");			
			$this->db->beginTransaction();
			$this->inTransaction = true;
		}
	}
	
	public function commit(){
		if(!empty($this->db) && $this->inTransaction){
			$this->db->commit();
			$this->inTransaction = false;
		}
	}
	
	public function rollback(){
// print_r($this->db)		;
// print_r($this->params);
// print_r("intran = ".$this->inTransaction);
		if(!empty($this->db) && $this->inTransaction){
			// $this->db->rollback();
			$this->inTransaction = false;
		}
	}
	
	public function describe($table, $db = ''){
		if(empty($db))
			$db = $this->get('db');
//print_r("db = $db, table = $table \n");	
		if (empty($this->describes[$db][$table])){
			$this->setDb($db);
//print_r($dbAdapter);			
//			$res = $dbAdapter->query("SHOW FULL COLUMNS FROM $db.$table");
//			$this->describes[$db][$table] = $res->fetchAll();//$this->db->describeTable($table, $db);
			$this->describes[$db][$table] = $this->db->describeTable($table, $this->get('real_db_name'));
		}
		return $this->describes[$db][$table];
	}
	
	public function getAllTables($db = '', $emptyFirst = false){
		$tables = array();
		if ($emptyFirst)
			$tables[''] = '==NONE==';
		$realDb = '';
		$this->setDb($db);
		$field = "Tables_in_".$this->get('real_db_name');
        $res = $this->db->query("show tables");
		while($row = $res->fetch()){
			$tables[$row[$field]] = $row[$field];
		}
		return $tables;
	}
	
    public function tableExist($table, $db = ''){
		if(empty($db))
			$db = $this->get('db');
		$this->setDb($db);
        $sql = "show tables where tables_in_".$this->get('real_db_name')."=".$this->db->quote($table);
        $res = $this->db->query($sql);
        return $res->rowCount();
    }
    
	public function fieldExist($table, $field, $db = ''){
		$cols = $this->getTableFields($table, $db);
		return isset($cols[$field]);
	}
	
	public function getTableFields($table, $db = ''){
		$cols = $this->describe($table, $db);//$this->db->describeTable($table, $db);
		return array_keys($cols);
	}
	
	public function getElementId($table, $valuePair, $keyFields = array(), &$is_new = true, $db = ''){
		if(empty($db))
			$db = $this->get('db');
		$this->setDb($db);
		$where = array(1);
		$whereV = array();
		$realVP = array();
		$desc = $this->describe($table, $db);
		foreach($desc as $k=>$v){
			if (isset($valuePair[$v['COLUMN_NAME']]))
				$realVP[$v['COLUMN_NAME']] = $valuePair[$v['COLUMN_NAME']];
		}
		if (empty($keyFields))
			$keyFields = array_keys($realVP);
			
		foreach($keyFields as $k){
			if(isset($valuePair[$k])){
				$where[] = "`$k`=:$k";
				// $where[] = "`$k`=".$dbAdapter->quote($realVP[$k]);
				$whereV[$k] = $realVP[$k];
			}
		}
		$res = $this->query("SELECT * FROM $table where ".implode(' AND ', $where), $whereV);
		// $res = $dbAdapter->query("SELECT * FROM $table where ".implode(' AND ', $where));
		if ($row = $res->fetch()){
			$realVP['id'] = $row['id'];
			$this->update($table, $realVP, "id=".$row['id'], $db);
			$is_new = false;
			return $row['id'];
		}
		$is_new = true;
// if($table == 'register_ver' && $realVP['register_id'] == 63 && $realVP['description'] == 'ADC Plus-Side General Calibration Value Register'){
	// $sql = "SELECT * FROM $table where ".implode(' AND ', $where);
	// print_r($sql);
// }
		return $this->insert($table, $realVP, $db);
	}

	function save($row, $table_name, $db_name = '', &$is_new = true){
		// $is_new = true;
		if(!isset($row['id']))
			$row['id'] = 0;
		return $this->getElementId($table_name, $row, array('id'), $is_new, $db_name);
	}
}
?>
