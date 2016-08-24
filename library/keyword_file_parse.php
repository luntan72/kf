<?php
require_once('file_parse.php');

class keyword_file_parse extends file_parse{
	protected $context = null;
	
	public function setContext($context){
		$this->context = $context;
	}
	
	public function setFile($file){
		parent::setFile($file);
		$this->context->shiftToState('undefined');
	}
	
	protected function _parse(){
		$this->handle = fopen($this->filename, "rb");
		if($this->handle){
			while(!feof($this->handle)){
				$line = fgets($this->handle, 2049);
				$keyword = '';
				$value = '';
				$ret = $this->parseLine($line);
// print_r($ret);				
				if(!empty($ret)){
					$keyword = array_keys($ret);
					$keyword = $keyword[0];
					$value = $ret[$keyword];
				}
				$this->handleKeywords($keyword, $value, $line);
			}
		}
		fclose($this->handle);
		return ERROR_OK;
	}
	
	protected function parseLine($line){
		$ret = $this->context->request($line);
		return $ret;
	}
	
	protected function handleKeywords($keyword, $value, $line){
		
	}
	
}


?>
