<?php
require_once('const_def.php');
require_once('action_jqgrid.php');
require_once('importerfactory.php');

class action_import extends action_jqgrid{
	protected $importer = 'base';
	protected $importer_dir = '/../library';
	protected function getViewParams($params){
// print_r($params);		
		$view_params = $params;
	
		$view_params['view_file'] = "import_type.phtml";
		$dir = '/jqgrid/'.$params['db'].'/'.$params['table'].'/view';
		if(file_exists(APPLICATION_PATH.$dir.'/'.$view_params['view_file']))
			$view_params['view_file_dir'] = $dir;
		else
			$view_params['view_file_dir'] = '/jqgrid/view';
		// $subdir = $this->table_desc->getParams('subdir');
// print_r($subdir);		
		// $view_params['subdir'] = empty($params['id']) ? "my".rand() : $params['id'];

		return $view_params;
	}
	
	protected function handlePost(){
// print_r($this->params);		
		if(isset($this->params['import_type'])){
			$importer = importerFactory::get($this->params['import_type'], $this->params);
			$importer->setOptions($this);
			return $importer->import();
		}
		else{ //直接将文件拷贝到相应的目录
			$dir = UPLOAD_ROOT.'/'.$this->params['db'].'/'.$this->params['table'].'/'.$this->params['subdir'];
// print_r($dir);
			$this->tool->createDirectory($dir);
			if($_FILES["uploaded_file"]["error"] == UPLOAD_ERR_OK) {
				$tmp_name = $_FILES["uploaded_file"]["tmp_name"];
				$name = $_FILES["uploaded_file"]["name"];
				if(move_uploaded_file($tmp_name, "$dir/$name"))
					print_r($name." has uploaded\n");
				else
					print_r("Failed to upload $name");
			}
			if(!empty($this->params['isfiles'])){ // 更新数据
				$this->tool->setDb($this->params['db']);
				$res = $this->tool->query("select {$this->params['cell_id']} from {$this->params['table']} where id={$this->params['id']}");
				$row = $res->fetch();
				$v = $row[$this->params['cell_id']];
				if(empty($v))
					$v = "$dir/$name";
				else{
					$v = $v.",$dir/$name";
				}
				$v = str_ireplace("\\", "/", $v);
				$this->tool->update($this->params['table'], array($this->params['cell_id']=>$v), "id=".$this->params['id']);
			}
		}
	}
	
}

?>