<?php
require_once('importer_base.php');

class xt_zzvw_cycle_importer_update_link_tables extends importer_base{
	public function import(){
		$tables = array('build_target_cycle', 'compiler_cycle', 'cycle_prj', 'cycle_tester', 'cycle_testcase_type');
		foreach($tables as $table){
			if(preg_match('/^(.*)_cycle$/', $table, $matches)){
				$new_table = $matches[1];
			}
			else if(preg_match('/^cycle_(.*)$/', $table, $matches)){
				$new_table = $matches[1];
			}
			$keyID = $new_table."_ids";
			$kID = $new_table."_id";
			if($new_table == 'rel')
				$keyID = $kID;
			$res = $this->tool->query("SELECT id, $keyID FROM cycle WHERE id >4593 AND creater_id in (148, 116, 117)");
			while($row = $res->fetch()){
				$keys = array();
				if(stripos($row[$keyID], ",") !== false){
					$keys = explode(",", $row[$keyID]);
				}
				else{
					$keys = array($row[$keyID]);
				}
				foreach($keys as $v){
					if(!empty($v)){
						$insert = array('cycle_id'=>$row['id'], $kID=>$v);
						$result = $this->tool->query("SELECT id FROM $table WHERE cycle_id={$row['id']} AND $kID=$v LIMIT 1");
						if($info = $result->fetch()){
							continue;
						}
						else{
							$this->tool->insert($table, $insert);
						}
					}
				}	
			}
		}
print_r("Process Done !!!");
	}
}
?>