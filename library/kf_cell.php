<?php
require_once('const_def.php');
require_once('toolfactory.php');

class kf_cell{
	protected $params = array();
	protected $values = array();
	protected $strClass = '';
	protected $strProps = '';
	protected $multi_value = false;
	protected $multi_edit = false;
	protected $is_single_tag = false;
	function __construct($params, $values = array()){
		$this->init($params, $values);
	}
	
	protected function init($params, $values){
		$this->params = $params;
		$this->values = $values;
		$this->tool = toolFactory::get('kf');
		
		// print_r($params);
		if (empty($this->params['tag']))
			$this->params['tag'] = $params['type'];

		$props = $this->selectProps();

		$this->strProps = $this->propStr($props);
		if(!isset($this->params['class']))
			$this->params['class'] = array();
		$class = $this->params['class'];
		$this->strClass = implode(' ', $class);
		if(!empty($this->params['post'])){
			if(!array_key_exists(0, $this->params['post']))
				$this->params['post'] = array($this->params['post']);
		}
	}
	
	function getParams(){
		return $this->params;
	}
	
	function display($display_status = DISPLAY_STATUS_EDIT){
		$value = $this->_getValue();
		$props = $this->selectProps();
		if(empty($this->params['editable']))
			$display_status = DISPLAY_STATUS_VIEW;
		if(($display_status == DISPLAY_STATUS_EDIT && $this->multi_edit) || $this->multi_value){
			$ret = $this->_interTable($value, $props, $display_status);
		}
		else{
			if(empty($props['class']))
				$props['class'] = array('ces-item');
			else
				$props['class'][] = 'ces-item';
			// if(empty($props['style']))
				// $props['style'] = 'width:100%;';
			// else
				// $props['style'] .= ';width:100%;';
			if($display_status != DISPLAY_STATUS_VIEW){
				$ret = $this->oneEdit($value, $props);
			}
			else{
				$ret = $this->oneView($value, $props);
			}
		}
		return $ret;
	}
	
	protected function _interTable($value, $props, $display_status){
		$count = 0;
		$data = array();
		if($display_status == DISPLAY_STATUS_EDIT){
			if(!empty($this->params['onlyshowchecked'])){
				foreach($this->params['value'] as $k=>$v){
					$data[$k] = $v;
				}
			}
			elseif(!empty($this->params['editoptions']['value'])){
				$data = $this->params['editoptions']['value'];
			}
		}
		else{
			if(!is_array($value))
				$value = array($value=>$value);
			$data = $value;
		}
		$count = count($data);

		$displayField = '';
		$ret = array();
		$cols = isset($this->params['cols']) ? $this->params['cols'] : 4;
		$currentCol = 0;
		$needFieldSet = false;
		if($count > 1 || ($display_status == DISPLAY_STATUS_EDIT && ($this->params['type'] == 'cart' || $this->params['type'] == 'checkbox'))){
			$needFieldSet = true;
		}
		if($needFieldSet){//用fieldset围起来
			// $ret[] = "<fieldset id='fieldset_{$this->params['id']}'>";
			// if($display_status == DISPLAY_STATUS_VIEW){
				// $values = $this->params['value'];
				// if(is_array($values))
					// $values = implode(",", $values);
				// $ret[] = "<input type='hidden' name='{$this->params['id']}[]' value='{$values}'>";
			// }
			$ret[] = "<table id='table_{$this->params['id']}' style='width:100%'>";
		}
// print_r($data);		
		foreach($data as $k=>$v){
			// if(is_numeric($v)) //可能存在问题
				// $k = $v;
				
			if(empty($k))
				continue;
// print_r(">>>k = $k, v = $v<<<");
			if($needFieldSet){
				if($currentCol ++ == 0){
					$ret[] = "<tr>";
				}
				$ret[] = "<td class='inter-table'>";
			}
			if($display_status != DISPLAY_STATUS_VIEW){
				$ret[] = $this->oneEdit($k, $props);
			}
			else{
				$ret[] = $this->oneView($k, $props);
			}
			if($needFieldSet){
				$ret[] = "</td>";
				if($currentCol == $cols){
					$ret[] = "</tr>";
					$currentCol = 0;
				}
			}
		}
		if($needFieldSet){
			if($currentCol != 0)
				$ret[] = "</tr>";
			$ret[] = "</table>";
			// $ret[] = "</fieldset>";
		}
		if(!empty($this->params['post']) && $this->params['post']['type'] == 'button' && $display_status == DISPLAY_STATUS_EDIT){
			$ret[] = $this->post(DISPLAY_STATUS_EDIT);
		}
		return implode("\n", $ret);	
	}
	
