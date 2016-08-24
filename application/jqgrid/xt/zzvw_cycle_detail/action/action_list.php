<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_list.php');

class xt_zzvw_cycle_detail_action_list extends action_list{

	protected function handlePost(){
		$ret = parent::handlePost();
		$ret['additional'] = $this->table_desc->getStatistics();
		return $ret;
	}
	
	protected function prepareParams(){
		parent::prepareParams();
		$isHistorySearch = false;
		$isResultSearch = false;
		foreach($this->params['searchConditions'] as $k=>$v){
			switch($v['field']){
				case 'testcase_id':
					//zzvw_cycle_detail
					$isHistorySearch = true;
					break;
				case 'result_type_id':
					$isResultSearch = true;
					break;
				case 'codec_stream_id':
					//zzvw_cycle_detail_stream
					$isHistorySearch = true;
					$this->params['codec_stream_id'] = $v['value'];
					break;
				default:
					break;
			}	
		}
		if($isHistorySearch){
			if(!$isResultSearch){
				$this->params['searchConditions'][$k+1]['field'] = 'result_type_id';
				$this->params['searchConditions'][$k+1]['op'] = '!=';
				$this->params['searchConditions'][$k+1]['value'] = '0';
			}
			if(empty($this->params['order']))
				$this->params['order'] = 'created DESC';
		}
	}		
	
	protected function filterParams(){
		$params = parent::filterParams();
//print_r($params['searchConditions']);		
		foreach($params['searchConditions'] as $k=>&$v){
			switch($v['field']){
				case 'key':
					$v['field'] = 'd_code,zzvw_cycle_detail.summary';
					$v['op'] = 'like';
					break;
				case 'result_type_id':
				case 'build_result_id':
					if ($v['value'] == -1)
						$v['value'] = RESULT_TYPE_BLANK;
					break;
				case 'tester_id':
					if ($v['value'] == -1)
						$v['value'] = RESULT_TYPE_BLANK;
					break;
			}
			$new_add = array('creater_id');
			if(in_array($v['field'], $new_add))
				unset($params['searchConditions'][$k]);
// print_r($v);
		}
		return $params;
	}
}
?>