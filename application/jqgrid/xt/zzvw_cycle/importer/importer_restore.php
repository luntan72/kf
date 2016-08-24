<?php
require_once('C:\Users\b46350\Documents\XiaoTian\xampp\xt\library\importer_base.php');

class xt_zzvw_cycle_importer_restore extends importer_base{

	protected function _import($fileName){
		// $this->parse($fileName);
		return $this->process();
	}
	
	protected function process(){
		$xt2013 = toolFactory::get('xt2013');
// print_r($xt2013);
		//testcase_id, codec_stream_id, result_type_id, test_env_id, cycle_id, defect_ids, comment, build_result_id, finish_time, 
		//duration_minutes, deadline, tester_id, task_detail_id, issue_comment-------one detail
		$sql = "select cycle.*, prj.name as prj, rel.name as rel, cycle_type.name as cycle_type from cycle".
			" left join prj on prj.id = cycle.prj_id". 
			" left join rel on rel.id = cycle.rel_id". 
			" left join cycle_type on cycle_type.id = cycle.cycle_type_id". 
			" where cycle.id in (22, 24, 27, 30, 70)";
// print_r($sql);
		$res = $xt2013->query($sql);
		$i = 0;
// print_r($res);
		while($row = $res->fetch()){
			$isNew = false;
			$old_id = $row['id'];
			$p_sql = "select os.name as os, chip.name as chip, board_type.name as board_type from prj".
				" left join os on os.id = prj.os_id".
				" left join chip on chip.id = prj.chip_id".
				" left join board_type on board_type.id = prj.board_type_id".
				" where prj.id = ".$row['prj_id'];
			$p_res = $xt2013->query($p_sql);
			if($p = $p_res->fetch()){
				if($p['chip'] == 'i.MX6DL/S')
					$p['chip'] = 'i.MX6DL';
				if($p['chip'] == 'i.MX6DQ')
					$p['chip'] = 'i.MX6Q';
				if($p['board_type'] == 'SabreSD')
					$p['board_type'] = 'SD';
				$chip_id = $this->tool->getExistedId('chip', array('name'=>$p['chip']), array('name'));
				$board_type_id = $this->tool->getExistedId('board_type', array('name'=>$p['board_type']), array('name'));
				$os_id = $this->tool->getExistedId('os', array('name'=>$p['os']), array('name'));
				if($chip_id && $board_type_id && $os_id){
					$prj = $p['chip']."-".$p['board_type']."-".$p['os'];
					$info = array('name'=>$prj, 'chip_id'=>$chip_id, 'board_type_id'=>$board_type_id, 'os_id'=>$os_id );
// print_r($info);
// print_r("\n<BR />");
					$prj_id = $this->tool->getExistedId('prj', $info, array('name'));
				}
					
			}
			$rel_id = $this->tool->getExistedId('rel', array('name'=>$row['rel']), array('name'));
			$cycle_type_id = $this->tool->getExistedId('cycle_type', array('name'=>$row['cycle_type']), array('name'));
			if($prj_id && $rel_id && $cycle_type_id){
				$cycleInfo = array('name'=>$row['name'], 'creater_id'=>$row['name'], 'start_date'=>$row['start_date'], 'end_date'=>$row['end_date'],
					'rel_id'=>$rel_id, 'build_target_id'=>1, 'compiler_id'=>1, 'cycle_status_id'=>CYCLE_STATUS_ONGOING, 'cloned_id'=>$row['cloned_id'], 'tester_ids'=>$row['tester_ids'],
					'cycle_category_id'=>$row['cycle_category_id'], 'cycle_type_id'=>$cycle_type_id, 'test_env_id'=>1, 'isactive'=>ISACTIVE_ACTIVE, 'description'=>$row['description'],
					'prj_id'=>$prj_id, 'created'=>$row['created'], 'link'=>$row['link'], 'testcase_type_id'=>TESTCASE_TYPE_CODEC, 'group_id'=>GROUP_CODEC
				);
				$new_id = $this->tool->getElementId('cycle', $row, array('name'), $isNew);
print_r('cycle:'.$new_id."\n<BR />");
				$cycle[$old_id] = $new_id;
				if($isNew){
					$i ++;
				}
			}
		}
		$sql = "select detail.*, stream.name as stream, stream.location as location from cycle_detail detail".
			" left join codec_stream stream on stream.id = detail.codec_stream_id".
			" where detail.cycle_id in(22, 24, 27, 30, 70)";
		$res = $xt2013->query($sql);
		$j = 0;
		while($data = $res->fetch()){
			$isNew = false;
			$old_id = $data['id'];
			$codec_stream_id = $this->tool->getExistedId('codec_stream', array('name'=>$data['stream'], 'location'=>$data['location']), array('name', 'location'));
			if($codec_stream_id == 'NEW'){
print_r($data['stream']."\n<BR />");
print_r($data['location']."\n<BR />");
continue;
			}
print_r('codec_stream:'.$codec_stream_id."\n<BR />");
			$data['codec_stream_id'] = $codec_stream_id;
			$data['cycle_id'] = $cycle[$data['cycle_id']];
			unset($data['id']);
			unset($data['testcase_ver_id']);
			$new_id = $this->tool->getElementId('cycle_detail', $data, array(), $isNew);
			$cycle_detail[$old_id] = $new_id;
			if($isNew){
				$j++;
			}
		}
print_r('cycle:'.count($cycle)."--".$i."    cycle_detail:".count($cycle_detail)."--".$j);
	}
}

?>