// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.qygl;

	DB.zzvw_yw_yunshu = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.zzvw_yw_yunshu;
	XT.extend($table, gc_grid_action);

	$table.prototype.information_open = function(divId, element_id, pageName, display_status){
		var $this = this;
		pageName = pageName || 'all';
		$table.supr.prototype.information_open.call(this, divId, element_id, pageName, display_status);
		if(display_status != 1) // NOT VIEW
			eventsBindForYunshu(divId, this, false);
	};
	
	var eventsBindForYunshu = function(divId, context, first){ //订单类事件绑定
		//交易方和物资之间绑定
		var btn, temp, yw_fl_id = parseInt($('#' + divId + ' #yw_fl_id').val());
		switch(yw_fl_id){
			case 2: //收货
				btn = 'zzvw_yw_sh_detail';
				break;
			case 6: //发货
				btn = 'zzvw_yw_fh_detail';
				break;
			case 10: //退货
				btn = 'zzvw_yw_th_detail';
				break;
			case 11: //接收退货
				btn = 'zzvw_yw_jth_detail';
				break;
		}
		temp = '#' + divId + ' #' + btn + '_temp';
		var target = [
			{
				selector:temp + ' #dingdan_id', 
				type:'select', 
				field:'dingdan_id', 
				url:'/jqgrid/jqgrid/oper/get_dingdan_by_hb/db/qygl/table/zzvw_dingdan/yw_fl_id/' + yw_fl_id + '/status/1' //正在执行中的订单
			}
		];
XT.debug($(temp + ' #hb_id'));		
		XT.linkage({selector:temp + ' #hb_id'}, target);
		//订单和计量单位及默认单价绑定
		$(temp + ' #dingdan_id').unbind('change').bind('change', function(event){
			var option = $(this).find("option:selected"), unit_name = option.attr('unit_name'), wz_id = option.attr('wz_id'), 
				dingdan_amount = option.attr('amount'), dingdan_completed = option.attr('completed_amount'), 
				dingdan_remained = dingdan_amount - dingdan_completed;
// XT.debug([default_price, unit_name, remained, temp]);
			$(temp + ' #happen_amount_post,#amount_post,#dingdan_amount_post,#dingdan_completed_amount_post,#pici_remained_post,#dingdan_remained_post').html(unit_name);
			$(temp + ' #wz_id').val(wz_id);
			$(temp + ' #dingdan_amount').val(dingdan_amount);
			$(temp + ' #dingdan_remained').val(dingdan_remained);
			$(temp + ' #dingdan_completed_amount').val(dingdan_completed);
			//获取批次信息
			$.post('/jqgrid/jqgrid/db/qygl/table/zzvw_pici/oper/get_pici_by_dingdan_for_yunshu', {dingdan_id:$(this).val(), wz_id:wz_id, yw_fl_id:yw_fl_id}, function(data){
				$(temp + ' #pici_id').find('option').remove();
				XT.generateOptions($(temp + ' #pici_id'), data, 'id', 'name', true);
			}, 'json');
		});
		
		$(temp + ' #pici_id').unbind('change').bind('change', function(event){
			var option = $(this).find("option:selected"), pici_amount = option.attr('amount') || 0, 
				pici_remained = option.attr('remained') || 0, pici_defect_id = option.attr('defect_id') || 0;
// XT.debug([pici_remained, temp, yw_fl_id]);
			$(temp + ' #pici_remained').val(pici_remained);
			$(temp + ' #pici_defect_id').val(pici_defect_id);
			switch(yw_fl_id){
				case 2: //收货
					$(temp + ' #happen_amount').removeAttr('max');
					break;
				case 6: //发货
				case 10://退货
					$(temp + ' #happen_amount').attr('max', pici_remained);
					break;
				case 11://接退货
					$(temp + ' #happen_amount').attr('max', pici_amount - pici_remained);
					break;
			}
		});
		$(temp + ' #' + btn + '_add').unbind('click').bind('click', function(){
			//计算重量
			var unit_id = parseInt($(temp + ' #wz_id').find("option:selected").attr('unit_id')), tj = parseFloat($(temp + ' #wz_id').find("option:selected").attr('tj')), 
				wz_id = parseInt($(temp + ' #wz_id').val()), 
				sl = parseInt($(temp + ' #amount').val()),
				weight = parseFloat($('#' + divId + ' #weight').val());
			//可能涉及单位转换
			if(unit_id == 18){ //吨
				$('#' + divId + ' #weight').val(weight + sl);
			}
			else if(unit_id == 19){ //个
				$('#' + divId + ' #weight').val(weight + sl * tj * 7.9); //默认钢材，包括不锈钢的密度为7.8g/cm3，转换为吨
			}
		});
		
		// //数量、单价和总价之间绑定
		// var auto_generate_total = function(){
			// var $source = [temp + ' #amount', temp + ' #price'];
			// var $dest = temp + ' #total_money';
			// XT.auto_fill_calc_result($dest, $source, '*', 2);
		// }
		// $(temp + ' #amount').bind('keyup', auto_generate_total);
		// $(temp + ' #price').bind('keyup', auto_generate_total);
	};
}());
