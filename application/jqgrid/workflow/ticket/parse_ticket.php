<?php
defined('APPLICATION_PATH') || define('APPLICATION_PATH', "C:/Users/b19268/xampp/kuafu/application");

require_once(APPLICATION_PATH.'/../library/excel_parse.php');
require_once(APPLICATION_PATH.'/../library/toolfactory.php');

class ticket_parse extends excel_parse{
	protected $tool = null;
	
	protected function init($filename, $params){
		parent::init($filename, $params);
		$this->tool = toolFactory::get('db');
		$this->tool->setDb('workflow');
	}
	
	// protected function setOptions(){
		// parent::setOptions();
		// $this->columnMaps['Customer Support'] = array(
			// 'start_row'=>2,
			// 'columns'=>array(
				// 'A'=>'week',
				// 'B'=>'team',
				// 'C'=>'ae',
				// 'D'=>'open_date',
				// 'E'=>'close_date',
				// 'F'=>'effort',
				// 'G'=>'segment',
				// 'H'=>'customer',
				// 'I'=>'family',
				// 'J'=>'part',
				// 'K'=>'customer_phase',
				// 'L'=>'mp_date',
				// 'M'=>'prj',
				// 'N'=>'ltr',
				// 'O'=>'module',
				// 'P'=>'tool',
				// 'Q'=>'content',
				// 'R'=>'ticket_trace',
				// 'S'=>'root_cause',
				// 'T'=>'solution',
				// 'U'=>'support_type',
				// 'V'=>'input_source',
				// 'W'=>'community_link',
			// )
		// );
		// $this->columnMaps['Reference Design'] = array(
			// 'start_row'=>2,
			// 'columns'=>array(
				// 'A'=>'week',
				// 'B'=>'team',
				// 'C'=>'ae',
				// 'D'=>'open_date',
				// 'E'=>'close_date',
				// 'F'=>'effort',
				// 'G'=>'family',
				// 'H'=>'segment',
				// 'I'=>'prj',
				// 'J'=>'reference_phase',
				// 'K'=>'milestone_date',
				// 'L'=>'ticket_trace',
			// )
		// );
		// $this->columnMaps['Apps Lead'] = array(
			// 'start_row'=>2,
			// 'columns'=>array(
				// 'A'=>'week',
				// 'B'=>'team',
				// 'C'=>'ae',
				// 'D'=>'open_date',
				// 'E'=>'close_date',
				// 'F'=>'effort',
				// 'G'=>'total_effort',
				// 'H'=>'family',
				// 'I'=>'Part',
				// 'J'=>'npi_phase',
				// 'K'=>'to_date',
				// 'L'=>'enablement_task',
				// 'M'=>'highlight',
				// 'N'=>'ticket_trace'
			// )
		// );
	// }

