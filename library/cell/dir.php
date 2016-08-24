<?php
require_once('const_def.php');
require_once('kf_cell.php');
//在upload目录下如何组织文件？
class kf_dir extends kf_cell{
	protected function init($params, $values){
// print_r($params);
		parent::init($params, $values);
		$dir = '';
		if(!empty($this->params['db'])){
			$dir = '/'.$this->params['db'];
			if(!empty($this->params['table']))
				$dir .= '/'.$this->params['table'];
		}
		if(empty($this->params['subdir']))
			$this->params['subdir'] = $this->params['value'];
		if(isset($this->params['subdir']))
			$dir .= '/'.$this->params['subdir'];
		
		$this->params['post'] = array('type'=>'button', 'value'=>'upload', 'event'=>array('onclick'=>"XT.upload(\"{$this->params['db']}\", \"{$this->params['table']}\", \"{$this->params['value']}\", \"{$params['id']}\", \"{$this->params['subdir']}\")")); //如果是edit状态，则显示该按钮
		$this->params['dir'] = UPLOAD_ROOT.$dir;
		$this->params['rel'] = isset($params['rel']) ? $params['rel'] : '';
// print_r($this->params['dir']);		
		if(!file_exists($this->params['dir']))
			$this->tool->createDirectory($this->params['dir']);
		$files = scandir($this->params['dir']);
		unset($this->params['value']);
		unset($files[0]); 	// .
		unset($files[1]);	// ..
// print_r($files);		
		foreach($files as $file){
			if(is_dir($file))
				continue;
			$this->params['value'][$file] = $this->params['editoptions']['value'][$file] = $file; //将目录转化成文件集合
		}
// print_r($this->params['value']);		
		$this->multi_edit = true;
		$this->multi_value = true;
	}
	
	public function display($display_status = DISPLAY_STATUS_EDIT){
		$str = parent::display($display_status);
		$str .= "<input type=\"hidden\" id=\"subdir\" value=\"{$this->params['subdir']}\" />";
		return $str;
	}

	protected function oneEdit($value, $props){
		return $this->showFile($value, $props, true);
	}
	
	protected function oneView($value, $props){
		return $this->showFile($value, $props, false);
	}
	
	protected function showFile($value, $props, $is_edit_status = false){
		$filename = $this->params['dir'].'/'.$value;
		$rel_filename = $this->params['rel'].'/'.$value;
		$id = str_replace(array(' ', '.'), '_', $value);
		$params = array('type'=>'file', 'name'=>'file_in_'.$this->params['name'].'[]', 'value'=>$filename, 'rel'=>$rel_filename, 'id'=>$id, 'editable'=>true);
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