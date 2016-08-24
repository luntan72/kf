<?php
require_once('table_desc.php');

class xt_zzvw_request extends table_desc{
	protected function init($params){
        parent::init($params);
		// $this->options['linktype'] = 'infoLink';
		$this->options['real_table'] = 'request';
        $this->options['list'] = array(
			'id'=>array('label'=>'ID', 'hidden'=>true),
			'cycle'=>array('width'=>290, 'formatter'=>'eShowLink', 'formatoptions'=>array('type'=>'field_value_link', 'field'=>'cycle_id', 'baseLinkUrl'=>'/jqgrid/jqgrid/db/xt/table/zzvw_cycle/oper/information/newpage/1/element/')),
			'cycle_id'=>array('hidden'=>true, 'hidedlg'=>true),
			'*'
		);
	}
	
	protected function getButtons(){
		$buttons = parent::getButtons();
		unset($buttons['add']);
		unset($buttons['tag']);
		unset($buttons['removeFromTag']);
		return $buttons;
	}
	
	public function calcSqlComponents($params, $limited = true){
		$components = parent::calcSqlComponents($params, $limited);
		if(empty($components['order']))
			$components['order'] = 'id desc';
		return $components;
	}
}
?>