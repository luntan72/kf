<?php 
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('const_def_qygl.php');

class qygl_wz_action_save extends action_save{
	// protected function prepare($db, $table, $pair){
		// if($pair['wz_fl_id'] != WZ_FL_CHANPIN){
			// $this->params['gx_wz']['data'][0] = array(
				// 'gx_id'=>GX_CG,
				// 'min_kc'=>$this->params['min_kc1'],
				// 'max_kc'=>$this->params['max_kc1'],
				// 'pd_days'=>$this->params['pd_days1'],
				// 'defect_gx_wz'=>array(
					// 'data'=>array(
						// array(
							// 'defect_id'=>1,
							// 'price'=>$this->params['price1'],
							// 'remained'=>$this->params['remained1'],
							// 'ck_weizhi_id'=>$this->params['ck_weizhi_id1']
						// )
					// )
				// )
			// );
		// }
		// $pair['default_price'] = $this->params['price1'];
		// return parent::prepare($db, $table, $pair);
	// }
	
	protected function fillDefaultValues($action, &$pair, $db, $table){
		parent::fillDefaultValues($action, $pair, $db, $table);
	}
}

?>