<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_linkage.php');

class qygl_zzvw_defect_gx_action_linkage extends action_linkage{//}action_jqgrid{
	protected function handlePost(){
		$ret = parent::handlePost();
		$exist = false;
		foreach($ret as $e){ //检查有没有正品选项
			if($e['id'] == 1){
				$exist = true;
				break;
			}
		}
		if(empty($exist)) //如果没有正品选项，则添加
			$ret[] = array('id'=>1, 'name'=>'正品,无缺陷');
		return $ret;
	}
}

?>