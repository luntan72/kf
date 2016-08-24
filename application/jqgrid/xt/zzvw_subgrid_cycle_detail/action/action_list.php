<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/action/action_list.php');

class xt_zzvw_subgrid_cycle_detail_action_list extends xt_zzvw_cycle_detail_action_list{
	protected function prepareParams(){
		parent::prepareParams();
		foreach($this->params['searchConditions'] as $k=>$v){
			if(isset($v['field']) && ($v['field'] == 'id')){
				$res = $this->tool->query("SELECT id, cycle_id, codec_stream_id, test_env_id, prj_id, build_target_id, compiler_id FROM cycle_detail WHERE id=".$v['value']);
				if($detail = $res->fetch()){
					$this->params['searchConditions'][$k+1]['field'] = 'cycle_id';
					$this->params['searchConditions'][$k+1]['op'] = '=';
					$this->params['searchConditions'][$k+1]['value'] = $detail['cycle_id'];
					$this->params['searchConditions'][$k+2]['field'] = 'codec_stream_id';
					$this->params['searchConditions'][$k+2]['op'] = '=';
					$this->params['searchConditions'][$k+2]['value'] = $detail['codec_stream_id'];
					$this->params['searchConditions'][$k+3]['field'] = 'test_env_id';
					$this->params['searchConditions'][$k+3]['op'] = '=';
					$this->params['searchConditions'][$k+3]['value'] = $detail['test_env_id'];
					$this->params['searchConditions'][$k+4]['field'] = 'prj_id';
					$this->params['searchConditions'][$k+4]['op'] = '=';
					$this->params['searchConditions'][$k+4]['value'] = $detail['prj_id'];
					$this->params['searchConditions'][$k+5]['field'] = 'build_target_id';
					$this->params['searchConditions'][$k+5]['op'] = '=';
					$this->params['searchConditions'][$k+5]['value'] = $detail['build_target_id'];
					$this->params['searchConditions'][$k+6]['field'] = 'compiler_id';
					$this->params['searchConditions'][$k+6]['op'] = '=';
					$this->params['searchConditions'][$k+6]['value'] = $detail['compiler_id'];
					// $special = " AND (cycle_id=".$detail['cycle_id'].")".
						// " AND (codec_stream_id=".$detail['codec_stream_id'].")".
						// " AND (test_env_id=".$detail['test_env_id'].")";
					unset($this->params['searchConditions'][$k]);
				}
			}
		}
	}
}
?>