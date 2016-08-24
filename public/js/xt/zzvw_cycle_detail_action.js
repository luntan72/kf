// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.xt;	
	DB.zzvw_cycle_detail = function(grid){
		$table.supr.call(this, grid);
	};
	
	var $table = DB.zzvw_cycle_detail;
	var tool = new kf_tool();
	tool.extend($table, gc_grid_action);
	
	// show selections to add
	$table.prototype.addCase = function(params){
		$this = this;
		var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/add_case/parent/' + params.parent;	
		var div_id = 'div_addcase_normal_req';
		params.mcu =  false;
		var dialogParams = {
			div_id: div_id, 
			width: 600, 
			height: 200, 
			title: 'Select Case Add Type',
			close: function(){$(this).remove();},
			buttons: {
				Ok: function(){
					var type = tool.getAllInput('div#' + div_id).data.case_add_type;
					switch(type){
						case 'case_list':
							$this.addFromCase(params);
							break;
						case 'mcu_case_add_list':
							params.mcu = true;
							$this.addFromCase(params);
							break;
						case 'cycle_list':
							$this.addFromCycle(params);
							break;
					}
					$(this).dialog( "close" );
				},
				Cancel:function(event, ui){
					$(this).remove();
				}
			}
		};
		tool.actionDialog(dialogParams, url);
	};
	
	// add from test case list
	$table.prototype.addFromCase = function(params){
		var div_id = 'div_case_add';
		if(params.mcu == true)
			div_id = 'div_mcu_case_add_select';
		var divSelector = "#" + div_id;
		
		params = $.extend(true, params, {dstTable: 'testcase', container: div_id, flag: 0});
		
		//
		var fun_open = function(){
			var grid = grid_factory.get(params.db, params.dstTable, {container: div_id});
			var tmpParams = grid.getParams();
			//testcase advanced filter selector
			var newCondSelector = tmpParams.base + "_cond_advanced";
			var tmpFields = {prj_ids: "prj_id", testcase_type_ids: "testcase_type_id"};		
			$.each(tmpFields, function(i,n){
				var val = $(params.tabSelector + " #" + i).val();
				if(val != undefined){
					$(tmpParams.conditionSelector + " #" + n).parent().next().remove();
					$(tmpParams.conditionSelector + " #" + n).attr('value', val).attr('disabled',true);
					// $(tmpParams.conditionSelector + " #" + n).parent().next().find("#single_to_multi").attr('disabled',true);
					
				}
				//get case_module
				if(n == "testcase_type_id")
					$this.setLinkage('div' + tmpParams.conditionSelector, ['select#testcase_type_id'], true);
			});
			//disable edit status
			// $(tmpParams.conditionSelector + " " + newCondSelector + ' input[name="edit_status_id"]').attr('disabled',true);
			$(tmpParams.conditionSelector + " #edit_status_id").attr('disabled',true);
			//disable isactive
			$(tmpParams.conditionSelector + " #isactive").attr('value', '1').attr('disabled',true);
			
			//remove query add button
			$(divSelector).hide();
			$(tmpParams.base + "_query_add").remove();
			$(divSelector).show();
		};
		//dialog prepare
		var dialog_params = {
			title: 'Add Case From Case List To Current Cycle',
			modal: true,
			width: 1024,
			height: 600,
			div_id: div_id,
			autoOpen:false,
			close: function(){$(this).remove();},
			open: function(){
				if(params.mcu == true){
					var tmpUrl = '/jqgrid/index/db/' + params.db + '/table/' + params.dstTable + '/container/' + div_id + '/parent/' + params.parent;
					
					$.get(tmpUrl, function(datas){	
						$(divSelector).append(datas);
						fun_open();
						$(divSelector + ' #ces_tr_new_prj_ids :button').unbind('click').bind('click', function(){
							var id = $(this).attr('id');
							$this.buttonActions(id, this);
						});
					});
				}
				else{
					fun_open();
				}
			}
		};
		//enable button
		dialog_params.buttons = $this.getButtons(params);
		//enable dialog
		var url = '/jqgrid/index/db/' + params.db + '/table/' + params.dstTable + '/container/' + div_id + '/parent/' + params.parent;
		if(params.mcu == true)
			url = "/jqgrid/jqgrid/db/" + params.db + "/table/" + params.table + "/oper/add_mcu_case/parent/" + params.parent;
		dialog_params = $.extend(true, dialog_params, {html_type:'url', text:url});
		return tool.actionDialog(dialog_params, url);	
	};
	
	// add from existed cycles
	$table.prototype.addFromCycle = function(params){
		var $this = this;
		// var db = params.db;
		// var table = params.table;
		var div_id = 'div_new_case_add';
	
		var dialog_params = {
			title: 'Add Case From Other Cycel To Current Cycle',
			modal: true,
			width: 1120,
			height: 600,
			div_id: div_id,
			autoOpen: false,
			close: function(){$(this).remove();},
			open: function(){
				var grid = grid_factory.get(params.db, params.table, {container: div_id});
				var divCond = 'div' + grid.getParams('conditionSelector');
				
				var tmpFields = { prj_ids:"prj_id", os_id:"os_id", chip_id:"chip_id", board_type_id:"board_type_id", compiler_ids:"compiler_id",
					build_target_ids:"build_target_id", testcase_type_ids:"testcase_type_id", creater_id:"creater_id" };
				$.each(tmpFields, function(key, value){
					keyVal = $(params.tabSelector + " #" + key).val();
					if(keyVal != undefined){
						$(divCond + " #" + value).val(keyVal);
						if(key == "testcase_type_ids")
							$(divCond + " #" + value).attr('disabled', true);
					}
				});
				var source = ['select#os_id', 'select#board_type_id', 'select#chip_id', 'select#prj_id', 
						'select#creater_id', 'select#cycle_id', 'select#testcase_module_id', 'select#testcase_type_id'];

				$this.setLinkage(divCond, source, true);
				$(divCond + " #query_add").remove();
				$(divCond + " #query_remove").remove();
				$(divCond + " strong").remove();
			}
		};
		// process button
		params = $.extend(true, params, {dstTable: params.table, container: div_id, flag: 1});
		dialog_params.buttons = $this.getButtons(params);
		// enable dialog
		var url = '/jqgrid/index/db/' + params.db + '/table/' + params.table + '/container/' + div_id + "/parent/" + params.parent;
		dialog_params = $.extend(true, dialog_params, {html_type:'url', text:url});	
		return tool.actionDialog(dialog_params, url);	
	};
		
	// actions on the jqgrid	
	$table.prototype.buttonActions = function(action, options){
		var $this = this, c_f = '', cell = [], element = '', oper = action, ret = true, _postData;
		var params = $this.getParams();		
		var selectedRows = $(params.gridSelector).getGridParam('selarrrow');
		
		params.cycle_id = $(params.conditionSelector).parent().parent().children().find("#id").attr('value');
		if(action == "query_remove")
			oper = 'remove_case';
		
		var postData = {db: params.db, table: params.table, parent: params.cycle_id, oper: oper};
		var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/' + oper + '/parent/' + params.cycle_id;	
		if( typeof selectedRows != 'undefined' ){
			$.each(selectedRows, function(i, item){
				cell[i] = $(params.gridSelector).getCell(item, 'c_f');
			});
			c_f = JSON.stringify(cell);
			element = JSON.stringify(selectedRows);
			_postData = {element: element, c_f: c_f};
			postData = $.extend(true, postData, _postData);
		}
		var dialogParams = {div_id: 'div_' + oper, width: 400, height: 200, postData: _postData};
		
		switch(action){
			case 'query_add':
				var tmpParams = {
					refeshTab: params.container, 
					parent: params.cycle_id, 
					tabSelector: '#' + $('#' + $this.getParams('container')).parent().attr('id')
				};
				params = $.extend(true, params, tmpParams);
				return $this.addCase(params);
			case 'query_update':
				// if (tool.checkSelectedRows(selectedRows, 1)){
					$.post('/jqgrid/jqgrid', postData, function(data){
						if(data == 'success')
							alert("Update Successfully!");
						else
							alert("Update Wrong!");
					});
				// }
				break;
			case 'set_build_result':
				if (tool.checkSelectedRows(selectedRows, 1)){
					dialogParams.title = 'Set Build Result';
					tool.actionDialog(dialogParams, url, undefined, function(){
						$(params.gridSelector).trigger('reloadGrid');
					});
				}
				break;
			case 'set_crid':
				if (tool.checkSelectedRows(selectedRows, 1)){
					dialogParams.title = 'Set CRID';
					tool.actionDialog(dialogParams, url, undefined, function(){
						$(params.gridSelector).trigger('reloadGrid');
					});
				}
				break;
			case 'set_deadline':
				if (tool.checkSelectedRows(selectedRows, 1)){
					var div_id = 'div_' + oper;
					dialogParams.title = 'Set Deadline';
					dialogParams.open = function(){
						$("#" + div_id + ' input[date="date"]').each(function(i){
							tool.datePick(this);
						});
					};
					tool.actionDialog(dialogParams, url, undefined, function(){
						$(params.gridSelector).trigger('reloadGrid');
					});
				}
				break;
			case 'set_result':
				if (tool.checkSelectedRows(selectedRows, 1)){
					dialogParams.width = 900;
					dialogParams.height = 400;
					dialogParams.title = 'Set Result';
					tool.actionDialog(dialogParams, url, undefined, function(data){
						datas = JSON.parse(data);
						$(params.conditionSelector + " strong").html(datas.statistics);
						$(params.gridSelector).trigger('reloadGrid');
					});
				}
				break;
			case 'set_tester':
				if (tool.checkSelectedRows(selectedRows, 1)){
					tool.actionDialog(dialogParams, url, undefined, function(){
						$(params.gridSelector).trigger('reloadGrid');
					});
				}
				break;
			case 'query_remove':
			case 'remove_case':
				if (tool.checkSelectedRows(selectedRows, 1)){
					postData.flag = '0';
					$this.removecase(params, postData);
				}
				break;
			case 'update_ver':
				if($(params.conditionSelector + " #ver").val() == 0){
					alert("Pls Select Prj & Ver In FilterCondition!");
					break;
				}
				if (tool.checkSelectedRows(selectedRows, 1)){
					$.post('/jqgrid/jqgrid', postData, function(data){
						if(data == 'success')
							alert("Update Successfully!");
						else
							alert("Update Wrong!");
					});
				}
				break;
			case 'update_env'://show all case_env
				if (tool.checkSelectedRows(selectedRows, 1)){
					// $this.addDelEnv(params.gridSelector, container, postData);
					$this.updateEnv(params, postData);
				}
				break;	
			case 'update_trickmode':
				if (tool.checkSelectedRows(selectedRows, 1)){
					$this.updateTrickMode(params, postData);
				}
				break;
			// case 'set_comment':
				// if (tool.checkSelectedRows(selectedRows, 1)){
					// dialogParams = {div_id: 'div_' + oper, width: 600, height: 400, title: 'Set Comment', postData: _postData};
					// tool.actionDialog(dialogParams, url, undefined, function(){
						// $(gridSelector).trigger('reloadGrid');
					// });
				// }
				// break;
			
			case 'export':
				if (tool.checkSelectedRows(selectedRows, 1)){
					$this.exports(params, postData);
				}
				break;
			case 'single_to_multi':
				$this.single2multi(options);
				break;
			case 'run':
				if (tool.checkSelectedRows(selectedRows, 1)){
					url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/' + oper + '/parent/' + params.cycle_id;	
					dialogParams.title = 'Run';
					dialogParams.postData = {id: selectedRows};
					var fun_validate = function(data){
						if(data.token_ids.length == 0){
							alert("The token is required");
							return false;
						}
						return true;
					};
					tool.actionDialog(dialogParams, url, fun_validate, function(data){
						tool.noticeDialog("Success to Run the cycle", "Run");
					});
				}
				break;
			default:
				ret = $table.supr.prototype.buttonActions.call(this, action, options);							
		}
		return ret;					
	};
	
	// dialog of setting build result
	$table.prototype.buildResult = function(id, gridSelector, selectedValue, parent){
		var $this = this;
		var c_f = $(gridSelector).getCell(id, 'c_f');
		var db = $this.getParams('db'), table = $this.getParams('table');
		var oper = 'set_build_result';
		var ids = [];
		ids[0] = id;
		var url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/' + oper + '/parent/' + parent;	
		var postData = {element: JSON.stringify(ids), c_f: JSON.stringify(c_f)};
		var dialogParams = {div_id: 'div_' + oper, width: 400, height: 200, title: 'Set Build Result', postData: postData};
		tool.actionDialog(dialogParams, url, undefined, function(){
			$(gridSelector).trigger('reloadGrid');
		});
	};
	
	// export all kinds of cycle detail report
	$table.prototype.exports = function(params, postData){
		var sqls = $(params.hiddenSelector + " #sqls").val();
		var dialog_params = {
			div_id:'export_options',
			width:600,
			height:300,
			title:'Export',
			postData:{element: postData.element, sqls: sqls},
			open:function(){
				var selector = "#export_options";
				$(selector + ' #div_for_playlist_cte, #div_for_playlist_gvb, #div_for_playlist_android, #div_for_playlist_android_old').hide();
				$(selector + ' input:radio[name="export_type"]').change(function(event){
					if ($(this).is(':checked')){
						var tmpFields = [];
						switch($(this).val()){
							case 'codec_playlist_cte':
								$('#export_options #div_for_playlist_cte').show();
								tmpFields = ["div_for_playlist_android", "div_for_playlist_gvb", "div_for_playlist_android_old"];
								$.each(tmpFields, function(i,n){
									$(selector + " #" + n).hide();
								});
								break;
							case 'codec_playlist_gvb':
								tmpFields = ["div_for_playlist_android", "div_for_playlist_cte", "div_for_playlist_android_old"];
								$.each(tmpFields, function(i,n){
									$(selector + " #" + n).hide();
								});
								$('#export_options #div_for_playlist_gvb').show();
								break;
							case 'codec_playlist_android':
								tmpFields = ["div_for_playlist_gvb", "div_for_playlist_cte", "div_for_playlist_android_old"];
								$.each(tmpFields, function(i,n){
									$(selector + " #" + n).hide();
								});
								$('#export_options #div_for_playlist_android').show();
								break;
							case 'codec_playlist_android_old':
								tmpFields = ["div_for_playlist_gvb", "div_for_playlist_cte", "div_for_playlist_android"];
								$.each(tmpFields, function(i,n){
									$(selector + " #" + n).hide();
								});
								$('#export_options #div_for_playlist_android_old').show();												
								break;
							default:
								tmpFields = ["div_for_playlist_gvb", "div_for_playlist_cte", "div_for_playlist_android", "div_for_playlist_android_old"];
								$.each(tmpFields, function(i,n){
									$(selector + " #" + n).hide();
								});
								break;
						}
					}
				});
			}
		};
// tool.debug(dialog_params);
		url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/export/parent/' + params.cycle_id + '/container/' + params.container;
		tool.actionDialog(dialog_params, url, undefined, function(data){
			location.href = "/download.php?filename=" + encodeURIComponent(data) + "&remove=1";
		});
	};
	
	// keyword auto complete
	$table.prototype.getAutoCompleteField = function(){ // used by gc_grid_action.ready
		return [{}];
	};
	
	// buttons of add cases
	$table.prototype.getButtons = function(params){
		var fun_addcase = function(flag, replaced){
			var c_f = '';
			var ver_ids = '';
			var oper = 'add_case_new';
			var cell = [];
			var baseSelector = "#" + params.container + '_' + params.db + '_' + params.table;
			// var d_condId = base + '_cond';
			var tmpParams = {
				condSelector: baseSelector + '_cond',
				listSelector: baseSelector + '_list',
				gridSelector: "#" + params.container + '_' + params.db + '_' + params.dstTable + "_list",
			};
			// var tmpCondId = baseSelector + '_cond';
			// var d_listSelector = baseSelector + '_list';
			// var gridSelector =  "#" + params.container + '_' + params.db + '_' + params.dstTable + "_list";
			var prj_id = $("#" + params.container + " #prj_id").val();
			var selectedRows = $(tmpParams.gridSelector).getGridParam('selarrrow');
			var data = {element: selectedRows, parent: params.parent, replaced: true};

			if(flag === 0){
				var ver_id = [];
				$.each(selectedRows, function(i, val) {	
					ver_id[i] =  $(tmpParams.gridSelector).getCell(val, 'ver_ids');
				});	
				ver_ids = JSON.stringify(ver_id);
				oper = 'add_case';
			}
			else if(flag === 1){
				$.each(selectedRows, function(i, item){
					cell[i] = $(tmpParams.gridSelector).getCell(item, 'c_f');
				});
				c_f = JSON.stringify(cell);
			}
			var inputs = tool.getAllInput('#' + params.container);
			var postData = {
				db: params.db, 
				table: params.table, 
				c_f: c_f,
				oper: oper,
				ver_ids: ver_ids,
				parent: params.parent, 
				element: JSON.stringify(selectedRows), 
				prj_id: inputs.data.prj_id,
				replaced: replaced
			};
			if(params.mcu == true){
				postData.oper = 'add_mcu_case',
				postData.new_prj_ids = inputs.data.new_prj_ids,
				postData.compiler_ids = inputs.data.compiler_ids,
				postData.build_target_ids = inputs.data.build_target_ids
			}
			var item = tool.getAllInput(tmpParams.condSelector).data;
			$.post('/jqgrid/jqgrid', $.extend(postData, item), function(data){
				// if($(d_listSelector).attr('class') == 'scroll'){
					var grid = grid_factory.get(params.db, params.table, {container: params.refeshTab, parent: params.parent});
					var filters = JSON.stringify({groupOp:'AND', rules:[{"field":"cycle_id","op":"eq","data":params.parent}]});				
					//grid.setParams($.extend(true, params, {p_id:params.parent, parent:params.parent, filters:filters}));
					grid.indexInDiv({filters:filters});
					if(data == 'sucess')
						alert("Add successfully!!!");
				// }
				// else
					// $(d_listSelector).trigger('reloadGrid');
			});
		};
		var buttons;
		if (params.flag == 0){
			buttons = {
				"Add&Replace the cases to Current Project": function(){
					fun_addcase(params.flag, 1);
					if(params.mcu == false)
						$(this).dialog( "close" );
				},
				"Add&NOT-Replace the cases to Current Project": function() {
					fun_addcase(params.flag, 0);
					if(params.mcu == false)
						$(this).dialog( "close" );
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			};
		}
		else {
			buttons = {
				"Add&Replace the cases": function(){
					fun_addcase(params.flag, 1);
					if(params.mcu == false)
						$(this).dialog( "close" );
				},
				"Add&NOT-Replace the cases": function() {
					fun_addcase(params.flag, 0);
					if(params.mcu == false)
						$(this).dialog( "close" );
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			};
		}
		return buttons;
	};
	
	// input result on jqgrid list
	$table.prototype.inputResult = function(id, gridSelector, divSelector, parent){
		var $this = this;
		var params = $this.getParams();
// tool.debug(params);
		var selectedValue = $(gridSelector + " " + divSelector).val();
		var rowData = $(gridSelector).getRowData(id);
		if (selectedValue > 1){
				$this.resultInfo(id, gridSelector, divSelector, selectedValue, parent);
		}
		else{
			// if('' != rowData.parse_rule_id && 'Default' != rowData.parse_rule_id)
				// $this.resultInfo(id, gridSelector, divSelector, selectedValue, parent);
			// else{
				var ids = [];
				ids[0] = id;
				var c_f = $(gridSelector).getCell(id, 'c_f');
				var content =$(divSelector + ' option[value="' + selectedValue +'"]').html(); 
				var postData = {
					db: params.db, 
					table: params.table, 
					parent: parent, 
					oper: 'set_result', 
					element: JSON.stringify(ids), 
					select_item: selectedValue, 
					c_f: JSON.stringify(c_f)
				};
				$.post('/jqgrid/jqgrid', postData, function(data){	
					if(data){
						var datas = JSON.parse(data);
						$(gridSelector).setRowData(id, datas.data);
						$(params.conditionSelector + " strong").html(datas.statistics);
					}
				});
			// }
		}
	};
	
	// get jira info for the result info dialog
	$table.prototype.jiraInfo = function(event){
		var $this = this;
		var db = $this.getParams('db'), table = $this.getParams('table');
		var parent = event.data.parent, element = event.data.element;
		var base = '#div_result_bug_submit_' + element;
		if($(event.data.resSelector + ' input[name="submit_bug[]"]').is(':checked')){
			var url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/get_jirainfo/parent/' + parent + '/element/' + element;
			var postData = tool.getAllInput(event.data.resSelector).data;

			var account = postData.submit_username;
			var password = postData.submit_password;

			$.post(url, postData,
				function(data, status){
					if(data == '1')
						alert("please input your JiraPassword!");
					else if(data == '2')
						alert("please correct your JiraPassword!");
					else if( data == '3'){//403 禁止
						alert("please correct your JiraPassword!");
					}
					else if(data == '4'){ // 401 验证为通过
						alert("U have already input wrong password more than 3 times. Please login from web instead of XiaoTian!");
					}
					else if(data == '5'){ // curl fail
						alert("Please contact with XT administor");
					}
					else if(data){
						$.cookie('submit_username', account, {expires: 90});
						$.cookie('submit_password', password, {expires: 90});
						$(base).html(data);
						$("#jira_project").unbind('change').bind('change', function(){
							var jira_project = $(this).val();
							if(jira_project != '0'){
								var post_Data = {db:db, table:table, jira_project:jira_project, submit_username:account, submit_password:password, oper:'get_jira_components'};
								$.post('/jqgrid/jqgrid', post_Data, function(datas){
									var datass = JSON.parse(datas);
									$.each(datass, function(i, value){
										var selector = '#' + i;
										var currentVal = $(selector).val();
										$(selector).find('option').remove();
										tool.generateOptions($(selector), value.value, 'id', 'name', true);
										$(selector).val(currentVal);
// tool.debug(value.defval);
										if(value.defval)
											$(selector).val(value.defval);
									});
								});
								var div = '#div_submit_jira_edit';
								$('#div_submit_jira_edit :button').unbind('click').bind('click', function(){
									var id = $(this).attr('id');
									$this.buttonActions(id, this);
								});
							}
						});
						$('#jira_labels').autocomplete({
							minLength: 2,
							source: "/jqgrid/jqgrid/oper/get_jira_labels/db/" + db + "/table/" + table 
								+ "/submit_username/" + escape(account) + "/submit_password/" + escape(password),
						});
					}
				}
			);
		}
		else
			$(base).html('');
	};
	
	// log download
	$table.prototype.log_download = function(db, table, element, fileName){
		var $this = this;
		var postData = {db:db, table:table, element:element, oper:'log_download', fileName:fileName};
		$.post('/jqgrid/jqgrid', postData, function(data){
			if(data)
				location.href = "/download.php?filename=" + encodeURIComponent(data) + "&remove=0";
			else
				alert("no log");
		});
	};
	
	// log delete 
	$table.prototype.log_delete = function(db, table, gridSelector, element, fileName){
		var $this = this;
		var postData = {db:db, table:table, element:element, oper:'log_delete', fileName:fileName};
		$.post('/jqgrid/jqgrid', postData, function(data){
			if(data){
				var datas = JSON.parse(data);
				$(gridSelector).setRowData(element, datas);
			}
			else
				alert("delete fail");
		});
	};
	
	//???
	$table.prototype.loadComplete = function(data){
		$table.supr.prototype.loadComplete.call(this, data);
		if(data.additional){
			var conditionSelector = this.getParams("conditionSelector");
			$(conditionSelector + " strong").html(data.additional);
		}
	};
	
	// query
	$table.prototype.query = function(){
		$this = this;
		var container = $this.getParams('container');
		if(container == 'div_new_case_add'){
			var cycle_id = tool.getAllInput("#div_new_case_add").data.cycle_id;
			if(cycle_id == ''){
				alert("Pls select a cycle");
				return;
			}
		}
		$table.supr.prototype.query.call(this);
	};
	
	// query, then remove
	$table.prototype.query_remove = function(params){
		$this = this;
		var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/' + params.oper + '/parent/' + params.parent;	
		var div_id = 'div_query_remove_normal_req';
		var dialogParams = {
			div_id: div_id, 
			width: 800, 
			height: 500, 
			title: 'Select Remove Type',
			close: function(){$(this).remove();},
			open: function(){
				$('#div_query_remove_normal_req #div_for_query_remove_cond').hide();
				$('#div_query_remove_normal_req input:radio[name="case_query_remove_type"]').change(function(event){
					if ($(this).is(':checked')){
						switch($(this).val()){
							case 'remove_all':
								$('#div_query_remove_normal_req #div_for_query_remove_cond').hide();
								break;
							case 'remove_cond':
								$('#div_query_remove_normal_req #div_for_query_remove_cond').show();
								break;
							default:
								$('#div_query_remove_normal_req #div_for_query_remove_cond').hide();
								break;
						}
					}
				});
			},
			buttons: {
				Ok: function(){
					var type = tool.getAllInput('div#' + div_id).data.case_query_remove_type;
					var postData;
					switch(type){
						case 'remove_all':
							postData = {db: params.db, table: params.table, oper:'query_remove_all', parent: params.parent};
							$.post("/jqgrid/jqgrid", postData, function(data){
							});
							$(this).dialog( "close" );
							break;
						case 'remove_cond':
							var inputs = tool.getAllInput('div#div_for_query_remove_cond').data;
							postData = {db: params.db, table: params.table, oper:'query_remove_cond', parent:  params.parent};
							$.post("/jqgrid/jqgrid", $.extend(postData, inputs), function(data){
							});
							$(this).dialog( "close" );
							break;
					}
				},
				Cancel:function(event, ui){
					$(this).remove();
				}
			}
		};
		tool.actionDialog(dialogParams, url);
	};
	
	// ready for cycle deatil index
	$table.prototype.ready = function(base){
		$table.supr.prototype.ready.call(this, base);
		var params = this.getParams();

		$(params.conditionSelector + " #ver").unbind('change').bind('change', function(){
			if($(this).val() > 1){
				if($(params.conditionSelector + " #prj_id").val() == 0)
					alert("Pls Select One Prj First!");
				if($(params.conditionSelector + " input[name='prj_id[]']").val() == 0)
					alert("Pls Select Only One Prj First!");
			}
		});
		if(params.container == "cycle_detail_" + params.parent){   
			var source = [];
			var isSpecial = false;
			source = ['select#testcase_module_id', 'select#prj_id', 'select#compiler_id'];
			this.setLinkage('div' + params.conditionSelector, source, isSpecial);
		}
	};
	
	// $table.prototype.removecase = function(gridSelector, conditionSelector, postData){
	$table.prototype.removecase = function(params, postData){
		var $this = this;
		var div_id = 'div_removecase';
		
		//same oper to confirm delete with result cases
		var _removecase = function(retData, params, postData){
			var _div_id = 'div_remove_rows';
			var _dialog_params = {
				height:300,
				width: 650,
				modal: true,
				autoOpen:false,
				close: function(){$(this).remove();},
				buttons: {
					"Still Delete": function() {
						var item = tool.getAllInput(params.conditionSelector).data;
						// var postData = {db: params.db, table: table, oper: params.oper, flag: '1', parent: params.parent, element: params.element, c_f: params.c_f};
						postData.flag = 1;
						$.post('/jqgrid/jqgrid', $.extend(postData, item), function(revData){
							$(params.gridSelector).trigger('reloadGrid');
						});
						$(this).dialog( "close" );
					},
					Cancel: function() {
						$(this).dialog( "close" );
					}
				}
			};
			var _html_params = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>' 
				+ JSON.parse(retData) + ' \n\r<br />\n\r<br /> These items has been set result And will be permanently deleted and cannot be recovered. Are you sure?</p>';
			var _mydialog = $('<div id="' + _div_id + '" title="Still remove the cases from cycle?"></div"').html(_html_params).dialog(_dialog_params);
			_mydialog.dialog('open');				
		};
		
		//defult dialog params
		//ask if need to delete
		var dialog_params = {
			height:150,
			width: 650,
			modal: true,
			autoOpen:false,
			close: function(){$(this).remove();},
			buttons: {
				"Delete Selected items": function() {
					var item = tool.getAllInput(params.conditionSelector).data;
					$.post('/jqgrid/jqgrid', $.extend(postData, item), function(revData){
						//think again
						if(revData){
							_removecase(revData, params, postData);
						}
						$(params.gridSelector).trigger('reloadGrid');
					});
					$(this).dialog( "close" );
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			}
		};
		var html_params = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>These items will be permanently deleted and cannot be recovered. Are you sure?</p>';
		var mydialog = $('<div id="' + div_id + '" title="Remove the cases from cycle?"></div"').html(html_params).dialog(dialog_params);
		mydialog.dialog('open');
	};
	
	// dialog when input result
	$table.prototype.resultInfo = function(id, gridSelector, divSelector, selectedValue, parent){
		var $this = this;
		var c_f = $(gridSelector).getCell(id, 'c_f');
		var params = $this.getParams();
		var div_id = 'div_' + params.db + '_' + params.table + '_res_' + id;
		var resSelector = '#' + div_id;
		var oper = 'save_one';
		var selVal;
		if(typeof selectedValue == 'undefined')
			selVal =$(gridSelector + " " + divSelector).val();
		else
			selVal = selectedValue;	
		var dialog_params = {
			ok: 'close', 
			open: function(){
				var submit_username = $.cookie('submit_username');
				var submit_password = $.cookie('submit_password');
				$(resSelector + ' #submit_username').val(submit_username);
				$(resSelector + ' #submit_password').val(submit_password);
				var formid = $(resSelector + ' form').attr('id');
				var hidden_frame_id = 'detail_log_hidden_frame';
				$(resSelector + ' #logfile').parent().append(
					"<iframe name='" + hidden_frame_id + "' id='" + hidden_frame_id + "' width='39%' height='20px'" + 
					" frameborder='0' scrolling='no' style='position:relative;float:right' marginheight='3'></iframe>");
				var data = "<input id='id' type='hidden' name='id' value='" + id + "'>" +
					"<input id='purpose' type='hidden' name='purpose' value='upload'>" +
					"<input id='cellName' type='hidden' name='cellName' value='logfile'>" ;
				$(resSelector + ' #' + formid).append(data);
				var eventData = {gridSelector: gridSelector, resSelector: resSelector, parent: parent, element: id};
				$(resSelector + ' input[name="submit_bug[]"]').unbind('change').bind('change', eventData, function(event){
					return $this.jiraInfo(event);
				});
				$(resSelector + " #save").unbind('click', save).bind('click', {dialog:this}, save);
				$(resSelector + " #cancel").unbind('click', cancel).bind('click', {dialog:this}, cancel);
			},
			close: function(){
				if(typeof selectedValue == 'undefined')
					$(gridSelector).setRowData(id, {result_type_id:0, id:id});
				$(this).remove();
			},
			gridId: gridSelector.substr(1),
			rowId: id,
			title: 'Result Information for Case', 
			height: 600,
			width: 900
		};
		var save = function(event) {
			var inputs = tool.getAllInput(resSelector);
			selVal = inputs.data.result_type_id;
			var ids = [];
			var c_fs = [];
			ids[0] = id;
			c_fs[0] = c_f;
			if (inputs.passed.length == 0){
				var postData = {db: params.db, table: params.table, parent: parent, oper: oper, element: JSON.stringify(ids), c_f: JSON.stringify(c_fs)};
				inputs.data.logfile_path = $('iframe').contents().find('#logfile_path').attr('value');
				$.post('/jqgrid/jqgrid', $.extend(postData, inputs.data), function(data, textStatus){
					if(data){
						var datas = JSON.parse(data);
						$(gridSelector).setRowData(id, datas.data);
						$(params.conditionSelector + " strong").html(datas.statistics);
						// if($(gridSelector + "_" + id + "_t"))
							// $(gridSelector + "_" + id + "_t").trigger('reloadGrid');
					}
				});
			}
			else{
				alert(inputs.tips.join('\n'));
			}
			$(event.data.dialog).dialog( "close" );
		};
		var	cancel = function(event) {
			if(typeof selectedValue == 'undefined')
				$(gridSelector).setRowData(id, {result_type_id:0, id:id});
			$(event.data.dialog).dialog( "close" );
		};
		// dialog_params['buttons'] = buttons;
		var postData = {db: params.db, table: params.table, parent: parent, oper: 'get_resultinfo', element: id, result_type_id: selVal, c_f: c_f};
		$.post('/jqgrid/jqgrid', postData, function(data){						
			var mydialog = $('<div id="' + div_id + '" title="Result Information for Case"></div"').html(data).dialog(dialog_params);
			mydialog.dialog('open');
		});
	};
	
	// asign testers
	$table.prototype.setOneTester = function(id, gridSelector, divSelector, parent){
		var $this = this;
		var c_f = $(gridSelector).getCell(id, 'c_f');
		var db = $this.getParams('db'), table = $this.getParams('table');
		var selectedValue = $(gridSelector + " " + divSelector).val();
		var ids = [];
		ids[0] = id;
		var postData = {db:db, table:table, parent:parent, oper:'set_tester', element:JSON.stringify(ids), select_item:selectedValue, c_f:JSON.stringify(c_f)};
		$.post('/jqgrid/jqgrid', postData, function(data){
			//返回链接状态
			if(data == 'success'){
				$(gridSelector).setRowData(id, {tester_id:selectedValue});
			}
		});
	};
	
	// swith from singe to multi with + button
	$table.prototype.single2multi = function(options){
		var $this = this;
		var td = $(options).parent('td').prev('td.cont-td'),
			se = td.children('select');
		var str = tool.single2multi(se);
		td.html(str);
		$(options).button('destroy');
		$(options).attr('id', 'multi_to_single');
		$(options).val('-');
		$(options).html('-');
		$(options).attr('title', 'Change to single selection');
		$(options).button();
		$(options).unbind('click').bind('click', function(){
			$this.buttonActions('multi_to_single', options);
		});
						
		var tmpFields = {jira_customfield_10300: "jira_boards", jira_customfield_10301: "jira_tool_chains", new_prj_ids: "prj"};
		$.each(tmpFields, function(i, n){
			var selector = "#cart_add_" + i;
			if(i != "new_prj_ids"){
				var div = '#div_submit_jira_edit';
				selector = div + ' #cart_add_' + i;
			}
			var onclick = $(selector).attr('onclick');
			$(selector).removeAttr('onclick').unbind('click').bind('click', function(){
				var rules = [], cart_data = {};
				switch(i){
					case "jira_customfield_10300":
					case "jira_customfield_10301":
						var jira_project = $(div + " #jira_project").val();
						if (jira_project > 0)
							rules.push({field:'jira_project', op:'in', data:jira_project});
						break;
					
					case "new_prj_ids":
						var input = tool.getAllInput("#ces_tr_prj_ids");
						rules.push({field:'id', op:'in', data:input.data.prj_ids});
						break;
				}
				if (rules.length > 0){
					cart_data.filters = JSON.stringify({groupOp:'AND', rules:rules});
					tool.selectToCart(i, "xt", n, n, cart_data);
				}
				else
					$(selector).removeAttr('click').attr('onclick', onclick);
			});
		});
		$(td).find('#cart_add_' + se.attr('id')).click();
	};
	
	// change when relavent filed changed	
	$table.prototype.setLinkage = function(divSelector, sources, isSpecial){
		var $this = this;
		var module_point = true, testcase_type_disable = false, compiler_os = true;
		var db = $this.getParams('db'), table = $this.getParams('table'), parent = $this.getParams('parent');
		var os_chip_board_type = {os_id:divSelector + ' select#os_id', chip_id:divSelector + ' select#chip_id', board_type_id:divSelector + ' select#board_type_id'};
		sources = sources || ['select#os_id', 'select#board_type_id', 'select#chip_id'];
		
		if(parent == undefined)
			parent = 0;
		if($(divSelector + ' select#testcase_testpoint_id').val() == undefined)
			module_point = false;
		if($(divSelector + ' select#compiler_id').val() == undefined)
			compiler_os = false;
		if($(divSelector + ' select#testcase_type_id').attr("disabled") == "disabled")
			testcase_type_disable = true;
		var target, srcDiv, isBind, params;
		for(var i in sources){
			isBind  = true;
			target = undefined;
			srcDiv = undefined;
			params = undefined;
			switch(sources[i]){
				case 'select#os_id':
					target = [{selector:divSelector + ' select#prj_id', type:'select', field:'os_id', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#chip_id', type:'select', field:'os_id', url:'/jqgrid/jqgrid/oper/getchip/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#board_type_id', type:'select', field:'os_id', url:'/jqgrid/jqgrid/oper/getboardtype/db/' + db + '/table/zzvw_prj'}];
					if(testcase_type_disable == false)
						target.push({selector:divSelector + ' select#testcase_type_id', type:'select', field:'os_id', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/testcase_type'});
					if(compiler_os == true)
						target.push({selector:divSelector + ' select#compiler_id', type:'select', field:'os_ids', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/compiler'});		
					srcDiv = {selector:divSelector + ' select#os_id'};
					params = {selector:os_chip_board_type};
					isBind = false;
					break;
				case 'select#board_type_id':
					target = [{selector:divSelector + ' select#prj_id', type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#chip_id', type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/getchip/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#os_id', type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/getos/db/' + db + '/table/zzvw_prj'}];
					srcDiv = {selector:divSelector + ' select#board_type_id'};
					params = {selector:os_chip_board_type};
					isBind = false;
					break;
				case 'select#chip_id':
					target = [{selector:divSelector + ' select#prj_id', type:'select', field:'chip_id', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#board_type_id', type:'select', field:'chip_id', url:'/jqgrid/jqgrid/oper/getboardtype/db/' + db + '/table/zzvw_prj'},
						{selector:divSelector + ' select#os_id', type:'select', field:'chip_id', url:'/jqgrid/jqgrid/oper/getos/db/' + db + '/table/zzvw_prj'}];
					srcDiv = {selector:divSelector + ' select#chip_id'};
					params = {selector:os_chip_board_type};
					
					isBind = false;
					break;
				case 'select#prj_id':
					target = [
						{selector:divSelector + ' select#compiler_id', type:'select', field:'prj_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/compiler/db/' + db + '/table/zzvw_cycle/id/' + parent},
						{selector:divSelector + ' select#build_target_id', type:'select', field:'prj_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/build_target/db/' + db + '/table/zzvw_cycle/id/' + parent}
					];
					srcDiv = {selector:divSelector + ' select#prj_id'};
					params = {};
					if(isSpecial != "undefined" && isSpecial == true){
						target = [{selector:divSelector + ' select#cycle_id', type:'select', field:'prj_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/cycle/db/' + db + '/table/' + table + '/parent/' + parent},
							{selector:divSelector + ' select#creater_id', type:'select', field:'prj_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/creater/db/' + db + '/table/' + table + '/parent/' + parent},
							// {selector:divSelector + ' select#prj_id', type:'select', field:'prj_id', url:'/jqgrid/jqgrid/oper/getPrj/db/' + db + '/table/' + table + '/parent/' + parent}
						];
					}
					break;
				case 'select#compiler_id':
					var id = $("#div_hidden #id").attr("value");
					srcDiv = {selector:divSelector + ' select#compiler_id'};
					target = [{selector:divSelector + ' select#build_target_id', type:'select', field:'compiler_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/build_target/db/' + db + '/table/zzvw_cycle/id/' + parent}];
					params = {selector:{prj_id:divSelector + ' select#prj_id'}};
					break;
				case 'select#cycle_id':
					//var module_priority_testor = {testcase_module_id:divSelector + ' select#testcase_module', testcase_priority_id:divSelector + ' select#testcase_priority', tester_id:divSelector + ' select#tester_id'};
					target = [{selector:divSelector + ' select#testcase_module_id', type:'select', url:'/jqgrid/jqgrid/oper/get_linkage/dst/module/db/' + db + '/table/' + table + '/parent/' + parent},
						{selector:divSelector + ' select#tester_id', type:'select', url:'/jqgrid/jqgrid/oper/get_linkage/dst/testers/db/' + db + '/table/zzvw_cycle'},
					];
					srcDiv = {selector:divSelector + ' select#cycle_id'};
					params = {};
					break;
				case 'select#testcase_module_id':
					if(module_point == true){
						target = [{selector:divSelector + ' select#testcase_testpoint_id', type:'select', field:'testcase_module_id', url:'/jqgrid/jqgrid/oper/linkage/db/xt/table/testcase_testpoint'}];
						srcDiv = {selector:divSelector + ' select#testcase_module_id'};
						params = {};
					}
					else{
						target = 'undefined';
					}
					break;
				case 'select#creater_id':
					target = [{selector:divSelector + ' select#cycle_id', type:'select', field:'creater_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/creater_cycle/db/' + db + '/table/' + table + '/parent/' + parent}];
					srcDiv = {selector:divSelector + ' select#creater_id'};
					tmp = os_chip_board_type
					tmp['prj_id'] = divSelector + ' select#prj_id'
					params = {selector:tmp};
					break;
				case 'select#testcase_type_id':
					target = [{selector:divSelector + ' select#testcase_module_id', type:'select', field:'testcase_type_ids', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/linkage/db/xt/table/testcase_module'}];
					srcDiv = {selector:divSelector + ' select#testcase_type_id'};
					params = {};
					if(isSpecial != "undefined" && isSpecial == true){
						target = [{selector:divSelector + ' select#testcase_module_id', type:'select', field:'testcase_type_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/testcase_module/db/' + db + '/table/' + table}];
					}
					break;		
			}
			if(target != 'undefined'){
				if(isSpecial != "undefined" && isSpecial == true && isBind == true)
					tool.linkage(srcDiv, target, params, false);
				else
					tool.linkage(srcDiv, target, params);
			}
		}
	};
	
	// update (add or remove) env for cases
	$table.prototype.updateEnv = function(params, postData){
		// var $this = this;
		var dstTable = 'test_env';
		var div_id = "div_select_env";
		var parent = postData.parent;
		// open grid of table env for dialog.
		var _grid, _gridSelector;	
		
		var fun_addenv = function(isDel){
			var _selectedRows = $(_gridSelector).getGridParam('selarrrow');
			var test_env_id = JSON.stringify(_selectedRows);
			postData = $.extend(true, postData, {test_env_id: test_env_id, isDel: isDel});
			
			$.post('/jqgrid/jqgrid', postData, function(data){
				var grid = grid_factory.get(params.db, params.table, {container: params.container, parent: parent});
				var filters = JSON.stringify({groupOp:'AND', rules:[{"field":"cycle_id","op":"eq","data":parent}]});
				//grid.setParams($.extend(true, params, {p_id:parent, parent:parent, filters:filters}));
				grid.indexInDiv({filters:filters});
			});
		};
		
		var defaultParams = tool.defaultDialogParams();
		var dialog_params = {
			title: 'Select Resource',
			width: 1024,
			height: 600,
			div_id: div_id,
			close: function(event, ui){
				$(this).remove();
			},
			open:function(){
				_grid = grid_factory.get(params.db, dstTable, {container: div_id});
				_gridSelector = _grid.getParams('gridSelector');
				return _grid.load();
			}
		};
		var dialogParams = $.extend(true, defaultParams, dialog_params);
		var url = '/jqgrid/index/db/' + params.db + '/table/' + dstTable + '/container/' + div_id + '/parent/' + parent;
		var buttons = {
			'Add': function(){
				// need id & name
				fun_addenv(0);
				$(this).dialog('close');
			},
			'Del': function(){
				// need id & name
				fun_addenv(1);
				$(this).dialog('close');
			},
			// 'Modify & Add': function(){
				// need id & name
				// fun_addenv(2);
				// $(this).dialog('close');
			// },
			Close: function(){
				$(this).dialog('close');
			}
		};

		dialogParams.buttons = buttons;
		tool.actionDialog(dialogParams, url);	
	};
	
	// update (add or remove) trickmode for stream
	$table.prototype.updateTrickMode = function(params, postData){
		var $this = this;
		var dstTable = 'testcase';
		var div_id = 'div_stream_action';
		var parent = postData.parent;
		var gridSelector;
		
		// open grid of table codec stream
		var fun_stream_action = function(isDel){
			var actions = $(gridSelector).getGridParam('selarrrow');
			postData = $.extend(true, postData, {actions: JSON.stringify(actions), isDel: isDel});
			
			$.post('/jqgrid/jqgrid', postData, 
				function(data, status){
					if(isDel == 1 && data == 'success')
						alert("Delete Actions Successfully!!!");
					else if(isDel == 0 && data == 'success')
						alert("Add Actions Successfully!!!");
					var grid = grid_factory.get(params.db, params.table, {container: params.container, parent: parent});
					var filters = JSON.stringify({groupOp:'AND', rules:[{"field":"cycle_id","op":"eq","data":parent}]});
					//grid.setParams($.extend(true, params, {p_id:parent, parent:parent, filters:filters}));
					grid.indexInDiv({filters:filters});
				}
			);
		};
		
		var url = '/jqgrid/index/db/'+ params.db + "/table/" + dstTable + "/container/" + div_id + '/parent/' + parent;
		// default params for trikmode update dialog
		var dialog_params = {
			height:600,
			width: 1024,
			modal: true,
			div_id: "div_stream_action",
			autoOpen:false,
			close: function(){$(this).remove();},
			open: function(){
				var grid = grid_factory.get(params.db, dstTable, {container: div_id});
				var conditionSelector = grid.getParams('conditionSelector');
				gridSelector = grid.getParams('gridSelector');
				
				var tmpFields = {prj_ids: "prj_id", testcase_type_ids: "testcase_type_id"};
				$.each(tmpFields, function(i, n){
					var selector = "#view_edit_" + parent + " #div_view_edit";
					var val = $(selector + " #" + i).attr('value');
					if(val != undefined)
						$(conditionSelector + " #" + n).attr('value', val).attr('disabled', true);
				});
				
				$(conditionSelector + " #isactive").attr('value', '1').attr('disabled',true);
				//test_module:uniformed_trimodes
				$(conditionSelector + " #testcase_module_id").attr('value', '507').attr('disabled',true);
				$(conditionSelector + " #query_new").remove();
				$(conditionSelector + " #query_import").remove();		
				$this.setLinkage('div' + conditionSelector, ['select#testcase_type_id'], true);
			},
			buttons: {
				"Add": function() {
					fun_stream_action(0);
					$(this).dialog( "close" );
				},
				"Del": function() {
					fun_stream_action(1);
					$(this).dialog( "close" );
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			}
		};
		$.get(url, function(data){
			var mydialog = $('<div id="' + div_id + '" title="add or delete action for stream?"></div"').html(data).dialog(dialog_params);
			mydialog.dialog('open');
		});
	};
	
	// after save
	$table.prototype.view_edit_afterSave = function(divId, id, p_id, data){
		$('#' + divId).setRowData(id, data);
	};
		
}());
