// var XT;
XT = XT || {};
(function(){
	this.data_type_index = function(){
		return this.grid_index('qygl', 'data_type', '数据类型');
	}
	
	this.yg_index = function(){
		return this.grid_index('qygl', 'zzvw_yg', '员工管理');
	}
	
	this.scdj_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_scdj', '生产登记');
	}
	
	this.scjh_index = function(){
		return this.grid_index('qygl', 'scjh', '生产计划');
	}
	
	this.gys_index = function(){
		return this.grid_index('qygl', 'zzvw_gys', '供应商管理');
	}
	
	this.kh_index = function(){
		return this.grid_index('qygl', 'zzvw_kh', '客户管理');
	}
	
	this.zhengjian_fl_index = function(){
		return this.grid_index('qygl', 'zhengjian_fl', '证件类型');
	}
	
	this.wz_index = function(){
		return this.grid_index('qygl', 'wz', '物资管理');
	}
	
	this.wz_fl_index = function(){
		return this.grid_index('qygl', 'wz_fl', '物资分类管理');
	}
	
	this.ck_fl_index = function(){
		return this.grid_index('qygl', 'ck_fl', '仓库分类管理');
	}
	
	this.ck_index = function(){
		return this.grid_index('qygl', 'ck', '仓库管理');
	}
	
	this.ck_pd_index = function(){
		return this.grid_index('qygl', 'zzvw_ck_pd', '库存盘点');
	}
	
	this.hobby_index = function(){
		return this.grid_index('qygl', 'hobby', '爱好管理');
	}
	
	this.ck_weizhi_index = function(){
		return this.grid_index('qygl', 'ck_weizhi', '仓位管理'); //仓库位置
	}
	
	this.zl_index = function(){
		return this.grid_index('qygl', 'zl', '质量管理');
	}
	
	this.defect_index = function(){
		return this.grid_index('qygl', 'defect', '缺陷管理');
	}
	
	this.pici_index = function(){
		return this.grid_index('qygl', 'zzvw_pici', '批次管理');
	}
	
	this.hb_index = function(){
		return this.grid_index('qygl', 'hb', '伙伴管理');
	}
	
	this.skill_index = function(){
		return this.grid_index('qygl', 'skill', '技能管理');
	}
	
	this.skill_grade_index = function(){
		return this.grid_index('qygl', 'skill_grade', '技能等级管理');
	}
	
	this.credit_level_index = function(){
		return this.grid_index('qygl', 'credit_level', '信用等级管理');
	}
	
	this.dept_index = function(){
		return this.grid_index('qygl', 'dept', '部门管理');
	}
	
	this.position_index = function(){
		return this.grid_index('qygl', 'position', '职位管理');
	}
	
	this.hb_fl_index = function(){
		return this.grid_index('qygl', 'hb_fl', '伙伴类型管理');
	}
	
	this.work_type_index = function(){
		return this.grid_index('qygl', 'work_type', '工种管理');
	}
	
	this.contact_method_index = function(){
		return this.grid_index('qygl', 'contact_method', '联系方式管理');
	}
	
	this.yw_index = function(){
		return this.grid_index('qygl', 'yw', '业务管理');
	}
	
	this.yw_cg_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_xd', '下采购单');
	}
	
	this.yw_sh_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_yunshu', '收货', {url_add:{yw_fl_id:2}, postFix:'sh'});
		// return this.grid_index('qygl', 'zzvw_yw_sh', '收货');
	}
	
	this.yw_th_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_yunshu', '退货', {url_add:{yw_fl_id:10}, postFix:'th'});
	}
	
	this.yw_xs_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_jd', '接订单');
	}
	
	this.yw_fh_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_yunshu', '发货', {url_add:{yw_fl_id:6}, postFix:'fh'});
		// return this.grid_index('qygl', 'zzvw_yw_fh', '发货');
	}
	
	this.yw_sth_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_yunshu', '收退货', {url_add:{yw_fl_id:11}, postFix:'jth'});
		// return this.grid_index('qygl', 'yw_sth', '收退货');
	}
	
	this.yw_chuku_index = function(){
		return this.grid_index('qygl', 'yw_chuku', '出库');
	}
	
	this.dingdan_index = function(){
		return this.grid_index('qygl', 'dingdan', '订单管理');
	}
	
	this.yw_fl_index = function(){
		return this.grid_index('qygl', 'yw_fl', '业务分类管理');
	}
	
	this.fh_fl_index = function(){
		return this.grid_index('qygl', 'fh_fl', '发货方式管理');
	}
	
	this.jszb_index = function(){
		return this.grid_index('qygl', 'jszb', '技术指标管理');
	}
	
	this.unit_fl_index = function(){
		return this.grid_index('qygl', 'unit_fl', '单位分类管理');
	}
	
	this.unit_index = function(){
		return this.grid_index('qygl', 'zzvw_unit', '计量单位管理');
	}
	
	this.yw_zj_jinchu_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_zj_jinchu', '资金变动');
	}
	
	this.yw_zj_zhifu_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_zj_zhifu', '支付');
	}
	
	this.yw_zj_hk_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_zj_hk', '回款');
	}
	
	this.yw_kp_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_kp', '开发票');
	}
	
	this.yw_jfp_index = function(){
		return this.grid_index('qygl', 'zzvw_yw_jfp', '接到发票');
	}
	
	this.fp_index = function(){
		return this.grid_index('qygl', 'fp', '发票管理');
	}
	
	this.zjzh_huabo = function(){
		return this.grid_index('qygl', 'zzvw_yw_zj_huabo', '账户间划拨');
	}
	
	this.chengdui_tiexi = function(){
		return this.grid_index('qygl', 'zzvw_yw_zj_pj_tiexi', '票据贴息/拆分');
	}
	
	this.chengdui_chaifen = function(){
		return this.grid_index('qygl', 'chengdui_chaifen', '承兑拆分');
	}
	
	this.zjzh_index = function(){
		return this.grid_index('qygl', 'zjzh', '资金账户管理');
	}
	
	this.zj_fl_index = function(){
		return this.grid_index('qygl', 'zj_fl', '资金类型管理');
	}
	
	this.zj_cause_index = function(){
		return this.grid_index('qygl', 'zj_cause', '资金变动原因管理');
	}
	
	this.zj_pj_index = function(){
		return this.grid_index('qygl', 'zj_pj', '金融票据管理');
	}
	
	this.gx_index = function(){
		return this.grid_index('qygl', 'gx', '工序管理');
	}
	
	this.gx_fl_index = function(){
		return this.grid_index('qygl', 'gx_fl', '工序分类管理');
	}
	
	this.gx_de_index = function(){
		return this.grid_index('qygl', 'gx_de', '定额管理');
	}
	
	this.hjcs_index = function(){
		return this.grid_index('qygl', 'hjcs', '工作环境参数');
	}

	this.tjbb_index = function(){
		return this.grid_index('qygl', 'tj', '统计报表');
	}
	
	
}).apply(XT);
