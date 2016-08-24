<?php
require_once("dbfactory.php");

class ServiceController extends Zend_Controller_Action{
    var $request;
    
    public function init(){
        /* Initialize action controller here */
        $this->request = $this->getRequest();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
	}

    public function downloadAction(){
    	$params = $this->request->getParams();
    	print_r($params);
		$fileName = $params['filename'];
		$rename = isset($params['rename']) ? $params['rename'] : $fileName;
		$remove = isset($params['remove']) ? $params['remove'] : false;
		if (isset($fileName)){
			$file = @ fopen($fileName,"r"); 
		//	PrintDebug($argValues["filename"]);
			if (!$file){ 
				echo "Can not open file:" . $fileName; 
			} 
		    else{ 
				Header("Content-type: application/octet-stream");
				Header("Content-Disposition: attachment; filename=\"".basename($rename)."\"");
				while (!feof ($file)) { 
					echo fread($file,500000); 
				} 
				fclose ($file); 
				if ($remove)
				  unlink($fileName);
			} 
		}
    }

    public function deletefileAction(){
    	$params = $this->request->getParams();
    	// print_r($params);
		$fileName = $params['filename'];
		$container_id = $params['container_id'];
		$id = $params['id'];
		$isFiles = $params['isfiles'];
		$db = $params['db'];
		$table = $params['table'];
		//$fileName = realpath($fileName);
		$fileName = urldecode(trim($fileName));
		if (isset($fileName)){
			// print_r($fileName);
			// print_r("isfile = $isFiles");
			if($isFiles){ //仅仅修改数据库数据，断开连接即可，不实际删除文件
				$o_db = dbFactory::get($db, $rel_db);
				$res = $o_db->query("SELECT $container_id from $table where id=$id");
				$row = $res->fetch();
				$files = explode(',', $row[$container_id]);
				$key = array_search($fileName, $files);
				if($key !== false){
					unset($files[$key]);
					$o_db->update($table, array($container_id=>implode(',', $files)), "id=$id");
				}
				$ret = 1;
			}
			else
				$ret = unlink($fileName);
			print_r($ret);
		}
    }
	
    public function getfilecontentAction(){
    	$params = $this->request->getParams();
    	// print_r($params);
		$fileName = $_REQUEST['filename'];
		// print_r($_REQUEST);
		if (isset($fileName)){
			$fileName = urldecode(trim($fileName));
			$file = fopen($fileName,"rb"); 
			if (!$file){ 
				echo "Can not open file:" . $fileName; 
			} 
			else{ 
				echo fread($file, 1024);
				fclose ($file); 
			} 
		}
    }

	public function uploadfileAction(){
		
	}
}







