<?php

require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_set_result.php');

class xt_zzvw_subgrid_cycle_detail_action_set_result extends xt_zzvw_cycle_detail_action_set_result{
	protected function returnData($data){
		$res = $this->tool->query("SELECT cycle_id, test_env_id, codec_stream_id, prj_id, compiler_id, build_target_id FROM cycle_detail WHERE id=".$data['id']);
		if($info = $res->fetch()){
			$res0 = $this->tool->query("SELECT id FROM cycle_detail WHERE cycle_id = {$info['cycle_id']} AND test_env_id = {$info['test_env_id']}".
				" AND codec_stream_id = {$info['codec_stream_id']} AND prj_id = {$info['prj_id']} AND compiler_id = {$info['compiler_id']}".
				" AND build_target_id = {$info['build_target_id']}");
			while($row = $res0->fetch()){
				$element[] = $row['id'];
			}
		}
		
		$total_res = $this->tool->query("SELECT id, comment, result_type_id, issue_comment, test_env_id, defect_ids, finish_time FROM cycle_detail WHERE id in (".implode(',', $element).")");
		$total = array('comment'=>'null', 'issue_comment'=>'null', 'defect_ids'=>'null');
		while($row = $total_res->fetch()){
			$total['defect_idss'][] = $row['defect_ids'];
			$total['result_type_id'][] = $row['result_type_id'];
			$total['comments'][] = $row['comment'];
			$total['issue_comments'][] = $row['issue_comment'];
		}
			
		$res = $this->tool->query("SELECT id, name FROM result_type");
		$result_type[0] = 'Blank';
		while($info = $res->fetch())
			$result_type[$info['id']] = $info['name'];
		$total['result_type_id'] = array_unique($total['result_type_id']);
		if(count($total['result_type_id']) == 1){
			$total['codec_stream_result'] = 'All '.$result_type[$total['result_type_id'][0]];
			if($total['result_type_id'][0] == RESULT_TYPE_FAIL)
				$total['result_type_id'] = 112;
		}
		else{
			unset($result_type[1]);
			$results = $total['result_type_id'];
			foreach($result_type as $k=>$v){
				if(in_array($k, $results)){
					$total['result_type_id'] = 100 + $k;// Testing
					$total['codec_stream_result'] = 'Has '.$v;
					if(in_array(1, $results))
						$total['codec_stream_result'] = 'Has '.$v.' & Pass';
					if($k != '2'){
						if(in_array(2, $results))
							$total['codec_stream_result'] = 'Has '.$v.' & Fail';
					}
					if($k == 0 || $k = 2)
						break;
				}
			}
		}

		if(!empty($total['comments'])){
			$total['comments'] = array_unique($total['comments']);
			if(count($total['comments']) == 1){
				if(!empty($total['comments'][0]))
					$total['comment'] = $total['comments'][0];
			}
			else
				$total['comment'] = 'Pls Check Specific Trickmode Comment In Subgrid';
			unset($total['comments']);
		}
		if(!empty($total['issue_comments'])){
			$total['issue_comments'] = array_unique($total['issue_comments']);
			if(count($total['issue_comments']) == 1){
				if(!empty($total['issue_comments'][0]))
					$total['issue_comment'] = $total['issue_comments'][0];
			}
			else
				$total['issue_comment'] = 'Pls Check Specific Trickmode Comment In Subgrid';
			unset($total['issue_comments']);
		}
		if(!empty($total['defect_idss'])){
			$total['defect_idss'] = array_unique($total['defect_idss']);
			if(count($total['defect_idss']) == 1){
				if(!empty($total['defect_idss'][0]))
					$total['defect_ids'] = $total['defect_idss'][0];
			}
			else
				$total['defect_ids'] = implode(',', $total['defect_idss']);
			unset($total['defect_idss']);
		}
		$datass = array('datas'=>$total, 'subData'=>$data);	
		$datass['statistics'] = $this->getStatistics(True);	
		return (json_encode($datass));
	}
	
}

?>