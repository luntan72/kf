// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.xt;
	
	DB.zzvw_subgrid_cycle_detail = function(grid){
		$table.supr.call(this, grid);
	};

	var $parent = DB.zzvw_cycle_detail;
	var $table = DB.zzvw_subgrid_cycle_detail;
	var tool = new kf_tool();
	tool.extend($table, $parent);

	$table.prototype.buttonActions = function(action, options){
		var $this = this;
		var ret = true;
		
		var params = $this.getParams();
		var db = params.db, table = params.table, container = params.container;
		var gridSelector = params.gridSelector;
		var conditionSelector = params.conditionSelector;
		
		var selectedRows = $(gridSelector).getGridParam('selarrrow');
		var cycle_id = $(gridSelector).parents('.ui-tabs-panel').parent().children().find("#id").attr('value');

		var c_f = '';
		var element = '';
		var cell = new Array();
		if( typeof selectedRows != 'undefined' ){
			$.each(selectedRows, function(i, item){
				cell[i] = $(gridSelector).getCell(item, 'c_f');
			});
			c_f = JSON.stringify(cell);
			element = JSON.stringify(selectedRows);
		}
		//从前台传还是在后台取
		var oper = action;
		switch(action){
			case 'set_result':
				if (tool.checkSelectedRows(selectedRows, 1)){
					var url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/' + oper + '/parent/' + cycle_id;	
					var postData = {element: element, c_f: c_f};
					var dialogParams = {div_id: 'div_' + oper, width: 900, height: 400, title: 'Set Result', postData: postData};
					tool.actionDialog(dialogParams, url, undefined, function(data){
						$(gridSelector).trigger('reloadGrid');
						if(data){
							var datas = JSON.parse(data);
							var parent_id = $(gridSelector).parents('.ui-subgrid').prev().attr('id');
							$('#' + container.substring(0, container.lastIndexOf('_'))).setRowData(parent_id, datas.datas);
							var conditionSelector = '#cycle_stream_' + cycle_id + '_xt_zzvw_cycle_detail_stream_cond';
							$(conditionSelector + " strong").html(datas.statistics);
						}
					});
				}
				break;
			case 'set_build_result':
				if (tool.checkSelectedRows(selectedRows, 1)){
					var url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/' + oper + '/parent/' + cycle_id;	
					var postData = {element: element, c_f: c_f};
					var dialogParams = {div_id: 'div_' + oper, width: 400, height: 200, title: 'Set Build Result', postData: postData};
					tool.actionDialog(dialogParams, url, undefined, function(){
						$(gridSelector).trigger('reloadGrid');
					});
				}
				break;
			case 'set_tester':
				if (tool.checkSelectedRows(selectedRows, 1)){
					var url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/' + oper + '/condition/' + cycle_id + '/parent/' + cycle_id;	
					var postData = {element: element, cycle_id: cycle_id, c_f: c_f};
					var dialogParams = {div_id: 'div_' + oper, width: 400, height: 200, title: 'Set Testor', postData: postData};
					tool.actionDialog(dialogParams, url, undefined, function(){
						$(gridSelector).trigger('reloadGrid');
					});
				}
				break;
			case 'remove_case':
				if (tool.checkSelectedRows(selectedRows, 1)){
					var postData = {db: db, table: table, oper: oper, flag: '0', element: element, c_f: c_f, parent: cycle_id};
tool.debug(postData);
					// var removecase = function(){
						// $this.removecase(gridSelector, conditionSelector, postData);
					// }
					// removecase();
					$this.removecase(params, postData);
				}
				break;
			case 'set_crid':
				if (tool.checkSelectedRows(selectedRows, 1)){
					var url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/' + oper + '/parent/' + cycle_id;	
					var postData = {element: element, c_f: c_f};
					var dialogParams = {div_id: 'div_' + oper, width: 400, height: 200, title: 'Set CRID', postData: postData};
					tool.actionDialog(dialogParams, url, undefined, function(){
						$(gridSelector).trigger('reloadGrid');
					});
				}
				break;
			default:
				ret = $table.supr.prototype.buttonActions.call(this, action, options);							
		}
		return ret;					
	};
	
	$table.prototype.inputResult = function(id, gridSelector, divSelector, parent){
		var $this = this;
		var db = $this.getParams('db'), table = $this.getParams('table'), container = $this.getParams('container'),
			conditionSelector = '#cycle_stream_' + parent + '_xt_zzvw_cycle_detail_stream_cond';
		// conditionSelector.replace('_xt_zzvw_cycle_detail_stream_cond', '');
		var selectedValue = $(gridSelector + " " + divSelector).val();
		if (selectedValue > 1){
			$this.resultInfo(id, gridSelector, divSelector, selectedValue, parent);
		}
		else{
			var ids = new Array();
			ids[0] = id;
			var c_f = $(gridSelector).getCell(id, 'c_f');
			var content =$(divSelector + ' option[value="' + selectedValue +'"]').html(); 
			var postData = {db: db, table: table, parent: parent, oper: 'set_result', element: JSON.stringify(ids), select_item: selectedValue, c_f: JSON.stringify(c_f)};
			$.post('/jqgrid/jqgrid', postData, function(data){	
				if(data){
					var datas = JSON.parse(data);
					var parent_id = $(gridSelector).parents('.ui-subgrid').prev().attr('id');
					$(gridSelector).setRowData(id, datas.subData);
					$(conditionSelector + " strong").html(datas.statistics);
					$('#' + container.substring(0, container.lastIndexOf('_'))).setRowData(parent_id, datas.datas);
					// if($(gridSelector + "_" + id + "_t"))
						// $(gridSelector + "_" + id + "_t").trigger('reloadGrid');
				}
			});
		}
	}
	
	$table.prototype.resultInfo = function(id, gridSelector, divSelector, selectedValue, parent){
		var $this = this;
		var c_f = $(gridSelector).getCell(id, 'c_f');
		var db = $this.getParams('db'), table = $this.getParams('table'), container = $this.getParams('container'),
			conditionSelector = '#cycle_stream_' + parent + '_xt_zzvw_cycle_detail_stream_cond';
		var div_id = 'div_' + db + '_' + table + '_res_' + id;
		var resSelector = '#' + div_id;
		var oper = 'save_one';
		
		if(typeof selectedValue == 'undefined')
			var selVal =$(gridSelector + " " + divSelector).val();
		else
			var selVal = selectedValue;	
		var dialog_params = {
			ok: 'close', 
			open: function(){
				var submit_username = $.cookie('submit_username');
				var submit_password = $.cookie('submit_password');
				$(resSelector + ' #submit_username').val(submit_username);
				$(resSelector + ' #submit_password').val(submit_password);
				var formid = $(resSelector + ' form').attr('id');
				var hidden_frame_id = 'subgrid_log_hidden_frame';
				$(resSelector + ' #logfile').parent().append(
					"<iframe name='" + hidden_frame_id + "' id='" + hidden_frame_id + "' width='39%' height='20px'" + 
					" frameborder='0' scrolling='no' style='position:relative;float:right' marginheight='3'></iframe>");
				var data = "<input id='id' type='hidden' name='id' value='" + id + "'>" +
					"<input id='purpose' type='hidden' name='purpose' value='upload'>" +
					"<input id='cellName' type='hidden' name='cellName' value='logfile'>" ;
				$(resSelector + ' #' + formid).append(data);
				var eventData = {gridSelector: gridSelector, resSelector: resSelector, parent: parent, element: id};
				$(resSelector + ' input[name="submit_bug[]"]').unbind('change').bind('change', eventData, function(event){
					return $this.jiraInfo(event);
				});
				$(resSelector + " #save").unbind('click', save).bind('click', {dialog:this}, save);
				$(resSelector + " #cancel").unbind('click', cancel).bind('click', {dialog:this}, cancel);
			},
			close: function(){
				if(typeof selectedValue == 'undefined')
					$(gridSelector).setRowData(id, {result_type_id:0, id:id});
				$(this).remove();
			},
			gridId: gridSelector.substr(1),
			rowId: id,
			//div_id:'div_' + db + '_cycle_detail_result_' + id, 
			title: 'Result Information for cycle_detail', 
			height: 600,
			width: 900
		};
		var save =  function(event) {
			var inputs = tool.getAllInput(resSelector);
			var selVal = inputs['data'].result_type_id;
			var ids = new Array();
			var c_fs = new Array();
			ids[0] = id;
			c_fs[0] = c_f;
			if (inputs['passed'].length == 0){
				var postData = {db: db, table: table, oper: oper, element: JSON.stringify(ids), parent:parent, c_f: JSON.stringify(c_fs)};
				$.post('/jqgrid/jqgrid', $.extend(postData, inputs['data']), function(data, textStatus){
					if(data){
						var datas = JSON.parse(data);
						var parent_id = $(gridSelector).parents('.ui-subgrid').prev().attr('id');
						$(gridSelector).setRowData(id, datas.subData);
						$(conditionSelector + " strong").html(datas.statistics);
						$('#' + container.substring(0, container.lastIndexOf('_'))).setRowData(parent_id, datas.datas);
						// if($(gridSelector + "_" + id + "_t"))
							// $(gridSelector + "_" + id + "_t").trigger('reloadGrid');
					}
				});
			}
			else{
				alert(inputs['tips'].join('\n'));
			}
			$(event.data.dialog).dialog( "close" );
		};
		var cancel = function(event) {
			if(typeof selectedValue == 'undefined')
				$(gridSelector).setRowData(id, {result_type_id:0, id:id});
			$(event.data.dialog).dialog( "close" );
		};
		
		var postData = {db: db, table: table, oper: 'get_resultinfo', element: id,  parent:parent, result_type_id: selVal, c_f: c_f};
		$.post('/jqgrid/jqgrid', postData, function(data){						
			var mydialog = $('<div id="' + div_id + '" title="Result Information for cycle_detail"></div"').html(data).dialog(dialog_params);
			mydialog.dialog('open');
		});
	};
	
}());
