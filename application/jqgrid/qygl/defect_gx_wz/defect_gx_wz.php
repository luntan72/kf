<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//工序-物资-缺陷管理
class qygl_defect_gx_wz extends table_desc{
	protected function init($params){
		parent::init($params);
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'gx_wz_id',
			// 'wz_id'=>array('label'=>'物资'),
			// 'gx_id'=>array('label'=>'工序'),
            'defect_id'=>array('label'=>'缺陷', 'editrules'=>array('required'=>true)),
			'price'=>array('label'=>'单价'),
			'ck_weizhi_id'=>array('label'=>'存放位置'),
			'remained'=>array('label'=>'现存量')
        );
		$this->options['edit'] = array('defect_id', 'gx_wz_id', 'price', 'ck_weizhi_id', 'remained');
	}
}
