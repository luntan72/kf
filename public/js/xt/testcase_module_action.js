(function(){
	var DB = KF_GRID_ACTIONS.xt;
	
	DB.testcase_module = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.testcase_module;
	var tool = new kf_tool();
	tool.extend($table, gc_grid_action);
	
	$table.prototype.buttonActions = function(action, p){
		var $this = this;
		var ret = true;
		var params = $this.getParams();
		var db = params.db;
		var table = params.table;
		var gridSelector = params.gridSelector;
		var selectedRows = $(gridSelector).getGridParam('selarrrow');
		var element = JSON.stringify(selectedRows);
		var postData = {db: db, table: table, element: element};	
		switch(action){
			case 'export':
				if (tool.checkSelectedRows(selectedRows, 1)){
					var hidden = $this.getParams('hiddenSelector'); 
					var div_id = 'export_options';
					var sqls = $(hidden + " #sqls").val();
					var dialog_params = {
						div_id:div_id,
						width:600,
						height:400,
						title:'Export',
						postData:{element: element, sqls: sqls},
						open:function(){
							$('#export_options #div_for_report_options').hide();
							$("#export_options #div_for_report_options #cart_add_prj_ids").attr('required', false);
							$("#export_options #div_for_report_options #cart_reset_prj_ids").attr('required', false);
							$("#export_options #div_for_report_options #cart_clear_prj_ids").attr('required', false);
							$('#export_options input:radio[name="export_type"]').change(function(event){
								if ($(this).is(':checked')){
									switch($(this).val()){
										case 'excel_testplan':
											// if()
											$('#export_options #div_for_report_options').show();
											$("#export_options #div_for_report_options #cart_add_prj_ids").attr('required', true);
											$("#export_options #div_for_report_options #cart_reset_prj_ids").attr('required', true);
											$("#export_options #div_for_report_options #cart_clear_prj_ids").attr('required', true);
											break;
										default:
											$('#export_options #div_for_report_options').hide();
											$("#export_options #div_for_report_options #cart_add_prj_ids").attr('required', true);
											$("#export_options #div_for_report_options #cart_reset_prj_ids").attr('required', true);
											$("#export_options #div_for_report_options #cart_clear_prj_ids").attr('required', true);
											break;
									}
								}
							});
						}
					};
					var url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/export';
					tool.actionDialog(dialog_params, url, undefined, function(data){
						 location.href = "/download.php?filename=" + encodeURIComponent(data) + "&remove=1";
					});
				}
				break;
			default:
				ret = $table.supr.prototype.buttonActions.call(this, action, p);						
		}
		return ret;
	};
}())