<?php
require_once('table_desc.php');

class workflow_ticket extends table_desc{
    public function init($params){
		parent::init($params);
		//给module添加searchoptions
		$soptions = array(0=>' ');
		$res = $this->db->query("SELECT * FROM module");
		while($row = $res->fetch()){
			$soptions[$row['id']] = $row['name'];
		}
		
		$this->options['list'] = array(
			'id',
			'team_id'=>array('label'=>'Team'),
			'open_date'=>array('label'=>'Open Date'),
			'close_date'=>array('label'=>'Close Date', 'hidden'=>true),
			'effort'=>array('label'=>'Effort', 'post'=>array('value'=>'(Man-Days)')),
			'customer_id',
			'prj_id'=>array('label'=>'Project', 'data_source_table'=>'zzvw_prj_cs'),
			'content',
			'ticket_status_id'=>array('label'=>'Status'),
			'root_cause_id'=>array('label'=>'Root Cause'),
			'solution_id'=>array('hidden'=>true),
			'module_ticket'=>array('label'=>'Module', 'editable'=>true, 'data_source_db'=>'workflow', 
				'data_source_table'=>'module_ticket', 'from'=>'workflow.module_ticket',
				'search'=>true, 'stype'=>'select', 'searchoptions'=>array('value'=>$this->tool->array2str($soptions)), 
				'formatter'=>'multi_row_edit', 'formatoptions'=>array('subformat'=>'temp', 'temp'=>'Module[%(module_id)s] has %(question_type_id)s question'), 'legend'=>'Modules'),
			'ticket_trace'=>array('label'=>'Progress Record', 'data_source_table'=>'ticket_trace', 'data_source_db'=>'workflow', 'from'=>'workflow.ticket_trace', 
				'search'=>false, 
				'formatter'=>'multi_row_edit', 'formatoptions'=>array('subformat'=>'temp', 'temp'=>'[%(creater_id)s updated on %(update_date)s]: <BR />%(content)s<BR>'), 'legend'=>'Records'),
			'community_link'=>array('formatter'=>'link'),
			'*'=>array('hidden'=>true)
		);
		$this->options['edit'] = array('input_source_id'=>array('editable'=>false), 'input_person'=>array('editable'=>false), 
			'customer_id'=>array('editable'=>false), 'prj_id'=>array('editable'=>false), 'ticket_status_id'=>array('editable'=>true), 'module_ticket', 'content', 'effort',
			'community_thread_number', 'community_link', 'linked_ct_number', 'root_cause_id', 'close_date', 'ticket_trace');
		$this->options['add'] = array('input_source_id', 'input_person', 'customer_id', 'prj_id', 'ticket_status_id', 'module_ticket', 'content', 'effort',
			'community_thread_number', 'community_link', 'Linked_ct_number', 'root_cause_id', 'open_date', 'ticket_trace');
		
		$this->options['linkTables'] = array(
			'one2m'=>array(
				'ticket_trace'=>array('link_table'=>'ticket_trace', 'self_link_field'=>'ticket_id'),
				'module_ticket'=>array('link_table'=>'module_ticket', 'self_link_field'=>'ticket_id')
			),
		);
    }
	
	protected function getButtons(){
		$buttons = array(
			'import'=>array('caption'=>'Upload'),
		);
		return array_merge(parent::getButtons(), $buttons);
	}
	
}
