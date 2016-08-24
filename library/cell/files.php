<?php
require_once('const_def.php');
require_once('kf_cell.php');
//在upload目录下如何组织文件？
class kf_files extends kf_cell{ //多文件管理，允许upload文件，需要一个dir参数来指定upload的文件的存放位置
	protected function init($params, $values){
// print_r($params);
// print_r($values);
		parent::init($params, $values);
		if(!isset($this->params['subdir']))
			$this->params['subdir'] = '';
		if(!isset($values['id']))
			$values['id'] = 0;
		$this->params['post'] = array('type'=>'button', 'value'=>'upload', 'event'=>array('onclick'=>"XT.upload(\"{$this->params['db']}\", \"{$this->params['table']}\", \"{$values['id']}\", \"{$params['id']}\", \"{$this->params['subdir']}\", 1)")); //如果是edit状态，则显示该按钮
// print_r($this->params['post']);
		$this->params['rel'] = isset($params['rel']) ? $params['rel'] : '';
// print_r($this->params['dir']);	
		$files = $this->params['value'];
		unset($this->params['value']);
		if(!is_array($files))
			$files = explode(',', $files);
		foreach($files as $file){
			$this->params['value'][$file] = $file;
			$this->params['editoptions']['value'][$file] = $file; //将转化成文件集合
		}
// print_r($this->params['value']);		
		$this->multi_edit = true;
		$this->multi_value = true;
	}
	
	public function display($display_status = DISPLAY_STATUS_EDIT){
		$str = parent::display($display_status);
		$value = implode(',', $this->params['value']);
		$str .= "<input type=\"hidden\" id=\"{$this->params['name']}\" value=\"{$value}\" />";
		return $str;
	}

	protected function oneEdit($value, $props){
		return $this->showFile($value, $props, true);
	}
	
	protected function oneView($value, $props){
		return $this->showFile($value, $props, false);
	}
	
	protected function showFile($value, $props, $is_edit_status = false){
		$pathinfo = pathinfo($value);
		$filename = $value;
		$rel_filename = $this->params['rel'].'/'.$pathinfo['filename'];
		$id = str_replace(array(' ', '.', '/',"\\", ':'), '_', $value);
		$params = array('type'=>'file', 'name'=>$id, 'value'=>$filename, 'rel'=>$rel_filename, 'id'=>$id, 'editable'=>true, 
			'from'=>"kf_files", 'db'=>$this->params['db'], 'table'=>$this->params['table'], 'tb_id'=>$this->values['id'], 'container_id'=>$this->params['id']);
		$file = cellFactory::get($params);
		$disp_status = $is_edit_status ? DISPLAY_STATUS_EDIT : DISPLAY_STATUS_VIEW;
		return $file->display($disp_status);
	}
	
	protected function _getValue(){
		$value = isset($this->params['value']) ? $this->params['value'] : '';
		return $value;	
	}
	
}
?>