<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//员工管理
class qygl_hb_fl extends table_desc{
	protected function init($params){
		parent::init($params);
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'name'=>array('label'=>'伙伴类型'),
        );
	}
}