	protected function analyze_Customer_Support($sheet, $title){
		parent::default_analyze_sheet($sheet, $title);
		//需要处理基表
		$fields = array('ae'=>'ae', 'team'=>'team', 'customer'=>'customer', 
			'root_cause'=>'root_cause', 'family'=>'family',
			'segment'=>'segment', 'support_type'=>'support_type', 'solution'=>'solution');
		
		$date_fields = array('open_date', 'close_date');
		foreach($this->data[$title] as $row=>&$data){
			if(empty($data['ae']))//没有AE，略过
				continue;
			//处理Customer Phase
			if(!empty($data['customer_phase'])){
				$phase_date = array('name'=>$data['customer_phase'], 'prj_type'=>3);
				$data['prj_phase_id'] = $this->tool->getElementId('prj_phase', $phase_date);
			}
			else{
				$data['prj_phase_id'] = 0;
			}
			//处理时间
			foreach($date_fields as $f){
				if(!empty($data[$f]))
					$data[$f] = $this->excelTime($data[$f], false);
			}
			foreach($fields as $table=>$f){
				if(!empty($data[$f])){
					$data[$f.'_id'] = $this->tool->getElementId($table, array('name'=>$data[$f]));
				}
				else
					$data[$f.'_id'] = 0;
					
				unset($data[$f]);
			}
			//处理part
			$part_data = array('name'=>$data['part'], 'family_id'=>$data['family_id']);
			$data['part_id'] = $this->tool->getElementId('part', $part_data);
			unset($data['part']);
			//处理prj
			$data['prj_id'] = 0;
			if(!empty($data['prj'])){
				$prj_data = array('name'=>$data['prj'], 'segment_id'=>$data['segment_id'], 'part_id'=>$data['part_id'], 
					'prj_type_id'=>3, 'mp_date'=>$data['mp_date'], 'ltr'=>$data['ltr']);
				$data['prj_id'] = $this->tool->getElementId('prj', $prj_data);
			}
			unset($data['prj']);
			unset($data['tool_id']);
			unset($data['segment_id']);
			unset($data['part_id']);
			// unset($data['mp_date']);
			unset($data['ltr']);
			//处理prj和tool的连接
			$tools = explode('  ', $data['tool']);
			foreach($tools as $tool){
				if(!empty($tool)){
					$t_id = $this->tool->getElementId('tool', array('name'=>$tool));
					$this->tool->getElementId('prj_tool', array('prj_id'=>$data['prj_id'], 'tool_id'=>$t_id));
				}
			}
			//处理prj和Customer的连接
			$this->tool->getElementId('customer_prj', array('customer_id'=>$data['customer_id'], 'prj_id'=>$data['prj_id'], 
				'mp_date'=>$data['mp_date'], 'prj_phase_id'=>$data['prj_phase_id']));
			//处理input Source. Input Source的格式不规范，需要分情况处理
			$pat = "/(.*?)\((.*)\)/";
			preg_match($pat, $data['input_source'], $matches);
			if(count($matches) > 1){
				$data['input_source_id'] = $this->tool->getElementId('input_source', array('name'=>$matches[1]));
				$data['input_person'] = $matches[2];
			}
			unset($data['input_source']);
			$ticket_trace = $data['ticket_trace'];
			unset($data['ticket_trace']);
			$modules = explode(',', $data['module']);
			unset($data['module']);
			$data['ticket_status_id'] = 1; //open
			if(!empty($data['close_date']))
				$data['ticket_status_id'] = 3; //closed
			//处理ticket表
			$ticket_id = $this->tool->getElementId('ticket', $data);
			//处理ticket trace
			$ticket_trace_data = array('ticket_id'=>$ticket_id, 'content'=>$ticket_trace, 'update_date'=>$data['open_date'], 'creater_id'=>$data['ae_id']);
			$this->tool->getElementId('ticket_trace', $ticket_trace_data);
			//处理module
			foreach($modules as $m){
				if(!empty($m)){
					$m_id = $this->tool->getElementId('module', array('name'=>$m));
					$this->tool->getElementId('module_ticket', array('ticket_id'=>$ticket_id, 'module_id'=>$m_id));
				}
			}
		}
	}
	
	protected function analyze_Reference_Design($sheet, $title){
		parent::default_analyze_sheet($sheet, $title);
		//需要处理基表
		$fields = array('ae'=>'ae', 'team'=>'team', 'family'=>'family', 'segment'=>'segment');
		
		$date_fields = array('open_date', 'close_date');
		foreach($this->data[$title] as $row=>&$data){
			if(empty($data['ae']))//没有AE，略过
				continue;
			//处理Customer Phase
			if(!empty($data['reference_phase'])){
				$phase_date = array('name'=>$data['reference_phase'], 'prj_type'=>2);
				$data['prj_phase_id'] = $this->tool->getElementId('prj_phase', $phase_date);
			}
			else{
				$data['prj_phase_id'] = 0;
			}
			//处理时间
			foreach($date_fields as $f){
				if(!empty($data[$f]))
					$data[$f] = $this->excelTime($data[$f], false);
			}
			foreach($fields as $table=>$f){
				if(!empty($data[$f])){
					$data[$f.'_id'] = $this->tool->getElementId($table, array('name'=>$data[$f]));
				}
				else
					$data[$f.'_id'] = 0;
					
				unset($data[$f]);
			}
			//处理part
			$data['part_id'] = 0;
			// $part_data = array('name'=>$data['part'], 'family_id'=>$data['family_id']);
			// $data['part_id'] = $this->tool->getElementId('part', $part_data);
			// unset($data['part']);
			//处理prj
			$data['prj_id'] = 0;
			if(!empty($data['prj'])){
				$prj_data = array('name'=>$data['prj'], 'segment_id'=>$data['segment_id'], 'part_id'=>$data['part_id'], 'prj_type_id'=>2);
				$data['prj_id'] = $this->tool->getElementId('prj', $prj_data);
			}
			unset($data['prj']);
			unset($data['segment_id']);
			unset($data['part_id']);

			$ticket_trace = $data['ticket_trace'];
			unset($data['ticket_trace']);
			$data['ticket_status_id'] = 1; //open
			if(!empty($data['close_date']))
				$data['ticket_status_id'] = 3; //closed
			//处理ticket表
			$ticket_id = $this->tool->getElementId('reference_design_ticket', $data);
			//处理ticket trace
			$ticket_trace_data = array('reference_design_ticket_id'=>$ticket_id, 'content'=>$ticket_trace, 'update_date'=>$data['open_date'], 'creater_id'=>$data['ae_id']);
			$this->tool->getElementId('reference_design_ticket_trace', $ticket_trace_data);
		}
		
	}
	
