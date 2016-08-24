<?php
require_once('kf_cell.php');
/*
比较复杂的一个组件，允许以两种形式出现：select（单选）或cart（多选），用一个+按钮来切换。
如果当前没有或只有一个选中项，则以select来表现，否则以checkbox来表现
*/
class kf_single_multi extends kf_cell{
	protected function init($params, $values){
// print_r($params);
	// $params['type'] = 'checkbox';
		parent::init($params, $values);
		$this->multi_edit = true;
		$this->multi_value = true;
		$this->params['onlyshowchecked'] = true;
		
		$post = array('type'=>'button', 'value'=>'+', 'id'=>'single_to_multi', 'title'=>'Change to multe-selction', 
			// 'event'=>array('onclick'=>'XT.single_or_multi(this)'), 
			// 'class'=>array('single-multi')
			);
		if(!isset($this->params['init_type']))$this->params['init_type'] = 'single';
		if($this->params['init_type'] == 'single'){
			$post['id'] = 'single_to_multi';
			$post['class'] = 'single';
			$post['value'] = '...';
			$post['title'] = 'Change to multi-selection';
		}
		else{
			$post['id'] = 'multi_to_single';
			$post['class'] = 'multi';
			$post['value'] = '.';
			$post['title'] = 'Change to single selection';
		}
		if(empty($this->params['post']))
			$this->params['post'] = array($post);
		else{
			$this->params['post'][] = $post;
		}
		
// print_r($this->params);		
	}
	
	protected function getProps(){
		$props = parent::getProps();
		$props[] = 'single_multi';
		if(!isset($this->params['cols']))
			$props['cols'] = 4;
// print_r($props);		
		return $props;
	}
	
	public function display($display_status = DISPLAY_STATUS_EDIT){
// print_r("db = ".$this->params['single_multi']['db']);
		$ret = '';
		$params = $this->params;
		if($this->params['init_type'] == 'single'){
			unset($params['single_multi']);
			$ret = "<div id='div_cart_{$this->params['name']}' current_state='{$this->params['init_type']}'".
				" single_multi='".json_encode($this->params['single_multi'])."'";
			// if($display_status ==  DISPLAY_STATUS_EDIT){
				$ret .= " onmouseout='XT.hideCartButton(\"div_cart_{$this->params['name']}\")' onmouseover='XT.showCartButton(\"div_cart_{$this->params['name']}\")'";
			// }
			$ret .= ">";
			$params['type'] = 'select';
			$params['tag'] = 'select';
		}
		else{
			$params['type'] = 'cart';
		}
// print_r($params)		;
		$select = cellFactory::get($params);
		$ret .= $select->display($display_status);

		if($this->params['init_type'] == 'single'){
			$ret .= "</div>";
		}
		return $ret;
	}
}
?>