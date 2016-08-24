<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_list.php');

class xt_codec_stream_action_list extends action_list{
	protected function filterParams(){
		$params = parent::filterParams();
//print_r($params['searchConditions']);		
		foreach($params['searchConditions'] as &$v){
			switch($v['field']){
				case 'key':
					$v['field'] = 'name, code';
					$v['op'] = 'like';
					break;
			}
		}
		return $params;
	}
}

?>