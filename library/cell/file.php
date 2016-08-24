<?php
require_once('kf_cell.php');

class kf_file extends kf_cell{
	protected function init($params, $values){
		parent::init($params, $values);
	}

	protected function oneEdit($value, $props){
// print_r($this->params['from']);
		$str = "";
		//应该支持删除一个文件
		if(!empty($value)){
			if(isset($this->params['from']) && $this->params['from'] == 'kf_files')
				$isFiles = 1;
			else
				$isFiles = 0;
			
			$str = "<a href='javascript:void(0)' onclick=\"XT.deleteFile('".urlencode($value)."', '{$this->params['id']}', $isFiles, '{$this->params['db']}', '{$this->params['table']}', '{$this->params['container_id']}', {$this->params['tb_id']})\" title=\"Remove the file\">[x]</a> ";
			$str .= $this->oneView($value, $props);
		}
		return $str;
	}
	
	protected function oneView($value, $props){
		if(empty($value)) return '';
		
		unset($props['type']);
		unset($props['value']);
// print_r($value);	
		//某些类型的文件允许浏览
		$pathinfo = pathinfo($value);
		if(!empty($pathinfo['extension'])){
			switch(strtolower($pathinfo['extension'])){
				case 'exe':
				case 'zip':
				case 'rar':
				case 'tar':
				case 'gz':
				case 'vsd':
				case 'pdf':
				case 'ppt':
				case 'pptx':
				case 'xls':
				case 'xlsx':
				case 'doc':
				case 'docx':
				case 'dll':
					break;
				case 'jpg':
				case 'png':
				case 'gif':
				case 'img':
				case 'bmp':
					//计算相对地址
					if(!empty($this->params['rel'])){
						$rel = $this->params['rel'];
						
						$props['event']['onmouseover'] = "XT.previewPicture(event, this,\"$rel\")"; 	//将内容显示在title里
						$props['event']['onmouseout'] = "XT.clearPicture(this)";
					}
					break;
				case 'txt':
				case 'log':
				case 'html':
				case 'php':
				case 'ini':
				case 'c':
				case 'cpp':
				case 'h':
				case 'hpp':
				case 'bat':
				case 'pl':
				case 'py':
				case 'java':
				case 'xml':
				case 'sql':
				default:
					$props['event']['onmouseover'] = "XT.getFileContent(this,\"".urlencode($value)."\")"; 	//将内容显示在title里
					break;
			}
		}
		$props['title'] = 'click to download it';
		$strProps = $this->propStr($props);
		$str = "<a href='/download.php?filename=".urlencode($value)."&remove=0' $strProps>{$pathinfo['basename']}</a>";
		return $str;
	}
	
}
?>