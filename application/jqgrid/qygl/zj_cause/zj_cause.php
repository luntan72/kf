<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//资金变动原因管理
class qygl_zj_cause extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'name'=>array('label'=>'资金变动原因', 'editrules'=>array('required'=>true)),
			'zj_direct_id'=>array('label'=>'资金流动方向')
        );
	}
}
