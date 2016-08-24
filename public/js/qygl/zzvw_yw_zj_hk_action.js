// 资金进出管理
(function(){
	var DB = KF_GRID_ACTIONS.qygl;

	DB.zzvw_yw_zj_hk = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.zzvw_yw_zj_hk;
	XT.extend($table, gc_grid_action);

	$table.prototype.information_open = function(divId, element_id, pageName, display_status){
		var $this = this;
// XT.debug(divId);		
		var showFields = function(){
			var zj_fl_id = $('#' + divId + ' #zj_fl_id').val();
			var disp_fields = [], hidden_fields = [];

			if(zj_fl_id == undefined || zj_fl_id == '1' || zj_fl_id == 0){ //现金
				hidden_fields.push('code');
				hidden_fields.push('expire_date');
			}
			else{
				disp_fields.push('code');
				disp_fields.push('expire_date');
			}
			for(var i in disp_fields){
				if(display_status != 1){
					$('#' + divId + ' #' + disp_fields[i]).addClass('required');
					$('#' + divId + ' #' + disp_fields[i] + '_label').addClass('required');
				}
				$('#' + divId + ' #ces_tr_' + disp_fields[i]).show();
			}
			for(var i in hidden_fields){
				$('#' + divId + ' #ces_tr_' + hidden_fields[i]).hide();
				$('#' + divId + ' #' + hidden_fields[i]).removeClass('required');
				$('#' + divId + ' #' + hidden_fields[i] + '_label').removeClass('required');
			}
		};
		var eventsBindForZJ_HK = function(divId, context, first){ //资金进出事件绑定
			$('#' + divId + ' #zj_fl_id').bind('change', function(event){
				showFields();
				//根据资金类型，确定账户类型
				$.post('/jqgrid/jqgrid/oper/get_zjzh_by_zj_fl/db/qygl/table/zjzh', {zj_fl_id:$(this).val()}, function(data){
					$('#' + divId + ' #zjzh_id').find('option').remove();
					XT.generateOptions($('#' + divId + ' #zjzh_id'), data, 'id', 'name', false);
				}, 'json');
			});
			$('#' + divId + ' #hb_id').bind('change', function(event){
				var hb_id = $(this).val(), account_receivable = 0;
				if(hb_id != 0){
					$.post('/jqgrid/jqgrid/oper/get_info/db/qygl/table/hb', {id:$(this).val()}, function(data){
						$('#' + divId + ' #account_receivable').val(data);
					});
				}
			});
		};
		
		pageName = pageName || 'all';
		$table.supr.prototype.information_open.call(this, divId, element_id, pageName, display_status);
		if(display_status != 1) // NOT VIEW
			eventsBindForZJ_HK(divId, this, false);
		showFields();
	};
	
	$table.prototype.buttonActions = function(action, p){
		p = p || {};
		var db = this.getParams('db'), table = this.getParams('table');
		var $this = this;
		var gridId = this.getParams('gridSelector');
		var selectedRows = $(gridId).getGridParam('selarrrow');
		var element = JSON.stringify(selectedRows);
		switch(action){
			case 'jh': //重启订单
				if (XT.checkSelectedRows(selectedRows, 1)){
					// 询问是否真的重启该订单，如果是，则修改订单状态，否则，不动
					var buttons = {
						'重启订单':function(){
							var dialog = this;
							$.post('/jqgrid/jqgrid/oper/change_status/db/qygl/table/yw_cg', {id:element, status:'jh'}, function(data){
	// XT.debug(data);
								$(dialog).dialog('close');
								$(gridId).trigger('reloadGrid');
							}, 'json');
						},
						'放弃':function(){
							$(this).dialog('close');
						}
					};
					XT.optionsDialog('真的要重启本订单？', '重启订单', buttons, 300, 200);
				}
				break;
					
			case 'js': //结束订单
				if (XT.checkSelectedRows(selectedRows, 1)){
					//询问是否真的已经完成，如果是，则修改订单状态，否则，不动
					var buttons = {
						'结束订单':function(){
							var dialog = this;
							XT.debug(dialog);
							$.post('/jqgrid/jqgrid/oper/change_status/db/qygl/table/yw_cg', {id:element, status:'jieshu'}, function(data){
	// XT.debug(data);
								$(dialog).dialog('close');
								$(gridId).trigger('reloadGrid');
							}, 'json');
						},
						'放弃':function(){
							$(this).dialog('close');
						}
					};
					XT.optionsDialog('结束选中的订单？', '结束订单', buttons, 300, 200);
				}
				break;
					
			case 'genzong':
					//跟踪包括大量内容，比如运输，入库，生产等
					
				break;
					
			default:
				$table.supr.prototype.buttonActions.call(this, action, p);
		}
	};
}());
