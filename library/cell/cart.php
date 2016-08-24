<?php
require_once(realpath(dirname(__FILE__)) . '/checkbox.php');
/*
比较复杂的一个组件，允许以两种形式出现：select（单选）或cart（多选），用一个+按钮来切换。
如果当前没有或只有一个选中项，则以select来表现，否则以checkbox来表现
*/
class kf_cart extends kf_checkbox{
	protected function init($params, $values){
// print_r($values);		
		// $params['type'] = 'checkbox';
		parent::init($params, $values);
		$this->multi_edit = true;
		$this->multi_value = true;
		$this->params['onlyshowchecked'] = true;
	}
	
	protected function getProps(){
		$props = parent::getProps();
		$props[] = 'single_multi';
		if(!isset($this->params['cols']))
			$props['cols'] = 4;
		return $props;
	}
	
	// protected function oneEdit($value, $props){ //一个Cart可以拆解成一个div包含的checkbox和buttons
		// $ret = array();
		// $ret[] = "<div id='div_cart_{$this->params['name']}' current_state='multi'";
		// $ret[] = " onmouseout='XT.hideCartButton(\"div_cart_{$this->params['name']}\")' onmouseover='XT.showCartButton(\"div_cart_{$this->params['name']}\")'";
		// if(isset($this->params['single_multi']))
			// $ret[] = " single_multi='".json_encode($this->params['single_multi'])."'";
		// $ret[] = ">";
		// $ret[] = parent::display($display_status);
		
	// }
	
	public function display($display_status = DISPLAY_STATUS_EDIT){
		$ret = "<div id='div_cart_{$this->params['name']}' current_state='multi'";
		if($display_status != DISPLAY_STATUS_VIEW)
			$ret .= " onmouseout='XT.hideCartButton(\"div_cart_{$this->params['name']}\")' onmouseover='XT.showCartButton(\"div_cart_{$this->params['name']}\")'";
		if(isset($this->params['single_multi']))
			$ret .= " single_multi='".json_encode($this->params['single_multi'])."'";
		$ret .= ">";
		// if($display_status == DISPLAY_STATUS_EDIT && empty($this->params['value'])){
			// $ret .= "<fieldset id='fieldset_{$this->params['name']}'>";
			// $ret .= "<table id='table_cart_{$this->params['name']}'>";
		// }
// print_r("sadfds");		
// print_r($this->params['value']);	
// print_r($this->values);	
		$ret .= parent::display($display_status);
		// if($display_status == DISPLAY_STATUS_EDIT && empty($this->params['value'])){
			// $ret .= "</table>";
			// $ret .= " </fieldset>";
		// }
		if($display_status != DISPLAY_STATUS_VIEW){
			$display = "";
			if(!empty($this->params['value']))
				$display = "style='display: none;'";
			$ret .= "<div id='cart_button' $display>";
			if (empty($this->params['cart_data']))
				$this->params['cart_data'] = '{}';
			$addParams = array('type'=>'button', 'id'=>'cart_add_'.$this->params['name'], 'value'=>'Add '.$this->params['name'], 'event'=>array('onclick'=>"XT.selectToCart(\"{$this->params['name']}\", \"{$this->params['cart_db']}\", \"{$this->params['cart_table']}\", \"{$this->params['label']}\", {$this->params['cart_data']})"));
			$resetParams = array('type'=>'button', 'id'=>'cart_reset_'.$this->params['name'], 'value'=>'Reset '.$this->params['name'], 'event'=>array('onclick'=>"XT.resetCart(\"{$this->params['name']}\", \"{$this->params['cart_db']}\", \"{$this->params['cart_table']}\", \"{$this->params['label']}\", {$this->params['cart_data']})"));
			$addButton = cellFactory::get($addParams);
			$resetButton = cellFactory::get($resetParams);
			$ret .= $addButton->display(DISPLAY_STATUS_EDIT).$resetButton->display(DISPLAY_STATUS_EDIT);
			$ret .= "</div>";
		}
		$ret .= "</div>";
		return $ret;
	}
}
?>