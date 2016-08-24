// 资金进出管理
(function(){
	var DB = KF_GRID_ACTIONS.qygl;

	DB.zzvw_ck_pd = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.zzvw_ck_pd;
	XT.extend($table, gc_grid_action);

	$table.prototype.information_open = function(divId, element_id, pageName, display_status){
		var $this = this;
		pageName = pageName || 'all';
		$table.supr.prototype.information_open.call(this, divId, element_id, pageName, display_status);
		if(display_status != 1) // NOT VIEW
			eventsBindForCK_PD(divId, this, false);
	};
	
	var eventsBindForCK_PD = function(divId, context, first){ //仓库盘点事件绑定
// XT.debug(divId);
		var getPiciList = function(){
			var gx_id = $('#' + divId + ' #gx_id').val(), 
				wz_id = $('#' + divId + ' #wz_id').val(), 
				defect_id = $('#' + divId + ' #defect_id').val();
			$.post('/jqgrid/jqgrid/oper/get_pici_list_by_gx_wz_defect/db/qygl/table/pici', {gx_id:gx_id, wz_id:wz_id, defect_id:defect_id}, function(data){
// XT.debug(data);				
				$('#' + divId + ' #pici_id').find('option').remove();
				XT.generateOptions($('#' + divId + ' #pici_id'), data, 'id', 'name', true);
			}, 'json');
		};
		$('#' + divId + ' #gx_id').bind('change', function(event){
			getPiciList();
		})
		$('#' + divId + ' #wz_id').bind('change', function(event){
			getPiciList();
		})
		$('#' + divId + ' #defect_id').bind('change', function(event){
			getPiciList();
		})
		$('#' + divId + ' #pici_id').bind('change', function(event){
			var option = $(this).find('option:selected'), remained = option.attr('remained'), unit_name = option.attr('unit_name');
			$('#' + divId + ' #expected_amount').val(remained);
			$('#' + divId + ' #amount').val(remained);
			$('#' + divId + ' #expected_amount_post').html(unit_name);
			$('#' + divId + ' #amount_post').html(unit_name);
		})
		
	};
}());
