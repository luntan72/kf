<?php
require_once('table_desc.php');

class xt_env_item extends table_desc{
    protected function init($params){
		parent::init($params);
		$this->parent_field = 'env_item_type_id';
		$this->parent_table = 'env_item_type';
    } 
}
