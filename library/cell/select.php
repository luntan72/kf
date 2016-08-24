<?php
require_once('kf_cell.php');

class kf_select extends kf_cell{
	protected function init($params, $values){
		parent::init($params, $values);
		// $this->params['can_new'] = true;
		if(!empty($this->params['can_new'])){
			$new_method = isset($this->params['new_method']) ? $this->params['new_method'] : 'null';
			if(empty($this->params['post']))
				$this->params['post'] = array(array('type'=>'button', 'value'=>'+', 'id'=>'new_'.$this->params['id'], 'event'=>array('onclick'=>$new_method)));
			else
				$this->params['post'][] = array('type'=>'button', 'value'=>'+', 'id'=>'new_'.$this->params['id'], 'event'=>array('onclick'=>$new_method));
		}
	}
	
	protected function oneView($v, $props){
// print_r($props);
// print_r($this->params);
		$ret = array();
		$ret[] = "<input type='hidden' id='{$this->params['id']}' value='$v'>";
		$props['id'] = 'label_'.$props['id'];
		$ret[] = parent::oneView($v, $props);
// print_r($v);		
// print_r($ret);
		return implode("\n", $ret);
	}
	
	// protected function oneEdit($value, $props){
// // if($this->params['name'] == 'owner_id')	
// // print_r($this->params);	
		// $str = "<select {$this->strProps}>\n";
		// if(!empty($this->params['editoptions']['value'])){
			// foreach($this->params['editoptions']['value'] as $k=>$v){
				// $str .= $this->displayOption($k, $v, $value);
			// }
		// }
		// $str .= "</select>";
		// return $str;
	// }
	
	protected function getEditValue($value){
		$ret = array();
		if(!empty($this->params['editoptions']['value'])){
			foreach($this->params['editoptions']['value'] as $k=>$v){
				$ret[] = $this->displayOption($k, $v, $value);
			}
		}
		return implode("\n", $ret);
	}
	
	protected function displayOption($k, $v, $value){
		$props = array('value'=>$k);
		if(is_array($v)){
			$label = isset($v['label']) ? $v['label'] : (isset($v['value']) ? $v['value'] : (isset($v['id']) ? $v['id'] : '[unknown]'));
			$props = array_merge($props, $v);
		}
		else
			$label = $v;
		if($k == $value)
			$props['selected'] = 'selected';
		$strProps = $this->propStr($props);
// if($this->params['id'] == '__interTag'){
// print_r($props);			
// print_r($strProps);
// }
		$str = "<option $strProps>$label</option>\n";
		return $str;
	}
	
	protected function getProps(){
		$props = parent::getProps();
		$props['style'] = 'width:100%;';
		$props['single_multi'] = array();
		return $props;
	}
}
?>