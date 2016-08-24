<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_list.php');
require_once('const_def_qygl.php');

class qygl_wz_action_list extends action_list{
	// protected $unit_name = array();
	// public function setParams($params){
		// parent::setParams($params);
		// $res = $this->db->query("SELECT * FROM unit");
		// while($row = $res->fetch())
			// $this->unit_name[$row['id']] = $row['name'];
	// }
	
	// protected function getUnknownInfoForRow($row, $fields){
// // print_r($row);
		// foreach($fields as $field){
			// switch($field){
				// case 'unit_name':
					// $row[$field] = $this->unit_name[$row['unit_id']];
					// break;
				// // case 'price1':
					// // if(!empty($row['gx_wz'][0]) && !empty($row['gx_wz'][0]['defect_gx_wz'][0]))
						// // $row[$field] = $row['gx_wz'][0]['defect_gx_wz'][0]['price'];
					// // break;
				// // case 'min_kc1':
					// // if(!empty($row['gx_wz'][0]))
						// // $row[$field] = $row['gx_wz'][0]['min_kc'];
					// // break;
				// // case 'max_kc1':
					// // if(!empty($row['gx_wz'][0]))
						// // $row[$field] = $row['gx_wz'][0]['max_kc'];
					// // break;
				// // case 'pd_days1':
					// // if(!empty($row['gx_wz'][0]))
						// // $row[$field] = $row['gx_wz'][0]['pd_days'];
					// // break;
				// // case 'remained1':
					// // if(!empty($row['gx_wz'][0]) && !empty($row['gx_wz'][0]['defect_gx_wz'][0]))
						// // $row[$field] = $row['gx_wz'][0]['defect_gx_wz'][0]['remained'];
					// // break;
				// // case 'ck_weizhi_id1':
					// // if(!empty($row['gx_wz'][0]) && !empty($row['gx_wz'][0]['defect_gx_wz'][0]))
						// // $row[$field] = $row['gx_wz'][0]['defect_gx_wz'][0]['ck_weizhi_id'];
					// // break;
			// }
		// }
		// return $row;
	// }
}

?>