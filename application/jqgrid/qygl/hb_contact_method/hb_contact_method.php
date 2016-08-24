<?php
require_once('table_desc.php');
//联系方式
class qygl_hb_contact_method extends table_desc{
	protected function init($params){
		parent::init($params);
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'hb_id'=>array('label'=>'名称', 'editable'=>false, 'formatter'=>'text'),
			'contact_method_id'=>array('label'=>'联系方式'),
			'content'=>array('label'=>'内容')
        );
		
		$this->options['edit'] = array('contact_method_id', 'content');
	}
}
