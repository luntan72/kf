// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.qygl;
	
	DB.wz = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.wz;
	XT.extend($table, gc_grid_action);

	$table.prototype.contextActions = function(action, el){
// XT.debug(action);
		return $table.supr.prototype.contextActions.call(this, action, el);
	};
	
	$table.prototype.information_open = function(divId, element_id, pageName, display_status){
		// display_status = display_status;
		pageName = pageName || 'all';
		$table.supr.prototype.information_open.call(this, divId, element_id, pageName);
		var hidefields = function(){
			var wz_fl_id = parseInt($('#' + divId + ' #wz_fl_id').val()), hide_fields = [], disp_fields = [];
			if(display_status == 1){ //view
				var zuhe = parseInt($('#' + divId + ' #zuhe').val());
			}
			else
				var zuhe = parseInt($('#' + divId + ' [name="zuhe"]:checked').val());
				
		// $this->options['edit'] = array('wz_fl_id', 'name', 'unit_fl_id'=>array('label'=>'计量单位类型'), 
			// 'unit_id', 'default_price', 
			// 'min_kc', 'max_kc', 'pd_days', 'pd_last', 'jy_days', 'wh_days',
			// 'midu', 'tj', 'bmj', 'zuhe', 'wz_cp_zuhe', 'jszb_wz', 'gys_id', 'kh_id', 'pic', 'note', 'isactive'
		// );
// XT.debug([display_status, wz_fl_id, zuhe]);
			switch(wz_fl_id){
				case 0: //没有选中
					hide_fields = ['pd_last', 'jy_days', 'wh_days', 'gx_wz',
						'midu', 'tj', 'bmj', 'cp', 'youxiaobili', 'zuhe', 'wz_cp_zuhe', 'jszb_wz', 'muju', 'wz_sb', 'gys_id', 'kh_id', 'pic', 'tuzhi'];
					// disp_fields = [];
					break;
				case 3: //产品
					disp_fields = ['jy_days', 'tj', 'bmj', 'cp', 'youxiaobili', 'zuhe', 'jszb_wz', 'gx_wz', 'kh_id', 'pic'];
					hide_fields = ['wz_sb', 'midu', 'wh_days', 'gys_id'];
					if(zuhe == 2){ //是组合产品
						disp_fields.push('wz_cp_zuhe');
						hide_fields.push('muju', 'youxiaobili', 'cp');
					}
					else{ //单个零件
						disp_fields.push('muju', 'cp', 'youxiaobili', 'tuzhi');
						hide_fields.push('wz_cp_zuhe');
					}
					break;
				case 2: //设备
					disp_fields = ['wz_sb', 'pd_last', 'wh_days', 'gys_id'];
					hide_fields = ['gx_wz', 'midu', 'tj', 'bmj', 'cp', 'youxiaobili', 'zuhe', 'wz_cp_zuhe', 'jszb_wz', 'muju', 'jy_days', 'kh_id', 'tuzhi'];
					break;
				case 7: //维修用品
					disp_fields = ['gx_wz', 'pd_last', 'wh_days', 'gys_id'];
					hide_fields = ['midu', 'tj', 'bmj', 'cp', 'youxiaobili', 'zuhe', 'wz_cp_zuhe', 'jszb_wz', 'muju', 'wz_sb', 'jy_days', 'kh_id', 'tuzhi'];
					break;
				case 5: //劳保用品
				case 6: //办公用品
					hide_fields = ['midu', 'tj', 'bmj', 'cp', 'youxiaobili', 'zuhe', 'wz_cp_zuhe', 'jszb_wz', 'muju', 'wz_sb', 'wh_days', 'kh_id', 'tuzhi'];
					disp_fields = ['pd_last', 'gx_wz', 'jy_days', 'gys_id'];
					break;
				case 1: //原材料
					hide_fields = ['tj', 'bmj', 'cp', 'youxiaobili', 'zuhe', 'wz_cp_zuhe', 'jszb_wz', 'muju', 'wz_sb', 'wh_days', 'kh_id', 'tuzhi'];
					disp_fields = ['gx_wz', 'pd_last', 'jy_days', 'midu', 'gys_id'];
					break;
				case 9: // 其他
				case 4: //服务
				case 8: //能源
				default:
					hide_fields = ['midu', 'tj', 'bmj', 'cp', 'youxiaobili', 'zuhe', 'wz_cp_zuhe', 'wh_days', 'kh_id', 
						'jszb_wz', 'pd_last', 'jy_days', 'muju', 'wz_sb', 'wh_days', 'pic', 'gx_wz', 'tuzhi'];
					disp_fields = ['gys_id'];
					// hide_fields = ['tj', 'bmj', 'cp', 'youxiaobili', 'zuhe', 'wz_cp_zuhe', 'jszb_wz', 'muju', 'wz_sb', 'wh_days', 'kh_id', 'gx_wz'];
					// disp_fields = ['price1', 'min_kc1', 'max_kc1', 'ck_weizhi_id1', 'remained1', 'pd_days1', 'pd_last', 'jy_days', 'midu', 'gys_id'];
					break;
			}
			for(var i in disp_fields){
				$('#' + divId + ' #ces_tr_' + disp_fields[i]).show();
			}
			for(var i in hide_fields){
				$('#' + divId + ' #ces_tr_' + hide_fields[i]).hide();
			}
		};
		hidefields();
		//事件绑定
		//根据物资类型隐藏一些fields
		$('#' + divId + ' #wz_fl_id').unbind('change').bind('change', function(){
			hidefields();
		});
		//根据是否组合产品决定是否显示详细组合信息
		$('#' + divId + ' input[name="zuhe"]').unbind('change').bind('change', function(){
			hidefields();
		});
		
		//单位类型绑定单位
		var target = [
			{
				selector:'#' + divId + ' #unit_id', 
				type:'select', 
				field:'unit_fl_id', 
				url:'/jqgrid/jqgrid/oper/linkage/db/qygl/table/unit'
			},
		];
		XT.linkage({selector:'#' + divId + ' #unit_fl_id'}, target);
		//工序和缺陷类型绑定
		var target = [
			{
				selector:'#' + divId + ' #defect_gx_wz_temp #defect_id', 
				type:'select', 
				field:'gx_id', 
				url:'/jqgrid/jqgrid/oper/linkage/db/qygl/table/zzvw_defect_gx'
			},
		];
		XT.linkage({selector:'#' + divId + ' #gx_id'}, target);
		
		$('#' + divId  + ' #unit_id').unbind('change').bind('change', function(){
			var unit = $(this).find("option:selected").text(), post = ['min_kc1', 'max_kc1', 'remained1', 'min_kc', 'max_kc', 'remained'];
			for(var i in post){
				$('#' + divId + ' #' + post[i] + '_post').html(unit);
			}
		});
		$('#' + divId  + ' #wz_cp_zuhe_temp #wz_cp_zuhe_add').unbind('click').bind('click', function(){
			//计算表面积和体积
			var option = $('#' + divId  + ' #wz_cp_zuhe_temp #input_wz_id').find("option:selected"), sl = parseInt($('#' + divId  + ' #wz_cp_zuhe_temp #amount').val());
			var tj = parseFloat(option.attr('tj')), bmj = parseFloat(option.attr('bmj')), old_tj = parseFloat($('#' + divId + ' #tj').val()), old_bmj = parseFloat($('#' + divId + ' #bmj').val());
			$('#' + divId + ' #tj').val(old_tj + tj * sl);
			$('#' + divId + ' #bmj').val(old_bmj + bmj * sl);
			// $('#wz_cp_zuhe_values #del_button:last').bind('click', {e:this}, function(event){
				// var tr = $(event.currentTarget).parent('tr'), input_wz_id = tr.find('td #input_wz_id').val(), amount = tr.find('td #amount').val();
				// alert("del");
				// XT.debug([input_wz_id, amount]);
				// XT.debug(event);
			// });
		});
	};
	
	$table.prototype.getGridsForInfo = function(divId){
		var grids = [
			{tab:'genzong', container:'genzong', table:'zzvw_yw_yunshu', params:{real_table:'yw', from:'wz', yw_fl_id:0}},
		];
		return grids;
	};
	
	
}());
