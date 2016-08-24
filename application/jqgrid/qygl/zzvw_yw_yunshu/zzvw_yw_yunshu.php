<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH."/jqgrid/qygl/yw/yw.php");
//运输管理，包括发货，收货，退货，接退货，至于是哪种由参数决定
class qygl_zzvw_yw_yunshu extends qygl_yw{
	protected function init($params){
		// if(isset($params['url_add'])){
			// foreach($params['url_add'] as $k=>$v)
				// $params[$k] = $v;
		// }
// print_r($params['yw_fl_id']);
		parent::init($params);
		$this->options['list']['hb_id']['label'] = g_str('cyr');
		$this->options['list']['hb_id']['data_source_db'] = 'qygl';
		$this->options['list']['hb_id']['data_source_table'] = 'zzvw_cyr';
		$this->options['edit']['yw_fl_id']['type'] = 'hidden';
		$this->options['edit']['yw_fl_id']['defval'] = $this->params['yw_fl_id'];
		$this->options['add']['yw_fl_id']['type'] = 'hidden';
		$this->options['add']['yw_fl_id']['defval'] = $this->params['yw_fl_id'];
	}
	
	protected function getDetailListColumns(){
		parent::getDetailListColumns();
// print_r($this->params);
		//获取运输和装卸的默认单价
		$price = array(WZ_YUNSHU=>0, WZ_ZHUANGXIE=>0);
		$res = $this->tool->query("SELECT id, default_price FROM wz WHERE id in (".WZ_YUNSHU.','.WZ_ZHUANGXIE.")");
		while($row = $res->fetch())
			$price[$row['id']] = $row['default_price'];
		$ret = array(
			'yunshu_price'=>array('post'=>array('value'=>'元/吨'), 'defval'=>$price[WZ_YUNSHU], 'from'=>'qygl.yw_yunshu', 'editable'=>true, 'DATA_TYPE'=>'float'),
			'zxr_id'=>array('data_source_table'=>'zzvw_zxr', 'from'=>'qygl.yw_yunshu', 'editable'=>true),
			'zx_price'=>array('post'=>array('value'=>'元/吨'), 'defval'=>$price[WZ_ZHUANGXIE], 'from'=>'qygl.yw_yunshu', 'editable'=>true, 'DATA_TYPE'=>'float'),
		);
		switch($this->params['yw_fl_id']){
			case YW_FL_FH: //发货
				$ret['zzvw_yw_fh_detail'] = array('label'=>g_str('fh_detail'), 'editable'=>true, 'legend'=>'', 'required'=>true,
					'formatter'=>'multi_row_edit', 
					'formatoptions'=>array('subformat'=>'temp', 'temp'=>"%(hb_id)s订购的%(wz_id)s %(amount)s"),
					'itemParams'=>array('yw_fl_id'=>$this->params['yw_fl_id'])
				);
				$this->options['caption'] = g_str('yw_fh');
				$this->options['postFix'] = 'fh';
				$this->options['linkTables'] = array(
					'one2one'=>array(
						array('table'=>'yw_yunshu', 'self_link_field'=>'yw_id')
						),
					'one2m'=>array(
						array('table'=>'zzvw_yw_fh_detail',
							'real_table'=>'yw_fh_detail',
							'self_link_field'=>'yw_id'
						)
					)
				);
				break;
			case YW_FL_JTH: //接退货
				$ret['zzvw_yw_jth_detail'] = array('label'=>g_str('jth_detail'), 'editable'=>true, 'legend'=>'', 'required'=>true,
					'formatter'=>'multi_row_edit', 
					'formatoptions'=>array('subformat'=>'temp', 'temp'=>"%(hb_id)s订购的%(wz_id)s %(amount)s"),
					'itemParams'=>array('yw_fl_id'=>$this->params['yw_fl_id'])
				);
				$this->options['caption'] = g_str('yw_jth');
				$this->options['postFix'] = 'jth';
				$this->options['linkTables'] = array(
					'one2one'=>array(
						array('table'=>'yw_yunshu', 'self_link_field'=>'yw_id')
						),
					'one2m'=>array(
						array('table'=>'zzvw_yw_jth_detail',
							'real_table'=>'yw_jth_detail',
							'self_link_field'=>'yw_id'
						)
					)
				);
				break;
			case YW_FL_SH: //收货
				$ret['zzvw_yw_sh_detail'] = array('label'=>g_str('sh_detail'), 'editable'=>true, 'legend'=>'', 'required'=>true,
					'formatter'=>'multi_row_edit', 
					'formatoptions'=>array('subformat'=>'temp', 'temp'=>"向%(hb_id)s订购的%(wz_id)s %(amount)s"),
					'itemParams'=>array('yw_fl_id'=>$this->params['yw_fl_id'])
				);
				$this->options['caption'] = g_str('yw_sh');
				$this->options['postFix'] = 'sh';
				$this->options['linkTables'] = array(
					'one2one'=>array(
						array('table'=>'yw_yunshu', 'self_link_field'=>'yw_id')
						),
					'one2m'=>array(
						array('table'=>'zzvw_yw_sh_detail',
							'real_table'=>'yw_sh_detail',
							'self_link_field'=>'yw_id'
						)
					)
				);
				break;
			case YW_FL_TH: //退货
				$ret['zzvw_yw_th_detail'] = array('label'=>g_str('th_detail'), 'editable'=>true, 'legend'=>'', 'required'=>true,
					'formatter'=>'multi_row_edit', 
					'formatoptions'=>array('subformat'=>'temp', 'temp'=>"向%(hb_id)s订购的%(wz_id)s %(amount)s"),
					'itemParams'=>array('yw_fl_id'=>$this->params['yw_fl_id'])
				);
				$this->options['caption'] = g_str('yw_th');
				$this->options['postFix'] = 'th';
				$this->options['linkTables'] = array(
					'one2one'=>array(
						array('table'=>'yw_yunshu', 'self_link_field'=>'yw_id')
						),
					'one2m'=>array(
						array('table'=>'zzvw_yw_th_detail',
							'real_table'=>'yw_th_detail',
							'self_link_field'=>'yw_id'
						)
					)
				);
				break;
		}
		$ret['weight'] = array('post'=>array('value'=>'吨'), 'from'=>'qygl.yw_yunshu', 'editable'=>true, 'DATA_TYPE'=>'float');
		$ret['kg_id'] = array('from'=>'qygl.yw_yunshu', 'data_source_table'=>'zzvw_yg', 'editable'=>true);
			
		return $ret;
	}
	
	
	protected function _setSubGrid(){
        $this->options['gridOptions']['subGrid'] = true;
		$this->options['subGrid'] = array('expandField'=>'yw_id', 'db'=>'qygl', 'table'=>'zzvw_pici_sh');
	}
}
