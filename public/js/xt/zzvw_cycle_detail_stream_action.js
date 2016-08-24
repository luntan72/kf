// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.xt;
	
	DB.zzvw_cycle_detail_stream = function(grid){
		$table.supr.call(this, grid);
	};
	var $parent = DB.zzvw_cycle_detail;
	var $table = DB.zzvw_cycle_detail_stream;
	var tool = new kf_tool();
	tool.extend($table, $parent);

	$table.prototype.ready = function(base){
		gc_grid_action.prototype.ready.call(this, base);
		var conditionSelector = this.getParams('conditionSelector');
		this.setLinkage('div' + conditionSelector, ['select#codec_stream_type_id']);
	};
	
	$table.prototype.getPostDataForSubgrid = function(){
		var conditionSelector = $this.getParams('conditionSelector');
		var cycle_id = $(conditionSelector).parent().parent().children().find("#id").attr('value');
		var postData = {};
		postData['parent'] = cycle_id;
		return postData;
	}
		
	$table.prototype.addCase = function(params){
		$this = this;
		var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/add_case/parent/' + params.parent;	
		var div_id = 'div_addcase_stream_req';
		var dialogParams = {
			div_id: div_id, 
			width: 600, 
			height: 200, 
			title: 'Select Case Add Type',
			close: function(){$(this).remove();},
			buttons: {
				Ok: function(){
					var type = tool.getAllInput('div#' + div_id)['data']['case_add_type'];
					switch(type){
						case 'stream_list':
							$this.addFromStreamCycle(params);
							$(this).dialog( "close" );
							break;
						case 'cycle_list':
							$this.addFromCycle(params);
							$(this).dialog( "close" );
							break;
					}
				},
				Cancel:function(event, ui){
					$(this).remove();
				}
			}
		};
		tool.actionDialog(dialogParams, url);
	}
	
	$table.prototype.gridComplete = function(){
	};
	
	$table.prototype.addFromCycle = function(params){
		var $this = this;
		var db = params.db;
		var table = params.table;
		var div_id = 'div_new_case_add';
	
		var dialog_params = {
			div_id: div_id,
			title: 'Add Case From Other Cycel To Current Cycle',
			height:600,
			width: 1120,
			modal: true,
			autoOpen:false,
			close: function(){$(this).remove();},
			open: function(){
				var grid = grid_factory.get(db, table, {container: div_id});
				var divCond = 'div' + grid.getParams('conditionSelector');
				var prj_id = $(params.tabSelector + ' #prj_ids').val();
				var os_id = $(params.tabSelector + ' #os_id').val();
				var chip_id = $(params.tabSelector + ' #chip_id').val();
				var board_type_id = $(params.tabSelector + ' #board_type_id').val();
				var testcase_type_id = $(params.tabSelector + ' #testcase_type_ids').val();
				// $(divCond + " #os_id").attr('value', os_id).attr('disabled', true);
				if(testcase_type_id != undefined)
					$(divCond + " #testcase_type_id").attr('value', testcase_type_id).attr('disabled', true);
				if(prj_id != undefined){
					$(divCond + " #prj_id").attr('value', prj_id);
				}
				if(os_id != undefined)
					$(divCond + " #os_id").attr('value', os_id);
				if(chip_id != undefined)
					$(divCond + " #chip_id").attr('value', chip_id);
				if(board_type_id != undefined)
					$(divCond + " #board_type_id").attr('value', board_type_id);
				var source = ['select#os_id', 'select#board_type_id', 'select#chip_id', 'select#prj_id', 'select#creater_id', 'select#cycle_id', 'select#codec_stream_type_id', 'select#codec_stream_format_id'];
				$this.setLinkage(divCond, source, true);
				$(divCond + " #query_add").remove();
				$(divCond + " #query_remove").remove();
			}
		};
		// process button
		var buttonParams = {db: db, target_table: table, table: table, container: dialog_params.div_id, 
				refeshTab: params.refeshTab, parent: params.parent, tabSelector: params.tabSelector, flag: 1};
		dialog_params['buttons'] = $this.addCaseButtons(buttonParams);
		// enable dialog
		var url = '/jqgrid/index/db/' + db + '/table/' + table + '/container/' + dialog_params.div_id + "/parent/" + params.parent;
		var dialog_params = $.extend(true, dialog_params, {html_type:'url', text:url});	
		return tool.actionDialog(dialog_params, url);	
	};
	
	$table.prototype.addFromStreamCycle = function(params){
		var db = params.db;
		// var table = 'zzvw';
		var table = params.table;//'zzvw_testcase_ver';	
		var target_table = 'codec_stream';
		var div_id = 'div_case_add_for_codec';
		
		var dialog_params = {
			div_id: 'div_case_add_for_codec',
			title: 'Add Case From Stream List And TrickModes To Current Cycle',
			height:600,
			width: 1024,
			modal: true,
			autoOpen:false,
			close: function(){$(this).remove();},
			open: function(data){
				$('#' + div_id + " #testcase_type_ids").attr("disabled", true);
				//$('#' + div_id + " #prj_ids").attr("disabled", true);
				var url = '/jqgrid/index/db/' + db + '/table/' + target_table + '/container/' + div_id + '/parent/' + params.parent;
				$.get(url, function(datas){	
					$("#" + div_id).append(datas);
				});
			}
		};
		
		var gridSelector_t = '#' + dialog_params.div_id + '_' + db + '_' + target_table + '_list';
		var detailSelector = params.tabSelector + '_' + db + '_' + table + '_list';	
		var fun_addcase = function(replaced){
			var inputs = tool.getAllInput('#' + dialog_params.div_id);
			if (inputs['passed'].length == 0){
				var selectedRows = $(gridSelector_t).getGridParam('selarrrow');
				var postData = {
					db: db, 
					table: table, 
					oper: 'add_case_stream', 
					element: JSON.stringify(selectedRows), 
					testcase_id: JSON.stringify(inputs['data']['testcase_id']),
					test_env_id: inputs['data']['test_env_id'],
					prj_ids: inputs['data']['prj_ids'],
					testcase_type_ids: inputs['data']['testcase_type_ids'],
					parent: params.parent,
					replaced: replaced
				};
			
				$.post('/jqgrid/jqgrid', postData, function(data){
					if(data == 'done'){
						var grid = grid_factory.get(db, table, {container: params.refeshTab, parent: params.parent});
						var filters = JSON.stringify({groupOp:'AND', rules:[{"field":"cycle_id","op":"eq","data":params.parent}]});
						// detail_grid.setParams($.extend(true, params, {p_id:rowId, parent:rowId, filters:filters}));
						grid.indexInDiv({filters:filters});
						alert("Add Successfully");
					}
					else
						alert("Add Error");
				});
			}
			else{
				alert(inputs['tips'].join('\n'));
			}
		};
		dialog_params['buttons'] = {
			"Add & Replace Stream-Action": function() {
				fun_addcase(1);
				//$(this).dialog( "close" );
			},
			"Add & NOT-Replace Stream-Action": function() {
				fun_addcase(0);
				//$(this).dialog( "close" );
			},
			Cancel: function() {
				$(this).dialog( "close" );
			}
		};
		var url = "/jqgrid/jqgrid/db/" + db + "/table/" + table + "/oper/add_case_stream/parent/" + params.parent;
		var dialog_params = $.extend(true, dialog_params, {html_type:'url', text:url});	
		return tool.actionDialog(dialog_params, url);	
	};
	
	$table.prototype.setLinkage = function(divSelector, sources, isSpecial){
		var $this = this;
		var db = $this.getParams('db'), table = $this.getParams('table'), parent = $this.getParams('parent');
		if(parent == undefined)
			parent = 0;
		sources = sources || ['select#os_id', 'select#board_type_id', 'select#chip_id'];
		var os_chip_board_type = {os_id:divSelector + ' select#os_id', chip_id:divSelector + ' select#chip_id', board_type_id:divSelector + ' select#board_type_id'};
		for(var i in sources){
			var isBind = true;
			switch(sources[i]){
				case 'select#os_id':
					var target = [{selector:divSelector + ' select#prj_id', type:'select', field:'os_id', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#chip_id', type:'select', field:'os_id', url:'/jqgrid/jqgrid/oper/getchip/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#board_type_id', type:'select', field:'os_id', url:'/jqgrid/jqgrid/oper/getboardtype/db/' + db + '/table/zzvw_prj'}];
					var srcDiv = {selector:divSelector + ' select#os_id'};
					var params = {selector:os_chip_board_type};
					isBind = false;
					break;
				case 'select#board_type_id':
					var target = [{selector:divSelector + ' select#prj_id', type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#chip_id', type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/getchip/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#os_id', type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/getos/db/' + db + '/table/zzvw_prj'}];
					var srcDiv = {selector:divSelector + ' select#board_type_id'};
					var params = {selector:os_chip_board_type};
					isBind = false;
					break;
				case 'select#chip_id':
					var target = [{selector:divSelector + ' select#prj_id', type:'select', field:'chip_id', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#board_type_id', type:'select', field:'chip_id', url:'/jqgrid/jqgrid/oper/getboardtype/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#os_id', type:'select', field:'chip_id', url:'/jqgrid/jqgrid/oper/getos/db/' + db + '/table/zzvw_prj'}];
					var srcDiv = {selector:divSelector + ' select#chip_id'};
					var params = {selector:os_chip_board_type};
					isBind = false;
					break;
				case 'select#prj_id':	
					var target = [{selector:divSelector + ' select#cycle_id', field:'prj_id', type:'select', url:'/jqgrid/jqgrid/oper/get_linkage/dst/cycle/db/' + db + '/table/' + table + '/parent/' + parent}];
					var srcDiv = {selector:divSelector + ' select#prj_id'};
					// var params = {selector:{prj_id:divSelector + ' select#prj_id'}};
					var params = {};
					break;
				case 'select#cycle_id':
					//var module_priority_testor = {testcase_module_id:divSelector + ' select#testcase_module', testcase_priority_id:divSelector + ' select#testcase_priority', tester_id:divSelector + ' select#tester_id'};
					var target = [{selector:divSelector + ' select#codec_stream_format_id', type:'select', url:'/jqgrid/jqgrid/oper/get_linkage/dst/module/db/' + db + '/table/' + table + '/parent/' + parent},
						{selector:divSelector + ' select#tester_id', type:'select', url:'/jqgrid/jqgrid/oper/getTesters/db/' + db + '/table/zzvw_cycle'},
						{selector:divSelector + ' select#codec_stream_type_id', type:'select', url:'/jqgrid/jqgrid/oper/get_linkage/dst/stream_type/db/' + db + '/table/' + table + '/parent/' + parent},
						];
					var srcDiv = {selector:divSelector + ' select#cycle_id'};
					var params = {};
					if(isSpecial != undefined && isSpecial == true){
						params = {selector:{prj_id:divSelector + ' select#prj_id'}};
						target.push({selector:divSelector + ' select#creater_id', field:'prj_id', type:'select', url:'/jqgrid/jqgrid/oper/get_linkage/dst/creater/db/' + db + '/table/' + table + '/parent/' + parent});
					}	
					break;
				case 'select#codec_stream_type_id':
					var target = [{selector:divSelector + ' select#codec_stream_format_id', type:'select', field:'codec_stream_type_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/stream_format/db/xt/table/' + table + '/parent/' + parent}];
					var srcDiv = {selector:divSelector + ' select#codec_stream_type_id'};
					var params = {};
					break;
				case 'select#creater_id':
					var target = [{selector:divSelector + ' select#cycle_id', type:'select', field:'creater_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/creater_cycle/db/' + db + '/table/' + table + '/parent/' + parent}];
					var srcDiv = {selector:divSelector + ' select#creater_id'};
					var params = {selector:{prj_id:divSelector + ' select#prj_id', os_id:divSelector + ' select#os_id', chip_id:divSelector + ' select#chip_id', board_type_id:divSelector + ' select#board_type_id'}};
					break;	
				case 'select#testcase_module_id':
					var target = [{selector:divSelector + ' select#testcase_testpoint_id', type:'select', field:'testcase_module_id', url:'/jqgrid/jqgrid/oper/linkage/db/xt/table/testcase_testpoint'}];
					var srcDiv = {selector:divSelector + ' select#testcase_module_id'};
					var params = {};
					break;
				case 'select#testcase_type_id':
					var target = [{selector:divSelector + ' select#testcase_module_id', type:'select', field:'testcase_type_ids', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/testcase_module'}];
					var srcDiv = {selector:divSelector + ' select#testcase_type_id'};
					var params = {};
					if(isSpecial != undefined && isSpecial == true){
						target = [{selector:divSelector + ' select#testcase_module_id', type:'select', field:'testcase_type_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/testcase_module/db/' + db + '/table/' + table}];
					}
					break;
				case 'select#jira_project':
					var target = [{selector:divSelector + ' select#jira_components', type:'select', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/get_jira_components/db/' + db + '/table/' + table},
						{selector:divSelector + ' select#jira_versions', type:'select', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/get_jira_vers/db/' + db + '/table/' + table}
						];
					var srcDiv = {selector:divSelector + ' select#jira_project'};
					var params = {selector:{submit_username:divSelector + ' input#submit_username', submit_password:divSelector + ' input#submit_password'}};
					//var value = 'undefined';
					break;	
			}
			if(target != 'undefined'){
				if(isSpecial != "undefined" && isSpecial == true && isBind == true)
					tool.linkage(srcDiv, target, params, false);
				else
					tool.linkage(srcDiv, target, params);
			}
		}
	};
}());
