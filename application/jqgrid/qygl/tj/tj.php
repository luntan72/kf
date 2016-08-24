<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//批次管理
class qygl_tj extends table_desc{ //应该包括生产情况，财务情况，应收应付情况，采购情况，发货情况，订单情况
	protected function init($params){
		parent::init($params);
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'from_date'=>array('label'=>'开始日期', 'editrules'=>array('required'=>true)),
			'end_date'=>array('label'=>'结束日期', 'editable'=>true, 'editrules'=>array('required'=>true)),
			'pdf'=>array('label'=>'文件名', 'formatter'=>'files', 'type'=>'files', 'db'=>'qygl', 'table'=>'tj', 'subdir'=>''),
			'updated'=>array('label'=>'更新时间')
        );
		$this->options['view'] = array('from_date', 'end_date', 'pdf');
		$this->options['edit'] = array('from_date', 'end_date');
	}
	
	protected function getButtonForList(){
        $buttons = parent::getButtonForList();
		unset($buttons['edit']);
		return $buttons;
	}
	
	protected function getButtonForInfo(){
		$buttons = parent::getButtonForInfo();
		unset($buttons['edit']);
		return $buttons;
	}
}
