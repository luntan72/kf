<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_version_information.php');

class xt_testcase_action_information extends action_version_information{
	public function setParams($params){
		parent::setParams($params);
		$this->params['ver_table'] = 'testcase_ver';
	}
	
	protected function paramsFor_view_edit($params){
		return parent::paramsFor_view_edit($params);
	}

	protected function paramsFor_edit_history($params){
		return parent::paramsFor_edit_history($params);
	}

	protected function paramsFor_test_history($params){
// print_r($this->userInfo->roles);
		if(!in_array("visitor", $this->userInfo->roles)){
			$view_params = array('label'=>'Test History', 'id'=>$params['element'], 'disabled'=>empty($params['element']), 
				'view_file_dir'=>'xt/testcase/view');
			return $view_params;
		}
	}

	protected function paramsFor_srs_cover($params){
		// $res = $this->db->query("SELECT * FROM prj_srs_node_testcase WHERE testcase_id=".$params['id']);
		// $test_history = $res->fetchAll();
		$view_params = array('label'=>'SRS Cover', 'id'=>$params['element'], 'disabled'=>empty($params['element']), 
			'view_file_dir'=>'xt/testcase/view');
		return $view_params;
	}

	protected function getViewEditButtons($params){
// print_r($this->params);
// print_r($this->params);		
		$view_buttons = parent::getViewEditButtons($params);
		$vers = explode(',', $params['ver']);
		if (count($vers) > 1)
			return $view_buttons;
		$res = $this->tool->query("SELECT * FROM testcase_ver WHERE id={$params['ver']}");
		$row = $res->fetch();
		$testcase_ids = $row['testcase_id'];
		$style = 'position:relative;float:left';
		$newBtns = array(
			'view_edit_ask2review'=>array('label'=>'Ask To Review', 'style'=>$style),
			'view_edit_publish'=>array('label'=>'Publish', 'style'=>$style),
			'view_edit_coversrs'=>array('label'=>'Cover SRS', 'style'=>$style),
		);
		switch($row['edit_status_id']){
			case EDIT_STATUS_EDITING:
			case EDIT_STATUS_REVIEW_WAITING:
			case EDIT_STATUS_REVIEWING:
			case EDIT_STATUS_REVIEWED:
				$view_buttons['view_edit_ask2review'] = $newBtns['view_edit_ask2review'];
				$view_buttons['view_edit_publish'] = $newBtns['view_edit_publish'];
				break;
			// case EDIT_STATUS_REVIEW_WAITING:
			// case EDIT_STATUS_REVIEWING:
			// case EDIT_STATUS_REVIEWED:
				// $view_buttons['view_edit_publish'] = $newBtns['view_edit_publish'];
				// break;
			case EDIT_STATUS_PUBLISHED:
			case EDIT_STATUS_GOLDEN:
				$view_buttons['view_edit_coversrs'] = $newBtns['view_edit_coversrs'];
				unset($view_buttons['view_edit_abort']);
				break;
		}
		return $view_buttons;
	}
}

?>