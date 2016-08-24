<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//员工管理
class qygl_credit_level extends table_desc{
	protected function init($params){
		parent::init($params);
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'name'=>array('label'=>'信用等级'),
			'total'=>array('label'=>'总金额', 'post'=>'元'),
			'duration'=>array('label'=>'账期', 'post'=>'天'),
			'note'=>array('label'=>'描述')
        );
	}
}
