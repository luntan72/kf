<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
class qygl_gx_wz_zl_detail extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'gx_id'=>array('label'=>'工序', 'data_source_table'=>'zzvw_gx_sc'), //生产性工序
			'wz_id'=>array('label'=>'物资'),
			'defect_id'=>array('label'=>'质量等级'),
			'price'=>array('label'=>'单价', 'post'=>'元'),
			'ck_weizhi_id'=>array('label'=>'存放位置'),
			'min_kc'=>array('label'=>'最低库存'),
			'max_kc'=>array('label'=>'最高库存'),
			'pd_days'=>array('label'=>'盘点周期', 'post'=>'天')
        );
	}
}