	function pre($display_status){
		$label = array('type'=>'label', 'id'=>$this->params['id'].'_label', 'value'=>$this->params['label'].':', 'class'=>$this->params['class']);
		$e = cellFactory::get($label);
		return $e->display(DISPLAY_STATUS_VIEW, true);
	}
	
	function post($display_status){
// print_r($this->params['post']);		
		$ret = array();
		$posts = $this->params['post'];
		if(!array_key_exists(0, $posts))
			$posts = array($posts);
		foreach($posts as $post){
			if(empty($post['type']))
				$post['type'] = 'text';
				
			if(!isset($post['value']))
				$post['value'] = '';
			if(!isset($post['title']))
				$post['title'] = '';
			if (empty($post['class']))
				$post['class'] = array('e-post');
			else{
				if(is_string($post['class']))
					$post['class'] = array($post['class']);
				$post['class'][] = 'e-post';
			}
			if(empty($post['id']))
				$post['id'] = $this->params['id'].'_post';
			$hasPost = 0;
			if($display_status == DISPLAY_STATUS_VIEW && $post['type'] == 'text'){
				$hasPost = 1;
			}
			elseif($display_status != DISPLAY_STATUS_VIEW)
				$hasPost = 2;
			if($hasPost){
				$e = cellFactory::get($post);
				if($hasPost == 1)
					$ret[] = '(';
				$ret[] = $e->display($display_status, true);
				if($hasPost == 1)
					$ret[] = ')';
			}
		}
		return implode("\n", $ret);
	}

	protected function propStr($prop, $empty = true){
		$str = array();
		foreach($prop as $p=>$v){
			$str[] = $this->onePropStr($p, $v, $empty);
		}
		return implode(' ', $str);
	}
	
	protected function onePropStr($prop, $value, $empty = true){
		$str = "";
		if(is_null($value) || isset($value) && $value != 0 && $value != '0' && $value == '')
			return $str;
		if(in_array($prop, array('disabled', 'readonly', 'editable')) && empty($value))
			return $str;
	
// if($this->params['id'] == 'last_run'){
	// print_r("prop = $prop, ");
// if(is_null($value))	
	// print_r("Is Null");
// if($value === false)
	// print_r("is false");
	// print_r("value = ");
	// print_r($value);
	// print_r(", empty=$empty<BR>\n");
// }
		if (isset($value) && ($empty || !empty($value))){
			if ($prop == 'name' && ($this->params['type'] == 'checkbox' || $this->params['type'] == 'cart'))
				$str = "name='{$value}[]'";
			elseif($prop == 'class' && is_array($value)){
				$str = "class='".implode(' ', $value)."'";
			}
			else if($prop == 'event'){ //直接生成javascript:func
				$str = $this->eventStr($value);
			}
			elseif($prop == 'note')
				$str = "title='".htmlentities($value)."'";
			// elseif($prop == 'checked'){
				// $values = $this->_getValue();
				// if($this->multi_value){
					// if(in_array($value, $values))
						// $str = "checked='checked'";
				// }
				// else if($value == $values)
					// $str = "checked='checked'";
			// }
			elseif(is_array($value)){
				$str = "$prop='".json_encode($value)."'";
			}
			else
				$str = "$prop='".htmlentities($value)."'";
		}
		return $str;
	}
	
	protected function eventStr($events){
		$ret = '';
		if(!empty($events)){
			//event = array('onclick'=>array('fun1', 'fun2'), 'onchange'=>array('fun1', 'fun2'))
			$str = array();
			foreach($events as $event=>$functions){
				$str[] = strtolower($event)."='javascript:";
				if(is_array($functions)){
					$es = array();
					foreach($functions as $f){//可能有需要实际值替换的
						$f = $this->tool->vsprintf($f, $this->params['value']);
						$es[] = $f;
					}
					$str[] = implode(';', $es);
				}
				else{//可能有需要实际值替换的
					$f = $this->tool->vsprintf($functions, $this->params['value']);
					$str[] = $f;
				}
				$str[] = "'";
			}
			$ret = implode(' ', $str);
		}
		return $ret;
	}
	
