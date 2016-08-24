<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
//物资管理
class qygl_wz extends table_desc{
	protected function init($params){
		parent::init($params);

		// print_r($params);
		$post = '?';
		$wz_fl_id = 0;
		if(!empty($params['id']) && count($params['id']) == 1){
			$res = $this->tool->query("select unit.name as unit, wz.wz_fl_id from wz left join unit on wz.unit_id=unit.id where wz.id={$params['id']}");
			$row = $res->fetch();
			$post = $row['unit'];
			if(empty($post))
				$post = '?';
			
			$wz_fl_id = $row['wz_fl_id'];
		}
		
        $this->options['list'] = array(
            'id'=>array('editable'=>false, 'hidden'=>true),
            'name'=>array('label'=>'名称', 'editrules'=>array('required'=>true)),
			'wz_fl_id'=>array('label'=>'类型', 'editrules'=>array('required'=>true)),
			'unit_id'=>array('label'=>'计量单位'),
			// 'unit_name'=>array('label'=>'计量单位', 'hidden'=>true, 'hidedlg'=>true),
			// 'price1'=>array('label'=>'默认单价', 'DATA_TYPE'=>'float', 'editable'=>true, 'post'=>'元', 'hidden'=>true, 'hidedlg'=>true),
			// 'min_kc1'=>array('label'=>'最低库存', 'DATA_TYPE'=>'float', 'editable'=>true, 'post'=>$post, 'hidden'=>true, 'hidedlg'=>true),
			// 'max_kc1'=>array('label'=>'最高库存', 'DATA_TYPE'=>'float', 'editable'=>true, 'post'=>$post, 'hidden'=>true, 'hidedlg'=>true),
			// 'ck_weizhi_id1'=>array('label'=>'库存位置', 'DATA_TYPE'=>'int', 'formatter'=>'select', 'editable'=>true, 'data_source_db'=>'qygl', 'data_source_table'=>'ck_weizhi', 'hidden'=>true, 'hidedlg'=>true),
			// 'remained1'=>array('label'=>'库存量', 'DATA_TYPE'=>'float', 'editable'=>true, 'post'=>$post, 'hidden'=>true),// 'hidedlg'=>true),
			'pd_days1'=>array('label'=>'盘点周期', 'DATA_TYPE'=>'int', 'editable'=>true, 'post'=>'天', 'hidden'=>true, 'hidedlg'=>true),
			// 'pd_last'=>array('label'=>'最近盘点日期', 'hidden'=>true),
			'zuhe'=>array('label'=>'是否组合', 'defval'=>1,
				'formatter'=>'select', 'formatoptions'=>array('value'=>array(1=>'单个零件', 2=>'组合产品')), 
				'stype'=>'select', 'searchoptions'=>array('value'=>array(0=>'', 1=>'单个零件', 2=>'组合产品')), 
				'edittype'=>'radio', 'editoptions'=>array('value'=>array(1=>'单个零件', 2=>'组合产品'))
			),
			'wz_cp_zuhe'=>array('from'=>'wz_cp_zuhe', 'label'=>'组合情况', 'legend'=>'零部件组合', 'data_source_table'=>'wz_cp_zuhe', 
				'formatter'=>'multi_row_edit', 'hidden'=>true, 'formatoptions'=>array('subformat'=>'temp', 'temp'=>'%(input_wz_id)s: %(amount)s')),
			'cp'=>array('label'=>'正式用品', 'defval'=>1, 'hidden'=>true,
				'formatter'=>'select', 'formatoptions'=>array('value'=>array(1=>'正式产品', 2=>'辅助产品')), 
				'stype'=>'select', 'searchoptions'=>array('value'=>array(0=>'', 1=>'正式产品', 2=>'辅助产品')), 
				'edittype'=>'radio', 'editoptions'=>array('value'=>array(1=>'正式产品', 2=>'辅助产品'))
			),
			'youxiaobili'=>array('label'=>'有效比例', 'post'=>'%', 'hidden'=>true),
			'tuzhi'=>array('label'=>'图纸', 'hidden'=>true, 'type'=>'files', 'db'=>'qygl', 'table'=>'wz'),
			'jszb_wz'=>array('from'=>'jszb_wz', 'label'=>'技术指标', 'legend'=>'详细技术指标要求', 'data_source_table'=>'jszb_wz', 
				'formatter'=>'multi_row_edit', 'hidden'=>true, 'formatoptions'=>array('subformat'=>'temp', 'temp'=>'%(jszb_id)s:[%(min_value)s, %(max_value)s]')),
			'gx_wz'=>array('label'=>'工序情况', 'legend'=>'默认的单价，存放位置等', 
				'data_source_table'=>'gx_wz', 'formatter'=>'multi_row_edit', 'hidden'=>true,
				'formatoptions'=>array('subformat'=>'temp', 'temp'=>'工序：%(gx_id)s, 库存情况:%(defect_gx_wz)s')),
			'muju'=>array('label'=>'模具', 'legend'=>'模具', 'data_source_table'=>'muju', 'hidden'=>true, 
				'formatter'=>'multi_row_edit', 'formatoptions'=>array('subformat'=>'temp', 'temp'=>'模具：%(muju_type_id)s')),
			'wz_sb'=>array('label'=>'设备', 'legend'=>'设备清单', 'data_source_table'=>'wz_sb', 'hidden'=>true, 
				'formatter'=>'multi_row_edit', 'formatoptions'=>array('subformat'=>'temp', 'temp'=>'设备：%(fix_code)s')),
			'jy_days'=>array('label'=>'最大积压天数', 'post'=>'天', 'hidden'=>true),
			// 'wh_days'=>array('label'=>'维护周期', 'post'=>'天', 'hidden'=>true),
			'midu'=>array('label'=>'密度', 'post'=>'克/立方厘米', 'hidden'=>true),
			'min_kc'=>array('label'=>'最小库存', 'post'=>'?', 'hidden'=>true),
			'tj'=>array('label'=>'体积', 'post'=>'立方厘米', 'hidden'=>true),
			'bmj'=>array('label'=>'表面积', 'post'=>'平方厘米', 'hidden'=>true),
			'default_price'=>array('label'=>'默认单价', 'post'=>'元'),
			'hb_id'=>array('label'=>'客户/供应商', 'editable'=>true, 'data_source_table'=>'zzvw_gys_kh'),
			'pic'=>array('label'=>'照片', 'hidden'=>true, 'type'=>'files', 'db'=>'qygl', 'table'=>'wz'),
			'note'=>array('label'=>'备注', 'hidden'=>true),
			'isactive'
        );
		$this->options['linkTables'] = array(
			'm2m'=>array(
				'hb'=>array('link_table'=>'hb_wz', 'self_link_field'=>'wz_id', 'link_field'=>'hb_id', 'refer_table'=>'hb'), //只能选择供应商或客户
				),
			// 'one2one'=>array(),
			'one2m'=>array(
				'muju',
				'jszb_wz'=>array('link_table'=>'jszb_wz', 'self_link_field'=>'wz_id', 'link_field'=>'jszb_id'),
				'wz_cp_zuhe'=>array('link_table'=>'wz_cp_zuhe', 'self_link_field'=>'wz_id', 'link_field'=>'input_wz_id'),
				'gx_wz'=>array('link_table'=>'gx_wz', 'self_link_field'=>'wz_id', 'link_field'=>'gx_id'),
				'wz_sb'=>array('link_table'=>'wz_sb', 'self_link_field'=>'wz_id', 'link_field'=>''),
				),
			);
		
		$this->options['edit'] = array('wz_fl_id', 'name', 
			'unit_id', 
			
			// 'price1', 'min_kc1', 'max_kc1', 'ck_weizhi_id1', 'remained1', 'pd_days1', //专门为原料和设备定制
			
			'zuhe', 'wz_cp_zuhe', 'cp', 'youxiaobili', 'tuzhi', 'jszb_wz',  'gx_wz', 'muju', //为产品定制
			'wz_sb', //为设备定制
			'jy_days', //'wh_days',
			'midu', 'min_kc', 'tj', 'bmj', 'default_price', 'hb_id', 'pic', 'note', 'isactive'
		);
		$this->options['add'] = array('wz_fl_id', 'name', 'unit_fl_id'=>array('label'=>'计量单位类型'), 
			'unit_id', 
			
			// 'price1', 'min_kc1', 'max_kc1', 'ck_weizhi_id1', 'remained1', 'pd_days1', //专门为原料和设备定制
			
			'zuhe', 'wz_cp_zuhe', 'cp', 'youxiaobili', 'tuzhi', 'jszb_wz',  'gx_wz', 'muju', //为产品定制
			'wz_sb', //为设备定制
			'jy_days', //'wh_days',
			'midu', 'min_kc', 'tj', 'bmj', 'default_price', 'hb_id', 'pic', 'note', 'isactive'
		);
		$this->options['caption'] = '物资';
		// $this->options['navOptions']['refresh'] = false;
	}
	
	protected function contextMenu(){
		$menu = array(
			'cg'=>'采购',
			'xs'=>'接订单',
			'scdj'=>'生产登记',
		);
		return $menu;
	}
}
