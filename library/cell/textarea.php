<?php
require_once('kf_cell.php');

class kf_textarea extends kf_cell{
	protected function init($params, $values){
		if(empty($params['class']))
			$params['class'] = array('text-area');
		
		parent::init($params, $values);
	}
	
	protected function _getValue(){
		$val = parent::_getValue();
		return $val;
	}
	
	protected function getProps(){
		$ret = parent::getProps();
		if(!empty($this->values[$this->params['name']]))
			$ret['rows'] = substr_count($this->values[$this->params['name']], "\n") + 1;
		else
			$ret['rows'] = 3;
		if($ret['rows'] > 10)
			$ret['rows'] = 10;
		$ret['style'] = "height:auto";
		// $ret['cols'] = 100;
		return $ret;
	}
	
	// protected function oneEdit($value, $props){
		// $strProps = $this->propStr($props);
		// $ret = "<{$this->params['tag']} $strProps >$value</{$this->params['tag']}>";
		// return $ret;
	// }

	protected function oneView($value, $props){
		if(empty($props['style']))
			$props['style'] = "line-height:140%"; //设定行高
		else
			$props['style'] .= ";line-height:140%"; //设定行高
			
		//value里的回车符需要单独处理，转化成</br>
		$order   = array("\r\n", "\n", "\r");
		$replace = '<br>';
		// Processes \r\n's first so they aren't converted twice.
		$newstr = str_replace($order, $replace, $value);
		// $newstr = $this->tool->insertLink($newstr);
		
		// return "<textarea class='text-area'>$newstr</textarea>";
		return parent::oneView($newstr, $props);
	}
}
?>