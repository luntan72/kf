// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.property;
	
	DB.device = function(grid){
		$table.supr.call(this, grid);
		this.comm_action = new tc_ver_comm_action(this);
	};

	var $table = DB.device;
	XT.extend($table, gc_grid_action);
	
	$table.prototype.bindEventsForInfo = function(divId, rowId){
		var base = 'div#' + divId + ' #div_view_edit';
		var target = [
			{selector:base + ' #device_property_temp #property_id', type:'select', field:'device_type_id', url:'/jqgrid/jqgrid/oper/linkage/db/property/table/property'},
		];
		XT.linkage({selector:base + ' select#device_type_id'}, target);
		
		//需要对不同属性的数据类型进行输入检查
		$(base + ' #device_property_temp #property_id').unbind('change').bind('change', function(event){
// XT.debug(event);
			var data_type_id = $(this).find('option:selected').attr('data_type_id');
// XT.debug(data_type_id);
			if(data_type_id == '1'){ //string
				$(base + ' #device_property_temp #content').removeAttr('invalidChar');
			}
			else if(data_type_id == '2'){//number
				$(base + ' #device_property_temp #content').attr('invalidChar', '[^0-9\.-]');
			}
		});
	};
}());
