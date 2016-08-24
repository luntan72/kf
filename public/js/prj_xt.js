// var XT;
XT = XT || {};
(function(){
	this.tc_index = function(){
		return this.grid_index('xt', 'testcase', 'Testcase');
	}

	this.tc_type_index = function(){
		return this.grid_index('xt', 'testcase_type', 'Testcase Type');
	}

	this.tc_source_index = function(){
		return this.grid_index('xt', 'testcase_source', 'Testcase Source');
	}

	this.tc_priority_index = function(){
		return this.grid_index('xt', 'testcase_priority', 'Testcase Priority');
	}

	this.tc_category_index = function(){
		return this.grid_index('xt', 'testcase_category', 'Testcase Category');
	}

	this.testpoint_index = function(){
		return this.grid_index('xt', 'testcase_testpoint', 'Testpoint');
	}

	this.module_index = function(){
		return this.grid_index('xt', 'testcase_module', 'Module');
	}

	this.test_env_index = function(){
		return this.grid_index('xt', 'test_env', 'Test Environment');
	}

	this.env_item_index = function(){
		return this.grid_index('xt', 'env_item', 'Environment Item');
	}

	this.env_item_type_index = function(){
		return this.grid_index('xt', 'env_item_type', 'Environment Item Type');
	}

	this.cycle_type_index = function(){
		return this.grid_index('xt', 'cycle_type', 'Cycle Type');
	}

	this.cycle_category_index = function(){
		return this.grid_index('xt', 'cycle_category', 'Cycle Category');
	}

	this.test_result_index = function(){
		return this.grid_index('xt', 'result_type', 'Test Result Type');
	}

	this.cycle_index = function(){
		return this.grid_index('xt', 'zzvw_cycle', 'Test Cycle');//, {real_table:'cycle'});
	}

	this.cycle_detail_index = function(){
		return this.grid_index('xt', 'zzvw_cycle_detail', 'Test Cycle Detail');
	}

	this.srs_module_index = function(){
		return this.grid_index('xt', 'srs_module', 'SRS Module');
	}

	this.srs_index = function(){
		return this.grid_index('xt', 'srs_node', 'SRS Item');
	}

	this.prj_index = function(){
		return this.grid_index('xt', 'zzvw_prj', 'Project Management');
	}

	this.board_type_index = function(){
		return this.grid_index('xt', 'board_type', 'Board Type Management');
	}

	this.compiler_index = function(){
		return this.grid_index('xt', 'compiler', 'Compiler Management');
	}

	this.build_target_index = function(){
		return this.grid_index('xt', 'build_target', 'Build Target Management');
	}

	this.chip_type_index = function(){
		return this.grid_index('xt', 'chip_type', 'Chip Type Management');
	}

	this.chip_index = function(){
		return this.grid_index('xt', 'chip', 'Chip Management');
	}

	this.rel_category_index = function(){
		return this.grid_index('xt', 'rel_category', 'Release Category');
	}

	this.rel_index = function(){
		return this.grid_index('xt', 'rel', 'Release Management');
	}

	this.platform_index = function(){
		return this.grid_index('hengshan', 'platform', 'Platform Management');
	}

	this.os_index = function(){
		return this.grid_index('xt', 'os', 'OS Management');
	}

	this.issue_index = function(){
		return this.grid_index('issue', 'issue', 'Issue Management');
	}

	this.codec_output_index = function(){
		return this.grid_index('xt', 'output', 'Codec Screen Management');
	}

	this.codec_release_index = function(){
		return this.grid_index('xt', 'rel', 'Codec Release Management');
	}

	this.codec_trickmode_index = function(){
		return this.grid_index('xt', 'codec_trickmode', 'Trickmode Management');
	}

	this.codec_prj_index = function(){
		return this.grid_index('xt', 'prj', 'Project Management');
	}

	this.codec_os_index = function(){
		return this.grid_index('xt', 'os', 'OS Management');
	}

	this.codec_chip_index = function(){
		return this.grid_index('xt', 'chip', 'Chip Management');
	}

	this.codec_output_index = function(){
		return this.grid_index('xt', 'output', 'Video Output');
	}

	this.codec_audio_output_index = function(){
		return this.grid_index('xt', 'audio_output', 'Audio Output');
	}

	this.codec_platform_index = function(){
		return this.grid_index('xt', 'platform', 'Platform Management');
	}

	this.codec_v4cc_index = function(){
		return this.grid_index('xt', 'codec_stream_v4cc', 'Video Codec');
	}

	this.codec_a_codec_index = function(){
		return this.grid_index('xt', 'codec_stream_a_codec', 'Audio Codec');
	}
	
	this.stream_steps_index = function(){
		return this.grid_index('xt', 'stream_steps', 'Stream Information');
	}

	this.stream_tools_index = function(){
		return this.grid_index('xt', 'stream_tools', 'Stream Tools');
	}

	this.codec_cycle_index = function(){
		return this.grid_index('xt', 'cycle', 'Cycle Management');
	}

	this.codec_stream_index = function(){
		return this.grid_index('xt', 'codec_stream', 'Stream Management');
	}
	
	this.codec_stream_format_index = function(){
		return this.grid_index('xt', 'codec_stream_format', 'Stream Format');
	}

	this.codec_priority_index = function(){
		return this.grid_index('xt', 'priority', 'Priority Management');
	}

	this.codec_player_index = function(){
		return this.grid_index('xt', 'player', 'Player Management');
	}

	this.codec_container_index = function(){
		return this.grid_index('xt', 'codec_stream_container', 'Container Management');
	}

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

	this.tag_index = function(){
		return this.grid_index('xt', 'tag', 'My Tags');
	}
	
	this.doc_index = function(){
		return this.grid_index('doc', 'doc', 'Documents');
	}
	
	this.doc_type_index = function(){
		return this.grid_index('doc', 'doc_type', 'Document Types');
	}
	
	this.mcu_device_index = function(){
		return this.grid_index('mcu', 'device', 'Devices');
	}
	
	this.mcu_device_ver_index = function(){
		return this.grid_index('mcu', 'zzvw_device_ver', 'Device Vers');
	}
	
	this.inputResult = function(db, table, container, id, gridSelector, divSelector, parent){	
		var grid = grid_factory.get(db, table, {container:container});
		var action = grid.getAction();
		return action.inputResult(id, gridSelector, divSelector, parent);
	};
	
	this.resultInfo = function(db, table, container, id, gridSelector, divSelector, selectedValue, parent){
		var grid = grid_factory.get(db, table, {container:container});
		var action = grid.getAction();	
		return action.resultInfo(id, gridSelector, divSelector, selectedValue, parent);
	};
	
	this.buildResult = function(db, table, container, id, gridSelector, selectedValue, parent){
		var grid = grid_factory.get(db, table, {container:container});
		var action = grid.getAction();	
		return action.buildResult(id, gridSelector, selectedValue, parent);
	};
	
	this.setOneTester = function(db, table, container, id, gridSelector, divSelector, parent){
		var grid = grid_factory.get(db, table, {container:container});
		var action = grid.getAction();	
		return action.setOneTester(id, gridSelector, divSelector, parent);
	};
	
	this.log_download = function(db, table, container, rowId, fileName){	
		var grid = grid_factory.get(db, table, {container:container});
		var action = grid.getAction();		
		return action.log_download(db, table, rowId, fileName);
	};
	
	this.log_delete = function(db, table, container, gridselector, rowId, fileName){	
		var grid = grid_factory.get(db, table, {container:container});
		var action = grid.getAction();		
		return action.log_delete(db, table, gridselector, rowId, fileName);
	};
	
	this.beginImportSubmit = function(e, db, table, iframe, uploaded_file){
		//可能file是空的，则不做任何处理
		if($('#' + uploaded_file).val() == '')
			return;
		$(e).val('Processing...').attr('disabled', true);
		$('#' + uploaded_file).attr('disabled', true);
		$(document.getElementById(iframe).contentWindow.document.body).html('Processing...');	
		return true;
	};
	
	this.endImportSubmit = function(e, db, table, iframe, uploaded_file){
		$(e).val('Upload').attr('disabled', false);
		$('#' + uploaded_file).attr('disabled', true);
		return true;
	}
	
}).apply(XT);
