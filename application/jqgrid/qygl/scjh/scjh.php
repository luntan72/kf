<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//生产登记批次管理
class qygl_scjh extends table_desc{
	protected function init($params = array()){
		parent::init($params);
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'gx_id'=>array('label'=>'工序'),
            'wz_id'=>array('label'=>'产品', 'editrules'=>array('required'=>true)),
			'sc_date'=>array('label'=>'生产日期'),
			'amount'=>array('label'=>'计划数量'),
			'completed'=>array('label'=>'实际完成量'),
			'note'=>array('label'=>'备注', 'hidden'=>true),
			'isactive'=>array('label'=>'是否有效', 'editoptions'=>array('value'=>array(1=>'有效', 2=>'无效')), 'searchoptions'=>array('value'=>array(0=>' ', 1=>'有效', 2=>'无效'))),
        );
		$this->options['edit'] = array('gx_id', 'wz_id', 'sc_date', 'amount', 'note');
	}
}
