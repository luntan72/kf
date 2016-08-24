<?php

require_once('action_jqgrid.php');

class xt_codec_stream_action_remove_unsupported_trickmodes extends action_jqgrid{

   public function handlePost(){
		//$params = $this->parseParams();
		$params = $this->params;
		$params['actions'] = json_decode($params['actions']);//检查$params是否非空
		$actions = implode(",", $params['actions']);
		$stream = array();
		$id = array();
		$ret = 0;
		$sql = "SELECT id, testcase_ids as trickmode_ids FROM codec_stream WHERE id in (".implode(",", $params['id']).")";
		$res = $this->tool->query($sql);
		while($row = $res->fetch()){
			if(empty($row['trickmode_ids']))
				continue;
			$trickmodes = explode(",", $row['trickmode_ids']);
			foreach($params['actions'] as $action){
				$key = array_search($action, $trickmodes);
				if($key != false){
					unset($trickmodes[$key]);
				}
			}
			$this->tool->update("codec_stream", array('testcase_ids'=>implode(",", $trickmodes)), "id=".$row['id'] );
		}
		print_r("success");
	}
	
}

?>