	protected function selectProps(){
		$props = $this->getProps();
		if(is_string($props))
			$props = explode(',', $props);
		$standard = array();
		foreach($props as $p=>$defaultValue){
			if(is_int($p)) {
				$p = $defaultValue;
				$defaultValue = null;
			}
			$standard[$p] = isset($this->params[$p]) ? $this->params[$p] : (isset($defaultValue) ? $defaultValue : null);
		}
		return $standard;
	}
	
	protected function getProps(){
		return array('type', 'id', 'name', 'ignored', 'unique', 'title', 'editable', 'readonly', 'from', 'multirowtemp', 'can_new', 'new_method',
			'value', 'placeholder', 'disabled', 'class', 'style', 'required', 'min', 'max', 'invalidChar', 'width', 'event', 'original_value');
	}
	
	protected function oneEdit($value, $props){
		$ret = array();
		$hasPost = false;
// print_r($this->params['post']);	
		if(!empty($this->params['post'])){
// print_r($this->params['post']);			
			$hasPost = true;
		}
		$postClass = '';
		if($hasPost){
			$postClass = 'post-td';
		}
		$strProps = $this->propStr($props);
		if($this->is_single_tag)
			$ret[] = "<{$this->params['tag']} $strProps />";
		else
			$ret[] = "<{$this->params['tag']} $strProps >".$this->getEditValue($value)."</{$this->params['tag']}>";
		// display the post
		if($hasPost){
			$ret[] = "</td>";
			$ret[] = "<td id='post_{$this->params['id']}' class='$postClass' style='width:auto' style='white-space: nowrap' nowrap='nowrap'>";
			$ret[] = $this->post(DISPLAY_STATUS_EDIT);
			$ret[] = "</td>";
			// $ret[] = "</tr></table></td>";
		}
		// $strProps = $this->propStr($props);
		// $ret = "<{$this->params['tag']} $strProps >$value</{$this->params['tag']}>";
		return implode("\n", $ret);
	}
	
	protected function getEditValue($value){
		return $value;
	}
	
	protected function oneView($value, $props){
		$ret = array();
// print_r("value = $value\n");
// print_r($this->params['editoptions']);
		if(!empty($this->params['editoptions']['value'][$value]))
			$value = $this->params['editoptions']['value'][$value];
// if($this->params['name'] == 'prj_id'){
	// print_r($value);
	// print_r($this->params);
// }		
// print_r("value = $value\n");			
		$label = array('type'=>'label', 'value'=>$value, 'class'=>$this->params['class'], 'style'=>isset($props['style']) ? $props['style'] : '', 'editoptions'=>isset($this->params['editoptions']) ? $this->params['editoptions'] : array());
// print_r("label = ");
// print_r($label['class']);
		$required_index = array_search('required', $label['class']);
		if($required_index !== false){
// print_r('required_index = '.$required_index);			
			unset($label['class'][$required_index]);
		}
		if(!empty($props['id']))
			$label['id'] = $props['id'];
		
		$e = cellFactory::get($label);
		$ret[] = $e->display(DISPLAY_STATUS_VIEW, true);
		if(!empty($this->params['post'])){
			$ret[] = $this->post(DISPLAY_STATUS_VIEW);
		}
		return implode("\n", $ret);
	}
	
	protected function _getValue(){
		$value = isset($this->params['value']) ? $this->params['value'] : '';
		if($this->multi_value){
			if(is_string($value))
				$value = explode(',', $value);
			if(!is_array($value))
				$value = array($value);
// print_r("adfasdfasdfdas>>>>>>");			
// print_r($value);
			$newValues = array();
			foreach($value as $k=>$v){
				if(!empty($v) || !empty($k))
					$newValues[$v] = $v;
			}
			$this->params['value'] = $value = $newValues;
// print_r($this->params['value']);				
		}
		return $value;	
	}
}
?>