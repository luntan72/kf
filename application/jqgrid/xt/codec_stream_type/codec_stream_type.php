<?php
require_once('table_desc.php');

class xt_codec_stream_type extends table_desc{
    protected function init($params){
		parent::init($params);
		$cart_data = new stdClass;
		$cart_data->filters =  '{"groupOp":"AND","rules":[{"field":"testcase_module_id","op":"in","data":"507,467"}]}';
        $this->options['edit'] = array(
			'name'=>array('label'=>'Stream Type'),
			'testcase_ids'=>array('label'=>'Default Actions', 'editrules'=>array('required'=>false), 'hidden'=>true, 'type'=>'cart', 'cart_db'=>'xt', 'cart_table'=>'testcase', 'cart_data'=>json_encode($cart_data)),
		);
	}
}