// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.qygl;
	
	DB.zzvw_unit = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.zzvw_unit;
	XT.extend($table, gc_grid_action);
	$table.prototype.bindEventsForInfo = function(divId, element_id){
		var $this = this;
		var div = '#' + divId + " #view_edit_" + element_id;
		$(div + " #unit_fl_id").unbind('change').bind('change', function(event){
			var unit_fl_id = $(this).children('option:selected').val();
			$.post("/jqgrid/jqgrid", {db:'qygl', table:'unit_fl', oper:"get_standard_unit", element:unit_fl_id}, function(data){
				if(XT.handleRequestFail(data))return;
				if(data.cc == '0'){
					$(div + ' #fen_zi').val(1);
					$(div + ' #fen_zi').attr('disabled', 'disabled');
					$(div + ' #fen_mu').val(1);
					$(div + ' #fen_mu').attr('disabled', 'disabled');
					$(div+ ' #standard_unit_id').val(0);
					$(div+ ' #label_standard_unit_id').html('');
				}
				else{
					$(div + ' #fen_zi,#fen_mu').removeAttr('disabled');
				}
				$(div+ ' #standard_unit_id').val(data.id);
				$(div+ ' #label_standard_unit_id').html(data.name);
			}, 'json')
		});
	};
}());
