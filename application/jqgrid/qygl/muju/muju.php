<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//技术指标管理
class qygl_muju extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'wz_id'=>array('editable'=>false, 'hidden'=>true),
			'muju_type_id'=>array('label'=>'模具类型'),
			'chupinlv'=>array('label'=>'出品率', 'post'=>'个每模'),
			'hemu_seconds'=>array('label'=>'合模时间', 'post'=>'秒'),
			'lengque_seconds'=>array('label'=>'冷却时间', 'post'=>'秒'),
			'chaimu_seconds'=>array('label'=>'拆模时间', 'post'=>'秒'),
			'caozuo_seconds'=>array('label'=>'操作时间', 'post'=>'秒'),
			'in_used'=>array('label'=>'可用数量', 'post'=>'副'),
			'muju_from_id'=>array('label'=>'模具来源', 'formatter'=>'select', 'data_source_table'=>'zzvw_kh'),
        );
	}
}
