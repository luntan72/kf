<?php
require_once('action_jqgrid.php');
class xt_zzvw_cycle_action_update_skywalker extends action_jqgrid{
	
	// protected function handlePost(){
		// // $params = $this->params;
// // print_r($this->params)
		// $params = array(
			// 'app'=>'xiaotian', 
			// 'mod'=>'skywalker',
			// 'action'=>'createCycle',
			// 'testplan'=>'http://10.192.244.6/daily_test/vte_IMX6Q-Sabre-SD_d/runtest/imx63_SMD_auto',
			// 'txt'=>"http://shlx12.ap.freescale.net/daily_test/vte_IMX6DL-Sabre-SD/results/IMX6DL-Sabre-SD_AUTO_imx61_SMD_auto_00_04_9f_02_67_f2_8d3aab.txt",
			// 'html'=>"http://shlx12.ap.freescale.net/daily_test/vte_IMX6DL-Sabre-SD/results/IMX6DL-Sabre-SD_AUTO_ada_00_04_9f_01_ee_ee_514ac4.html"
			// );
		// $this->createCycle($params);
    // }
	protected function handlePost(){
		$params = $this->params;
		return $this->createCycle($params);
	}
	
	private function createCycle($params){
		$cycle_id = 0;
		$file = basename($params['txt']);
		$os = "Linux";//3.10, 3.14???
		$pair_values = array(
            "creater_id"=>TESTER_SKYWALKER, // Skywalker
            "created" => date("Y-m-d H:i:s"),
            "start_date" => date("Y-m-d"),
            "end_date" => date("Y-m-d"),
            "cycle_type_id" => CYCLE_TYPE_FUNCTION, // fun
            "name" => $file,
            "tester_ids"=>TESTER_SKYWALKER.",16,63",
            "group_id"=>GROUP_LINUXBSP, // linux BSP
            "testcase_type_ids"=>TESTCASE_TYPE_LINUX_BSP; //linux BSP
            'cycle_status_id'=>CYCLE_STATUS_FROZEN, // frozen
            'link'=>$params['html'],
        );
		if(preg_match("/^(.*?)_.*$/i", $file, $matches)){
            $platform = $matches[1];
			$board_type[0] = 'sabre-sd';
			$res = $this->db->query("select * from board_type");
			while($row = $res->fetch()){
				$board_type[$row['id']] = strtolower($row['name']);
			}
			$board_type = implode("|", $board_type);			
			if(preg_match("/^(IMX.*?)-(".$board_type.")$/i", $platform , $mt)){
				$chip = $mt[1];
				$board = $mt[2];
			}
			else if(preg_match("/^(IMX.*?)-(sabreauto|rdp3)$/i", $platform , $mt)){
				$chip = $mt[1];
				$board = $mt[2];
			}
			else if(preg_match("/^(IMX.*)(".$board_type.")$/i", $platform , $mt)){
				$chip = $mt[1];
				$board = $mt[2];
			}
			else if(preg_match("/^(IMX.*)(sabreauto|rdp3)$/i", $platform, $mt)){
				$chip = $mt[1];
				$board = $mt[2];
			}
			$models = array('sololite', 'solo');
			$models = implode("|", $models);
			if(preg_match("/^(IMX\d+)(".$models.")/i", $chip, $mtess)){
				if($mtess[2] == 'sololite')
					$mtess[2] = 'sl';
				else if($mtess[2] == 'solo')
					$mtess[2] = 's';
				$chip = $mtess[1].$mtess[2];
			}
			$chip = trim(strtoupper($chip));
			$chip = lcfirst($chip);
			$chip = str_replace("i", "i.", $chip);
			if($chip == 'i.MX6')
				$chip = 'i.MX6Q';
			$board = trim(strtoupper($board));
			if($board == 'SABRE-SD')
				$board = 'Sabre_SDB';
			else if($board == 'SABREAUTO')
				$board = 'ARD';
			$os_id = $this->tool->getElementId('os', array('name'=>$os));
			$board_type_id = $this->tool->getElementId("board_type", array('name'=>$board), array('name'));
			// $chip_id = $this->getChipId($chip, $os_id, $board_type_id);	
			$chip_id = $this->tool->getChipId($chip, array("os_id"=>$os_id, "board_type_id"=>$board_type_id));
			$name = $chip."-".$board."-".$os;
			$prj_id = $this->tool->getExistedId("prj", array('name'=>$name, 'os_id'=>$os_id, 'chip_id'=>$chip_id, 'board_type_id'=>$board_type_id),
				array('os_id', 'chip_id', 'board_type_id'));          
		   if ('error' == $prj_id)
				return;
			$pair_values['prj_ids'] = $prj_id;
			$pair_values['rel_id'] = $this->tool->getElementId('rel', array('name'=>$pair_values['start_date']));
			
			$cycle_id = $this->tool->getElementId("cycle", $pair_values, array('name'));		
			$detail = array();
			// get the cases from testplan
			$handle = fopen($params['testplan'], "r");
			if (!$handle)
				return $cycle_id;
			while(!feof($handle)){
				$line = trim(fgets($handle));
				if (empty($line))
					continue;
					
				if (preg_match("/^(.*?)_*[H|L]*\s+(.*)$/i", $line, $matches)){
					if (!empty($matches[1])){
						$parse_result[$matches[1]] = array('code'=>$matches[1], 'prj_id'=>$prj_id, 'cycle_id'=>$cycle_id, 
							'tester_id' => TESTER_SKYWALKER, 'finish_time'=>0);
					}
				}
			}
			fclose($handle);
			
			$handle = fopen($params['txt'], "r");
			if (!$handle)
				return $cycle_id;
			$this->parse($handle, $parse_result, $cycle_id, $prj_id);
			fclose($handle);
			$this->process($parse_result);
		}
		return $cycle_id;
	}
	
