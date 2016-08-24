<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
// require_once(APPLICATION_PATH."/jqgrid/qygl/yw_tool.php");
//业务管理
class qygl_yw extends table_desc{
	protected $yw_fl_options = array();
	protected $hb_options = array();
	// protected $ywTool = null;
	protected function init($params){
// print_r($params);
		parent::init($params);
		$this->params['real_table'] = 'yw';
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'name'=>array('label'=>g_str('yw_name'), 'editable'=>false),
            'yw_fl_id'=>array('label'=>g_str('fl'), 'editrules'=>array('required'=>true), 'hidden'=>true),
			'hb_id'=>array('editrules'=>array('required'=>true)),
			);
		$detailColumns = $this->getDetailListColumns();
		foreach($detailColumns as $k=>$v){
			if(is_int($k)){
				$k = $v;
				$v = array();
			}
			$this->options['list'][$k] = $v;
		}
		$this->options['list']['dj'] = array('type'=>'files', 'db'=>'qygl', 'table'=>'yw');
		$this->options['list']['note'] = array();
		$this->options['list']['jbr_id'] = array('data_source_db'=>'qygl', 'data_source_table'=>'zzvw_yg_manager');
		$this->options['list']['happen_date'] = array();

		$this->options['edit'] = $this->options['add'] = array('yw_fl_id'=>array(), 'hb_id',);
		$detailColumns = $this->getDetailEditColumns();
		foreach($detailColumns as $k=>$v){
			if(is_int($k)){
				$k = $v;
				$v = array();
			}
			$this->options['edit'][$k] = $v;
		}
		$this->options['edit']['dj'] = $this->options['edit']['note'] = $this->options['edit']['jbr_id'] =  $this->options['edit']['happen_date'] = array();
		
		$detailColumns = $this->getDetailAddColumns();
		foreach($detailColumns as $k=>$v){
			if(is_int($k)){
				$k = $v;
				$v = array();
			}
			$this->options['add'][$k] = $v;
		}
		$this->options['add']['dj'] = $this->options['add']['note'] = $this->options['add']['jbr_id'] =  $this->options['add']['happen_date'] = array();
		// $this->options['displayField'] = 'id';
	}
	
	protected function getDetailListColumns(){
		if(!isset($this->params['yw_fl_id']) && isset($this->params['id'])){
			$res = $this->tool->query("SELECT yw_fl_id FROM yw WHERE id={$this->params['id']}");
			$row = $res->fetch();
			$this->params['yw_fl_id'] = $row['yw_fl_id'];
		}
		return array();
	}

	protected function getDetailEditColumns(){
		return $this->getDetailListColumns();
	}

	protected function getDetailAddColumns(){
		return $this->getDetailEditColumns();
	}
}
