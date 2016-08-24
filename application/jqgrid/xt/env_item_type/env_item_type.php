<?php
require_once('table_desc.php');
class xt_env_item_type extends table_desc{
    protected function init($params){
		parent::init($params);
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'env_item_type_id', 'db'=>'xt', 'table'=>'env_item');
    }
}
