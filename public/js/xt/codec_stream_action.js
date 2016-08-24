// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.xt;
	var tool = new kf_tool();
	
	DB.codec_stream = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.codec_stream;
	tool.extend($table, gc_grid_action);
	
	$table.prototype.getAutoCompleteField = function(){ // used by gc_grid_action.ready
		return [{input:'key', field:'code'}];
	};
	
	$table.prototype.getGridsForInfo = function(divId){
		var grids = [
			{tab:'test_history', container:'test_history', table:'zzvw_cycle_detail_stream', params:{real_table:'cycle_detail'}},
		];
		return grids;
	};
	
	$table.prototype.buttonActions = function(action, p){
		var $this = this;
		var ret = true;
		
		var params = $this.getParams();
		var db = params.db, table = params.table, container = params.container;
		var gridSelector = params.gridSelector;
		var conditionSelector = params.conditionSelector;
		
		var selectedRows = $(gridSelector).getGridParam('selarrrow');
		var element = JSON.stringify(selectedRows);
		var oper = action;
		switch(action){
			case 'set_supported_trickmodes':		
				if (tool.checkSelectedRows(selectedRows, 1)){
					var postData = {db: db, table: table, element: element, oper: oper};
					var addDelTrickMode = function(){
						return $this.setRemoveTrickMode(gridSelector, container, postData);
					}
					addDelTrickMode();
				}
				break;
			case 'remove_unsupported_trickmodes':		
				if (tool.checkSelectedRows(selectedRows, 1)){
					var postData = {db: db, table: table, element: element, oper: oper};
					var addDelTrickMode = function(){
						return $this.setRemoveTrickMode(gridSelector, container, postData);
					}
					addDelTrickMode();
				}
				break;
			default:
				ret = $table.supr.prototype.buttonActions.call(this, action, p);						
		}
		return ret;
	};
	
	$table.prototype.setRemoveTrickMode = function(gridSelector, refeshTab, postData){
		var $this = this;
		var target_table = 'testcase';
		var div_id = 'div_stream_action';

		var target_grid = grid_factory.get(postData.db, target_table, {container: div_id});
		target_grid.ready();
		var target_gridSelector = target_grid.getParams('gridSelector');
		
		var fun_stream_action = function(){
			var actions = $(target_gridSelector).getGridParam('selarrrow');
			postData = $.extend(true, postData, {actions: JSON.stringify(actions)});
			
			$.post('/jqgrid/jqgrid', postData, 
				function(data, status){
					if('remove_unsupported_trickmodes' == postData.oper && data == 'success')
						alert("Remove Actions Successfully!!!");
					else if('set_supported_trickmodes' == postData.oper && data == 'success')
						alert("Set Actions Successfully!!!");
				}
			);
		};		
		var url = '/jqgrid/index/db/'+ postData.db + "/table/" + target_table + "/container/" + div_id;
		var dialog_params = {
			div_id: div_id,
			height:600,
			width: 1024,
			modal: true,
			autoOpen:false,
			close: function(){$(this).remove();},
			open: function(){
				var grid = grid_factory.get(postData.db, target_table, {container: div_id});
				grid.ready();
				// var view_edit_selector = "#view_edit_" + parent + " #div_view_edit"
				var conditionSelector = grid.getParams('conditionSelector');
				var advancedButtonSelector = grid.getParams('advancedButtonSelector');
				var advancedCheckbox = 'div' + conditionSelector + ' ' + advancedButtonSelector;
				var advancedDiv = 'div' + conditionSelector + ' ' + conditionSelector + '_advanced';				
				// var prj_id = $(view_edit_selector + " #prj_ids").attr('value');
				// var testcase_type_id = $(view_edit_selector + " #testcase_type_ids").attr('value');		
				// if(prj_id != undefined)
					// $(conditionSelector + " #prj_id").attr('value', prj_id).attr('disabled', true);
				// if(testcase_type_id != undefined)
					// $(conditionSelector + " #testcase_type_id").attr('value', testcase_type_id).attr('disabled', true);
				$(conditionSelector + " #isactive").attr('value', '1').attr('disabled',true);
				//test_module:uniformed_trimodes
				$(conditionSelector + " #testcase_module_id").attr('value', '507').attr('disabled',true);		
				//var url = '/jqgrid/jqgrid/oper/getCaseModule/db/' + postData.db + '/table/' + postData.table;
				// $this.setLinkage('div' + conditionSelector, ['select#testcase_type_id'], {testcase_type_id: testcase_type_id, field: testcase_type_id});
			}
		};
		if('set_supported_trickmodes' == postData.oper){
			dialog_params.buttons = {
				"Set": function() {
					fun_stream_action();
					$(this).dialog( "close" );
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			};
		}
		else if('remove_unsupported_trickmodes' == postData.oper){
			dialog_params.buttons = {
				"Remove": function() {
					fun_stream_action();
					$(this).dialog( "close" );
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			};
		}

		$.get(url, function(data){
			var mydialog = $('<div id="' + div_id + '" title="Set or Remove action for stream?"></div"').html(data).dialog(dialog_params);
			mydialog.dialog('open');
		})
// alert("test test");
	};

}());
