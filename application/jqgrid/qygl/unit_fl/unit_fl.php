<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');

class qygl_unit_fl extends table_desc{
	protected function init($params){
		parent::init($params);
// print_r($params);		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'name'=>array('label'=>'名称'),
            'description'=>array('label'=>'描述'),
			'unit_id'=>array('label'=>'标准单位'),
        );
		$this->options['edit'] = array('name', 'description', 'unit_id'=>array('editrules'=>array('required'=>true)));
		$this->options['add'] = array('name', 'description');
		if(!empty($this->params['id'])){
			$res = $this->tool->query("SELECT id FROM unit where unit_fl_id={$this->params['id']}");
			while($row = $res->fetch())
				$this->options['edit']['unit_id']['limit'][] = $row['id'];//res->fetchAll();
		}
	}
	
	// protected function _getLimit($params){
		// $ret = false;
		// if(!empty($this->params['id'])){
			// $res = $this->tool->query("SELECT id FROM unit where unit_fl_id={$this->params['id']}");
			// $ret['unit_id'] = $res->fetch();
		// }
// print_r($ret);		
		// return $ret;
	// }
	
	
}
