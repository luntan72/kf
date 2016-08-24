<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//资金类型管理
class qygl_zhengjian_fl extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'name'=>array('label'=>g_str('zhengjian_fl'), 'editrules'=>array('required'=>true)),
			'isactive'
        );
	}
}
