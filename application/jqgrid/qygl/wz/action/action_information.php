<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_information.php');
require_once('const_def_qygl.php');

class qygl_wz_action_information extends action_information{
	protected function paramsFor_view_edit($params){
		$view_params = parent::paramsFor_view_edit($params);
// print_r($view_params['node']['value']);
		return $view_params;
	}

	protected function paramsFor_genzong($params){
		$view_params = array('label'=>'历史记录', 'id'=>$params['element'], 'disabled'=>empty($params['element']), 
			'view_file_dir'=>'qygl/yw/view');
		return $view_params;
	}
	
	// protected function getValue($view_params){
		// $value = parent::getValue($view_params);
// print_r($value);	
		// if($view_params){
			
		// }
			
		// return $value;
	// }
	
	protected function getViewEditButtons($params){
		$view_buttons = parent::getViewEditButtons($params);
		
		if(!empty($params['id'])){
			$style = 'position:relative;float:left';
			$newBtns = array(
				'genzong'=>array('label'=>'跟踪', 'style'=>$style),
				'scqk'=>array('label'=>'生产情况', 'style'=>$style),
				'kcqk'=>array('label'=>'库存情况', 'style'=>$style),
				'qx'=>array('label'=>'取消', 'style'=>$style),
				'jieshu'=>array('label'=>'完成', 'style'=>$style),
			);
			$res = $this->tool->query("SELECT * FROM zzvw_dingdan WHERE id={$params['id']}");
			$row = $res->fetch();
			switch($row['dingdan_status_id']){
				case DINGDAN_STATUS_ZHIXING:
					$view_buttons['genzong'] = $newBtns['genzong'];
					$view_buttons['qx'] = $newBtns['qx'];
					$view_buttons['jieshu'] = $newBtns['jieshu'];
					break;
			}
			if($row['yw_fl_id'] == YW_FL_JD){ //销售
				$view_buttons['scqk'] = $newBtns['scqk']; //查看生产情况
				$view_buttons['kcqk'] = $newBtns['kcqk']; //查看生产情况
			}
		}
		return $view_buttons;
	}
}

?>