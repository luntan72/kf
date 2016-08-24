<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//员工管理
class qygl_ck_weizhi extends table_desc{
	protected function init($params){
		parent::init($params);
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            // 'ck_fl_id'=>array('label'=>'仓库类型'),
			'name'=>array('label'=>'仓位名称'),
			'ck_id'=>array('label'=>'仓库'),
			'note'=>array('label'=>'描述')
        );
	}
}
