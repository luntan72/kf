<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//员工管理
class qygl_hobby extends table_desc{
	protected function init($params){
		parent::init($params);
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'name'=>array('label'=>'爱好'),
			'note'=>array('label'=>'描述')
        );
	}
}
