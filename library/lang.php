<?php
$g_current_lang = 'ch'; //en:english, ch:chinese
$g_LANGS = array(
	//tips
	'placeholder'=>array('en'=>'Please input ', 'ch'=>'请在此输入'),
	'keyword'=>array('en'=>'Keyword', 'ch'=>'关键字'),
	'key'=>array('en'=>'Keyword', 'ch'=>'关键字'),
	
	//buttons
	'add'=>array('en'=>'Add', 'ch'=>'添加'),
	'new'=>array('en'=>'New', 'ch'=>'新增'),
	'edit'=>array('en'=>'edit', 'ch'=>'编辑'),
	'clone'=>array('en'=>'Clone', 'ch'=>'复制'),
	'cancel'=>array('en'=>'Cancel', 'ch'=>'取消'),
	'save'=>array('en'=>'Save', 'ch'=>'保存'),
	'saveandnew'=>array('en'=>'Save & New', 'ch'=>'保存并新增'),
	'abort'=>array('en'=>'Abort', 'ch'=>'丢弃'),
	'publish'=>array('en'=>'Publish', 'ch'=>'发布'),
	'review'=>array('en'=>'Review', 'ch'=>'评审'),
	'asktoreview'=>array('en'=>'Ask To Review', 'ch'=>'申请评审'),
	'reset'=>array('en'=>'Reset', 'ch'=>'重置'),
	'query'=>array('en'=>'Query', 'ch'=>'查询'),
	'report'=>array('en'=>'Report', 'ch'=>'报表'),
	'upload'=>array('en'=>'upload', 'ch'=>'上传'),
	'advanced'=>array('en'=>'Advanced', 'ch'=>'更多'),
	'hide'=>array('en'=>'Hide', 'ch'=>'隐藏'),
	'show'=>array('en'=>'Show', 'ch'=>'显示'),
	'columns'=>array('en'=>'Columns', 'ch'=>'调整列'),
	'export'=>array('en'=>'Export', 'ch'=>'导出'),
	'show'=>array('en'=>'Show', 'ch'=>'显示'),
	'activate'=>array('en'=>'Activate', 'ch'=>'激活'),
	'inactivate'=>array('en'=>'Inactivate', 'ch'=>'灭活'),
	'subscribe'=>array('en'=>'Subscribe', 'ch'=>'订阅'),
	'lend'=>array('en'=>'Lend To', 'ch'=>'出借'),
	'make_tag'=>array('en'=>'Make a Tag', 'ch'=>'创建标记'),
	'removeFromTag'=>array('en'=>'Remove From Tag', 'ch'=>'从一个标记移除'),
	'ver_diff'=>array('en'=>'Diff the Versions', 'ch'=>'比较不同版本'),
	'his_diff'=>array('en'=>'Disff the History', 'ch'=>'比较历史记录'),
	
	//field label
	'__intertag'=>array('en'=>'TAG', 'ch'=>'标记'),
	
	'isactive'=>array('en'=>'Is Active', 'ch'=>'是否有效'),
	'created'=>array('en'=>'Created', 'ch'=>'创建时间'),
	'updated'=>array('en'=>'Updated', 'ch'=>'更新时间'),
	'creater'=>array('en'=>'Creater', 'ch'=>'创建人'),
	'manager'=>array('en'=>'Manager', 'ch'=>'管理人'),
	'updater'=>array('en'=>'Updater', 'ch'=>'更新人'),
	'updated'=>array('en'=>'Updated', 'ch'=>'更新时间'),
	'happen_date'=>array('en'=>'Happen Date', 'ch'=>'发生日期'),
	'ver'=>array('en'=>'Version', 'ch'=>'版本'),
	'gender'=>array('en'=>'Gender', 'ch'=>'性别'),
	'name'=>array('en'=>'Name', 'ch'=>'名称'),
	'city'=>array('en'=>'City', 'ch'=>'城市'),
	'address'=>array('en'=>'Address', 'ch'=>'地址'),
	'contact_method'=>array('en'=>'Contact Method', 'ch'=>'联系方式'),
	'fl'=>array('en'=>'Type', 'ch'=>'类型'),
	'note'=>array('en'=>'Note', 'ch'=>'备注'),
	'hb'=>array('en'=>'Mate', 'ch'=>'伙伴'),
	'comment'=>array('en'=>'Comment', 'ch'=>'评论意见'),
	'attachments'=>array('en'=>'Attachments', 'ch'=>'附件'),
	'description'=>array('en'=>'Description', 'ch'=>'描述'),
	'group'=>array('en'=>'Group', 'ch'=>'小组'),
	'cause'=>array('en'=>'Cause', 'ch'=>'原因/理由'),
	'result'=>array('en'=>'Result', 'ch'=>'结果'),
	'data_type'=>array('en'=>'Data Type', 'ch'=>'数据类型'),
	
	//device
	'device_type'=>array('en'=>'Device Type', 'ch'=>'设备类型'),
	'device'=>array('en'=>'Device', 'ch'=>'设备'),
	'property'=>array('en'=>'Property', 'ch'=>'属性'),
	'device_property'=>array('en'=>'Device Property', 'ch'=>'设备属性'),

	//device_trace
	'borrower'=>array('en'=>'Borrower', 'ch'=>'借入人'),
	'borrow_date'=>array('en'=>'Borrow Date', 'ch'=>'借入日期'),
	//device_property
	'content'=>array('en'=>'Content/Value', 'ch'=>'内容'),
	
	//gx
	'gx_hjcs'=>array('en'=>'Environment Parameters', 'ch'=>'工作环境参数'),
	'hjcs'=>array('en'=>'Environment Parameters', 'ch'=>'工作环境参数'),
	
	
	//hb
	'xingming'=>array('en'=>'name', 'ch'=>'姓名'),
	'zhengjian_fl'=>array('en'=>'ID Type', 'ch'=>'证件类型'),
	'credit_level'=>array('en'=>'Credit Level', 'ch'=>'信用级别'),
	'identity_no'=>array('en'=>'ID No', 'ch'=>'证件号码'),
	'bank'=>array('en'=>'Bank', 'ch'=>'开户行'),
	'bank_account_no'=>array('en'=>'Bank Account No', 'ch'=>'银行账号'),
	'tax_no'=>array('en'=>'Tax No', 'ch'=>'税号'),
	'account_receivable'=>array('en'=>'Account Receivable', 'ch'=>'应收金额'),
	'hobby'=>array('en'=>'Hobby', 'ch'=>'爱好'),
	'lxr'=>array('en'=>'Emergency Contact', 'ch'=>'紧急联系人'),
	'cell_no'=>array('en'=>'Cell No.', 'ch'=>'手机号码'),
	
	'history_dingdan'=>array('en'=>'Total Order', 'ch'=>'历来订单总额'),
	'current_dingdan'=>array('en'=>'Current Order', 'ch'=>'当前订单总额'),
	//zzvw_yg
	'yg'=>array('en'=>'Employee', 'ch'=>'员工'),
	'enter_date'=>array('en'=>'Enter Date', 'ch'=>'进入日期'),
	'work_type'=>array('en'=>'Work Type', 'ch'=>'工种'),
	'dept'=>array('en'=>'Department', 'ch'=>'部门'),
	'position'=>array('en'=>'Position', 'ch'=>'职位'),
	'salary_fl'=>array('en'=>'Salary Type', 'ch'=>'工资类型'),
	'base_salary'=>array('en'=>'Base Salary', 'ch'=>'基本工资'),
	'ticheng_ratio'=>array('en'=>'Commission', 'ch'=>'提成比例'),
	'baoxian_type'=>array('en'=>'Insurance Type', 'ch'=>'保险类型'),
	'baoxian_start_date'=>array('en'=>'Insurance Start Date', 'ch'=>'保险生效日期'),
	'baoxian_feiyong'=>array('en'=>'Insurance Cost', 'ch'=>'保险费用'),
	'hb_skill'=>array('en'=>'Worker Skill', 'ch'=>'技能'),
	//zzvw_gys
	'gys'=>array('en'=>'Supplier', 'ch'=>'供应商'),
	'provide'=>array('en'=>'Supply', 'ch'=>'可提供'),
	//zzvw_kh
	'kh'=>array('en'=>'Customer', 'ch'=>'客户'),
	'demand'=>array('en'=>'Demand', 'ch'=>'需求'),
	
	//wz
	'wz_fl'=>array('en'=>'Resource Type', 'ch'=>'物资类型'),
	'unit'=>array('en'=>'Unit', 'ch'=>'计量单位'),
	'default_price'=>array('en'=>'Default Price', 'ch'=>'默认单价'),
	'jy_days'=>array('en'=>'Store Days', 'ch'=>'允许积压天数'),
	'wh_days'=>array('en'=>'Maintain Days', 'ch'=>'维护周期'),
	'default_price'=>array('en'=>'Default Price', 'ch'=>'默认单价'),
	'midu'=>array('en'=>'Density', 'ch'=>'密度'),
	'min_kc'=>array('en'=>'Min Stock', 'ch'=>'最低库存'),
	'max_kc'=>array('en'=>'Max Stock', 'ch'=>'最高库存'),
	'tj'=>array('en'=>'Volume', 'ch'=>'体积'),
	'bmj'=>array('en'=>'Superficial Area', 'ch'=>'表面积'),
	'cp'=>array('en'=>'Is Official', 'ch'=>'产品/辅助品'),
	'youxiaobili'=>array('en'=>'Ratio', 'ch'=>'有效比例'),
	'zuhe'=>array('en'=>'Composite', 'ch'=>'是否组合品'),
	'pic'=>array('en'=>'Picture', 'ch'=>'图片'),
	'tuzhi'=>array('en'=>'Craft', 'ch'=>'图纸'),
	'chengpinlv'=>array('en'=>'Good Ratio', 'ch'=>'成品率'),
	'pd_days'=>array('en'=>'Stocktaking cycle', 'ch'=>'盘点周期'),
	'pd_date'=>array('en'=>'Stocktaking date', 'ch'=>'盘点日期'),
	//wz_sb
	'fix_code'=>array('en'=>'Fix Code', 'ch'=>'资产编号'),
	'wh_date'=>array('en'=>'Maintain Date', 'ch'=>'上次维护日期'),
	'min_handle'=>array('en'=>'Min Handle', 'ch'=>'最低处理量'),
	'max_handle'=>array('en'=>'Max Handle', 'ch'=>'最高处理量'),
	
	//yw
	'yw_name'=>array('en'=>'Name', 'ch'=>'描述'),
	'dj'=>array('en'=>'Invoice', 'ch'=>'单据'),
	'fp'=>array('en'=>'Invoice', 'ch'=>'发票'),
	'jbr'=>array('en'=>'Operator', 'ch'=>'经办人'),
	'dingdan'=>array('en'=>'Order', 'ch'=>'订单'),
	'detail_list'=>array('en'=>'Detail List', 'ch'=>'清单'),
	'order'=>array('en'=>'Order', 'ch'=>'采购'),
	'cyr'=>array('en'=>'Carrier', 'ch'=>'承运人'),
	'zxr'=>array('en'=>'Stevedore', 'ch'=>'装卸人'),
	'yunshu_price'=>array('en'=>'Carry Price', 'ch'=>'运输单价'),
	'zx_price'=>array('en'=>'Stevedoring Price', 'ch'=>'装卸单价'),
	'fh_detail'=>array('en'=>'Detail', 'ch'=>'发运清单'),
	'jth_detail'=>array('en'=>'Detail', 'ch'=>'被退货清单'),
	'sh_detail'=>array('en'=>'Detail', 'ch'=>'收货清单'),
	'th_detail'=>array('en'=>'Detail', 'ch'=>'退货清单'),
	'yw_th'=>array('en'=>'Rejected', 'ch'=>'退货'),
	'yw_sh'=>array('en'=>'Received', 'ch'=>'收货'),
	'yw_jth'=>array('en'=>'Accept Rejected', 'ch'=>'接到退货'),
	'yw_fh'=>array('en'=>'Delivery', 'ch'=>'发货'),
	'weight'=>array('en'=>'Weight', 'ch'=>'重量'),
	'kg'=>array('en'=>'Stockman', 'ch'=>'库管'),
	
	'price'=>array('en'=>'Price', 'ch'=>'单价'),
	'stock_date'=>array('en'=>'Stock Date', 'ch'=>'入库日期'),
	
	//yw_scdj
	'scdj'=>array('en'=>'Products Registration', 'ch'=>'生产登记'),
	'gx'=>array('en'=>'Stage of Production', 'ch'=>'工序'),
	'input_pici'=>array('en'=>'Input Products', 'ch'=>'分解掉的产品'),
	'input_pici_amount'=>array('en'=>'Input Product Amount', 'ch'=>'分解掉的产品数量'),
	'pici_scdj'=>array('en'=>'Product Detail', 'ch'=>'产品清单'),
	'from_gx'=>array('en'=>'From Stage', 'ch'=>'来源工序'),
	'product'=>array('en'=>'Product', 'ch'=>'产品'),
	'defect'=>array('en'=>'Defect', 'ch'=>'缺陷'),
	'amount'=>array('en'=>'Amount', 'ch'=>'数量'),
	'ck_weizhi'=>array('en'=>'Stock Position', 'ch'=>'存放位置'),
	'remained'=>array('en'=>'Remained', 'ch'=>'剩余数量'),
	'product_date'=>array('en'=>'Product Date', 'ch'=>'生产日期'),
	
	//yw_zj
	'skf'=>array('en'=>'Beneficiary', 'ch'=>'收款方'),
	'account_receivable'=>array('en'=>'Account Receivable', 'ch'=>'应收款'),
	'pay_cause'=>array('en'=>'Pay Cause', 'ch'=>'支付原因'),
	'zj_fl'=>array('en'=>'Money Format', 'ch'=>'资金类型'),
	'pay_account'=>array('en'=>'Pay Account', 'ch'=>'支出账户'),
	'zj_pj'=>array('en'=>'Bill', 'ch'>'票据'),
	'expire_date'=>array('en'=>'Expire Date', 'ch'=>'到期日'),
	'cost'=>array('en'=>'Cost', 'ch'=>'费用'),
	'total_money'=>array('en'=>'Total Money', 'ch'=>'总金额'),
	'pj_total_money'=>array('en'=>'Bill Total Money', 'ch'=>'票据面额'),
	'pj_zjzh'=>array('en'=>'Bill Account', 'ch'=>'票据账户'),
	'cash_zjzh'=>array('en'=>'Cash Account', 'ch'=>'现金账户'),
	'divided_bill'=>array('en'=>'Divided Bill', 'ch'=>'拆分票据'),
	'out_zjzh'=>array('en'=>'Out Account', 'ch'=>'转出账户'),
	'in_zjzh'=>array('en'=>'In Account', 'ch'=>'转入账户'),
	'huabo_total_money'=>array('en'=>'Transfer Money', 'ch'=>'划拨金额'),
	'out_zjzh_remained'=>array('en'=>'Out Account Money', 'ch'=>'划出账户余额'),
	'in_zjzh_remained'=>array('en'=>'In Account Money', 'ch'=>'划入账户余额'),
	'divided_bill'=>array('en'=>'Divided Bill', 'ch'=>'拆分票据'),
	
	//testcase
	'code'=>array('en'=>'Code', 'ch'=>'编码'),
	'summary'=>array('en'=>'Summary', 'ch'=>'概述'),
	'prj'=>array('en'=>'Project', 'ch'=>'项目'),
	'testcase_type'=>array('en'=>'Testcase Type', 'ch'=>'类型'),
	'testcase_source'=>array('en'=>'Testcase Source', 'ch'=>'来源'),
	'testcase_category'=>array('en'=>'Testcase Category', 'ch'=>'分类'),
	'testcase_testpoint'=>array('en'=>'Test Point', 'ch'=>'测试点'),
	'testcase_module'=>array('en'=>'Test Module', 'ch'=>'所属模块'),
	'auto_level'=>array('en'=>'Auto Level', 'ch'=>'自动化水平'),
	'testcase_priority'=>array('en'=>'Priority', 'ch'=>'优先级'),
	'edit_status'=>array('en'=>'Edit Status', 'ch'=>'状态'),
	'ver'=>array('en'=>'Versions', 'ch'=>'版本'),
	'owner'=>array('en'=>'Owner', 'ch'=>'拥有人'),
	'last_run'=>array('en'=>'Last Run Since', 'ch'=>'最近运行'),
	'command'=>array('en'=>'Command', 'ch'=>'命令行'),
	'os'=>array('en'=>'OS', 'ch'=>'操作系统'),
	'board_type'=>array('en'=>'Board Type', 'ch'=>'板类型'),
	'chip'=>array('en'=>'Chip', 'ch'=>'芯片'),
	'command'=>array('en'=>'Command', 'ch'=>'命令行'),
	//testcase_ver
	'upate_from'=>array('en'=>'Update From', 'ch'=>'源自'),
	'objective'=>array('en'=>'Objecttive', 'ch'=>'目标'),
	'precondition'=>array('en'=>'Precondition', 'ch'=>'先置条件'),
	'steps'=>array('en'=>'Steps', 'ch'=>'步骤'),
	'expected_result'=>array('en'=>'Expected Result', 'ch'=>'期望的结果'),
	'resource_link'=>array('en'=>'Resource Link', 'ch'=>'所需资源'),
	'config'=>array('en'=>'Configure', 'ch'=>'配置'),
	'auto_run_minutes'=>array('en'=>'Auto Run Minutes', 'ch'=>'自动测试时间'),
	'manual_run_minutes'=>array('en'=>'Manual Run Minutes', 'ch'=>'手动测试时间'),
	'issue_comment'=>array('en'=>'Issue Comment', 'ch'=>'问题说明'),
	'update_comment'=>array('en'=>'Update Comment', 'ch'=>'升级说明'),
	'review_comment'=>array('en'=>'Review Comment', 'ch'=>'审核说明'),
	'issue_comment'=>array('en'=>'Issue Comment', 'ch'=>'问题评价'),
	//testcase module
	'cases'=>array('en'=>'Cases', 'ch'=>'用例数'),
	//testpoint
);

function g_str($id){
	global $g_LANGS, $g_current_lang;
	$str = isset($g_LANGS[$id][$g_current_lang]) ? $g_LANGS[$id][$g_current_lang] : ucwords(str_replace('_', ' ', $id));
	
	return $str;
}

function setLang($lang){
	global $g_current_lang;
	$g_current_lang = $lang == 'en' ? 'en' : 'ch';
}

?>