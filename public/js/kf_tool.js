XT = XT || {};

(function(){
	var loadedScript = {};
	this.debug = function($obj){
		if (window.console && window.console.log) {
			window.console.log($obj);
		}
	};
	
	this.timeStart = function($name){
		if (window.console && window.console.time) {
			window.console.time($name);
		}
	};
	
	this.timeEnd = function($name){
		if (window.console && window.console.timeEnd) {
			window.console.timeEnd($name);
		}
	};
	
	this.profile = function($name){
		if (window.console && window.console.profile) {
			window.console.profile($name);
		}
	};
	
	this.profileEnd = function($name){
		if (window.console && window.console.profileEnd) {
			window.console.profileEnd($name);
		}
	};
	
	this.isObject = function(obj){
		return (typeof obj=='object')&&obj.constructor==Object;
	};
	
	this.getDateStr = function(month, day){
		var today = new Date(), newM, newD;
		month = month || 0;
		day = day || 0;
		newM = today.getMonth() + month;
		newD = today.getDate() + day;
// this.debug([newM, newD]);
		today.setMonth(newM, newD);
		var dd = today.getDate();
		var mm = today.getMonth() + 1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd < 10) {
			dd = '0' + dd
		} 

		if(mm < 10) {
			mm = '0' + mm
		} 
// this.debug([yyyy, mm, dd]);
		return yyyy + '-' + mm + '-' + dd;
	};
	
	this.getParams = function(name, obj){
		var ret;
		if (name == null || name == undefined){
			ret = {};
			for(var i in obj){
				ret[i] = obj[i];
			}
		}
		else if($.isArray(name)){
			ret = {};
			for(var i in name){
				ret[name[i]] = obj[name[i]];
			}
		}
		else{
			ret = obj[name];
		}
		return ret;
	};
	
	this.setParams = function(p, obj, forced){
		if(forced == undefined) forced = true;
		p = p || {};
		for(var i in p){
			if (forced || !(i in obj)) // 不覆盖
				obj[i] = p[i];
		}
		return obj;
	};
	
	this.delParams = function(p, obj){
		for(var i in p){
			delete obj[p[i]];
		}
		return obj;
	};
	
	this.extend  = function(subCls,superCls) {    
		//暂存子类原型  
		var sbp = subCls.prototype;  
		//重写子类原型--原型继承  
		subCls.prototype = Object.create(superCls.prototype);
		//重写后一定要将constructor指回subCls  
		subCls.prototype.constructor = subCls;  
		//还原子类原型  
		for(var atr in sbp) {  
			subCls.prototype[atr] = sbp[atr];  
		}  
		subCls.supr = superCls;  
	};

	this.str2Array  = function(str){
		var ret = {}, a = [], b = [];
		if (typeof str == 'string'){
			a = str.split(';');
			for(var i = 0; i < a.length; i ++){
				b = a[i].split(':');
				ret[b[0]] = b[1];
			}
		}
		else	
			return str;
		return ret;
	};
	
	this.sendRequest = function(url, type, postData, fun_success, fun_fail, fun_error){
		fun_success = fun_success || function(msg){
			
		};
		fun_fail = fun_fail || function(msg){
			alert("No Enough Right");
		};
		fun_error = fun_error || function(request, textStatus, errorThrown){
			alert("ERROR:" + errorThrown.getMessage());
		};
		$.ajax({
			type:type,
			url:url,
			dataType:'json',
			data: postData,
			success: function(data, textStatus){
				if(data.errCode == undefined){
					return fun_success(data);
				}
				else if(data.errCode != undefined && data.errCode == 0){
					return fun_success(data.msg);
				}
				else{
					return fun_fail(data);
				}
			},
			error: function(request, textStatus, errorThrown){
				return fun_error(request, textStatus, errorThrown);
			}
		})
	};
	
	this.loadFile  = function(filePath, type){
		var cont;
		if (loadedScript[filePath] == undefined){
			type = type || 'js';
			if (type == 'js'){
				cont = '<script type="text/javascript" src="' + filePath + '"></script>'; 
			}        
			else if (type == 'css'){
				cont = '<script type="text/css" href="' + filePath + '"></script>';
			}
//this.debug(cont);		
			try{
				$('head').append(cont);    // 因异步的原因不能使用$.getScript
			}catch(e){
				// this.debug(">>>>>>>" + filePath + "<<<<<<<<");
			}
			loadedScript[filePath] = true;
	//this.debug(this.loadedScript);			
		}
	};

	this.createFunction  = function(obj,func){
		var args=[];
		if(!obj) obj = XT; //window;
		if (typeof func == 'string'){
			for(var i=2;i<arguments.length;i++)args.push(arguments[i]);
			return function(){
				obj[func].apply(obj,args);
			}
		}
		else{
			return function(){
				func.apply(obj, arguments);
			}
		}
	};

	this.newTab  = function(url, div4Tab, title, events){
		var $this = this;
		if (title == undefined)
			title = "Unknown Title";
		var id = div4Tab + ' #' + title.replace(/ /g, '');
		if($(id).length > 0){
			$(div4Tab).tabs('select', $(id).attr('href'));
		} 
		else{
			$(div4Tab).tabs({ajaxOptions:{type:'GET'}});
			if (events != undefined){
				for(var type in events){
					$(div4Tab).unbind(type).bind(type, events[type]);
				}
			}
			$(div4Tab).tabs('add', url, title);
		}
	};

	this.getTabId  = function(ele, tabSelector){
		tabSelector = tabSelector || '#mainContent > .ui-tabs-panel';
		var tab = $(ele).parents(tabSelector);
		return tab.attr('id');
	};
		
	this.go  = function(url){
		window.open(url);
	};
		
	this.ucwords  = function(str){
		return (str).replace(/^([a-z])|\s+([a-z])/g, function ($1) {
			return $1.toUpperCase();
		});
	};

	this.clearBox  = function(selector, value){
	//    debug(selector);
		if ($(selector).attr('value') == value)
			$(selector).attr('value', '');
	};

	this.jumpTo  = function(event, selector){
		if (event.keyCode == 13){
			$(selector).focus();
			$(selector).select();
		}
		return true;
	};

	this.beginEdit  = function(e, params){
		if (($e).attr('disabled') == true)
			$(e).attr('disable', false);
	};

	this.triggerButton  = function(event, selector){
		if (event.keyCode == 13){
			$(selector).focus();
			$(selector).click(); // 模拟一个click
		}
		return true;
	};

	this.datePick  = function(elem){
		if(!$(elem).attr('datepickinit')){
			$(elem).attr('datepickinit', true);
			$(elem).datepicker({dateFormat:'yy-mm-dd'});
		}
		// $(elem).click();
		// $(elem).datepicker('destroy').datepicker({dateFormat:'yy-mm-dd'});
	};

	this.getFileContent = function(elem, filename){
		if(!$(elem).attr('contentloaded')){
			$.post('/service/getfilecontent', {filename:filename}, function(data){
				$(elem).attr('title', data);
				$(elem).attr('contentloaded', true);
			});
		}
	};
	
	this.sameValue  = function(tr, ignoreCols){
		var same = true;
		var lastValue;
		ignoreCols = ignoreCols || [0];
		tr.find("td").each(function(i){
			if (ignoreCols.indexOf(i) == -1) {
				currentValue = $(this).html();
				if (lastValue != undefined && lastValue != currentValue)
					same = false;
				lastValue = currentValue;
			}
		});
		return same;
	};
	
	this.hideTheSame  = function(checkbox, event){
		var $this = this;
		var checked = $(checkbox).attr('checked');
		$(event.data.selector).each(function(){
			if (checked && $this.sameValue($(this)))
				$(this).hide();
			else
				$(this).show();
		});
	};
	
	// hilightTheNotSame : function(event){
		// var $this = this;
		// var checked = $(this).attr('checked');
// $this.debug($(this));
// $this.debug(checked);
// $this.debug(event.data);
		// $(event.data.selector).each(function(){
			// if (checked && !$this.sameValue($(this)))
				// $(this).addClass('hilight');
			// else
				// $(this).removeClass('hilight');
		// });
	// },
	
	this.replaceSelectOptions  = function(selectSelector, options, previousSelected){
		var data = [];
		$.each(options, function(i, n){
			data.push({id:i, name:n});
		});
		$(selectSelector).find('option').remove();
		this.generateOptions($(selectSelector), data, 'id', 'name', false);
	};
	
	this.generateCheckbox  = function(containerSelector, name, datapair, op, has_table, cols, id_field, name_field){ //op can be replace/append
		var del = [], current = 1, str = [], propStr = [];
		op = op || 'replace';
		has_table = has_table || true;
		cols = cols || 5;
		name_field = name_field || 'name';
		id_field = id_field || 'id';
XT.debug(name);
		
// XT.debug([has_table, cols, containerSelector]);
		// $(containerSelector + " label").remove();
		$(containerSelector).html('');
		$.each(datapair, function(i, obj){
// XT.debug(obj);
			if (obj != undefined){
				if(has_table == true){
					if(current == 1)
						str.push("<tr>");
					str.push('<td class="inter-table">');
				}
				propStr = XT.generatePropStr(obj, id_field, name_field);
// XT.debug(propStr);				
				str.push('<label><input type="checkbox" name="' + name + '" ' + propStr.join(' ') + '>' + obj[name_field] + "</label>");
				if(has_table){
					str.push("</td>");
					if(current == cols){
						str.push("</tr>");
						current = 0;
					}
					current ++;
				}
// XT.debug(str);				
				// $(containerSelector).append(str);
			}
		});
		if(current != cols)
			str.push("</tr>");
// XT.debug(str);
		$(containerSelector).append(str.join("\n"));
	};

	this.isEmail  = function(str){
       var reg = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
       return reg.test(str);
	};

	this.resetAllInput  = function(divSelector){
		var img = $(divSelector + ' #img_unique_check');
//debug(img);		
		if (img.length > 0 && !img.attr('disabled'))
			img.attr('src', '/img/aHelp.png');
		$(divSelector + " :input").each(function(i){
			$(this).removeClass('required_error');
			
			var original_value = $(this).attr('original_value');
			switch($(this).attr('type')){
				case 'button':
					break;
				case 'checkbox':
					if(original_value == '1')
						$(this).attr('checked', true);
					else if (original_value == '0')
						$(this).attr('checked', false);
					else
						$(this).attr('checked', false);
					break;
				case 'select':
				default:
					$(this).val(original_value);
					break;
			}
		});
		$(divSelector + ' :input:enabled:first').focus();
	};
/*	
	checkUnique : function(e, params){
//$this.debug("This is checkUnique");	
		if (!$(e).attr('unique')) return true;
		var field = $(e).attr('id');
		var img = $(e).parent().siblings('td:has(img)').find('img#img_unique_check')[0];
		var value = $(e).val();
		img.src = '/img/aHelp.png';
//$this.debug(e);		
		if (value != undefined && value != '' && value != null){
			var div = $(e).parents('div:has(#div_hidden)')[0];
			var hidden_div = $(div).find('#div_hidden');
			var db = $(hidden_div).find('#db').val() || params['db'], 
				table = $(hidden_div).find('#table').val() || params['table'], 
				isClone = $(hidden_div).find('#clone').val() || false,
				id = $(hidden_div).find('#id').val() || params['id'] || 0;
//$this.debug([db, table, isClone, id]);				
//$this.debug("isClone = " + isClone);				
			if (isClone == 'true')
				id = 0;
			$.ajax({
				url:'/jqgrid/jqgrid',
				type:'POST',
//				async: false,
				data: {oper:'checkUnique', db:db, table:table, field:field, value:value, id:id},
				success:function(data){
//alert("data = " + data);				
					if (data == '1')
						img.src = '/img/aCheck.png';
					else{
						img.src = '/img/b_drop.png';
					}
					return true;
				}
			});
		}
//$this.debug("End checkUnique");		
		return true;
	},
*/	
	this.getAllInput  = function(divSelector, ignored, multirowtemp){
		var $this = this;
// this.debug($(divSelector));	
		if(ignored == undefined)
			ignored = true;
		if(multirowtemp == undefined)
			multirowtemp = false;
		var $this = this;
		var params = {}, text = {};
		var passed = [];
		var inputName, label, input_id;
		var checkboxes = {}, checkboxes_text = {};
		var checkRequired = {};
		var uniqueClass = $(divSelector + ' .unique_unchecked'), uniq_id = uniqueClass.attr('id'), uniqText = $(divSelector + ' #' + uniq_id + '_label').html();
		// var uniqTd = uniqueClass.parent().pre();//.children('input;);
		// var uniqText = uniqueClass.parent().children('span:first').html();//find('input').attr('id');
//$this.debug(uniqText);		
		var tips = [];
//$this.debug(img);
//$this.debug(inputAfterImg);		
		$(uniqueClass).removeClass('unique_error');
		if (uniqueClass.length > 0/* && inputAfterImg.attr('readonly') != 'readonly'*/){
			passed.push('unique');
			tips.push(uniqText + ' is Not unique');
			$(uniqueClass).addClass('unique_error');
		}

		var selector = divSelector + " :input[multirowedit!='multirowedit']";
		if(ignored)
			selector += "[ignored!='ignored']";
		// if(!multirowtemp)
			// selector += "[multirowtemp!='multirowtemp']";
// this.debug(selector);		
		$.each($(selector), function(i, n){
			var required = n.required;//$(n).attr('required');
			var disabled = $(n).attr('disabled');
// $this.debug([i,$(n).attr('type'), n]);			
			if ($(n).attr('name') !== undefined)
				inputName = $(n).attr('name');
			else if ($(n).attr('id') !== undefined)
				inputName = $(n).attr('id');
			else{
//				alert("NO NAME, NO ID");
				return;
			}
// $this.debug([i, inputName, required]);
			input_id = $(n).attr('id');
			// 检查是否存在[],如存在，则去除
			var lastIndexOf = inputName.lastIndexOf('[]');
			if (lastIndexOf != -1)
				inputName = inputName.substring(0, lastIndexOf);
			if(input_id != undefined)
				label = $('#' + input_id + '_label').html();
			else
				label = $(n).parents('.cont-td').prev('td.e-pre').children('span:first').html();
			var n_type = $(n).attr('type');
// $this.debug(n_type);			
			switch(n_type){
				case 'button':
					//检查是否Cart Button
					var cartTable = $(n).attr('cart');
					required = $(n).attr('required');
					if (cartTable != undefined){
						if (checkboxes[cartTable] == undefined)
							checkboxes[cartTable] = [];
						if (required)
							checkRequired[cartTable] = 1;
						$.each($('table_' + cartTable + ' :checkbox'), function(j, m){
							if(m.checked){
								checkboxes[cartTable].push($(m).val());
								if(checkboxes_text[cartTable] == undefined)
									checkboxes_text[cartTable] = [];
								checkboxes_text[cartTable].push($(m).text());
							}
						});
					}
					break;
					
				case 'checkbox':
// $this.debug(inputName);
					if (checkboxes[inputName] == undefined)
						checkboxes[inputName] = [];
					if (required)
						checkRequired[inputName] = 1;
					if (n.checked){//$(n).attr('checked')){
						checkboxes[inputName].push($(n).val());
						if(checkboxes_text[inputName] == undefined)
							checkboxes_text[inputName] = [];
						checkboxes_text[inputName].push($(n).text());
					}
					break;
				case 'radio':
					params[inputName] = $(':radio[name=' + inputName + ']:checked').val();
					text[inputName] = $(':radio[name=' + inputName + ']:checked').attr('label') || $(n).parent('label').text();
					break;
				default:
					params[inputName] = $(n).val();
					text[inputName] = $(n).text();
// $this.debug(params);
// $this.debug(text);					
					if(n_type == 'select'){
						text[inputName] = $(n).find("option:selected").text();
					}
					else if(n_type == 'textarea'){
						text[inputName] = $(n).val();
					}
					else if(n_type == 'text')
						text[inputName] = $(n).val();
					if($(n).val() == null || params[inputName] == '' || params[inputName] == undefined || ((params[inputName] == 0 || params[inputName] == ' ' || params[inputName] == '')&& $(n).attr('type') == 'select')){
						params[inputName] = '';
						text[inputName] = '';
						if (required && disabled == undefined){
							passed.push(inputName);// = false;
							tips.push(label + ' is required');
							$(n).addClass('required_error');
						}
					}
					else{
						$(n).removeClass('required_error');
						var invalidChar = $(n).attr('invalidchar');
						if(invalidChar != undefined && invalidChar != ''){
							var pattern = new RegExp(invalidChar);
							if (pattern.test($(n).val())){
								passed.push(inputName);
								tips.push(label + ' has invalid char :' + invalidChar);
								$(n).addClass('required_error');
								$(n).attr('title', "There're invalid char " + invalidChar);
							}
						}
						var minval = $(n).attr('min'), maxval = $(n).attr('max'), intval = Number($(n).val());
						if (minval != undefined){
							if (intval < minval){
								passed.push(inputName);
								tips.push(label + ' must be >= ' + minval);
								$(n).addClass('required_error');
							}
						}
						if (maxval != undefined){
							if (intval > maxval){
								passed.push(inputName);
								tips.push(label + ' must be <= ' + maxval);
								$(n).addClass('required_error');
							}
						}
						if ($(n).attr('email') != undefined){
							if (!$this.isEmail($(n).val())){
								passed.push(inputName);
								tips.push(label + ' is NOT match the Email format');
								$(n).addClass('required_error');
								$(n).attr('title', "Not Email");
							}
						}
					}
			}
		})
	//debug(params);    	
		for(i in checkboxes){
			params[i] = checkboxes[i];
			text[i] = checkboxes_text[i];
			// 需要检查Checkbox是否required
			var fieldset = $("fieldset#fieldset_" + i);
			label = $(fieldset).parents('td.cont-td').prev('td.e-pre').children('span:first').html();
			if (checkRequired[i] != undefined && (params[i].length == 0 || params[i] == 0)){
				$(fieldset).addClass('required_error');
				passed.push(i);
				tips.push(label + ' is required');
			}
			else
				$(fieldset).removeClass('required_error');
		}
		// 检查有没有multirow
// $this.debug(divSelector + ' div[multirowedit="multirowedit"]');		
		$(divSelector + ' div[multirowedit="multirowedit"][ignored!="ignored"]').each(function(i_div){
// $this.debug($(this));
			var prefix = $(this).attr('id');
			var valuesTable = divSelector + ' #' + prefix + '_values';
			//需要检查是否required
			var required = $(this).attr('required'), input_id = $(this).attr('id'), label = $('#' + input_id + '_label').html();
			var values = [];
			$(valuesTable + ' > tbody > tr').each(function(i){
				var tr, row = {};
				if(i == 0)
					return true;
				$(this).find(':input').each(function(j){
					var id = $(this).attr('id');
					if(id != 'del'){
						row[id] = $(this).val();
						if($(this).attr('json'))
							row[id] = JSON.parse(row[id]);
					}
				})
				values.push(row);
			})
			params[prefix] = params[prefix] || {};
			params[prefix]['data'] = values;
// $this.debug([required, input_id, label]);
// $this.debug(values);
			if(required && values.length == 0){
				$(this).addClass('required_error');
				passed.push(0);
				tips.push(label + ' is required');
			}
			else{
				$(this).removeClass('required_error');
			}
		});
// $this.debug(passed);
// $this.debug(params);
// $this.debug(tips);
// $this.debug(text);

		return {passed:passed, data:params, tips:tips, text:text};
	};
	
	this.getHidden  = function(divSelector){
		var hidden = {}
		$(divSelector + ' :hidden').each(function(){
			hidden[$(this).attr('id')] = $(this).val();
		});
		return hidden;
	};
	
	this.checkSelectedRows  = function(selectedRows, limit, tips){
		var atleast, most, strTip = '', passed = true;;
		if (typeof limit == 'number')
			atleast = limit;
		else if (typeof limit == 'object'){
			atleast = limit.min;
			most = limit.max;
		}
		if(typeof selectedRows == 'object')
			selectedRows = selectedRows.length;
		
		if (atleast != undefined && selectedRows < atleast){
			strTip += " at least " + atleast;
			passed = false;
		}
		if (most != undefined && selectedRows > most){
			if (strTip.length > 0)
				strTip += ' AND ';
			strTip += " at most " + most;
			passed = false;
		}
		if(selectedRows == undefined)
			passed = false;
		if (!passed){
			strTip = 'Please Select ' + strTip + ' Record(s)';
			tips = tips || strTip;
		}
		
		if (!passed)
			alert(tips);
		return passed;
	};

	this.handleRequestFail = function(data){
		var ret = false;
		var $this = this;
		if(data == null) return false;
		if(typeof data == 'string'){
			var res = data.match(/^\{"errCode":\d+\}/);
// $this.debug(res);
			if(res != null){
				ret = $this.requestFail(JSON.parse(data));
			}
		}
		else if(data.errCode != undefined){
			ret = $this.requestFail(data);
		}
		return ret;
	};
	
	this.requestFail = function(data){
		switch(data.errCode){
			case 3: // not logined
				this.noticeDialog("You have no logined yet, please login first", "warning", undefined, 400, 200);
				break;
			
			case 4: // NO enough privilege
				this.noticeDialog("You have no enough privilege to do the operation", "warning", undefined, 400, 200);
				break;
		}
		return data.errCode;
	};

	this.defaultForActionDialog  = function(params, closeDialog){
		var $this = this;
		var data = $this.getAllInput('#' + params['div_id']);
// $this.debug(params);
// $this.debug(data);
		var validated = data['passed'].length == 0;
		var data_params = data['data'];
		if (closeDialog == undefined)
			closeDialog = true;
//debug(params);    			
//debug(data_params);
		if (params['fun_validation'] !== undefined){
			validated = params['fun_validation'](data);
		}
		if(validated){
//debug(myDialog);				
			if (closeDialog)
				$('#' + params['div_id']).dialog( "close" );
			if (params['postData'])
				$.extend(true, data_params, params['postData']);
			var dialog = $this.waitingDialog();
			$.post(params['text'], data_params, function(data){
				dialog.dialog('close');
				if (params['fun_complete'] !== undefined){
					params['fun_complete'](data, params);
				}
			});
		}
		else if (data['tips'].length > 0)
			alert(data['tips'].join('\n'));
	};
	
	this.defaultDialogParams  = function(){
		return {
			width:1024,
			height:800,
			autoOpen: false,
			title: 'Dialog',
			html_type:'text', // text or url
			text:'Notice....',
			modal: true,
			div_id:'div_id_tmp',
			zIndex:900,
			
			close: function(event, ui){
				$(this).html('');
				$(this).remove();
			},
			resize: function(event, ui){
				$(this).find('.ui-jqgrid-btable').setGridWidth($(this).width() - 45);
			}
		};
	};
		
	this.popDialog  = function(dialogParams){
		var myDialog;
		var $this = this;
		var params = this.defaultDialogParams();
		dialogParams = dialogParams || {};
		
		$.extend(true, params, dialogParams);
		if (params['html_type'] == 'text'){
			myDialog = $('<div id="' + params['div_id'] + '"></div>')
				.html(params['text'])
				.dialog(params);
			myDialog.dialog('open');
		}
		else{
			var wait_dialog = $this.waitingDialog();
			$('<div id="' + params['div_id'] + '"></div>').load(params['text'], function(data){
				wait_dialog.dialog('close');
				if($this.handleRequestFail(data) == false){
					myDialog = $(this).dialog(params);
					myDialog.dialog('open');
				}
			});
		}
		return myDialog;
	};

	this.waitingDialog  = function(dialogParams){
		dialogParams = dialogParams || {};
		dialogParams['modal'] = true;
		dialogParams['width'] = dialogParams['width'] || 300;
		dialogParams['height'] = dialogParams['height'] || 100;
		dialogParams['title'] = dialogParams['title'] || 'Processing...';
		dialogParams['text'] = dialogParams['text'] || 'Processing, please wait a moment...';
		return this.popDialog(dialogParams);
	};

	this.noticeDialog  = function(text, title, okbutton, width, height){
		var dialogParams = {html_type:'text', text:text, title:title, width:width || 500, height:height || 400};
		if(okbutton == undefined)
			dialogParams['buttons'] = {Close:function(){$(this).dialog('close');}};
		return this.popDialog(dialogParams);
	};

	this.optionsDialog  = function(text, title, buttons, width, height){
		var dialogParams = {html_type:'text', text:text, title:title, width:width || 500, height:height || 400};
		dialogParams['buttons'] = buttons;
		return this.popDialog(dialogParams);
	};

	this.actionDialog  = function(dialog_params, url, fun_validation, fun_complete){
		var $this = this;
		var dialogParams = $.extend(true, dialog_params, {html_type:'url', text:url, fun_validation:fun_validation, fun_complete:fun_complete});
	//$this.debug(dialogParams);
		if (dialogParams['buttons'] == undefined){
			dialogParams['buttons'] = {
				Ok:function(){
					$this.defaultForActionDialog(dialogParams);
				}, 
				Cancel:function(){
					$(this).dialog('close');
				}
			};
		}
		return this.popDialog(dialogParams);
	};

	this.dialogLoadGrid  = function(db, table, dialog_params, postData){
		var container = 'dialog_grid', base = container + '_' + db + '_' + table, table_id = base + '_list', pager_id = base + '_pager';
		postData = postData || {};
	
		dialog_params = dialog_params || {};
		dialog_params.div_id = container;
		// dialog_params.html_type = 'text';
		// dialog_params.text = '<table id="' + table_id + '"></table><div id="' + pager_id + '"></div>';
		dialog_params.html_type = 'url';
		dialog_params.text = 'jqgrid/index/db/' + db + '/table/' + table +'/container/' + container;
		dialog_params.open = function(){
			var grid = grid_factory.get(db, table, {container:container});
			grid.ready(postData);
		};
		this.popDialog(dialog_params);
	};
	
	this.defaultActionForTab  = function(tabId, selected){
		var $this = this;
		selected = selected || 0;
		$(tabId + ' input[date="date"]').each(function(i){
			$this.datePick(this);
		});
		var disabled = [];
		// gather the all disabled information to disable some tab-page
	//debug(tabId + ' li:disabled');			
		$(tabId + ' li').each(function(i){
	//debug(this);				
			if ($(this).attr('disabled') == 'disabled'){
				disabled.push(i);
				$(this).removeAttr('disabled');
				if (selected == i)
					selected ++;
			}
		});
		
	//		$(tabId).tabs('destroy').tabs({selected: 'tabs-current', disabled:disabled});		
		$(tabId).tabs('destroy').tabs({selected: selected, disabled:disabled});		
	};

	this.tabDialog  = function(dialog_params, url){
		var $this = this;
		var func_tab = function(event, ui, url){
			var tabId = dialog_params['tabId']; 
			$this.defaultActionForTab(tabId);
		};
		var dialogParams = $.extend(true, dialog_params, {html_type:'url', text:url});
		var openFunc = dialogParams['open'];
		dialogParams['open'] = function(){
			func_tab();
			if (openFunc)
				openFunc();
		};
//this.debug(dialogParams);
		return this.popDialog(dialogParams);
	};
	
	// 联动，source和linked都是Object，source = {selector:**, type:**, field:**}, linked = [{selector:**, type:**}]
	// source的类型限制为:select, checkbox, radio, text
	// linked的类型限制为:select, checkbox, radio, text
	this.linkage = function(source, linked, params, isBind){
		params = params || {};
		var $this = this;
		$(source.selector).each(function(i){
			$(this).unbind('change', $this.fun_linkage).bind('change', {linked:linked, params:params, src:$this}, $this.fun_linkage);
			if(isBind != undefined && isBind == false){
				if($(source.selector).val() > 0)
					$(this).trigger("change");
			}
		});
	};
	
	this.fun_linkage = function(event){
		var $this = event.data.src;
		var fun_oneLink = function(target, params, source_val){
			//解析params，如果其中存在selector，则将其值都读出来
			var newParams = {field:target.field, value:source_val};
			newParams[target.field] = source_val;
			newParams.cond = target.cond;
			if(params.selector != undefined){
				for(var i in params.selector){
					newParams[i] = $(params.selector[i]).val();
				}
//				delete params.selector;
			}		
			$.post(target.url, newParams, function(data){
				if (data.nochange != undefined && data.nochange == 1)
					return;
				if (target.type == undefined)
					target.type = 'select';
XT.debug(data);		
XT.debug(target.selector);		
				switch(target.type){
					case 'select':
						var currentVal = $(target.selector).val();
						$(target.selector).find('option').remove();
						var existed = $this.generateOptions($(target.selector), data, target.field_id || 'id', target.field_name || 'name', true, currentVal);
						var trigger = true;
						var target_id = $(target.selector).attr('id');

						if(target_id != 'tester_id' && data.length == 1){
							if(data[0]['id'] != currentVal){
								currentVal = data[0]['id'];
								trigger = true;
							}
							else	
								trigger = false;
						}
						else if(existed){
							trigger = false;
						}
						if(source_val != 0)
							$(target.selector).val(currentVal);
						if(trigger)
							$(target.selector).trigger('change');
						break;
					case 'checkbox':
// XT.debug(params);			
// XT.debug(data);		
						XT.generateCheckbox(target.selector, target.name, data, target.op, target.has_table || true, target.cols || 5, target.id_field || 'id', target.name_field || 'name');
						break;
					case 'radio':
						break;
					case 'text':
						for(var i in data){
							$(target.selector + '_' + i).val(data[i]);
						}
						break;
					case 'simpletext':
						$(target.selector).val(data);
						break;
				}
			}, 'json');
		};
		for(var i in event.data.linked){
			fun_oneLink(event.data.linked[i], event.data.params, $(this).val());
		}
	};

	this.doMoreOp  = function(more, data, target){
		var action = more.action, fun = more.fun;
		return action[fun](data, target, more);
	};
	
	this.auto_fill_calc_result  = function($dest, $source, $op, decimal){
		var result;
		var $this = this;
		$.each($source, function(i, n){
			var v = $(n).val();
// $this.debug([i, n, v]);
			switch($op){
				case '+':
					if(result == undefined)
						result = v;
					else
						result += v;
					break;
				case '*':
					if(result == undefined)
						result = v;
					else
						result *= v;
					break;
			}
		});
		decimal = decimal || 0;
		switch(decimal){
			case 0:
				result = Math.round(result);
				break;
			case 1:
				result = Math.round(result * 10) / 10;
				break;
			case 2:
				result = Math.round(result * 100) / 100;
				break;
		}
		$($dest).val(result);
	};
	
	this.checkElement  = function(e, params){
//$this.debug(params);		
//		var params = json_decode(strParams);
		this.checkRequiredElement(e, params);
		this.checkUnique(e, params);
		return false;
	};
	
	this.checkRequiredElement  = function(e, params){
		var field = $(e).attr('id');
		var value = $(e).val();
		var required = $(e).attr('required');
		var passed = true;
//debug(required);		
		if (required == 'required' && (value == undefined || value == null || value == '')){
			$(e).addClass('required_error');
			e.title = 'This field cannot be empty';
			passed = false;
		}
		else
			$(e).removeClass('required_error');
//debug(passed);		
		return passed;
	};
	
	this.checkUnique  = function(e, params){
//$this.debug("This is checkUnique");	
		if (!$(e).attr('unique')) return true;
		params = params || {};
		var field = $(e).attr('id');
		// var img = $(e).parent().siblings('td:has(img)').find('img#img_unique_check')[0];
		var value = $(e).val();
		// img.src = '/img/aHelp.png';
//$this.debug(e);		
		if (value != undefined && value != '' && value != null){
			var div = $(e).parents('div:has(#div_hidden)')[0];
			var hidden_div = $(div).find('#div_hidden');
			var db = params['db'] || $(hidden_div).find('#db').val(), 
				table = params['table'] || $(hidden_div).find('#table').val(), 
				isClone = $(hidden_div).find('#clone').val() || false,
				id = params['id'] || $(hidden_div).find('#id').val() || 0;
//$this.debug([db, table, isClone, id]);				
//$this.debug("isClone = " + isClone);				
			if (isClone == 'true')
				id = 0;
			$.ajax({
				url:'/jqgrid/jqgrid',
				type:'POST',
//				async: false,
				data: {oper:'checkUnique', db:db, table:table, field:field, value:value, id:id},
				success:function(data){
//alert("data = " + data);				
					$(e).removeClass('unique_unknown unique_unchecked unique_checked unique_error');
					if (data == '1'){
						$(e).addClass('unique_checked');
						$(e).attr('title', '');
						// img.src = '/img/aCheck.png';
					}
					else{
						$(e).addClass('unique_unchecked unique_error');
						$(e).attr('title', "ERROR: The " + field + " is not unique");
						// img.src = '/img/b_drop.png';
					}
					return true;
				}
			});
		}
//$this.debug("End checkUnique");		
		return true;
	};
	
	this.bindOptions  = function(event){
		var $this = this;
		$.post(event.url, event.data, function(data){
			if(event.blankItem == undefined)
				event.blankItem = true;
			event.currentVal = event.currentVal || 0;
			event.target.find('option').remove();
			$this.generateOptions(event.target, data, 'id', 'name', event.blankItem, event.currentVal);
			var data_length = 0;
			$.each(data, function(i, n){
				data_length ++;
			});
			if(data_length < 2 && event.blankItem != true){
				event.target.attr('disabled', true);
			}
			else{
				event.target.attr('disabled', false);
			}
			// event.target.trigger('change');
		}, 'json');
	};

	this.generateOptions  = function(select, data, value, title, blankItem, currentVal){
		var existed = false;
		var $this = this;
		value = value || 'id';
		title = title || 'name';
		blankItem = blankItem || false;
		currentVal = currentVal || 0;
		if (blankItem){
			select.append('<option value="0"> </option>');
			if(currentVal == 0)
				existed = true;
		}
		$.each(data, function(i, n){
			if(currentVal && currentVal == n[value]){
				existed = true;
				n['selected'] = 'selected';
			}
			//可以将data里的所有字段都打包到option里
			select.append($this.generateOptionStr(n, value, title));
		});
		return existed;
		// if(data.length == 1)
			// select.find("option[value='" + data[0][value] + "']").attr('selected', true);
	};
	
	this.generatePropStr = function(item, value, title){
		var optionProp = [];
		for(f in item){
			if(f == title)
				continue;
			if(f == value)
				optionProp.push('value="' + item[value] + '"');
			else if(f == 'note')
				optionProp.push('title="' + item['note'] + '"');
			else
				optionProp.push(f + '="' + item[f] + '"');
		}
		return optionProp;
	};
	
	this.generateOptionStr  = function(item, value, title){
		var optionProp = XT.generatePropStr(item, value, title);
		return '<option ' + optionProp.join(' ') + '>' + item[title] + '</option>';
	};
	
	this.single2multi2  = function(div){
// this.debug("single2multi2");		
// this.debug(div);		
		var	se = div.children('select'),
			se_id = se.attr('id'), 
			single_multi = JSON.parse(div.attr('single_multi')),
			cart_db = single_multi['db'], 
			cart_table = single_multi['table'], 
			cart_data = single_multi['data'] || "{}", 
			cols = single_multi['cols'] || 4,
			required = se.attr('required'),
			label = single_multi['label'] || se_id,
			onAddClick = " onclick='XT.selectToCart(\"" + se_id + "\", \"" + cart_db + "\", \"" + cart_table + "\", \"" + label + "\", " + cart_data + ")'",
			onResetClick = " onclick='XT.resetCart(\"" + se_id + "\", \"" + cart_db + "\", \"" + cart_table + "\", \"" + label + "\", " + cart_data + ")'",
			str = [];
// this.debug(onAddClick);
// this.debug(single_multi);
		str.push("<fieldset id='fieldset_" + se_id + "'>");
		str.push("<table cols='" + cols + "' id='table_" + se_id + "' style='width:100%'></table>");
		str.push("</fieldset>");

		str.push("<div id='cart_button' style='display:none'>");
		str.push("<button type='button' editable='1' id='cart_add_" + se_id + "' cart='" + se_id + "'");
		if(required)
			str.push(" required='" + required + "'");
		str.push(onAddClick);
		str.push(">Add</button>");
		
		str.push("<button type='button' editable='1' id='cart_reset_" + se_id + "' cart='" + se_id + "'");
		if(required)
			str.push(" required='" + required + "'");
		str.push(onResetClick);
		str.push(">Reset</button>");
		str.push("</div>");
		
		return str.join('');
	};
	
	this.single2multi  = function(div){
// this.debug("single2multi");		
// this.debug(div);		
		var	se = div.children('select'),
			se_id = se.attr('id'), 
			single_multi = JSON.parse(div.attr('single_multi')),
			cart_db = single_multi['db'], 
			cart_table = single_multi['table'], 
			cart_data = single_multi['data'] || "{}", 
			cols = single_multi['cols'] || 4,
			disabled = se.attr('disabled'),
			editable = se.attr('editable'),
			required = se.attr('required'),
			label = single_multi['label'] || se_id,
			onMouseOut = " onmouseout='XT.hideCartButton(\"div_cart_" + se_id + "\")'",
			onMouseOver = " onmouseover='XT.showCartButton(\"div_cart_" + se_id + "\")'",
			onAddClick = " onclick='XT.selectToCart(\"" + se_id + "\", \"" + cart_db + "\", \"" + cart_table + "\", \"" + label + "\", " + cart_data + ")'",
			onResetClick = " onclick='XT.resetCart(\"" + se_id + "\", \"" + cart_db + "\", \"" + cart_table + "\", \"" + label + "\", " + cart_data + ")'",
			str = [];
		// str.push("<div id='div_cart_" + se_id + "' prop_edit='disabled' single_multi='" + se.attr('single_multi') + "'");
		// if(disabled)
			// str.push(" disabled='" + disabled + "'");
		// if(editable)
			// str.push(" editable='" + editable + "'");
		// else
			// onMouseOver = '';
		// str.push(onMouseOut);
		// str.push(onMouseOver);
		// str.push(">");
		str.push("<fieldset id='fieldset_" + se_id + "'>");
		str.push("<table cols='" + cols + "' id='table_" + se_id + "' style='width:100%'></table></fieldset>");

		str.push("<div id='cart_button' style='display:none'>");
		str.push("<button type='button' editable='1' id='cart_add_" + se_id + "' cart='" + se_id + "'");
		if(required)
			str.push(" required='" + required + "'");
		str.push(onAddClick);
		str.push(">Add</button>");
		
		str.push("<button type='button' editable='1' id='cart_reset_" + se_id + "' cart='" + se_id + "'");
		if(required)
			str.push(" required='" + required + "'");
		str.push(onResetClick);
		str.push(">Reset</button>");
		str.push("</div>");
		// str.push("</div>");
		
		return str.join('');
	};
	
	this.multi2single  = function(div){
// this.debug("multi2single");		
// this.debug(div);		
		var se_id = div.attr('id').substr(9),
			disabled = div.attr('disabled'),
			editable = div.attr('editable'),
			style = div.attr('style'),
			css = div.attr('class'),
			single_multi = JSON.parse(div.attr('single_multi')),
			options = single_multi.options,
			size = options.size;
// this.debug(options);
		var str = [];
		str.push("<select type='select' id='" + se_id + "' name='" + se_id + "' prop_edit='disabled'");
		if(size)
			str.push(" size='" + size + "'");
		if(editable)
			str.push(" editable='" + editable + "'");
		if(disabled)
			str.push(" disabled='" + disabled + "'");
		if(style)
			str.push(" style='" + style + "'");
		else	
			str.push(" style='width:100%'");
		if(css)
			str.push(" class='" + css + "'");
		else
			str.push(" class='ces'");
		// str.push(" single_multi='" + div.attr('single_multi') + "'");
		str.push(">");
		
		var optionData = this.str2Array(options.value);
		var $this = this;
		$.each(optionData, function(i, n){
			if(typeof n == 'object'){
				str.push($this.generateOptionStr(n, 'id', 'name'));
			}
			else
				str.push($this.generateOptionStr({id:i, name:n}, 'id', 'name'));
		});
		str.push("</select>");
		
		return str.join('');
	};
	
	this.addToCart  = function(e_name, newAdded){//tableSelector, newAdded, checkboxName){
		var tableSelector = '#div_cart_' + e_name + ' #table_' + e_name;
		var cols = $(tableSelector).attr('cols') || 5;
		var str = '';
		var currents = {};
		var currentCol = 0;
		$(tableSelector + ' :checkbox').each(function(i){
			var id = $(this).val();
			currents[id] = $(this);
		});
		for (var i in newAdded){
			//检查该值是否已经存在，避免重复显示
			//如果已经存在，则Check，否则添加
			if (currents[i]){
				currents[i].attr('checked', true);
			}
			else{
				if (currentCol == 0)
					str += "<tr newadd='newadd'>";
				str += "<td class='inter-table'><label><input type='checkbox' checked='checked' editable='editable' value='" + i + "' name='" + e_name + "[]'>" + newAdded[i] + "</label></td>";
				currentCol ++;
				if (currentCol == cols){
					str += "</tr>";
					currentCol = 0;
				}
			}
		}
// this.debug(str);		
// this.debug(tableSelector);
// this.debug(cols);
// this.debug($(tableSelector));
// this.debug($(tableSelector).html());
		$(tableSelector).append(str);
	};
	
	this.selectToCart  = function(e_name, db, table, title, postData){
		var $this = this;
		// 获取Projects
		var container = "select_cart", 
			gridId = '#' + container + '_' + db + '_' + table + '_list', 
			pagerId = '#' + container + '_' + db + '_' + table + '_pager';
		var url='/jqgrid/index/db/' + db + '/table/' + table + '/container/' + container;
		var defaultParams = this.defaultDialogParams();
//		postData = JSON.parse(postData);
		postData['table'] = table;
		postData['db'] = db;
// this.debug(postData);		
		var dialog_params = {
			div_id: container,
			title: 'Select ' + title,
			width: 1024,
			height: 600,
			close: function(event, ui){
				$(this).html('');
				$(this).remove();
			},
			open:function(){
				var grid = grid_factory.get(db, table, {container:container});
//				var optionSelector = grid.getParams('optionSelector');
				return grid.load(postData);//, false, optionSelector);
			}
		};
		var dialogParams = $.extend(true, defaultParams, dialog_params, {html_type:'url', text:url});
		var buttons = {
			'Add ': function(){
				//需要得到id和name
				var selectedPrj = $(gridId).getGridParam('selarrrow');
				var prjs = {};
				var value = '';
				for(var i in selectedPrj){
					value = $(gridId).getCell(selectedPrj[i], 'name');
					if (value == false)
						value = $(gridId).getCell(selectedPrj[i], 'code');
					prjs[$(gridId).getCell(selectedPrj[i], 'id')] = value;
					if(db=='useradmin' && table=='users')
						prjs[$(gridId).getCell(selectedPrj[i], 'id')] = $(gridId).getCell(selectedPrj[i], 'nickname');
				}
// $this.debug([e_name, prjs]);				
				$(this).dialog('close');
				$this.addToCart(e_name, prjs);
			},
			Close: function(){
				$(this).dialog('close');
			}
		};
		dialogParams['buttons'] = buttons;
// $this.debug(dialogParams);		
		return $this.actionDialog(dialogParams, url);		
	};
	
	this.resetCart  = function(e_name){
		$('div#div_cart_' + e_name + ' table#table_' + e_name + ' tr[newadd="newadd"]').remove();
		//如果被清光了，那么需要显示出Buttons
		// $('div#div_cart_' + e_name + ' #cart_button').show();
	};
	
	this.clearCart  = function(e_name){
		$('div#div_cart_' + e_name + ' table#table_' + e_name + ' tbody').remove();
	};
	
	this.information = function($db, $table, $rowId, $container){
// tool.debug([$db, $table, $container]);	
		var grid = grid_factory.get($db, $table, {container:$container});
		var action = grid.getAction();
// tool.debug(action);		
		return action.information($rowId);
	};

	this.updateViewEditPage = function(db, table, container, rowId, parentId){
	// get the tabId from the gridId
		table = table.substr(0, table.length - 4); // remove _ver
		var grid = grid_factory.get(db, table, {container:container});
		var grid_action = grid.getAction();
		grid_action.updateInformationPage(rowId, parentId, 'view_edit');
	};

	this.hideCartButton = function(e_name){
		//要检查是否有元素，如果 没有元素，则不隐藏
		if($('#' + e_name + ' td').length > 0)
			this.hide('#' + e_name + ' #cart_button');
		return false;
	};
	
	this.showCartButton = function(e_name){
// tool.debug("e_name = " + e_name);
		var disabled = $('#' + e_name).attr('disabled');
// this.debug("disabled = " + disabled);		
		if(disabled == undefined || disabled != 'disabled'){
// this.debug("e_name = " + e_name);			
			this.show('#' + e_name + ' #cart_button');
		}
		return false;
	};
	
	this.showQueryFieldSet = function(e){
		$(e).find('fieldset').show();
	}
	
	this.hideQueryFieldSet = function(e){
		$(e).find('fieldset').hide();
	}
	
	this.hideMultiRowTemp = function (prefix){
// tool.debug(prefix);
		this.hide('#' + prefix + '_temp');
	}
	
	this.showMultiRowTemp = function (prefix){
// tool.debug(prefix);
		var disabled = $('#' + prefix + '_temp .cont-td :input:first').attr('disabled') || '';
		var readonly = $('#' + prefix + '_temp .cont-td :input:first').attr('readonly') || '';
// tool.debug([disabled, readonly]);		
		if(disabled != 'disabled' && readonly != 'readonly')
			this.show('#' + prefix + '_temp');
	}
	
	this.deleteSelfRow = function(e){
		var disabled = $(e).attr('disabled') || false;
		var tr = $(e).parents("tr")[0];
		if(!disabled){
			tr.remove();
		}
	};
	
	this.addNewRowForMulti  = function(prefix, post_fun){
		var $this = this;
		var temp = '#' + prefix + '_temp', valuesTable = '#td_' + prefix + ' #' + prefix + '_values';
		var row, id, td = [];
		row = $("<tr><td id='del'><button id='del_button' onclick='javascript:XT.deleteSelfRow(this)' href='javascript:void(0)'>X</button></td></tr>");
// this.debug("getAllInput from " + temp);
		var vs = this.getAllInput(temp, false, true);
// this.debug(vs);		
		$(valuesTable + " tr#" + prefix + "_header th").each(function(i){
// $this.debug($(this));
			var id = $(this).attr('id');
			if(id == 'del')
				return;
// $this.debug(id);			
			var v =vs['data'][id], t = vs['text'][id], json = '';
// $this.debug(v);
// $this.debug(t);
			if($this.isObject(v)){
// $this.debug($(this));
				v = JSON.stringify(v);
				t = $('div#' + id + '_temp').next().html();//' #' + id + '_values').html();//'Object';
// $this.debug(t);
				json = "json='json'";
			}
// $this.debug(v);
// $this.debug("<<<<");
			var td = "<td><input type='hidden' value='" + v + "' id='" + id + "' multirowedit='multirowedit'" + json + ">" + t + "</td>";
			// var input = $(this).children()[0];
			// var input_val = $(input).val();
			// // var td = "<td><input type='hidden' value='" + input_val + "' id="
			// // this.debug(" i = " + i + ", value = " + $(input).val());
			// var td = $(this).clone();
			// $(td.children()[0]).val(input_val);
			row.append(td);
		})
		if(vs.passed.length > 0){
			alert(vs.tips.join('\n'));
		}
		else{
			$(valuesTable).append(row);//"<tr>" + td.join() + "</tr>");
		}
	};

	this.hide = function(selector){
		$(selector).hide();
	};
	
	this.show = function(selector){
		$(selector).show();
	};
	
	this.previewPicture = function(e, obj, filename){
		var $this = this;
// this.debug(e);	
this.debug([e.pageX, e.pageY, e.clientX, e.clientY]);	
// this.debug(filename);
		var img_width, img_height;
		var img = new Image();
		img_onload = function(img){
			img_width = img.width;
			img_height = img.height;
// $this.debug([img_width, img_height]);
		};
		var xOffset = 20, yOffset = 150;
		if($('#tip_picture_preview').length == 0){
			$('body').append("<p id='tip_picture_preview' style='position:absolute;'><img src='" + filename +"' alt='url preview' onload='img_onload(this);' height='500' width='500'/></p>");
		}
		var top = e.pageY, left = e.pageX, doc_height = $(document.body).height(), doc_width = $(document.body).width();
		if(top + 500 > doc_height)
			top = doc_height - 600;
		// if(left + 500 > doc_width)
			// left = doc_width - 520;
this.debug([top, left, doc_height, doc_width]);
		$("#tip_picture_preview")
			.css("top", top + "px")
			.css("left", left + "px")
			.fadeIn("fast");
	};
	
	this.clearPicture = function(obj){
		$('#tip_picture_preview').remove();
	};
	
	this.autocomplete = function(obj, db, table, field, minLength, rows){
		//如果有hidden的**_id存在，则认为是select类型的autocomplete，否则，就认为是单纯的autocomplete
		var real_id = $(obj).attr('real_id');
		db = db || $(obj).attr('db');
		table = table || $(obj).attr('table');
		field = field || $(obj).attr('field') || 'name';
		minLength = minLength || $(obj).attr('minLength') || 2;
		rows = rows || $(obj).attr('rows') || 12;
		$(obj).autocomplete({
			minLength: minLength,
			source: "/jqgrid/jqgrid/oper/autocomplete/db/" + db + "/table/" + table + "/field/" + field + "/rows/" + rows
		});
	};
	
	this.single_or_multi = function(p){
		var td = $(p).parent('td').prev('td.cont-td'),
			div = td.children('div'),
			current_state = div.attr('current_state');
		if(current_state == 'single'){
			var se = div.children('select');
			var str = this.single2multi2(td.children('div'));
			div.attr('current_state', 'multi').html(str);
			$(p).val('-');
			$(p).html('-');
			$(p).attr('title', 'Change to single selection');
			$(div).find('#cart_add_' + se.attr('id')).click();
		}
		else{
			var str = this.multi2single(div);
			div.attr('current_state', 'single').html(str);

			$(p).val('+');
			$(p).html('+');
			$(p).attr('title', 'Change to multi-selection');
		}
	};
	
	this.multi_to_single = function(p){
		var td = $(p).parent('td').prev('td.cont-td'),
			cart = td.children('div');
		var str = this.multi2single(cart);
		td.html(str);

		$(p).button('destroy');
		$(p).attr('id', 'single_to_multi');
		$(p).val('+');
		$(p).html('+');
		$(p).attr('title', 'Change to multi-selection');
		$(p).button();
		$(p).onclick
		$(p).unbind('click').bind('click', function(){
			$this.buttonActions('single_to_multi', p);
		});
	};
	
	this.single_to_multi = function(p){
		var td = $(p).parent('td').prev('td.cont-td'),
			se = td.children('select'), div = td.children('div');
XT.debug(p);
XT.debug(se);
		var str = this.single2multi(div);
XT.debug(str);
XT.debug(div);
		div.html(str);
		$(p).button('destroy');
		$(p).attr('id', 'multi_to_single');
		$(p).val('-');
		$(p).html('-');
		$(p).attr('title', 'Change to single selection');
		$(p).button();
		$(p).unbind('click').bind('click', function(){
			$this.buttonActions('multi_to_single', p);
		});
		$(td).find('#cart_add_' + se.attr('id')).click();
	};
	
	this.textareaScroll = function(event){
		$(event).unbind('input').bind('input', function(){
// this.debug([this.style.height, this.scrollHeight]);
			if(this.style.height == 'auto')
				this.style.height = this.scrollHeight + 'px';
			else{
				var styleHeight = this.style.height, len = styleHeight.length, intHeight = parseInt(styleHeight.substring(0, len - 2));
// this.debug([intHeight, this.scrollHeight]);
				if(intHeight < this.scrollHeight)
					this.style.height = this.scrollHeight + 10 + 'px';
			}
		});
	};
	
	this.grid_index = function(db, table, title, params){
		var  grid = grid_factory.get(db, table, params);
		return grid.index(title);
	};
		
	this.upload = function(db, table, value, cell_id, subdir, isFiles){
		if(isFiles == undefined)
			isFiles = false;
		subdir = subdir || value;
		var $this = this, import_url = '/jqgrid/jqgrid/db/' + db + '/table/' + table + '/id/' + value + '/oper/import/subdir/' + subdir + "/isfiles/" + isFiles + '/cell_id/' + cell_id;
// this.debug(cell_id);		
// this.debug(subdir);		
// this.debug($('#td_' + cell_id));
		var dialog_params = {
			html_type:'url',
			text:import_url,
			div_id:'import_div', 
			width:500, 
			height:300, 
			title:'Upload',
			open: function(){
				$('#import_div :button,:submit').button();
			},
			close:function(){
				$(this).html('');
				$(this).remove();
				//需要刷新文件列表
				$.post('/jqgrid/jqgrid/db/' + db + '/table/' + table + '/cell_id/' + cell_id + '/id/' + value + '/oper/refreshcell/subdir/' +subdir, function(data){
					$('#td_' + cell_id).html(data);
				});
			},
			buttons:{
				Close:function(){
					$(this).dialog('close');
				}
			}
		};
		this.popDialog(dialog_params);
	};
	
	this.deleteFile = function(file, cell_id, isFiles, db, table, container_id, id){
		var $this = this;
		var buttons = {
			No:function(){
				$(this).dialog('close');
			},
			Yes:function(){
				$(this).dialog('close');
				$.post("/service/deletefile", {filename:file, isfiles:isFiles, cell_id:cell_id, db:db, table:table, container_id:container_id, id:id}, function(data){
					if(data == '1'){ // successed to remove the file
						//应该刷新界面
						$('#' + cell_id).parent('td').remove();
					}
					else{ //fail to remove the file
						alert("fail to remove the file");
					}
				});
			}
		};
		this.optionsDialog("Do you really want to delete the file?", "Delete the file", buttons, 500, 200);
	};
}).apply(XT);
