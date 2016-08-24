<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//工序管理
class qygl_hjcs extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
			'name'=>array(),
			'data_type_id',
			'gx_id'=>array('editable'=>true),
        );
		$this->options['linkTables'] = array(
			'm2m'=>array(
				'gx'
			),
		);
	}
}