	protected function analyze_Apps_Lead($sheet, $title){ //npi ticket
		parent::default_analyze_sheet($sheet, $title);
		//需要处理基表
		$fields = array('ae'=>'ae', 'team'=>'team', 'family'=>'family');
		
		$date_fields = array('open_date', 'close_date');
		foreach($this->data[$title] as $row=>&$data){
// print_r($data);
			if(empty($data['ae']))//没有AE，略过
				continue;
			if(empty($data['prj'])){
				$data['prj'] = 'NPI For '.$data['family'].' '.$data['part'];
			}
			//处理Customer Phase
			if(!empty($data['npi_phase'])){
				$phase_date = array('name'=>$data['npi_phase'], 'prj_type'=>1);
				$data['prj_phase_id'] = $this->tool->getElementId('prj_phase', $phase_date);
			}
			else{
				$data['prj_phase_id'] = 0;
			}
			//处理时间
			foreach($date_fields as $f){
				if(!empty($data[$f]))
					$data[$f] = $this->excelTime($data[$f], false);
			}
			//处理基表
			foreach($fields as $table=>$f){
				if(!empty($data[$f])){
					$data[$f.'_id'] = $this->tool->getElementId($table, array('name'=>$data[$f]));
				}
				else
					$data[$f.'_id'] = 0;
					
				unset($data[$f]);
			}
			//处理part
			$data['part_id'] = 0;
			if(!empty($data['part'])){
				$part_data = array('name'=>$data['part'], 'family_id'=>$data['family_id']);
				$data['part_id'] = $this->tool->getElementId('part', $part_data);
			}
			unset($data['part']);
			//处理prj
			$data['prj_id'] = 0;
			if(!empty($data['prj'])){
				$prj_data = array('name'=>$data['prj'], 'segment_id'=>0, 'part_id'=>$data['part_id'], 'prj_type_id'=>1);
				$data['prj_id'] = $this->tool->getElementId('prj', $prj_data);
			}
			unset($data['prj']);
			unset($data['segment_id']);
			unset($data['part_id']);

			$ticket_trace = $data['ticket_trace'];
			unset($data['ticket_trace']);
			$data['ticket_status_id'] = 1; //open
			if(!empty($data['close_date']))
				$data['ticket_status_id'] = 3; //closed
			//处理ticket表
			$ticket_id = $this->tool->getElementId('npi_ticket', $data);
			//处理ticket trace
			$ticket_trace_data = array('npi_ticket_id'=>$ticket_id, 'content'=>$ticket_trace, 'update_date'=>$data['open_date'], 'creater_id'=>$data['ae_id']);
			$this->tool->getElementId('npi_ticket_trace', $ticket_trace_data);
		}
	}

	protected function _parse(){
		parent::_parse();
// print_r($this->data);
		//根据解析后内容确定是否已经上传过了，如果已经上传过了，则不重复上传

		return ERROR_OK;
	}
	
