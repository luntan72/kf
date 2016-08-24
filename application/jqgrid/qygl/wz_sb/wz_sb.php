<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//技术指标管理
class qygl_wz_sb extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'wz_id'=>array('editable'=>false, 'hidden'=>true),
			'fix_code'=>array('label'=>'资产编号'),
			'wh_days'=>array('label'=>'维护周期', 'post'=>'天'),
			'wh_date'=>array('label'=>'最后一次维护日期'),
			'min_handle'=>array('label'=>'最小处理量'),
			'max_handle'=>array('label'=>'最大处理量'),
			'isactive'=>array('label'=>'可用')
        );
		$this->options['edit'] = array('wz_id', 'fix_code', 'wh_days', 'wh_date', 'min_handle', 'max_handle');
	}
}
