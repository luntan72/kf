<?php
require_once('kf_cell.php');

class kf_radio extends kf_input{
	protected function init($params, $values){
		parent::init($params, $values);
		$this->multi_edit = true;
		$this->multi_value = false;
	}
	
	protected function oneView($v, $props){
		$ret = array();
		$ret[] = "<input type='hidden' id='{$this->params['id']}' value='$v'>";
		$props['id'] = 'label_'.$props['id'];
		$ret[] = parent::oneView($v, $props);
// print_r($v);		
// print_r($ret);
		return implode("\n", $ret);
	}
	
	protected function oneEdit($k, $props){
		$ret = '';
		unset($props['checked']);
		$props['id'] = "{$this->params['id']}_{$k}";
		if($k == $this->params['value'])
			$props['checked'] = "checked";
		$v = $this->params['editoptions']['value'][$k];
		$props['value'] = $k;
		if(is_array($v)){
			$label = isset($v['label']) ? $v['label'] : (isset($v['value']) ? $v['value'] : (isset($v['id']) ? $v['id'] : '[unknown]'));
			// $label = $v[$displayField];
			$props = $this->tool->array_extends($v, $props);
		}
		else{
			$label = $v;
		}
// print_r($props);		
		$strProps = $this->propStr($props, false);
		$ret = "<label for='{$props['id']}'>".parent::oneEdit($k, $props)."{$label}</label>";
		return $ret;
	}
}
?>