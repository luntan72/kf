XT = XT || {};

(function(){
	this.workflow_prj = function(){
		// 只列出顶层prj
		var postData = {};
		var rules = [{field:'pid', op:'eq', data:'0'}];
		
		postData['filters'] = JSON.stringify({groupOp:'AND', rules:rules});
		return this.grid_index('workflow', 'prj', 'Workflow Project', postData);
	}
	
	this.workflow_daily_note = function(){
		return this.grid_index('workflow', 'daily_note', 'Daily Note');
	}

	this.workflow_period = function(){
		return this.grid_index('workflow', 'period', 'Period');
	}

	this.workflow_work_summary = function(){
		return this.grid_index('workflow', 'work_report', 'Work Report');
	}

	this.workflow_customer_support_ticket_index = function(){
		return this.grid_index('workflow', 'ticket', 'Customer Support Ticket');
	}

	this.workflow_cqi_ticket_index = function(){
		return this.grid_index('workflow', 'cqi_ticket', 'CQI Ticket');
	}

	this.workflow_npi_ticket_index = function(){
		return this.grid_index('workflow', 'npi_ticket', 'NPI Ticket');
	}

	this.workflow_reference_design_ticket_index = function(){
		return this.grid_index('workflow', 'reference_design_ticket', 'Reference Design Ticket');
	}

	this.workflow_ticket_trace_index = function(){
		return this.grid_index('workflow', 'ticket_trace', 'Ticket Trace');
	}

	this.workflow_module_index = function(){
		return this.grid_index('workflow', 'module', 'Module');
	}

	this.workflow_question_type_index = function(){
		return this.grid_index('workflow', 'question_type', 'Question Type');
	}

	this.workflow_customer_index = function(){
		return this.grid_index('workflow', 'customer', 'Customer');
	}

	this.workflow_region_index = function(){
		return this.grid_index('workflow', 'region', 'Region');
	}

	this.workflow_family_index = function(){
		return this.grid_index('workflow', 'family', 'Family');
	}

	this.workflow_part_index = function(){
		return this.grid_index('workflow', 'part', 'Part');
	}

	this.workflow_prj_phase_index = function(){
		return this.grid_index('workflow', 'prj_phase', 'Prj Phase');
	}

	this.workflow_customer_phase_index = function(){
		return this.grid_index('workflow', 'customer_phase', 'Customer Phase');
	}

	this.workflow_ticket_status_index = function(){
		return this.grid_index('workflow', 'ticket_status', 'Ticket Status');
	}
}).apply(XT);