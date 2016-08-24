<?php
require_once('action_jqgrid.php');

class xt_testcase_ver_action_publish extends action_jqgrid{
	protected function handlePost(){
        $params = $this->params;
// print_r($params);		
		if (empty($params['from']))$params['from'] = 'testcase_ver';
		if ($params['from'] == 'testcase_ver')
			$params['id'] = $params['ver'];
		$strEditStatus = EDIT_STATUS_EDITING.','.EDIT_STATUS_REVIEW_WAITING.','.EDIT_STATUS_REVIEWING.','.EDIT_STATUS_REVIEWED;
		$strPrj = '';
		$strTestcaseIds = $params['id'];
		if($params['from'] == 'testcase'){
			$params['prj_ids'] = $params['ver'];
			$sql0 = "SELECT link.testcase_ver_id FROM prj_testcase_ver link LEFT JOIN testcase_ver ver ON link.testcase_ver_id = ver.id".
				" WHERE link.testcase_id IN ($strTestcaseIds) AND link.prj_id={$params['prj_ids']} AND ver.edit_status_id IN ($strEditStatus)";
			$res = $this->tool->query($sql0);
			while($row = $res->fetch())
				$ver[] = $row['testcase_ver_id'];
			if (!empty($ver))
				$strTestcaseIds = implode(',', $ver);
			else
				$strTestcaseIds = '';
		}
		if (!empty($strTestcaseIds)){
			$res = $this->tool->query("SELECT * FROM prj_testcase_ver WHERE testcase_ver_id IN ($strTestcaseIds)");
			while($row = $res->fetch()){
				$sql = "DELETE prj_testcase_ver FROM prj_testcase_ver left join testcase_ver on prj_testcase_ver.testcase_ver_id=testcase_ver.id ".
					" WHERE prj_id={$row['prj_id']} AND prj_testcase_ver.testcase_id={$row['testcase_id']} AND (testcase_ver.edit_status_id=".EDIT_STATUS_PUBLISHED." OR testcase_ver.edit_status_id=".EDIT_STATUS_GOLDEN.")";
				$this->tool->query($sql);
				// $this->tool->delete('prj_testcase_ver', "prj_id={$row['prj_id']} AND testcase_id={$row['testcase_id']} and (edit_status_id=".EDIT_STATUS_PUBLISHED." OR edit_status_id=".EDIT_STATUS_GOLDEN.")");
			}
			
			$sql = 'UPDATE testcase_ver ver, prj_testcase_ver link SET ver.edit_status_id='.EDIT_STATUS_PUBLISHED.
				', ver.update_comment=concat(update_comment, "\n\r['.$this->userInfo->nickname.' At '.date('Y-m-d H:i:s').']\n\r", :note)'.
				', link.edit_status_id='.EDIT_STATUS_PUBLISHED.
				' WHERE ver.id in ('.$strTestcaseIds.') AND ver.edit_status_id IN ('.$strEditStatus.') AND link.testcase_ver_id=ver.id';
	//print_r($sql);
			$this->tool->query($sql, array('note'=>$params['note']));
			
			//应取消所有对该Version的Review请求
			$userAdmin_db = dbFactory::get('useradmin');
			$task_type_review = 1;
			$url = '/jqgrid/jqgrid/oper/review/db/xt/table/testcase_ver/element/'.$params['id'];
			$sql = "SELECT task.* FROM task WHERE task_type_id=$task_type_review AND url=:url";
			$userAdmin_db->update('task', array('progress'=>100, 'task_result_id'=>1), "task_type_id=$task_type_review AND url='$url'");
			
			//通知相关人员：Case的owner, module的Owner
			$vers = array();
			$module = array();
			$res = $this->tool->query("SELECT testcase_module.creater_id, testcase_module.id as module_id, testcase.code, testcase_ver.id, testcase_ver.testcase_id, testcase_ver.owner_id FROM testcase_ver left join testcase on testcase.id=testcase_ver.testcase_id left join testcase_module on testcase.testcase_module_id=testcase_module.id WHERE testcase_ver.id in ($strTestcaseIds)");
			while($row = $res->fetch()){
				$module[$row['module_id']]['creater'][$row['creater_id']] = $row['creater_id'];
				$module[$row['module_id']]['testcase'][$row['id']] = $row;
				
				$vers[$row['id']]['owner_id'][$row['owner_id']] = $row['owner_id'];
				$vers[$row['id']]['testcase'][$row['id']] = $row;
			}
			
			foreach($module as $module_id=>$module_data){
				$creater_id = $module_data['creater'];
				$testcase = $module_data['testcase'];
				
				$codes = array();
				$linkCodes = array();
				foreach($testcase as $p){
					$codes[] = $p['code'];
					$linkCodes[] = "<a href='/jqgrid/jqgrid/newpage/1/container/mainContent/oper/information/db/xt/table/testcase/element/{$p['testcase_id']}/parent/0/ver/{$p['id']}'>{$p['code']}</a>";
				}
					
				$subject = "The testcase ".implode(', ', $codes)." published";
				$body = "The following testcase ".implode(', ', $linkCodes)." published";
				$this->userAdmin->inform($creater_id, $subject, $body);
			}
			
			foreach($vers as $ver_id=>$ver_data){
				$owner_id = $ver_data['owner_id'];
				$testcase = $ver_data['testcase'];
				
				$codes = array();
				$linkCodes = array();
				foreach($testcase as $p){
					$codes[] = $p['code'];
					$linkCodes[] = "<a href='/jqgrid/jqgrid/newpage/1/container/mainContent/oper/information/db/xt/table/testcase/element/{$p['testcase_id']}/parent/0/ver/{$p['id']}'>{$p['code']}</a>";
				}
				
				$subject = "The testcase ".implode(', ', $codes)." published";
				$body = "The following testcase: ".
					implode(', ', $linkCodes)." published";
				$this->userAdmin->inform($owner_id, $subject, $body);
			}
			
		}
		return;
	}
	
	protected function getViewParams($params){
		$view_params = $params;
		$view_params['view_file'] = "publish.phtml";
		$view_params['view_file_dir'] = '/jqgrid/xt/testcase_ver/view';

		return $view_params;
	}
}

?>