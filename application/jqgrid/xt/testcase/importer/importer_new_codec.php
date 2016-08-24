<?php
require_once('importer_excel.php');

class xt_testcase_importer_new_codec extends importer_excel{

	protected function processSheetData($title, $sheet_data){
		foreach($sheet_data as $data){
			//i.MX6Q=>12, i.MX6DL=>13,i.MX6S=>14, i.MX6SL=>15, i.MX6SX=>20
			$res = $this->tool->query("select ver.*, ptv.prj_id".
					" from testcase tc left join testcase_ver ver on ver.testcase_id = tc.id".
					" left join prj_testcase_ver ptv on ver.id = ptv.testcase_ver_id".
					" left join prj on prj.id = ptv.prj_id".
					" where tc.code = '".$data['code']."' and tc.testcase_type_id = ".TESTCASE_TYPE_CODEC." and ptv.testcase_ver_id is not null and prj.os_id in (1,10,21)". 
					" and prj.chip_id in (12,13,14,15,20,55,107,113) and tc.isactive = ".ISACTIVE_ACTIVE.
					" and ver.edit_status_id in (".EDIT_STATUS_PUBLISHED.",".EDIT_STATUS_GOLDEN.")");
			$ret = 0;
			$ver = array();
			while($row = $res->fetch()){
				$case = $ptv = array();			
				$ptv['prj_id'] = $row['prj_id'];
				unset($row['prj_id']);
				$oldVer_id = $row['id'];
				
				if($row['command'] != $data['command']){
					if(!empty($data['command']))
						$row['command'] = $data['command'];
					if(!isset($ver[$oldVer_id])){
						unset($row['id']);
						$row['update_from'] = $row['ver'];
						$row['updated'] = date('Y-m-d H:i:s');
						$row['updater_id'] = $this->params['owner_id'];
						if(empty($row['update_comment'])){
							$row['update_comment'] = "update command by shan( by importing ".$title." excel to XT3.0.2)";
							if(!empty($data['expected_result']))
								$row['update_comment'] = "update summary, expected_result, command by shan( by importing ".$title." excel to XT3.0)";
						}
						else
							$row['update_comment'] .= "\n update command by shan( by importing ".$title." excel to XT3.0.2)";
						$row['review_comment'] = "publish by shan(by importing  ".$title." excel to XT3.0)";
						$result = $this->tool->query("SELECT max(ver) as max_ver FROM testcase_ver WHERE testcase_id={$row['testcase_id']}");
						$info = $result->fetch();
						$row['ver'] = $info['max_ver'] + 1;
						$ver[$oldVer_id] = $this->tool->getElementId("testcase_ver", $row);
					}
					if($ver[$oldVer_id] != $oldVer_id){
						$link = array('prj_id'=>$ptv['prj_id'], 'testcase_ver_id'=>$ver[$oldVer_id], 'testcase_id'=>$row['testcase_id']);
						$history = array('prj_id'=>$ptv['prj_id'], 'testcase_id'=>$row['testcase_id'], 'act'=>'remove');
						$res0 = $this->tool->query("SELECT * FROM prj_testcase_ver". 
							" left join testcase_ver on testcase_ver.testcase_id = prj_testcase_ver.testcase_id".
							" WHERE prj_testcase_ver.prj_id={$ptv['prj_id']}".
							" AND prj_testcase_ver.testcase_id={$row['testcase_id']} AND prj_testcase_ver.testcase_ver_id={$ver[$oldVer_id]}".
							" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")");
						if($row0 = $res0->fetch()){
							continue;
						}
						else{
							$res1 = $this->tool->query("SELECT * FROM prj_testcase_ver". 
								" left join testcase_ver on testcase_ver.testcase_id = prj_testcase_ver.testcase_id".
								" WHERE prj_testcase_ver.prj_id={$ptv['prj_id']}".
								" AND prj_testcase_ver.testcase_id={$row['testcase_id']}".
								" AND testcase_ver.edit_status_id IN (".EDIT_STATUS_PUBLISHED.','.EDIT_STATUS_GOLDEN.")");
							while($row1 = $res1->fetch()){
								$this->tool->delete('prj_testcase_ver', "prj_id={$ptv['prj_id']} AND testcase_id={$row['testcase_id']}");
								$history['testcase_ver_id'] = $row1['testcase_ver_id'];
								$history['act'] = 'remove';
								$this->tool->insert('prj_testcase_ver_history', $history);
							}
							$history['testcase_ver_id'] = $ver[$oldVer_id];
							$history['act'] = 'add';
							$this->tool->insert('prj_testcase_ver', $link);
							$this->tool->insert('prj_testcase_ver_history', $history);
						}
						// $this->tool->delete("prj_testcase_ver", "prj_id={$ptv['prj_id']} and testcase_ver_id={$oldVer_id} and testcase_id={$row['testcase_id']}");
						// $this->tool->insert("prj_testcase_ver_history", array('prj_id'=>$ptv['prj_id'], 'testcase_ver_id'=>$oldVer_id, 'testcase_id'=>$row['testcase_id'],
							// 'act'=>'delete', 'created'=>date('Y-m-d h:i:s')));
						// $this->tool->insert("prj_testcase_ver", array('prj_id'=>$ptv['prj_id'], 'testcase_ver_id'=>$ver[$oldVer_id], 'testcase_id'=>$row['testcase_id']));
						// $this->tool->insert("prj_testcase_ver_history", array('prj_id'=>$ptv['prj_id'], 'testcase_ver_id'=>$ver[$oldVer_id], 'testcase_id'=>$row['testcase_id'],
							// 'act'=>'add', 'created'=>date('Y-m-d h:i:s')));
					}
				}
			}
		}
	}
}
?>
