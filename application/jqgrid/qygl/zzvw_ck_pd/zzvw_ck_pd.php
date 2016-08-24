<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH."/jqgrid/qygl/yw_tool.php");
//库存盘点
class qygl_zzvw_ck_pd extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'ck_pd';
		$this->options['list'] = array(
			'id'=>array('hidden'=>true),
			'gx_id'=>array('label'=>'工序'),
			'wz_id'=>array('label'=>'物资'),
			'defect_id'=>array('label'=>'缺陷'),
			'pici_id'=>array('label'=>'批次'),
			'expected_amount'=>array('label'=>'理论余量', 'editable'=>true, 'disabled'=>true, 'post'=>'?'),
			'amount'=>array('label'=>'实际余量', 'post'=>'?'),
			'note'=>array('label'=>'备注'),
			'jbr_id'=>array('label'=>'盘点人', 'data_source_table'=>'zzvw_yg'),
			'happen_date'=>array('label'=>'盘点日期')
		);
	}
}

/*
那些已经使用完的并且盘点过的批次不再盘点。所有实际存量为0的批次设置为不需要后续盘点

输入时，以工序--物资--缺陷的顺序来筛选批次，所有
*/