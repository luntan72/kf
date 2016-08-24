<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//技巧等级管理
class qygl_defect extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'name'=>array('label'=>'缺陷', 'editrules'=>array('required'=>true)),
			'zl_id'=>array('label'=>'质量等级'),
			'description'=>array('label'=>'描述'),
			'gx_id'=>array('label'=>'可能影响的工序', 'editable'=>true),
			'pic'=>array('label'=>'图片', 'editable'=>true, 'type'=>'files', 'db'=>'qygl', 'table'=>'defect'),
			'root_cause'=>array('label'=>'可能的原因', 'editable'=>true),
			'method'=>array('label'=>'可采取的措施', 'editable'=>true),
        );
		$this->options['linkTables'] = array(
			'm2m'=>array('gx')
		);

		$this->options['parent'] = array('table'=>'zl', 'field'=>'zl_id');
	}
	
	protected function configForInfo($action_name){
		parent::configForInfo($action_name);
		// print_r($this->options);
	}	
}
