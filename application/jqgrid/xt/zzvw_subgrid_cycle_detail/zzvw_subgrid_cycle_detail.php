<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle_detail/zzvw_cycle_detail.php');

class xt_zzvw_subgrid_cycle_detail extends xt_zzvw_cycle_detail{
	protected function init($params){
		parent::init($params);
		$this->options['linktype'] = 'infoLink';
		$this->options['list'] = array(
			'id'=>array('formatter'=>'infoLink'),
			'c_f'=>array('hidden'=>true, 'excluded'=>true, 'edittype'=>'text', 'hidedlg'=>true),
			'cycle_id'=>array('hidden'=>true),
			'prj_id'=>array('label'=>'Prj', 'hidden'=>true),
			'd_code'=>array('label'=>'Testcase', 'editable'=>false, 'unique'=>false, 'formatter'=>'text_link', 'formatoptions'=>array('db'=>'xt', 'table'=>'testcase', 'newpage'=>true, 'data_field'=>'testcase_id', 'addParams'=>array('ver'=>'testcase_ver_id'))),
			'summary'=>array('label'=>'Summary', 'width'=>65, 'editable'=>false),
			'test_env_id'=>array('label'=>'Test Env', 'width'=>50, 'hidden'=>true),
			'result_type_id'=>array('label'=>'Result', 'width'=>30, 'editrules'=>array('required'=>true)), 
			'comment'=>array('label'=>'Comment', 'width'=>100),
			'issue_comment'=>array('label'=>'Issue Comment', 'hidden'=>true, 'editable'=>false),
			'defect_ids'=>array('label'=>'CRID/JIRA Key', 'formatter'=>'jira_link', 'required'=>false, 'width'=>50),
			'testcase_type_id'=>array('label'=>'Case Type', 'hidden'=>true, 'editable'=>false),
			'testcase_priority_id'=>array('label'=>'Priority', 'cols'=>6, 'editable'=>false, 'width'=>30),
			'auto_level_id'=>array('label'=>'Auto Level', 'width'=>50, 'editable'=>false, 'hidden'=>true),
			//'duration_minutes'=>array('hidden'=>true),
			'precondition'=>array('label'=>'Precondition', 'hidden'=>true, 'rows'=>8, 'editable'=>false),
			'objective'=>array('hidden'=>true),
			'steps'=>array('label'=>'Steps', 'hidden'=>true, 'rows'=>8, 'editable'=>false),
			'command'=>array('label'=>'CMDline', 'hidden'=>true, 'editable'=>false),
			'expected_result'=>array('label'=>'Expected Result', 'hidden'=>true, 'editable'=>false),
			'resource_link'=>array('label'=>'Resource Link', 'hidden'=>true, 'editable'=>false),
			'deadline'=>array('required'=>false),
			'finish_time'=>array('label'=>'Finished', 'width'=>70),
			'updater_id'=>array('label'=>'Updater', 'width'=>35, 'hidden'=>true),
			'tester_id'=>array('label'=>'Testor', 'width'=>35),
			'isTester'=>array('excluded'=>true, 'hidden'=>true, 'hidedlg'=>true),
			'logs'=>array('hidden'=>true, 'formatter'=>'log_link'),
			//'act'=>array('label'=>'Tips', 'excluded'=>true, 'hidden'=>true, 'width'=>80, 'search'=>false),
			'creater_id'=>array('hidden'=>true, 'hidedlg'=>true, 'required'=>false),
			'assistant_owner_id'=>array('hidden'=>true, 'hidedlg'=>true, 'required'=>false),
		);
		unset($this->options['query']);
		$this->options['edit'] = array('codec_stream_id'=>array('label'=>'Stream'), 'd_code', 'ver', 'summary', 'precondition', 'steps', 'expected_result', 'auto_level_id', 'test_env_id', 
			'result_type_id', 'defect_ids', 'comment', 'issue_comment', 'new_issue_comment'=>array('edittype'=>'textarea'), 'duration_minutes');
        $this->options['gridOptions']['inlineEdit'] = false;
		$this->options['gridOptions']['subGrid'] = false;
        $this->options['ver'] = '1.0';
		$this->getCond();
    }
	
	public function accessMatrix(){
		$access_matrix = parent::accessMatrix();
		$access_matrix['row_tester']['set_build_result'] = false;
		return $access_matrix;
	}
	
	protected function getCond(){
		$cond['field'] = 'cycle_id';
		if(!empty($this->params['parent'])){
			$cond['value'] = $this->params['parent'];
		}
		else if(!empty($this->params['filters'])){
			$filter = json_decode($this->params['filters']);
			foreach($filter as $k=>$v){
				if($v != 'AND'){
					foreach($v as $val){
						$f = 0;
						foreach($val as $kkk=>$data){
							if($data == $cond['field'])
								$f = 1;
							else if($data == 'id')
								$f = 2;			
							if($kkk == 'data'){
								if($f == 1)
									$cond['value'] = $data;
								else if($f == 2){
									$res = $this->tool->query("select cycle_id from cycle_detail where id=".$data);
									if($row = $res->fetch())
										$cond['value'] = $row['cycle_id'];
								}
							}
						}
					}
				}
			}
		}
		else if($this->params['id']){
			if(is_array($this->params['id']))
				$sql = "select cycle_id from ".$this->get('real_table')." where id in (".implode(", ", $this->params['id']).")";
			else
				$sql = "select cycle_id from ".$this->get('real_table')." where id = ".$this->params['id'];
			$res = $this->tool->query($sql);
			if($info = $res->fetch())
				$cond['value'] = $info['cycle_id'];
		}
		$this->params['cond'] = $cond;
// // print_r($cond);
		// return $cond;
	}
	
	public function getButtons(){
		$btns = parent::getButtons();
		unset($btns['export']);
		unset($btns['set_build_result']);
		if(isset($this->params['cond']['value'])){
			if(($this->params['cond']['field'] == 'cycle_id') && $this->params['cond']['value']){
				$cycle = '';
				$roleAndStatus = $this->roleAndStatus('cycle', $this->params['cond']['value'], 0, array('status'=>'cycle_status_id', 'assistant_owner'=>'assistant_owner_id'));
				$role = $roleAndStatus['role'];
				if(isset($roleAndStatus['status'])){
					$status = $roleAndStatus['status'];
					if($status == CYCLE_STATUS_ONGOING){
						unset($btns['update_trickmode']);
						unset($btns['update_env']);
						unset($btns['update_ver']);
						$btns['remove_case'] = array('caption'=>'Remove Stream Actions', 'title'=>'Delete records for cycle');
					}	
				}
			}
		}
		return $btns;
	}
	protected function handleFillOptionCondition(){
	}
	
	public function getMoreInfoForRow($row){
		$row = parent::getMoreInfoForRow($row);
		$row['c_f'] = 2;
		return $row;
	}
}

?>