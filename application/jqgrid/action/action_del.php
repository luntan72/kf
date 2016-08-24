<?php
require_once('action_jqgrid.php');

class action_del extends action_jqgrid{
	protected function handlePost(){
// print_r($this->params);		
		$ids = array();
		$index_field = isset($this->params['index_field']) ? $this->params['index_field'] : 'id';
// print_r("SELECT id FROM {$this->params['table']} WHERE $index_field in ({$this->params['id']})")		;
		$res = $this->tool->query("SELECT id FROM {$this->params['table']} WHERE $index_field in ({$this->params['id']})");
		while($row = $res->fetch())
			$ids[] = $row['id'];
		if(empty($ids))return;
// print_r($ids);
		$ids = implode(',', $ids);
		$linkTables = $this->table_desc->getLinkTables();
		if(!empty($linkTables)){
// print_r($linkTables);		
			foreach($linkTables as $rel=>$relData){
				foreach($relData as $linkInfo){
					switch($rel){
						case 'one2one':
							$this->delOne2One($ids, $linkInfo);
							break;
						case 'one2m':
							$this->delOne2M($ids, $linkInfo);
							break;
						case 'm2m':
							$this->delM2M($ids, $linkInfo);
							break;
						case 'node_ver_m2m':
							$this->delNodeVerM2M($ids, $linkInfo);
							break;
						case 'ver':
							$ret = $this->delVer($ids, $linkInfo);
							break;
						case 'history':
							$this->delHistory($ids, $linkInfo);
							break;
						case 'treeview':
							$ret = $this->delTree($ids, $linkInfo);
							break;
					}
				}
			}
		}
		$this->tool->delete($this->get('real_table'), "id in ($ids)", $this->get('db'));
	}
	
	protected function delLinkTable($ids, $linkInfo){
		$db = $linkInfo['db'];
		$table = $linkInfo['table'];
		if(isset($linkInfo['real_table']))
			$table = $linkInfo['real_table'];
		$index_field = $linkInfo['self_link_field'];
// print_r("db = $db, table = $table, index_field = $index_field, ids = $ids\n");
		$del_action = actionFactory::get(null, 'del', array('db'=>$db, 'table'=>$table, 'index_field'=>$index_field, 'id'=>$ids));
		$del_action->handlePost();
	}
	
	protected function delOne2One($ids, $linkInfo){
		$this->delLinkTable($ids, $linkInfo);
	}
	
	protected function delOne2M($ids, $linkInfo){
		$this->delLinkTable($ids, $linkInfo);
	}
	
	protected function delM2M($ids, $linkInfo){
		$this->delLinkTable($ids, $linkInfo);
	}
	
	protected function delNodeVerM2M($ids, $linkInfo){
		$this->delLinkTable($ids, $linkInfo);
	}
	
	protected function delVer($ids, $linkInfo){
		$this->delLinkTable($ids, $linkInfo);
	}
	
	protected function delHistory($ids, $linkInfo){
		$this->delLinkTable($ids, $linkInfo);
	}
	
	protected function delTree($ids, $linkInfo){
		$this->delLinkTable($ids, $linkInfo);
	}
}

?>