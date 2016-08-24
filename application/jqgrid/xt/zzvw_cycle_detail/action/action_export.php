<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_export.php');

class xt_zzvw_cycle_detail_action_export extends action_export{
	protected function getViewParams($params){
		$view_params = parent::getViewParams($params);
		$view_params['view_file_dir'] = '/jqgrid/xt/'.$this->get('table')."/view";
		if(!empty($this->params['parent']) && !empty($this->params['container']) &&
			(stripos($this->params['container'], "cycle_detail") === 0 || stripos($this->params['container'], "cycle_stream") === 0)){
			$sql = "select distinct os.name as os, cycle.group_id from cycle_detail detail".
				" left join cycle on cycle.id = detail.cycle_id".
				" left join prj on prj.id = detail.prj_id".
				" left join os on os.id = prj.os_id".
				" where cycle_id=".$this->params['parent'];
			$res = $this->tool->query($sql);
			while($info = $res->fetch()){
				$view_params['group_id'] = $info['group_id'];
				$oss[] = $info['os'];
			}
			$i = $j = 0;
			if(!empty($oss)){
				foreach($oss as $os){
					if(stripos(strtolower($os), 'android') !== false){
							$i++;
					}
					else if(stripos(strtolower($os), 'linux') !== false){
						$j++;
					}
				}
				if( $i== count($oss))
					$view_params['os'] = 'Android';
				else if($j == count($oss))
					$view_params['os'] = 'Linux';
			}
//print_r($view_params['os']);
		}
		return $view_params;
	}
}

?>