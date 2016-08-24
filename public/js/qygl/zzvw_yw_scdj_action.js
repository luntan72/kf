// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.qygl;

	DB.zzvw_yw_scdj = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.zzvw_yw_scdj;
	XT.extend($table, gc_grid_action);

	$table.prototype.information_open = function(divId, element_id, pageName, display_status){
		var $this = this;
		pageName = pageName || 'all';
		$table.supr.prototype.information_open.call(this, divId, element_id, pageName, display_status);
		if(display_status != 1) // NOT VIEW
			eventsBindForSCDJ(divId, this, false);
	};
	
	var eventsBindForSCDJ = function(divId, context, first){ //生产登记事件绑定
		var prefix = 'zzvw_pici_scdj', temp = '#' + divId + ' #' + prefix + '_temp';
		//先隐藏input_pici_id和input_pici_amount
		$('#' + divId + ' #ces_tr_input_pici_id').hide();
		$('#' + divId + ' #ces_tr_input_pici_amount').hide();
		
		//员工和工序互动
		$('#' + divId + ' #hb_id').bind('change', function(event){
			var hb_id = $(this).val();
			$.post('/jqgrid/jqgrid/oper/get_gx_by_hb/db/qygl/table/zzvw_yg/hb_id/' + hb_id, function(data){
				if(data != '0'){
					$('#' + divId +' #gx_id').val(data).trigger('change');
				}
			})
		});
		//工序和显示域的绑定，主要是分解工序要额外显示一些字段
		$('#' + divId + ' #gx_id').bind('change', function(event){
			var gx_id = $(this).val();
			//填充前置工序
			XT.bindOptions({url:'/jqgrid/jqgrid/oper/get_pre_gx/db/qygl/table/gx/', target:$('#' + divId + ' #pre_gx_id'),
				data:{gx_id:$(this).val()}, blankItem:false, currentVal:1});
			//调整缺陷列表
			XT.bindOptions({url:'/jqgrid/jqgrid/oper/linkage/db/qygl/table/zzvw_defect_gx/field/gx_id', target:$('#' + divId + ' #zzvw_pici_scdj_temp #defect_id'),
				data:{value:$(this).val()}, blankItem:true, currentVal:1});
			if(gx_id == '9'){ //分解工序
// XT.debug('divId = ' + divId + ", gx_id = " + gx_id);
				$('#' + divId + ' #input_pici_id_label').addClass('required');
				$('#' + divId + ' #input_pici_amount_label').addClass('required');
				$('#' + divId + ' #ces_tr_input_pici_id').show();
				$('#' + divId + ' #ces_tr_input_pici_amount').show();
				//填充可选的产品批次
				XT.bindOptions({url:'/jqgrid/jqgrid/oper/get_pici_list/db/qygl/table/zzvw_pici_scdj/', target:$('#' + divId + ' #input_pici_id'),
					data:{gx_id:$(this).val()}, blankItem:true, currentVal:1});
			}
			else{
				$('#' + divId + ' #input_pici_id_label').removeClass('required');
				$('#' + divId + ' #input_pici_amount_label').removeClass('required');
				$('#' + divId + ' #ces_tr_input_pici_id').hide();
				$('#' + divId + ' #ces_tr_input_pici_amount').hide();
				//填充可选的产品
				XT.bindOptions({url:'/jqgrid/jqgrid/oper/get_wz_list_by_gx/db/qygl/table/zzvw_pici_scdj/', target:$(temp + ' #wz_id'),
					data:{gx_id:$(this).val()}, blankItem:true, currentVal:1});
			}
		});

		//如果是分解产品工序，则input_pici_id决定了可用的产品
		$('#' + divId + ' #input_pici_id').bind('change', function(event){
			var option = $(this).find("option:selected"), unit_name = option.attr('unit_name'), remained = option.attr('remained');
			$('#' + divId + ' #input_pici_amount_post').html(unit_name);
			$('#' + divId + ' #input_pici_amount').val(remained);
			$('#' + divId + ' #input_pici_amount').attr('max', remained);
			$('#' + divId + ' #input_pici_amount').attr('placeholder', "最大不超过" + remained);
			XT.bindOptions({url:'/jqgrid/jqgrid/oper/get_wz_detail_for_fenjie/db/qygl/table/zzvw_pici_scdj/', target:$(temp + ' #wz_id'), data:{id:$(this).val()}, blankItem:true, currentVal:1});
		});
		//订单和计量单位及默认单价绑定
		var show_detail = function(){
			var gx_id = $('#' + divId + ' #gx_id').val(), wz_id = $(temp + ' #wz_id').val(), pre_gx_id = $(temp + ' #pre_gx_id').val() || 0,
				defect_id = $(temp + ' #defect_id').val() || 1, pici_id = $('#' + divId + ' #input_pici_id').val(), pici_amount = $('#' + divId + ' #input_pici_amount').val();
			$.post("/jqgrid/jqgrid/oper/get_wz_detail/db/qygl/table/wz/wz_id/" + wz_id + '/gx_id/' + gx_id + '/pre_gx_id/' + pre_gx_id + '/defect_id/' + defect_id + '/pici_id/' + pici_id + '/pici_amount/' + pici_amount, function(data){
// XT.debug(data);
				$(temp + ' #price').val(data.price);
				$(temp + ' #ck_weizhi_id').val(data.ck_weizhi_id);
				$(temp + ' #amount').attr('max', data.remained);
				$(temp + ' #amount').attr('placeholder', "最小为1，最大不超过" + data.remained);
				if(data.remained == 0 || data.remained == '0'){
					alert("选择的产品不满足生产条件");
				}
			}, 'json');
		};

		$(temp + ' #wz_id').unbind('change').bind('change', function(event){
			//关联可用的缺陷列表
			var option = $(this).find("option:selected"), unit_name = option.attr('unit_name'), gx_id = $('#' + divId + ' #gx_id').val();
			$(temp + ' #amount_post').html(unit_name);
			XT.bindOptions({url:'/jqgrid/jqgrid/oper/get_defect_list/db/qygl/table/wz/', target:$(temp + ' #defect_id'), data:{wz_id:$(this).val(), gx_id:gx_id}, blankItem:true, currentVal:1});
			show_detail();
		});
		
		$(temp + ' #defect_id').unbind('change').bind('change', function(event){
			show_detail();
		});
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