	protected function getColumnMap($title, $highestColumn){
		print_r($title.', column ='.$highestColumn);
		$ret = array('start_row'=>2);
		switch($title){
			case 'Customer Support':
				if($highestColumn == 'W'){
					$ret['columns'] = array(
						'A'=>'week',
						'B'=>'team',
						'C'=>'ae',
						'D'=>'open_date',
						'E'=>'close_date',
						'F'=>'effort',
						'G'=>'segment',
						'H'=>'customer',
						'I'=>'family',
						'J'=>'part',
						'K'=>'customer_phase',
						'L'=>'mp_date',
						'M'=>'prj',
						'N'=>'ltr',
						'O'=>'module',
						'P'=>'tool',
						'Q'=>'content',
						'R'=>'ticket_trace',
						'S'=>'root_cause',
						'T'=>'solution',
						'U'=>'support_type',
						'V'=>'input_source',
						'W'=>'community_link',
					);
				}
				else{
					$ret['columns'] = array(
						'A'=>'week',
						'B'=>'team',
						'C'=>'ae',
						'D'=>'open_date',
						'E'=>'close_date',
						'F'=>'effort',
						'G'=>'total effort',
						'H'=>'segment',
						'I'=>'customer',
						'J'=>'family',
						'K'=>'part',
						'L'=>'customer_phase',
						'M'=>'mp_date',
						'N'=>'prj',
						'O'=>'ltr',
						'P'=>'module',
						'Q'=>'tool',
						'R'=>'content',
						'S'=>'ticket_trace',
						'T'=>'root_cause',
						'U'=>'solution',
						'V'=>'support_type',
						'W'=>'input_source',
						'X'=>'community_link',
					);
				}
				break;
			case 'Reference Design':
				if($highestColumn == 'N'){
					$ret['columns'] = array(
						'A'=>'week',
						'B'=>'team',
						'C'=>'ae',
						'D'=>'open_date',
						'E'=>'close_date',
						'F'=>'effort',
						'G'=>'family',
						'H'=>'segment',
						'I'=>'prj',
						'J'=>'reference_phase',
						'K'=>'milestone_date',
						'L'=>'ticket_trace',
					);
				}
				else{
					$ret['columns'] = array(
						'A'=>'week',
						'B'=>'team',
						'C'=>'ae',
						'D'=>'open_date',
						'E'=>'close_date',
						'F'=>'effort',
						'G'=>'total effort',
						'H'=>'family',
						'I'=>'segment',
						'J'=>'prj',
						'K'=>'reference_phase',
						'L'=>'milestone_date',
						'M'=>'ticket_trace',
					);
				}
				break;
			case 'Apps Lead':
				if($highestColumn == 'O'){
					$ret['columns'] = array(
						'A'=>'week',
						'B'=>'team',
						'C'=>'ae',
						'D'=>'open_date',
						'E'=>'close_date',
						'F'=>'effort',
						'G'=>'family',
						'H'=>'part',
						'I'=>'npi_phase',
						'J'=>'to_date',
						'K'=>'enablement_task',
						'L'=>'highlight',
						'M'=>'ticket_trace'
					);
				}
				else{
					$ret['columns'] = array(
						'A'=>'week',
						'B'=>'team',
						'C'=>'ae',
						'D'=>'open_date',
						'E'=>'close_date',
						'F'=>'effort',
						'G'=>'total_effort',
						'H'=>'family',
						'I'=>'part',
						'J'=>'npi_phase',
						'K'=>'to_date',
						'L'=>'enablement_task',
						'M'=>'highlight',
						'N'=>'ticket_trace'
					);
				}
				break;
			default:
				$ret = parent::getColumnMap($title, $highestColumn);
		}
		return $ret;
// print_r($this->columnMaps);		
		if (!empty($this->columnMaps)){
			if (isset($this->columnMaps[$title]))
				return $this->columnMaps[$title];
			if (isset($this->columnMaps['default']))
				return $this->columnMaps['default'];
			// return array();
		}
		$column = array();
		for($i = 'A'; $i != $highestColumn; $i = $this->nextCol($i))
			$column[$i] = $i;
		$column[$highestColumn] = $highestColumn;
		return array('start_row'=>2, 'columns'=>$column);
	}
			
	
}
?> 
