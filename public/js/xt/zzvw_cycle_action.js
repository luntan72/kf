// JavaScript Document
(function(){
	var DB = KF_GRID_ACTIONS.xt;
	
	const GROUP_MQX = '6', GROUP_KSDK = '7', GROUP_USB = '10', GROUP_KIBBLE='12', GROUP_LINUXBSP = '1', GROUP_FAS = '9';
	
	DB.zzvw_cycle = function(grid){
		$table.supr.call(this, grid);
	};

	var $table = DB.zzvw_cycle;
	var tool = new kf_tool();
	tool.extend($table, gc_grid_action);
	
	//for the button in the cycle tab
	$table.prototype.buttonActions = function(action, p){
		var $this = this;
		var url, ret = true;
		var params = $this.getParams();
		var selectedRows = $(params.gridSelector).getGridParam('selarrrow');
		params.element = JSON.stringify(selectedRows);
		// var postData = {db: db, table: table, "element": "element"};	
		switch(action){
			// new a cycle
			case 'query_new':	
				url = '/jqgrid/jqgrid/newpage/1/oper/information/db/' + params.db + '/table/' + params.table + '/element/0/parent/0';
				window.open(url, '_blank');
				break;
			// import file to cycle
			case 'query_import':
				params = $.extend(true, params, {cycle_id: 0, real_table: params.table + "_detail", tabSelector: '#' + p.divId});
				$this.imports(params);
				break;
			// freeze current cycle
			case 'freeze':
				if (tool.checkSelectedRows(selectedRows, 1)){
					params = $.extend(true, params, {oper: action, flag: 1});
					$.post('/jqgrid/jqgrid', params, function(data, status){
						$(gridSelector).trigger('reloadGrid');
					});
				}
				break;
			// clone cycle
			case 'clone':
				if (tool.checkSelectedRows(selectedRows, 1)){
					$this.clone(gridSelector, params);
				}
				break;
			// not use yet
			case 'set_group':
				if (tool.checkSelectedRows(selectedRows, 1)){
					url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/set_group';	
					var dialogParams = {div_id: 'div_set_group', width: 400, height: 200, title: 'Set Group', postData: params};
					tool.actionDialog(dialogParams, url, undefined, function(){
						$(gridSelector).trigger('reloadGrid');
					});
				}
				break;
			// export all kind of file
			case 'export':
				if (tool.checkSelectedRows(selectedRows, 1)){
					params.sqls = $(params.hiddenSelector + " #sqls").val();
					$this.exports(params);
				}
				break;
			// swith from singe to multi with + button
			case 'single_to_multi':
				var td = $(p).parent('td').prev('td.cont-td'),
					se = td.children('select');
				var str = tool.single2multi(se);
				td.html(str);
				$(p).button('destroy');
				$(p).attr('id', 'multi_to_single');
				$(p).val('-');
				$(p).html('-');
				$(p).attr('title', 'Change to single selection');
				$(p).button();
				$(p).unbind('click').bind('click', function(){
					$this.buttonActions('multi_to_single', p);
				});
				var tmpFields = ["os_id", "prj_ids", "prj_id", "compiler_ids", "build_target_ids", "testcase_type_ids"];
				$this.initCart(params.conditionSelector, tmpFields);
				$(td).find('#cart_add_' + se.attr('id')).click();
				break;
			default:
				ret = $table.supr.prototype.buttonActions.call(this, action, p);						
		}
		return ret;
	};
	
	// For the clone button in the cycle information page
	$table.prototype.clone = function(gridSelector, params){
		var oper = 'cloneall';
		var div_id = 'div_clone_all';
		
		var dialog_params = {
			div_id: div_id,
			height: 300,
			width: 600,
			modal: true,
			autoOpen: false,
			close: function(){$(this).remove();},
			open: function(){
				$('#' + div_id + ' input[date="date"]').each(function(i){
					tool.datePick(this);
				});
			},
			buttons: {
				'Clone': function(){
					var dialog = $(this);
					var myname = $('#' + div_id + ' #myname').val();
					var checkData = params;
					checkData = $.extend(true, checkData, {oper: 'check_myname', myname: myname});
					
					$.post('/jqgrid/jqgrid', checkData, function(data){
						if(data){
							var datass = JSON.parse(data);
							alert(datass + "\n" + "Has Already Exists!!" + "\n" + "Pls Change To Another Name!!!");
						}
						else{
							var inputs = tool.getAllInput('#' + div_id).data;
							params.oper = oper;
							postData = $.extend(true, params, inputs);
							$.post('/jqgrid/jqgrid', postData, function(data, status){
								// need a clear jugement here
								alert('Clone Successfully');
								$(gridSelector).trigger('reloadGrid');
							});
							dialog.dialog( "close" );
						}
					});
				},
				Cancel: function(){
					$(this).dialog( "close" );				
				}
			}
		};
		var url = "/jqgrid/jqgrid/db/" + params.db + "/table/" + params.table + "/oper/" + oper;
		var dialogParams = $.extend(true, dialog_params, {html_type:'url', text:url});
		tool.actionDialog(dialogParams, url);
	};
	
	// download dapeng info of case & prj not in db
	$table.prototype.download = function(params){
		var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/download/element/' + params.element_id;	
		var div_id = 'div_download_req';
		var dialogParams = {
			div_id: div_id, 
			width: 600, 
			height: 200, 
			title: 'Select Case Add Type',
			close: function(){$(this).remove();},
			buttons: {
				ok: function(){
					var type = tool.getAllInput('div#' + div_id).data.download_type;
					switch(type){
						case 'download_type_dapeng':
							$.post("/jqgrid/jqgrid", {db:params.db, table:params.table, id:params.element_id, oper:'get_none_cases'}, function(data){
								if(data)
									location.href = "/download.php?filename=" + encodeURIComponent(data) + "&remove=0";
								else
									alert("Cases All Exist!!!");
							})
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
	}
	// For that export button in the cycle list grid
	$table.prototype.exports = function(params){
		var postData = {element: params.element, sqls: params.sqls, };
		var dialog_params = {
			width: 600,
			height: 400,
			title: 'Export',
			div_id: 'export_options',
			postData: postData,
			
			open: function(){
				var exportSelector = '#export_options #div_for_report_options';
				$(exportSelector).hide();
				$(exportSelector + " #cart_add_prj_id").attr('required', false);
				$(exportSelector + " #cart_reset_prj_id").attr('required', false);
				$(exportSelector + " #cart_clear_prj_id").attr('required', false);
				$('#export_options input:radio[name="export_type"]').change(function(event){
					if ($(this).is(':checked')){
						switch($(this).val()){
							case 'excel_testplan_detail_full':
								$(exportSelector).show();
								$(exportSelector + " #cart_add_prj_id").attr('required', true);
								$(exportSelector + " #cart_reset_prj_id").attr('required', true);
								$(exportSelector + " #cart_clear_prj_id").attr('required', true);
								break;
							default:
								$(exportSelector).hide();
								$(exportSelector + " #cart_add_prj_id").attr('required', false);
								$(exportSelector + " #cart_reset_prj_id").attr('required', false);
								$(exportSelector + " #cart_clear_prj_id").attr('required', false);
								break;
						}
					}
				});
			}
		};
		var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/export';
		tool.actionDialog(dialog_params, url, undefined, function(data){
			location.href = "/download.php?filename=" + encodeURIComponent(data) + "&remove=1";
		});
	};
	
	// free cycle
	$table.prototype.freeze = function(params){
		var $this = this;
		$.post('/jqgrid/jqgrid', {db:params.db, table:params.table, element:JSON.stringify([params.cycle_id]), oper:'freeze'}, 
			function(data, status){
				$(params.tabSelector + ' #view_edit_' + params.cycle_id + ' #div_button_edit').html(data);
				$(params.tabSelector).tabs('destroy').tabs({selected: 'tabs-current'});
				$this.information_open(params.tabSelector.substr(1), params.cycle_id);
			}
		);
	};
	
	// keyword auto complete
	$table.prototype.getAutoCompleteField = function(){
		return [{input:'name', field:'name'}];
	};
	
	//???
	$table.prototype.generateReport = function(params){
		//var dialog = XT.waitingDialog();
		var $this = this;
		$.ajax({
			type:'POST', 
			url:'/jqgrid/jqgrid', 
			data:{db:params.db, table:params.table, table_name:params.table, oper:'report', element:params.cycle_id}, 
			success: function(data, status){
					//dialog.dialog('close');
					if (data == 1004){ // not found the action
						alert("Sorry, this feature report is not implemented yet");
					}
					else{
						if(data){
							var datas = JSON.parse(data);
							location.href = "/download.php?filename=" + encodeURIComponent(datas.file_name) + "&remove=1";
						}
						else
							alert("No Report");
					}
				},
			error: function(httpReq, textStatus, errorThrown){
				alert("Error!! " + textStatus);
			}
		});
	};
	
	// prepare for cycle tabs of cycle information open page
	$table.prototype.getGridsForInfo = function(divId){
		var grids = [
			{tab:'cycle_detail', container:'cycle_detail', table:'zzvw_cycle_detail', params:{real_table:'cycle_detail'}},
			{tab:'cycle_stream', container:'cycle_stream', table:'zzvw_cycle_detail_stream', params:{real_table:'cycle_detail'}},
		];
		return grids;
	};	
	
	// for the buttons of cycle information open page
	$table.prototype.infoBtnActions = function(action, p){
		var $this = this;
		var td, st, url, cart, se;
		var params = $this.getParams();
		params = $.extend(true, params, {real_table: params.table + "_detail", tabId: p.divId, tabSelector: '#' + p.divId});
		params.element_id = $(params.tabSelector + " #div_hidden #element_id").val();
		params.cycle_id = params.element_id;	
		switch(action){
			case 'download':
				//download cases not exist
				$this.download(params);
				break;
				
			case 'generate_report':
				$this.generateReport(params);
				break;
				
			case 'inside_freeze':
				$this.freeze(params);
				break;
			
			case 'multi_to_single':
				p = p.item;
				td = $(p).parent('td').prev('td.cont-td');
				cart = td.children('div');
				str = tool.multi2single(cart);
				td.html(str);

				$(p).button('destroy');
				$(p).attr('id', 'single_to_multi');
				$(p).val('+');
				$(p).html('+');
				$(p).attr('title', 'Change to multi-selection');
				$(p).button();
				$(p).unbind('click').bind('click', function(){
					$this.buttonActions('single_to_multi', p);
				});
				break;
				
			case 'remove_combination':
				params.parents = $this;
				$this.removeCombination(params);
				break;
				
			case 'run':
				url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/run/element/' + params.element_id;	
				var dialogParams = {
					width: 800, 
					height: 400, 
					title: 'Run the cycle',
					div_id: 'run_cycle',
					close: function(){$(this).remove();},
				};
				var fun_validate = function(data){
					if(data.token_ids.length === 0){
						alert("The token is required");
						return false;
					}
					return true;
				};
				var fun_complete = function(data, params){
					if(data != '0'){
						$('#run').attr('id', 'wait');
						$('#wait').val('Refresh');
					}
				};
				
				tool.actionDialog(dialogParams, url, fun_validate, fun_complete);
				break;
				
			case 'script':
				$this.script(params);
				break;	
				
			case 'single_to_multi':
				p = p.item;
				td = $(p).parent('td').prev('td.cont-td');
				se = td.children('select');
				str = tool.single2multi(se);
				td.html(str);
				$(p).button('destroy');
				$(p).attr('id', 'multi_to_single');
				$(p).val('-');
				$(p).html('-');
				$(p).attr('title', 'Change to single selection');
				$(p).button();
				$(p).unbind('click').bind('click', function(){
					$this.buttonActions('multi_to_single', p);
				});
				var div = params.tabSelector;
				var tmpFields = ["os_id", "prj_ids", "prj_id", "compiler_ids", "build_target_ids", "testcase_type_ids"];
				$this.initCart(div, tmpFields);
				$(td).find('#cart_add_' + se.attr('id')).click();
				break;
				
			case 'stop':
				var buttons = {
					No:function(){
						$(this).dialog('close');
					},
					Yes:function(){
						$(this).dialog('close');
						var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/stop/element/' + params.element_id;	
						$.post(url, function(data){
							// alert(data);
							$('#stop').attr('id', 'run');
							$('#run').val('Run');
						});
					}
				};
				tool.optionsDialog("Do you really want to stop running the cycle?", "Stop Running", buttons);
				break;
			
			case 'update_dp':
				params.parents = $this;
				$this.updateDp(params, p);
				break;
			
			case 'unfreeze':
				$this.unfreeze(params);
				break;
				
			case 'uploadfile':
				$this.imports(params);
				break;
			
			case 'update':
				$this.updateFromOtherCycle(params);
				break;
				
			case 'view_edit_export':
				$this.view_edit_export(params);
				break;
				
			case 'wait':
				url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/get_request_status/element/' + params.element_id;	
				$.post(url, function(data){
					if(data == '1' || data == '2' || data == 1 || data == 2){ //waiting or running
						alert("The request is running");
						$('#wait').attr('id', 'stop');
						$('#stop').val('Stop');
					}
					else if(data == '6' || data == 6){
						alert("The request is still in init");
						//do nothing
					}
					else{
						alert("No request is running, you can start a new request");
						$('#wait').attr('id', 'run');
						$('#run').val('Run');
					}
				});
				break;
			default:
				return $table.supr.prototype.infoBtnActions.call(this, action, p);
		}
	};
	
	// processure of a cycle information open page
	$table.prototype.information_open = function(divId, element_id, pageName){
		var $this = this;
		var gridId = $this.getParams('gridId');
		var tabSelector = "#" + divId;
		//执行父类函数
		pageName = pageName || 'all';

		if( pageName != "cycle_overnight" ){
			$table.supr.prototype.information_open.call(this, divId, element_id, pageName);
			var div = tabSelector + " #view_edit_" + element_id;
			$(div + " #ces_tr_new_zzvw_mcuauto_request_ids").hide();
			var tmpFields = "";
			if(element_id == 0){
				// hide some field & single button by default when open a new cycle page
				tmpFields = ['#ces_tr_compiler_ids', '#ces_tr_build_target_ids', '#ces_tr_os_id #single_to_multi', 
					'#ces_tr_prj_ids #single_to_multi', '#ces_tr_testcase_type_ids #single_to_multi'];
				$.each(tmpFields, function(i, n){
					$(div + ' ' + n).hide();
				});
			}
			else{
				// hide multi button by default
				$(div + ' #multi_to_single').each(function(i){
					$(this).hide();
				});
				$(div + ' #single_to_multi').each(function(i){
					$(this).attr('disabled', true);
				});
				// hide these by default
				tmpFields = ['os_id', 'chip_id', 'board_type_id'];
				$.each(tmpFields, function(i, n){
					$(div + ' #ces_tr_' + n).hide();
				});
			}
			// hide when open cycle
			tmpFields = ['create_type', 'zzvw_mcuauto_request_ids', 'tag', 'template'];
			$.each(tmpFields, function(i,n){
				$(div + ' #ces_tr_' + n).hide();
			});
			
			// disable these
			$(div + " #tag").attr('disabled', true);
			$(div + " #template").attr('disabled', true);
				
			// add assistant_owner fields from testers
			if($(div + " #assistant_owner_id").attr("value") == 0){
				var db = $this.getParams('db'), table = $this.getParams('table');
				$(div + " #assistant_owner_id").unbind('click').bind('click', {div:div}, function(event){
					var inputs = tool.getAllInput(div);
					var tester_ids = inputs.data.tester_ids;
					if(tester_ids.length > 1){
						$.post("/jqgrid/jqgrid", {db:db, table:table, oper:"get_linkage", dst:"assistant_owner", element:element_id, tester_ids:tester_ids}, function(data){
							$(div + " #assistant_owner_id").find('option').remove();
							var options = tool.str2Array(JSON.parse(data));
							tool.generateOptions($(div + " #assistant_owner_id"), options, 'id', 'name', true);
							$(div + " #assistant_owner_id").unbind('click');
						});
					}
				});
			}
			
			// cart initialize
			var tmpFields = ["os_id", "prj_ids", "prj_id", "compiler_ids", "build_target_ids", "testcase_type_ids", "zzvw_mcuauto_request_ids"];
			$this.initCart(div, tmpFields);
			
			// format cycle name when new cycle
			$this.initName(div);
			
			// linkage
			$this.setLinkage('div' + div);
			$this.linkage(div, element_id);
		}
		else{
			alert("Update Successfully!");
		}
	};
	
	// init cart when a cycle infomation page opened
	$table.prototype.initCart = function(div, targetFields){
		var os_id;
		var db = this.getParams("db");
		var table = this.getParams("table");
		
		var val = $(div + ' #os_id').val();
		var tmpFields = targetFields;
		if(val)
			os_id = val;
		else{
			$(div + ' input[name="os_id[]"]').each(function(i){
				if(os_id == undefined)
					os_id = $(this).val();
				else
					os_id = os_id + "," + $(this).val();
			});
		}
		// default init
		$.each(tmpFields, function(i, n){
			var selector = div + " #cart_add_" + n;
			var onclick = $(selector).attr('onclick');
			$(selector).removeAttr('onclick').unbind('click').bind('click', function(){
				var rules = [], cart_data = {};
				switch(n){
					case "os_id":
						var group_id = $(div + ' #group_id').val();
						if (group_id > 0)
							rules.push({field:'groups_id', op:'in', data:group_id});
						break;
					case "prj_id":
					case "prj_ids":
						var board_type_id = $(div + ' #board_type_id').val(), 
							chip_id = $(div + ' #chip_id').val();
						if (os_id != undefined && os_id > '0')
							rules.push({field:'os_id', op:'in', data:os_id});
						if (board_type_id > 0)
							rules.push({field:'board_type_id', op:'eq', data:board_type_id});
						if (chip_id > 0)
							rules.push({field:'chip_id', op:'eq', data:chip_id});
						break;
					case "compiler_ids":
					case "build_target_ids":
					case "testcase_type_ids":
						if (os_id != undefined && os_id > '0')
							rules.push({field:'os_id', op:'in', data:os_id});
						break;
				}
			if (rules.length > 0){
					var newTable = n.substr(0, n.lastIndexOf("_"));
					cart_data.filters = JSON.stringify({groupOp:'AND', rules:rules});
					tool.selectToCart(n, db, newTable, newTable, cart_data);
				}
				else
					$(selector).removeAttr('click').attr('onclick', onclick);
			});
		});
		
		// special init
		if(div == '#import_div'){
			$(div + ' #cart_add_prj_ids').removeAttr('onclick').unbind('click').bind('click', function(){
				var prj_ids = 'undefined';
				var single_multi = $(div + ' #div_cart_prj_ids').attr('single_multi');
				single_multi = JSON.parse(single_multi);
				var data = JSON.parse(single_multi.data);
				var cart_data = {};
				cart_data.filters = data.filters;
				tool.selectToCart('prj_ids', db, 'prj', 'Project', cart_data);
			});
		}
	};
	
	// init week & myname from cycle name when open a cycle information page
	$table.prototype.initName = function(div){
		var cycleName = $(div + ' #name').attr("value");
		if(cycleName != '' && cycleName != undefined){
			var name = cycleName.match(/^.*-\d{2}WK(\d{2})-(.*)$/);
			if(name != undefined){
				var week = name[1];
				var myname = name[2];
				$(div + ' #myname').attr("value", myname).attr("original_value", myname);
				$(div + ' #week').attr("value", week).attr("original_value", week);
			}
		}
	};
	
	// format cycle name when edit, clone or new a cycle
	// show or hide sing2multi or multi2single button when cycle page is opened
	// bind event to create type when new a cycle
	$table.prototype.linkage = function(div, element_id){
		var params = {
			formatName: function(event){
				var div = event.data.div; 
				var prj = "", cycleName = "";	
				var myname = $(div + " #myname").val();
				switch(group_id){
					case GROUP_MQX:
					case GROUP_KSDK:
					case GROUP_USB:
					case GROUP_KIBBLE:
					case '0':
						break;
					default:
						prj = $(div + " #prj_ids").find("option:selected").text(); 
						break;
				}
				// don't change this sequence
				var createType = $(div + " #create_type").val();
				var tmpFields = ["group_id", "prj_ids", "cycle_type_id", "rel_id", "week"];
				$.each(tmpFields, function(i, n){
					var val = $(div + " #" + n).find("option:selected").text();
					if(cycleName == "")
						cycleName = val;
					else{
						if(n == "prj_ids" && prj == ""){
							return;
						}
tool.debug(n);
						cycleName = cycleName + "-" + val;
					}
				});
				
				if('' != myname)
					 cycleName =  cycleName + '-' + myname;
				$(div + " #name").val(cycleName);
				tool.checkUnique(div + " #name", {});
			},
			showHide: function(event){
				var div = event.data.div;
				var group_id = $(div + ' #group_id').val();
				var fields = ['compiler_ids', 'build_target_ids', 'create_type', 'compiler_ids #single_to_multi', 
					'prj_ids #single_to_multi', 'os_id #single_to_multi', 'testcase_type_ids #single_to_multi', 'zzvw_mcuauto_request_ids'];
				var show = false, linux_show = false, fas_show = false;
				
				switch(group_id){
					case GROUP_MQX:
					case GROUP_KSDK:
					case GROUP_USB:
					case GROUP_KIBBLE:
						show = true;
						break;
					case GROUP_LINUXBSP:
						linux_show = true;
						break;
					case GROUP_FAS:
						fas_show = true;
						break;
					case '0':
						linux_show = true;
						break;
					default:
						break;
				}
				$.each(fields, function(i, n){
					if(show){
						if(n == "create_type" && element_id > 0)
							$(div + ' #ces_tr_' + n).hide();
						else if(n == "zzvw_mcuauto_request_ids" && element_id == 0){
							var value = $(div + " #create_type").val();
							if(value != 2)
								$(div + ' #ces_tr_' + n).hide();
						}
						else
							$(div + ' #ces_tr_' + n).show();
					}
					else{
						$(div + ' #ces_tr_' + n).hide();
					}
					if(n == "create_type")
						$(div + " #create_type").val(0);
				});
				
				if(show){
					var new_fields = ['chip_id', 'board_type_id', 'os_id', 'prj_ids', 'testcase_type_ids', 'build_target_ids', 'compiler_ids'];
					$(div + " tr").each(function(i){
						var id = $(this).attr('id');
						if($.inArray(id, "ces_tr_" + new_fields) != '-1')
							$(this).show();
					});
					// $(div + " #ces_tr_zzvw_mcuauto_request_ids .e-pre").html('<span>DaPeng ReqId</span>:');
					// $(div + " #zzvw_mcuauto_request_ids").attr('required', true);
				}
				else{
					$(div + " #ces_tr_zzvw_mcuauto_request_ids .e-pre").html('<span>DaPeng ReqId</span>:');
					$(div + " #zzvw_mcuauto_request_ids").attr('required', false);
				}
				if(fas_show){
					$(div + ' #ces_tr_prj_ids #single_to_multi').show();
					$(div + ' #ces_tr_os_id #single_to_multi').show();
				}
				if(linux_show){
					$(div + ' #ces_tr_compiler_ids').hide();
					$(div + ' #ces_tr_compiler_ids #single_to_multi').hide();
				}	
			},
			createType: function(){
				var create_type = $(div + ' #create_type').val();
				var fields = ['ces_tr_chip_id', 'ces_tr_board_type_id', 'ces_tr_os_id', 'ces_tr_prj_ids',
						'ces_tr_testcase_type_ids', 'ces_tr_build_target_ids', 'ces_tr_compiler_ids'];
				if(create_type == 2){//dapeng
					//$(div + " #create_type").attr('disabled', true);
					$(div + " #ces_tr_zzvw_mcuauto_request_ids").show();
					//$(div + " #ces_tr_group_id").hide();
					$(div + " tr").each(function(i){
						var id = $(this).attr('id');
						if($.inArray(id, fields) != '-1')
							$(this).hide();
					});
					$(div + " #ces_tr_zzvw_mcuauto_request_ids .e-pre").html('<span>DaPeng ReqId</span><span style="color:red">*</span>:');
					$(div + " #zzvw_mcuauto_request_ids").attr('required', true);
				}
				else{
					$(div + " #create_type").val(0);
					$(div + " #ces_tr_create_type").hide();
					$(div + " #ces_tr_zzvw_mcuauto_request_ids").hide();
					$(div + " tr").each(function(i){
						var id = $(this).attr('id');
						if($.inArray(id, fields) != '-1')
							$(this).show();
					});
					$(div + " #ces_tr_zzvw_mcuauto_request_ids .e-pre").html('<span>DaPeng ReqId</span>:');
					$(div + " #zzvw_mcuauto_request_ids").attr('required', false);
				}
			}
		};
		
		if(element_id > 0){
			var event = {data:{div:div}};
			params.showHide(event);
		}		
		var tmpFields = ["prj_ids", "group_id", "rel_id", "cycle_type_id", "week", "myname"];
		$.each(tmpFields, function(i, n){
			var action = "change";
			if(n == "myname")action = "keyup";
			$(div + " #" + n).unbind(action, params.formatName).bind(action, {div:div}, params.formatName);
		});
		$(div + " #group_id").unbind('change', params.showHide).bind('change', {div:div}, params.showHide);
		$(div + " #create_type").unbind('change', params.createType).bind('change', {div:div}, params.createType);
	};
	
	// for import button of the cycle tab
	$table.prototype.imports = function(params){
		var $this = this;
		var div_id = 'import_div';
		var dialog_params = {
			html_type: 'url',
			text: '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/import/element/' + params.cycle_id,
			div_id: div_id, 
			width: 1000, 
			height: 450, 
			title: 'Upload',
			open: function(){
				$('#' + div_id + ' :button,:submit').button();
				$data = "<input id='element' type='hidden' name='element' value='" + params.cycle_id + "'>";
				$('#' + div_id + " #cycle_import_form").append($data);
				$('#' + div_id + ' :button').unbind('click').bind('click', function(){
					var id = $(this).attr('id');
					$this.infoBtnActions(id, {divId:div_id, item:this});
				});
			},
			close:function(){
				$(this).remove();
			}
		};
		tool.popDialog(dialog_params);
	};
	
    // enable linkage
	$table.prototype.ready = function(base){
		var conditionSelector = this.getParams('conditionSelector');
		$table.supr.prototype.ready.call(this, base);
		this.setLinkage(conditionSelector);
	};
		
	// For button of remove combination
	$table.prototype.removeCombination = function(params){
		$this = params.parents;
		var div_id = 'div_remove_combination';		
		var dialogParams = {
			width: 900, 
			height: 500,
			title: 'Remove Combination',
			div_id: div_id,
			open: function(){
				var divSelector = "#" + div_id ;
				var sources = ['select#prj_id', 'select#compiler_id'];
				$this.setLinkage(divSelector, sources);
				var str = '<button id="cart_add_seletct_items" cart="seletct_items" editable="1" type="button" style="position:relative;float:right">+</button>' + 
					'<button id="cart_reset_seletct_items" cart="seletct_items" editable="1" type="button" style="position:relative;float:right">-</button>';		
				$(divSelector + " #build_target_id").parent().append(str);
				
				$(divSelector + " #cart_add_seletct_items").unbind('click').bind('click', function(){
					var tmpStr = "";
					var val = {};
					var tmpFields = ["prj_id", "compiler_id", "build_target_id"];
					
					$.each(tmpFields, function(i, n){
						var tmpSelector = divSelector + " #" + n;
						var tmpVal = $(tmpSelector).val();
						var tmpData = $(tmpSelector + " option[value='" + tmpVal + "']").html();
						if(tmpData){
							if(tmpStr)
								tmpStr = tmpStr + " + " + tmpData;
							else
								tmpStr = tmpData;
						}
						val[n] = tmpVal;
					});
					val = JSON.stringify(val);
					var str = "<td><label><input type='checkbox' checked='checked' editable='editable' value='" +
						val + "' name='select_items[]'>" + tmpStr + "</label></td>";
					$(divSelector + " #fieldset_seletct_items table").append(str);
				});
				$(divSelector + " #cart_reset_seletct_items").removeAttr('onclick').unbind('click').bind('click', function(){
					$(divSelector + " #fieldset_seletct_items td").remove();
				});
			}
		};
		var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/remove_combination/id/' + params.element_id;
		tool.actionDialog(dialogParams, url, undefined, function(data){
			alert("Delete This Combination " + data + " cases From This Cycle!");
			$this.information_open(params.tabId, params.element_id);
		});
	};	
		
	// change when relavent filed changed		
	$table.prototype.setLinkage = function(divSelector, sources){
		var $this = this;
		var db = $this.getParams('db'), table = $this.getParams('table');
		sources = sources || ['select#os_id', 'select#board_type_id', 'select#chip_id', 'select#group_id'];
		var os_chip_board_type = {os_id:divSelector + ' select#os_id', chip_id:divSelector + ' select#chip_id', board_type_id:divSelector + ' select#board_type_id'};
		var id, target, prj_field = "";
		
		if($(divSelector + ' select#prj_ids').val() != undefined)
			prj_field = "prj_ids";
		else if($(divSelector + ' select#prj_id').val() != undefined)
			prj_field = "prj_id";
		
		var template = function(){
			var val = $(divSelector + ' select#group_id').val();
			if(val == 3 || val == 9){
				$(divSelector + " #tag").parent().parent().show();
				$(divSelector + " #template").parent().parent().show();
				$(divSelector + " #tag").attr('disabled', false);
				$(divSelector + " #template").attr('disabled', false);
			}
			else{
				$(divSelector + " #tag").parent().parent().hide();
				$(divSelector + " #template").parent().parent().hide();
				$(divSelector + " #tag").attr('disabled', true);
				$(divSelector + " #template").attr('disabled', true);
			}
		};
		for(var i in sources){
			target = undefined;
			switch(sources[i]){
				case 'select#os_id':
					target = [{selector:divSelector + ' select#testcase_type_ids', type:'select', field:'os_ids', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/testcase_type'},
								  {selector:divSelector + ' select#compiler_ids', type:'select', field:'os_ids', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/compiler'},
								  {selector:divSelector + ' select#' + prj_field, type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/zzvw_prj'},
								  {selector:divSelector + ' select#chip_id', type:'select', field:'os_id', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/getchip/db/' + db + '/table/zzvw_prj'},
								  {selector:divSelector + ' select#board_type_id', type:'select', field:'os_id', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/getboardtype/db/' + db + '/table/zzvw_prj'},
								  {selector:divSelector + ' select#rel_id', type:'select', field:'os_id', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/rel'}];
					tool.linkage({selector:divSelector + ' select#os_id'}, target, {selector:os_chip_board_type});
					break;
				case 'select#board_type_id':
					target = [{selector:divSelector + ' select#' + prj_field, type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/zzvw_prj'},
								  {selector:divSelector + ' select#chip_id', type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/getchip/db/' + db + '/table/zzvw_prj'},
								  {selector:divSelector + ' select#os_id', type:'select', field:'board_type_id', url:'/jqgrid/jqgrid/oper/getos/db/' + db + '/table/zzvw_prj'}];
					tool.linkage({selector:divSelector + ' select#board_type_id'}, target, {selector:os_chip_board_type});
					break;
				case 'select#chip_id':
					target = [{selector:divSelector + ' select#' + prj_field, type:'select', field:'chip_id', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/zzvw_prj'},
								 {selector:divSelector + ' select#board_type_id', type:'select', field:'chip_id', url:'/jqgrid/jqgrid/oper/getboardtype/db/' + db + '/table/zzvw_prj'},
								 {selector:divSelector + ' select#os_id', type:'select', field:'chip_id', url:'/jqgrid/jqgrid/oper/getos/db/' + db + '/table/zzvw_prj'}];
					tool.linkage({selector:divSelector + ' select#chip_id'}, target, {selector:os_chip_board_type});
					break;
				case 'select#group_id':
					$(divSelector + ' select#group_id').unbind('change', template).bind('change', template);
					target = [{selector:divSelector + ' select#os_id', type:'select', field:'groups_id', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/linkage/db/' + db + '/table/os'},
							{selector:divSelector + ' select#rel_id', type:'select', field:'groups_id', cond:'REGEXP', url:'/jqgrid/jqgrid/oper/get_linkage/dst/group_rel/db/' + db + '/table/' + table}
						];
					tool.linkage({selector:divSelector + ' select#group_id'}, target);
					break;
				case 'select#prj_id':
					//	prj_id-->compiler, build_target_id
					id = $("#div_hidden #id").attr("value");
					target = [{selector:divSelector + ' select#compiler_id', type:'select', field:'prj_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/compiler/db/' + db + '/table/' + table + "/id/" + id},
								  {selector:divSelector + ' select#build_target_id', type:'select', field:'prj_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/build_target/db/' + db + '/table/' + table + "/id/" + id}];
					tool.linkage({selector:divSelector + ' select#prj_id'}, target);
					break;
				case 'select#compiler_id':
					//	compiler_id + prj_id-->build_target_id
					id = $("#div_hidden #id").attr("value");
					target = [{selector:divSelector + ' select#build_target_id', type:'select', field:'compiler_id', url:'/jqgrid/jqgrid/oper/get_linkage/dst/build_target/db/' + db + '/table/' + table + "/id/" + id}];
					tool.linkage({selector:divSelector + ' select#compiler_id'}, target, {selector:{prj_id:divSelector + ' select#prj_id'}});
					break;
			}
		}
	};

	// unfree cycle
	$table.prototype.unfreeze = function(params){
		var $this = this;
		$.post('/jqgrid/jqgrid', {db:params.db, table:params.table, element:JSON.stringify([params.cycle_id]), oper:'unfreeze'}, 
			function(data, status){
				$(params.tabSelector + ' #view_edit_' + params.cycle_id + ' #div_button_edit').html(data);
				$(params.tabSelector).tabs('destroy').tabs({selected: 'tabs-current'});
				$this.information_open(params.tabSelector.substr(1), params.cycle_id);
			}
		);
	};

	// For button update dapeng
	$table.prototype.updateDp = function(params, p){
		var $this = params.parents;
		var div_id = 'div_download_req';
		var dialogParams = {
			div_id: div_id, 
			width: 1000, 
			height: 400, 
			title: 'Update DaPeng results to This cycle with dapeng request ids',
			close: function(){$(this).remove();},
			buttons: {
				update: function(){
					var zzvw_mcuauto_request_ids = [];
					$("#" + div_id + ' input[name="update_zzvw_mcuauto_request_ids[]"]').each(function(i){
						if(this.checked)
							zzvw_mcuauto_request_ids.push($(this).val()); 
					});
					$("#div_view_edit #new_zzvw_mcuauto_request_ids").val(zzvw_mcuauto_request_ids.join(","));
					$("#div_view_edit #img_unique_check").attr('src', '/img/aCheck.png');
					$("#div_view_edit").append("<input id='is_update_dp' type='hidden' name='is_update_dp' value='1'>");
					$this.view_edit_save(p);
					$("#div_view_edit #is_update_dp").val(0);
				},
				Cancel:function(event, ui){
					$(this).remove();
				}
			}
		};
		var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/update_dp/element/' + params.element_id;	
		tool.actionDialog(dialogParams, url);
	};
	
	// For linux to update result from another cycle
	$table.prototype.updateFromOtherCycle = function(params){
		var $this = this;
		var db = params.db;
		var table = params.table;
		var div_id = 'div_update_from_other';
		var gridSelector = "";
		var update = function(replaced){
			var selectedRows = $(gridSelector).getGridParam('selarrrow');
			var element = selectedRows;
			if(typeof selectedRows == 'object')
				selectedRows = selectedRows.length;
			if (selectedRows == 1){
				var postData = {db:db, table:table, oper:'update_from_other', element:element, replaced:replaced, parent:params.cycle_id};
				$.post('/jqgrid/jqgrid', postData, function(data){
					$(this).dialog( "close" );
					if(data)
						alert(data);
				});
			}
			else{
				alert("You can only select one cyce Once!");
			}
		};
		var dialog_params = {
			div_id: div_id,
			title: 'update Current Cycle From Other Cycel',
			height:600,
			width: 1120,
			modal: true,
			autoOpen:false,
			close: function(){$(this).remove();},
			open: function(){
				// initialize grid
				var grid = grid_factory.get(db, table, {container: div_id});
				grid.ready();
				// get grid params
				var divCond = 'div' + grid.getParams('conditionSelector');
				gridSelector = grid.getParams('gridSelector');
				// prj & testcase_type_id should not change
				var tmpFields = {prj_ids: "prj_id", testcase_type_ids: "testcase_type_id"};
				$.each(tmpFields, function(i, n){
					var val = $(params.tabSelector + ' #' + i).val();
					if(val !== undefined)
						$(divCond + " #" + n).attr('value', val).attr('disabled', true);					
				});
				
				// should not change
				tmpFields = ["os_id", "board_type_id", "chip_id"];
				$.each(tmpFields, function(i, n){
					$(divCond + " #" + n).attr('disabled', true);
				});
				
				// should remove
				$(divCond + " #query_new").remove();
				$(divCond + " #query_import").remove();
			},
			buttons:{
				'Update & Replace results': function(){	
					if(gridSelector !== "")
						update(true);
				},
				'Update & Not Replace results': function(){	
					if(gridSelector !== "")
						update(false);
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			}
		};
		var url = '/jqgrid/index/db/' + db + '/table/' + table + '/container/' + dialog_params.div_id;
		dialog_params = $.extend(true, dialog_params, {html_type:'url', text:url});	
		return tool.actionDialog(dialog_params, url);	
	};

	// for what?
	$table.prototype.updateInformationPage = function(verId, nodeId, pageName){
		var $this = this;
		var p = this.getParams(['db', 'table', 'container']);
		var tabId = 'information_tabs_' + p.db + '_' + p.table + '_' + nodeId;
		var tabSelector = '#' + tabId;
		var page = pageName + '_' + nodeId;
		var url = '/jqgrid/jqgrid/db/' + p.db + '/table/' + p.table + '/oper/update_information_page/page/' + pageName + '/element/' + nodeId;
		$(tabSelector + ' #' + page).load(url, function(data){
			$(tabSelector).tabs('enabled', page);
			$(tabSelector).tabs('option', 'selected', page);
			$this.information_open(tabId, verId, pageName);
		});
	};

	// processure after cycle is saved
	$table.prototype.view_edit_afterSave = function(divId, id, p_id, data){
		var $this = this;
		var p = this.getParams(['db', 'table', 'container']);
		var dialog = $('#' + divId);
		var element_id = dialog.find('#div_hidden #id').val();
		if(data.file)
			alert("!!! There are cases in DaPeng but not in XT !!!" + "\n<br >" + "Please click button <Download> at the bottom of page to download that cases!");
		window.location = '/jqgrid/jqgrid/newpage/1/db/' + p.db + '/table/' + p.table + '/oper/information/element/' + element_id + '/parent/0';
	};
	
	// // $table.prototype.view_edit_afterSave = function(divId, id, p_id, data){
		// // var gridId = this.getParams('gridSelector');
		// // $('#' + divId).find('#div_view_edit #div_addtional_clone_edit,#div_addtional_codec_clone_edit').remove();
		// // $(gridId).trigger('reloadGrid');
	// // };
	
    // cancel current edit action
	$table.prototype.view_edit_cancel = function(p){
		var $this = this;
		var divId = p.divId;
		var dialog = $('#' + divId);
		var node_id = dialog.find('div#div_hidden #id').val();
		if(node_id == 0)
			window.close();
		var view_edit_selector = "#view_edit_" + node_id;
		var group_id = dialog.find(view_edit_selector + ' #group_id').val();
		
		$table.supr.prototype.view_edit_cancel.call(this, p);
		
		var disabled = {prj_ids: false, compiler_ids: false, testcase_type_ids: false, build_target_ids: false};
		$.each(disabled, function(key, value){
			dialog.find("#div_cart_" + key + ' #cart_add_' + key).attr('disabled', value);
			dialog.find("#div_cart_" + key + ' #cart_clear_' + key).attr('disabled', value);
		});
		
		$('#' + divId).tabs('option', 'disabled', []);		
		disabled = {inside_freeze:false, unfreeze:false, addcase:false, uploadfile:false, script:false,
			os_id:true, board_type_id:true, chip_id:true, prj_ids:true, rel_id:true, 
			download:false, remove_combination:false, view_edit_export:false};
		$.each(disabled, function(key, value){
			dialog.find(view_edit_selector + ' #' + key).attr('disabled', value);
		});
		$('#' + divId + ' #ces_tr_os_id').hide();
		$('#' + divId + ' #ces_tr_chip_id').hide();
		$('#' + divId + ' #ces_tr_board_type_id').hide();
		
		dialog.find(view_edit_selector + ' #multi_to_single').each(function(i){
			$(this).attr('disabled', true);
		});
		dialog.find(view_edit_selector + ' #single_to_multi').each(function(i){
			$(this).attr('disabled', true);
		});
		if($('div#' + divId + " #zzvw_mcuauto_request_ids") != ""){
			$('div#' + divId + " #ces_tr_zzvw_mcuauto_request_ids").hide();
			$('div#' + divId + " #ces_tr_create_type").hide();
		}
		dialog.find('#div_view_edit #div_addtional_clone_edit,#div_addtional_codec_clone_edit').remove();
	};
	
	// clone cycle
	$table.prototype.view_edit_cloneit = function(p){
		var $this = this;
		var divSelector = '#' + p.divId;
		var dialog = $(divSelector);
		var db = dialog.find('#div_hidden #db').attr('value');
		var table = dialog.find('#div_hidden #table').attr('value');
		var cycle_id = dialog.find('#div_hidden #id').attr('value');
		var view_edit_selector = "#div_view_edit";//不要加上view_edit_ + id
		var group_id = dialog.find(view_edit_selector + ' #group_id').val();
		var oper = 'addtional';
		
		$table.supr.prototype.view_edit_cloneit.call(this, p);
		$(divSelector + ' #ces_tr_os_id').show();
		$(divSelector + ' #ces_tr_chip_id').show();
		$(divSelector + ' #ces_tr_board_type_id').show();
		$(divSelector).tabs('option', 'disabled', [1,2]);
		var disabled = {os_id:false, board_type_id:false, chip_id:false, prj_id:false, rel_id:false, testcase_id:false, test_env_id:false, 
			inside_freeze:true, unfreeze:true, addcase:true, uploadfile:true, script:true, download:true, remove_combination:true, 
			view_edit_export:true, prj_ids:false, group_id:false, testcase_type_ids:false, build_target_ids:false, compiler_ids:false};
		$.each(disabled, function(key, value){
			dialog.find("#view_edit_" + cycle_id + ' #' + key).attr('disabled', value);
		});
		
		disabled = {prj_ids: true, compiler_ids: true, testcase_type_ids: true, build_target_ids: true};
		$.each(disabled, function(key, value){
			dialog.find("#div_cart_" + key + ' #cart_add_' + key).attr('disabled', value);
			dialog.find("#div_cart_" + key + ' #cart_clear_' + key).attr('disabled', value);
		});
		
		$this.linkage('div#' + p.divId);
		var url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/' + oper + '/element/' + cycle_id;
		$.get(url, function(data, status){
			var oper = 'clone_stream';
			var input_csc = 'input[name="codec_stream_clone[]"]';
			var div_acce = '#div_addtional_codec_clone_edit';
			
			dialog.find(view_edit_selector).append(data);
			dialog.find(input_csc).unbind('change').bind('change', function(){	
				if (dialog.find(input_csc).attr('checked') == 'checked'){
					var id = dialog.find(div_acce).attr('id');
					if(typeof id == "undefined"){
						var c_url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/oper/' + oper + '/element/' + cycle_id;
						$.get(c_url, function(datas, status){
							dialog.find(view_edit_selector).append(datas);
						});
					}	
					else{
						dialog.find(div_acce).show();
					}
						
				}
				else{
					dialog.find(div_acce + ' :input').not(':disabled').each(function(i){
						var original_value = $(this).attr('original_value');
						switch($(this).attr('type')){
							case 'button':
								break;
							case 'checkbox':
								if(original_value == '1')
									$(this).attr('checked', true);
								else if (original_value == '0')
									$(this).attr('checked', false);
								break;
							default:
								$(this).val(original_value);
								break;
						}
					});
					dialog.find(div_acce).hide();
				}
			});
		});
	};
	
	// edit cycle
	$table.prototype.view_edit_edit = function(p){
		var $this = this;
		var divId = p.divId;
		var dialog = $('#' + divId);
		var db = dialog.find('#div_hidden #db').attr('value');
		var node_id = dialog.find('#div_hidden #id').attr('value');	
		var view_edit_selector = "#view_edit_" + node_id;
		var os_id = dialog.find(view_edit_selector + ' #os_id').val();
		var group_id = dialog.find(view_edit_selector + ' #group_id').val();
		var divSelector = '#' + divId + " " + view_edit_selector;
		
		$table.supr.prototype.view_edit_edit.call(this, p);
		
		// dialog.find('ul li:eq(1)').attr('disabled', true);
		$('#' + divId).tabs('option', 'disabled', [1,2]);
		var disabled = {inside_freeze:true, unfreeze:true, addcase:true, uploadfile:true, script:true, 
			download:true, remove_combination:true, view_edit_export:true, prj_ids:true, group_id:true, 
			testcase_type_ids:true, build_target_ids:true, compiler_ids:true, test_env_id:true};

		$.each(disabled, function(key, value){
			dialog.find(view_edit_selector + ' #' + key).attr('disabled', value);
		});
		
		var hided = {os_id:true, chip_id:true, board_type_id:true, create_type:true};
		$.each(hided, function(key, value){
			dialog.find(view_edit_selector + ' #ces_tr_' + key).hide();
		});
		
		dialog.find(view_edit_selector + ' #multi_to_single').each(function(i){
			$(this).hide();
		});
		if($('div#' + divId + " #zzvw_mcuauto_request_ids") != ""){
			var zzvw_mcuauto_request_ids = $('div#' + divId + ' #zzvw_mcuauto_request_ids').val();
			if(zzvw_mcuauto_request_ids != "" && zzvw_mcuauto_request_ids != 0){//dapeng
				//$('div#' + divId + " #zzvw_mcuauto_request_ids").attr('disabled', true);
				$('div#' + divId + " #ces_tr_zzvw_mcuauto_request_ids").show();
				$('div#' + divId + " #ces_tr_zzvw_mcuauto_request_ids .e-pre").html('<span>DaPeng ReqId</span><span style="color:red">*</span>:');
				$('div#' + divId + " #zzvw_mcuauto_request_ids").attr('required', true);
			}
			// else{
				// $('div#' + divId + " #ces_tr_create_type").show();
			// }
		}
		$this.linkage('div#' + divId);
	};
	
	// get all kind of cycle export
	$table.prototype.view_edit_export = function(params){
		var url = '/jqgrid/jqgrid/db/' + params.db + '/table/' + params.table + '/oper/view_edit_export/element/' + params.cycle_id;
		tool.actionDialog({div_id:'view_edit_export_div', width:400, height:300, title:'Export'}, url, undefined, function(data){
			location.href = "/download.php?filename=" + encodeURIComponent(data) + "&remove=1";
		});
	};
		
	// save after changed
	$table.prototype.view_edit_save = function(p){	
		var $this = this;
		var divId = p.divId;
		var dialog = $('#' + divId);
		var cloneit = dialog.find('#div_hidden #clone').val();
		var oper = (cloneit == "true") ? 'cloneit':'save';
		var node_id = dialog.find('div#div_hidden #id').val();
		var view_edit_selector = "#view_edit_" + node_id;
		var group_id = dialog.find(view_edit_selector + ' #group_id').val();
		$table.supr.prototype.view_edit_save.call(this, p);
		
		$('#' + divId).tabs('option', 'disabled', []);
		var disabled = {inside_freeze:false, unfreeze:false, addcase:false, uploadfile:false, script:false,
			download:false, remove_combination:false, view_edit_export:false};
		$.each(disabled, function(key, value){
			dialog.find('#div_button_edit #' + key).attr('disabled', value);
		});
		$('#' + divId + ' #ces_tr_os_id').hide();
		$('#' + divId + ' #ces_tr_chip_id').hide();
		$('#' + divId + ' #ces_tr_board_type_id').hide();
		
		dialog.find(view_edit_selector + ' #multi_to_single').each(function(i){
			$(this).attr('disabled', true);
		});
		dialog.find(view_edit_selector + ' #single_to_multi').each(function(i){
			$(this).attr('disabled', true);
		});
		if($('div#' + divId + " #zzvw_mcuauto_request_ids") != ""){
			$('div#' + divId + " #ces_tr_zzvw_mcuauto_request_ids").hide();
			$('div#' + divId + " #ces_tr_create_type").hide();
		}
		//dialog.find('#div_view_edit #div_addtional_clone_edit,#div_addtional_codec_clone_edit').remove();
		//tool.waitingDialog();
	};
	
	
	// $table.prototype.view_edit_save_input = function(divSelector){
		// var inputs = $table.supr.prototype.view_edit_save_input.call(this, divSelector);
		// var selector = $(divSelector).parent().attr("id");
		
		// inputs.data.unPackPath = $('iframe').contents().find('#unPackPath').attr('value');
		// return inputs;
	// };
	
}());