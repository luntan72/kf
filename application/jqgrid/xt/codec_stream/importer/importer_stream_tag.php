<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/codec_stream/importer/importer_codec_stream_byxt.php');

class xt_codec_stream_importer_stream_tag extends xt_codec_stream_importer_codec_stream_byxt{
	
	protected function processSheetData($title, $sheet_data){
		$total = 0;
// print_r($sheet_data);
		foreach($sheet_data as $stream){
			$this->processStream($stream, $title);
			$total ++;
		}
print_r("sheet $title, total = $total\n");		
// print_r($this->tags);
		if(!empty($this->tags)){
			//$element_ids = '';
			foreach($this->tags as $k=>$v){
print_r($k.":".count($v)."\n<br />");
				$element_ids = '';
				switch($k){
					case 'FAS':
						if(is_array($v)){
							$res = $this->tool->query("select * from tag where name = 'FAS' and creater_id = 27");
							if($info = $res->fetch()){
								if(!empty($info['element_ids'])){
									$ids = explode(",", $info['element_ids']);
print_r('fas:'.count($ids)."\n<br />");
									foreach($v as $k=>$i){
										if(in_array($i, $ids))
											unset($v[$k]);
										else
											$ids[] = $i;
									}
								}	
								else
									$ids = $v;
							}
							$element_ids[$k] = implode(',', $ids);
print_r('fas:'.count($ids)."\n<br />");
							$data = array('element_ids'=>$element_ids[$k], 'name'=>"FAS", 'db_table'=>'xt.codec_stream', 'id_field'=>'id', 'public'=>1, 
								'creater_id'=>27, 'modified'=>date('Y-m-d'));
							$this->tool->getElementId('tag', $data, array('name'));
							print_r('done');
						}
						break;
					case 'Android':
						if(is_array($v)){
							$res = $this->tool->query("select * from tag where name = 'Android_GraphicManager' and creater_id = 62");
							if($info = $res->fetch()){
								if(!empty($info['element_ids'])){
									$ids = explode(",", $info['element_ids']);
print_r('Android:'.count($ids)."\n<br />");
									foreach($v as $k=>$i){
										if(in_array($i, $ids))
											unset($v[$k]);
										else
											$ids[] = $i;
									}
								}	
								else
									$ids = $v;
							}
							$element_ids[$k] = implode(',', $ids);
print_r('Android:'.count($ids)."\n<br />");
							$data = array('element_ids'=>$element_ids[$k], 'name'=>"Android_GraphicManager", 'db_table'=>'xt.codec_stream', 'id_field'=>'id', 'public'=>1, 
								'creater_id'=>62, 'modified'=>date('Y-m-d'));
							$this->tool->getElementId('tag', $data, array('name'));
							print_r('done');
						}
						break;
					case 'Linux':
						if(is_array($v)){
							$res = $this->tool->query("select * from tag where name = 'Linux' and creater_id = 60");
							if($info = $res->fetch()){
								if(!empty($info['element_ids'])){
									$ids = explode(",", $info['element_ids']);
print_r('Linux:'.count($ids)."\n<br />");
									foreach($v as $k=>$i){
										if(in_array($i, $ids))
											unset($v[$k]);
										else
											$ids[] = $i;
									}
								}	
								else
									$ids = $v;
							}
							$element_ids[$k] = implode(',', $ids);
print_r('Linux:'.count($ids)."\n<br />");
							$data = array('element_ids'=>$element_ids[$k], 'name'=>"Linux", 'db_table'=>'xt.codec_stream', 'id_field'=>'id', 'public'=>1, 
								'creater_id'=>60, 'modified'=>date('Y-m-d'));
							$this->tool->getElementId('tag', $data, array('name'));
							print_r('done');
						}
						break;
					default:
						if(is_array($v)){
print_r($k."\n<br />");
print_r($v);
							$res = $this->tool->query("select * from tag where name = $k and creater_id = {$this->params['owner_id']}");
							if($info = $res->fetch()){
								if(!empty($info['element_ids'])){
									$ids = explode(",", $info['element_ids']);
									foreach($v as $k=>$i){
										if(in_array($i, $ids))
											unset($v[$k]);
										else
											$ids[] = $i;
									}
								}	
								else
									$ids = $v;
							}
							$element_ids[$k] = implode(',', $ids);
							$data = array('element_ids'=>$element_ids[$k], 'name'=>$k, 'db_table'=>'xt.codec_stream', 'id_field'=>'id', 'public'=>1, 
								'creater_id'=>$this->params['owner_id'], 'modified'=>date('Y-m-d'));
							$this->tool->getElementId('tag', $data, array('name'));
							print_r('done');
						}
				}
				
			}
// print_r($element_ids);
		}
	}
	
	protected function processStream($stream, $title){
		$stream_id = $this->getId('codec_stream', array('code'=>$stream['code']), array('code'));
		if('error' == $stream_id)
			return;
print_r($stream_id);
print_r("\n<br />");
		if("Y" == $stream['FAS'])
			$this->tags['FAS'][$stream_id] = $stream_id;
		if("Y" == $stream['Android'])
			$this->tags['Android'][$stream_id] = $stream_id;
		if("Y" == $stream['Linux'])
			$this->tags['Linux'][$stream_id] = $stream_id;
	}
	
	protected function getId($table, $valuePair, $keyFields = array(), &$is_new = true){
		static $elements = array();
		$cached = false;
		if (!empty($keyFields)){
			if(in_array('code', $keyFields)){
				$cached = true;
				foreach($keyFields as $k=>$v){
					if($v == 'code')
						$keyField = $keyFields[$k];
				}
			}
			else if(in_array('name', $keyFields)){
				$cached = true;
				foreach($keyFields as $k=>$v){
					if($v == 'name')
						$keyField = $keyFields[$k];
				}
			}
		}
		if (!$cached || empty($elements[$table][$valuePair[$keyField]])){
			$where = array();
			$realVP = array();
			$res = $this->tool->query("describe $table");
			while($row = $res->fetch()){
				if (isset($valuePair[$row['Field']]))
					$realVP[$row['Field']] = $valuePair[$row['Field']];
			}
// if($table == 'testcase_ver')
// print_r($realVP);
			if (empty($keyFields))
				$keyFields = array_keys($realVP);
			foreach($keyFields as $k){
				$where[] = "$k=:$k";
				$whereV[$k] = $realVP[$k];
			}
			$res = $this->tool->query("SELECT * FROM $table where ".implode(' AND ', $where), $whereV);
			if($info = $res->fetch()){
				$this->tool->update($table, $realVP, "id=".$info['id']);
				$is_new = false;
				return $info['id'];
			}
			return 'error';
		}
		$is_new = false;
		return $elements[$table][$valuePair[$keyField]];
	}
};

?>