	protected function parse($handle, &$parse_result, $cycle_id, $prj_id){
		while(!feof($handle))
		  $data[] = fgets($handle);
		
		if(!empty($data)){
			for($row=0; $row<count($data); $row++){
				$row_data = trim($data[$row]);
				if(preg_match("/^Test Start Time:\s(.*?)\s(.*?)(\d{1,2})\s(\d{2}):(\d{2}):(\d{2})\s(\d{4})$/i", $row_data , $mc)){
					$timestamp = strtotime($mc[3]." ".$mc[2]." ".$mc[7]." ".$mc[4].":".$mc[5].":".$mc[6]);
					$timestamp = date("Y-m-d H:i:m", $timestamp);
// print_r("\n<BR />");
				}
				elseif(preg_match("/^(.*?)\s{1,}(.*?)\s{1,}(\d+)\s{1,}(.*)$/i", $row_data , $matches)){
// print_r($row_data."\n<br />");
					$parse_result[$matches[1]] = array('code'=>$matches[1], 'prj_id'=>$prj_id, 'cycle_id'=>$cycle_id, 
						'tester_id' => TESTER_SKYWALKER, 'finish_time'=>$timestamp, 'result_type_id'=>$matches[2], 'auto_run_minutes'=>$matches[4]);
				}					
			}
		}
	}
	
	protected function process($parse_result){
		$key = array('testcase_id', 'prj_id', 'cycle_id');
		foreach($parse_result as $detail){	
			$testcase_id = $this->tool->getExistedId('testcase', array('code'=>trim($detail['code'])), array('code'));
			if($testcase_id == 'error')
				continue;
			$detail['testcase_id'] = $testcase_id;
			unset($detail['code']);
			$res = $this->tool->query("select prj_testcase_ver.* from prj_testcase_ver".
				" left join testcase_ver on testcase_ver.id = prj_testcase_ver.testcase_ver_id".
				" left join testcase on testcase.id = testcase_ver.testcase_id".
				" where prj_testcase_ver.testcase_id = {$testcase_id}".
				" and prj_testcase_ver.prj_id = {$detail['prj_id']}");
			if($row = $res->fetch()){
				$detail['testcase_ver_id'] = $row['testcase_ver_id'];
// print_r($detail['result_type_id']."\n<br />");		
				if(empty($detail['result_type_id']))
					$detail['result_type_id'] = 'skip';
// print_r($detail['result_type_id']."\n<br />");
				$result_type_id = $this->tool->getResultId($detail['result_type_id']);
				if($result_type_id == RESULT_TYPE_BLANK)
					$detail['result_type_id'] = RESULT_TYPE_SKIP;
				$detail['result_type_id'] =  $result_type_id ;
				if(!empty($detail['auto_run_minutes'])){
					$auto_run_minutes = $detail['auto_run_minutes'];
					unset($detail['auto_run_minutes']);
				}
				$affetctID = $this->tool->getElementId('cycle_detail', $detail, $key);
				$this->tool->updatelastresult($affetctID);
				if(1 == $result_type_id && !empty($auto_run_minutes))
					$this->tool->update('testcase_ver', array('auto_run_minutes'=>$auto_run_minutes), "id=".$detail['testcase_ver_id']);
			}
		}
// print_r('~~~~~~~~~~~~~~~~~~~~~~create cycle successfully !!!~~~~~~~~~~~~~~~~~~~~~~~~~~~');
	}
}
?>