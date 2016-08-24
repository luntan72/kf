<?php

require_once('action_jqgrid.php');

class xt_codec_stream_action_set_supported_trickmodes extends action_jqgrid{

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
			if(!empty($row['trickmode_ids'])){
				$trickmodes = explode(",", $row['trickmode_ids']);
				foreach($params['actions'] as $action){
					if(in_array($action, $trickmodes))
						continue;
					$row['trickmode_ids'] .= ",".$action;
					$this->tool->update("codec_stream", array('testcase_ids'=>$row['trickmode_ids']), "id=".$row['id'] );
				}
			}
			else{
				$this->tool->update("codec_stream", array('testcase_ids'=>$actions), "id=".$row['id'] );
			}
		}
		print_r("success");
	}
	
}

?>