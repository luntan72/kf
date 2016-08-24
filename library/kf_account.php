<?php
require_once('kf_object.php');

class kf_account extends kf_object{
	protected $orig_row = array();
	protected function init($params){
		parent::init($params);
// print_r($this->params);		
		$this->tool = toolFactory::get(array('tool'=>'db'));
		$this->tool->setDb($this->params['db']);
		//确定是哪张表，字段怎么对应，哪条记录。主要的字段有:init_value, current_value, id
		$res = $this->tool->query("SELECT * FROM {$this->params['table']} WHERE id={$this->params['id']}");
		$row = $res->fetch();
		$this->orig_row[$this->params['init_value_field']] = $row[$this->params['init_value_field']];
		$this->orig_row[$this->params['current_value_field']] = $row[$this->params['current_value_field']];
// print_r("Post Init");		
	}
	
	public function inc($amount){
		$this->orig_row[$this->params['current_value_field']] += $amount;
		$this->tool->update($this->params['table'], $this->orig_row, "id=".$this->params['id']);
	}
	
	public function dec($amount){ //允许透支，也就是不检查当前值是否为负的问题
		$this->orig_row[$this->params['current_value_field']] -= $amount;
		$this->tool->update($this->params['table'], $this->orig_row, "id=".$this->params['id']);
	}
	
	public function updateInit($amount){
		$dif = $amount - $this->orig_row[$this->params['init_value_field']];
		$this->orig_row[$this->params['init_value_field']] = $amount;
		$this->orig_row[$this->params['current_value_field']] = $amount + $dif;
		$this->tool->update($this->params['table'], $this->orig_row, "id=".$this->params['id']);
	}
}
?>