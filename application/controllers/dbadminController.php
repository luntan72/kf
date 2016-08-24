<?php
require_once('dbfactory.php');

class DbadminController extends Zend_Controller_Action{
    var $request;
    
    public function init(){
        /* Initialize action controller here */
        $this->request = $this->getRequest();
        if ($this->request->isXmlHttpRequest())
            $this->_helper->layout->disableLayout();
    }

    public function backupAction(){
		if($this->request->isPost()){
			$this->_helper->viewRenderer->setNoRender(true);
			$params = $this->request->getParams();
			$host = $params['host'];
			$db = $params['db_name'];
			$backupFile = APPLICATION_PATH.'/db_backup/'.$db.'_backup_'.date('Y-m-dHis').'.sql.gz';
			$command = "C:\\Users\\b19268\\xampp\\mysql\\bin\\mysqldump -h$host -uroot -pdbadmin --database $db | gzip > $backupFile";
			$ret = array('errcode'=>0, 'msg'=>$backupFile);
			try{
				exec($command, $output, $retVal);
			}catch(Exception $e){
				$ret['errcode'] = 1;
				$ret['msg'] = $e->getMessage();
			}
			print_r(json_encode($ret));
			return json_encode($ret);
		}
    }

    public function restoreAction(){
		if($this->request->isPost()){
			$this->_helper->viewRenderer->setNoRender(true);
			$params = $this->request->getParams();
			$backupFile = $params['file_name'];
			$ret = array('errcode'=>1, 'msg'=>"The file do not match the format: db_backup_datetime.sql(.gz)");
			if (preg_match("/^(.+)_backup.*\.(.*?)$/", $backupFile, $matches)){
				$db_name = $matches[1];
				$postFix = $matches[2];
// print_r($matches);				
				$backupFile = APPLICATION_PATH.'/db_backup/'.$backupFile;
				$matched = true;
				if ($postFix == 'gz')
					$command = "gunzip < $backupFile | mysql -uroot -pdbadmin $db_name";
				else if ($postFix == 'sql')
					$command = "C:\\Users\\b19268\\xampp\\mysql\\bin\\mysql -hlocalhost -uroot -pdbadmin $db_name < $backupFile";
				else{
					$matched = false;
				}
// print_r($command);				
				if($matched){
					try{
						exec($command, $output, $retVal);
						$ret['errcode'] = 0;
						$ret['msg'] = $db_name;
					}catch(Exception $e){
						$ret['errcode'] = 2;
						$ret['msg'] = $e->getMessage();
					}
				}
			}
			print_r(json_encode($ret));
			return json_encode($ret);
		}
		$backup_dir = APPLICATION_PATH."/db_backup";
		$files = scandir($backup_dir);
		unset($files[0]);
		unset($files[1]);
		$this->view->fileList = $files;
    }
	
    public function importAction(){
		if($this->request->isPost()){
			$this->_helper->viewRenderer->setNoRender(true);
			$params = $this->request->getParams();
			$backupFile = $params['file_name'];
			$ret = array('errcode'=>1, 'msg'=>"The file do not match the format: db_backup_datetime.sql(.gz)");
			if (preg_match("/^(.+)_backup.*\.(.*?)$/", $backupFile, $matches)){
				$db_name = $matches[1];
				$postFix = $matches[2];
// print_r($matches);				
				$backupFile = APPLICATION_PATH.'/db_backup/'.$backupFile;
				$matched = true;
				if ($postFix == 'gz')
					$command = "gunzip < $backupFile | mysql -uroot -pdbadmin $db_name";
				else if ($postFix == 'sql')
					$command = "C:\\Users\\b19268\\xampp\\mysql\\bin\\mysql -hlocalhost -uroot -pdbadmin $db_name < $backupFile";
				else{
					$matched = false;
				}
// print_r($command);				
				if($matched){
					try{
						exec($command, $output, $retVal);
						$ret['errcode'] = 0;
						$ret['msg'] = $db_name;
					}catch(Exception $e){
						$ret['errcode'] = 2;
						$ret['msg'] = $e->getMessage();
					}
				}
			}
			print_r(json_encode($ret));
			return json_encode($ret);
		}
		$backup_dir = APPLICATION_PATH."/db_backup";
		$files = scandir($backup_dir);
		unset($files[0]);
		unset($files[1]);
		$this->view->fileList = $files;
    }	
	
	public function relinkAction(){
		if($this->request->isPost()){
			$this->_helper->viewRenderer->setNoRender(true);
			$prjs = array(
				'i.MX6DL-ARD-Linux_4.1'=>'i.MX6DL-ARD-Linux_3.14',
				'i.MX6DL-SABRE_SDB-Linux_4.1'=>'i.MX6DL-SABRE_SDB-Linux_3.14',
				'i.MX6Q-ARD-Linux_4.1'=>'i.MX6Q-ARD-Linux_3.14',
				'i.MX6Q-SABRE_SDB-Linux_4.1'=>'i.MX6Q-SABRE_SDB-Linux_3.14',
				'i.MX6QP-ARD-Linux_4.1'=>'i.MX6QP-ARD-Linux_4.1',
				'i.MX6QP-SABRE_SDB-Linux_4.1'=>'i.MX6QP-SABRE_SDB-Linux_3.14',
				'i.MX6SL-EVK-Linux_4.1'=>'i.MX6SL-EVK-Linux_3.14',
				'i.MX6SX-ARD-Linux_4.1'=>'i.MX6SX-ARD-Linux_3.14',
				'i.MX6SX-SABRE_SDB-Linux_4.1'=>'i.MX6SX-SABRE_SDB-Linux_3.14',
				'i.MX6UL-EVK-Linux_4.1'=>'i.MX6UL-EVK-Linux_3.14',
				'i.MX7D-SABRE_SDB-Linux_4.1'=>'i.MX7D-SABRE_SDB-Linux_3.14'
			);

			$maps = array();
			$db = dbFactory::get('xt_new');
			foreach($prjs as $new=>$old){
				$res = $db->query("SELECT * FROM prj WHERE name=:name", array('name'=>$new));
				if($row = $res->fetch()){
					$new_id = $row['id'];
					$res = $db->query("SELECT * FROM prj WHERE name=:name", array('name'=>$old));
					if($row = $res->fetch()){
						$old_id = $row['id'];
						$maps[$new_id] = $old_id;
					}
				}
			}
			foreach($maps as $new=>$old){
				$res = $db->query("SELECT * FROM prj_testcase_ver WHERE prj_id=$old");
				while($row = $res->fetch()){
					$tmp = $db->query("SELECT * FROM prj_testcase_ver WHERE prj_id=$new AND testcase_id={$row['testcase_id']} AND testcase_ver_id={$row['testcase_ver_id']}");
					if(!$tmp_row = $tmp->fetch()){
						$insert = $row;
						unset($insert['id']);
						$insert['prj_id'] = $new;
						$db->insert('prj_testcase_ver', $insert);
					}
					
				}
			}
			print_r($maps);
			// return json_encode($maps);
		}
	}
}







