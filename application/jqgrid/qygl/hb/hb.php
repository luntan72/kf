<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//员工管理
class qygl_hb extends table_desc{
	protected function init($params){
		parent::init($params);
		$this->params['real_table'] = 'hb';
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'name'=>array('label'=>g_str('xingming'), 'editrules'=>array('required'=>true)),
			'gender_id'=>array('label'=>g_str('gender')),
			'zhengjian_fl_id'=>array('label'=>g_str('zhengjian_fl'), 'hidden'=>true),
            'identity_no'=>array('label'=>g_str('identity_no'), 'hidden'=>true),
            'credit_level_id'=>array('label'=>g_str('credit_level'), 'hidden'=>true),
            'bank'=>array('hidden'=>true),
            'bank_account_no'=>array('hidden'=>true),
            'tax_no'=>array('hidden'=>true),
            'account_receivable'=>array(),
            'city'=>array(),
            'address'=>array('hidden'=>true),
			'hb_contact_method'=>array('label'=>g_str('contact_method'), 'formatter'=>'multi_row_edit', 'legend'=>g_str('contact_method'), 'data_source_table'=>'hb_contact_method',
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>'%(contact_method_id)s:%(content)s'), 'from'=>'qygl.hb_contact_method'
				),
			'hb_fl_id'=>array('label'=>g_str('fl'), 'search'=>true, 'hidden'=>true, 'editable'=>true, 'editrules'=>array('required'=>true)),
			);
		$detailColumns = $this->getDetailListColumns();
		foreach($detailColumns as $k=>$v){
			if(is_int($k)){
				$k = $v;
				$v = array();
			}
			$this->options['list'][$k] = $v;
		}
		$this->options['list']['hobby_id'] = array('label'=>g_str('hobby'), 'editable'=>true, 'data_source_table'=>'hobby');
		$this->options['list']['lxr'] = array();
		$this->options['list']['cell_no'] = array('label'=>g_str('cell_no'));
		$this->options['list']['note'] = array('label'=>g_str('note'));
        $this->options['list']['isactive'] = array('label'=>g_str('isactive'));
		
		$this->options['linkTables'] = array(
			'm2m'=>array(
				'hobby'=>array('link_table'=>'hb_hobby', 'self_link_field'=>'hb_id', 'link_field'=>'hobby_id', 'refer_table'=>'hobby'),
			),
			'one2m'=>array(
				'hb_contact_method'=>array('link_table'=>'hb_contact_method', 'self_link_field'=>'hb_id'),
				),
			);
		
		$this->options['edit'] = $this->options['add'] = array('name', 'gender_id', 'zhengjian_fl_id', 'identity_no', 
			'credit_level_id', 'bank', 'bank_account_no', 'tax_no'=>array(), 'account_receivable', 'address',
			'hb_contact_method');
		$detailColumns = $this->getDetailEditColumns();
		foreach($detailColumns as $k=>$v){
			if(is_int($k)){
				$k = $v;
				$v = array();
			}
			$this->options['edit'][$k] = $v;
		}
		$this->options['edit']['note'] = $this->options['edit']['hobby_id'] = $this->options['edit']['cell_no'] = $this->options['edit']['lxr'] = array();
		
		$detailColumns = $this->getDetailAddColumns();
		foreach($detailColumns as $k=>$v){
			if(is_int($k)){
				$k = $v;
				$v = array();
			}
			$this->options['add'][$k] = $v;
		}
		$this->options['add']['note'] = $this->options['add']['hobby_id'] = $this->options['add']['cell_no'] = $this->options['add']['lxr'] = array();
		$this->options['edit']['hb_fl_id']['type'] = $this->options['add']['hb_fl_id']['type'] = 'hidden';
	}

	protected function getDetailListColumns(){
		return array();
	}
	
	protected function getDetailEditColumns(){
		return $this->getDetailListColumns();
	}
	
	protected function getDetailAddColumns(){
		return $this->getDetailEditColumns();
	}
	
}
