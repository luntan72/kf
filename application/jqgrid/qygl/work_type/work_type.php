<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//工种管理
class qygl_work_type extends table_desc{
	protected function init($params){
// print_r($params);
		parent::init($params);
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'name'=>array('label'=>'工种', 'editrules'=>array('required'=>true)),
			'note'=>array('label'=>'职责描述'),
			'skill_work_type_id'=>array('label'=>'需要技能', 'editable'=>true, 'data_source_table'=>'skill')
        );
		
		$this->options['linkTables'] = array(
			'm2m'=>array(
				'skill_work_type'=>array('link_table'=>'skill_work_type', 'self_link_field'=>'work_type_id', 'link_field'=>'skill_id', 'refer_table'=>'skill'),
			),
		);
	}
}
