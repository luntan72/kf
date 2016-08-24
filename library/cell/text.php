<?php
require_once('kf_input.php');

class kf_text extends kf_input{
	// protected function init($params, $values){
		// parent::init($params, $values);
		// $this->is_single_tag = true;
		// $this->params['tag'] = 'input';
	// }
	
	protected function oneEdit($value, $props){
		if($props['date'] == 'date'){
			//需要增加一个click的事件
			if(empty($props['event']['onclick']))
				$props['event']['onclick'] = array();
			$props['event']['onclick'][] = 'XT.datePick(this)';
		}
		if($props['unique']){
			if(empty($props['event']['onblur']))
				$props['event']['onblur'] = array();
			$uniq_params = array();
			$strP = '{}';
			if(!empty($this->params['uniq_db']))
				$uniq_params[] = "db:\"{$this->params['uniq_db']}\"";
			if(!empty($this->params['uniq_table']))
				$uniq_params[] = "table:\"{$this->params['uniq_table']}\"";
			if(!empty($uniq_params))
				$strP = "{".implode(",", $uniq_params)."}";
			$props['event']['onblur'][] = "XT.checkElement(this, $strP)";
		}
		return parent::oneEdit($value, $props);
		$strProps = $this->propStr($props);
		$ret = "<input {$strProps} >";
		return $ret;
	}

	protected function oneView($value, $props){
		// $value = $this->tool->insertLink($value);
		return parent::oneView($value, $props);
	}
	
	protected function getProps(){
		$props = parent::getProps();
		$props[] = 'auto_complete';
		$props[] = 'db';
		$props[] = 'table';
		$props[] = 'real_id';
		$props[] = 'date';
		return $props;
	}
	
}
?>