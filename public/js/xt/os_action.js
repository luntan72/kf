// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.xt;
	
	DB.os = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.os;
	XT.extend($table, gc_grid_action);
	
	$table.prototype.bindEventsForInfo = function(divId, rowId){
		var base = 'div#' + divId + ' #div_view_edit';
		var baseon_os_id = "div#" + divId + ' #baseon_id';
		var target = [
			{selector:base + ' #testcase_type_id', type:'checkbox', field:'os_id', name:'testcase_type_id', op:'check', url:'jqgrid/jqgrid/db/xt/table/testcase_type/oper/list'},
			{selector:base + ' #build_target_id', type:'checkbox', field:'os_ids', name:'build_target_id', op:'check', url:'jqgrid/jqgrid/db/xt/table/build_target/oper/list'},
			{selector:base + ' #groups_id', type:'checkbox', field:'os_id', name:'groups_id', op:'check', url:'jqgrid/jqgrid/db/useradmin/table/groups/oper/list'},
			];
		XT.linkage({selector:base + ' #baseon_id'}, target);
	};
	
	
	$table.prototype.information_open = function(divId, element_id, pageName){
		pageName = pageName || 'all';
		$table.supr.prototype.information_open.call(this, divId, element_id, pageName);
		//调整编辑UI的比例
// XT.debug(divId);
		// var t1 = $('#' + divId + " #div_view_edit table.ces:first"), t2 = $('#' + divId + " #div_view_edit table.ces:nth-child(2)");
		// t1.find("th.ces:first").width('10%');
		// t2.find("th.ces:first").width('10%');
		// if(element_id != 0){
			// var db = 'xt';
			// var step_postData = {db:db, table:'testcase_ver_step', 'parent':ver_id};

			// //加载Version Steps
			// if (pageName == 'all' || pageName == 'view_edit'){
				// var step_grid = grid_factory.get('xt', 'testcase_ver_step', {container:'steps_' + element_id});
				// step_postData.filters = JSON.stringify({groupOp:'AND', rules:[{"field":"testcase_ver_id","op":"eq","data":ver_id}]});
				// step_grid.load(step_postData);
			// }
		// }

		// $('#edit_history #btn #diff').button().unbind().bind('click', function(){
			// var vers = [];
			// var i = 0;
			// $('#edit_history [name="id"]:checked').each(function(){
				// vers[i ++] = $(this).val();
			// });
			// if (vers.length == 0)
				// alert("Please select at most 1 item");
			// else
				// xt.testcase_ver.ver_diff(vers);
		// });
		// $('#hide_same').unbind().bind('click', {selector:'#ver_diff tr.jqgrow'}, XT.hideTheSame);
		return true;	
		
	};
}());
