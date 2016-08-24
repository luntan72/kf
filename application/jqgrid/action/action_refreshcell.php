<?php
require_once('const_def.php');
require_once('action_jqgrid.php');

class action_refreshcell extends action_jqgrid{
	protected function handlePost(){
// print_r($this->params);		
		$cellInfo = $this->table_desc->getCellInfo($this->params['cell_id']);
		if(isset($this->params['subdir']) && isset($cellInfo['subdir'])) //主要是防止subdir是随机产生的，这样的话两次取之间值就有变化
			$cellInfo['subdir'] = $this->params['subdir'];
		$list_action = actionFactory::get(null, 'list', $this->params);
		$res = $list_action->handlePost();
		$value = $res['rows'][0];
		$cellInfo = $this->tool->model2e($cellInfo, $value, DISPLAY_STATUS_VIEW);
		$cell = cellFactory::get($cellInfo, $value);//, array('value'=>$this->params['id']));
		$res = $cell->display();
		return $res;
	}
}

?>