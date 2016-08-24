<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH.'/jqgrid/qygl/hb/hb.php');
//员工管理
class qygl_zzvw_yg extends qygl_hb{
	protected function init($params){
		parent::init($params);
		$this->options['caption'] = g_str('yg');
		unset($this->options['list']['tax_no']);
		unset($this->options['edit']['tax_no']);
		unset($this->options['add']['tax_no']);
		unset($this->options['view']['tax_no']);
		$this->options['linkTables']['one2m']['hb_skill'] = array('link_table'=>'hb_skill', 'self_link_field'=>'hb_id');
		$this->options['linkTables']['one2one'] = array(
			'hb_yg'=>array('link_table'=>'hb_yg', 'self_link_field'=>'hb_id')
			);
		// $this->options['navOptions']['refresh'] = false;
	}
	
	protected function getDetailListColumns(){
		$ret = array(
			'enter_date'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			'work_type_id'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			'dept_id'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			'position_id'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			'salary_fl_id'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			'base_salary'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			'ticheng_ratio'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			'baoxian_type_id'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			'baoxian_start_date'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			'baoxian_feiyong'=>array('editable'=>true, 'from'=>'qygl.hb_yg'),
			
			'hb_skill'=>array('formatter'=>'multi_row_edit', 'legend'=>'',
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>'%(skill_id)s:%(skill_grade_id)s')
			),
		);
		return $ret;
	}
	
	protected function contextMenu(){
		$menu = array(
			'cg'=>'采购',
			'xs'=>'接订单',
			'scdj'=>'生产登记',
		);
		return $menu;
	}
	
    // protected function getButtons(){
        // $buttons = array(
			// 'ask2review'=>array('caption'=>'申请审核'),
			// 'sign'=>array('caption'=>'正式签署'),
			// 'change'=>array('caption'=>'统一调整'), //统一调整工种、职位、基本工资、提成比例等
            // // 'scdj'=>array('caption'=>'生产登记'),
			// 'jsgz'=>array('caption'=>'生成工资单'),
            // // 'gz'=>array('caption'=>'发工资'),
        // );
        // return array_merge($buttons, parent::getButtons());
    // }
}
