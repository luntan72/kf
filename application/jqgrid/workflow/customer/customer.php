<?php
require_once('table_desc.php');

class workflow_customer extends table_desc{
    public function init($params){
		parent::init($params);
		$this->options['list'] = array(
			'id',
			'name',
			'customer_prj'=>array("label"=>'Project', 'editable'=>true, 'formatter'=>'multi_row_edit', 'legend'=>'Project', 
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>'Project [%(prj_id)s]\'s current phase is [%(customer_phase_id)s], MP date is [%(mp_date)s]'))
		);
		// $this->options['edit'] = array('name', 'description', 'prj_prj_property', 'segment_id', 'customer_id', 'manager_id', 'begin_from', 'duration', 'unit_id');//, 'prj_type_id');//, 'from', 'duration', 'unit_id', 'progress');

		$this->options['gridOptions']['label'] = 'Customer';
		
		$this->options['linkTables'] = array(
			'one2m'=>array('customer_prj'=>array('link_table'=>'customer_prj'))
		); 
    } 
	
}
