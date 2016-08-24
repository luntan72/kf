<?php
require_once('kf_object');
class yw_helper extends kf_object{
	protected $yw_tool = null;
	protected function init($params){
		parent::init($params);
		$this->tool = toolFactory::get(array('db'=>'gygl'));
	}
	
	protected function getListColumns(){
		return array();
	}
	
	protected function getEditColumns(){
		return $this->getListColumns();
	}

	protected function getAddColumns(){
		return $this->getEditColumns();
	}
	
	protected function save(){
		
	}
}
