<?php
require_once('table_desc.php');
require_once('const_def_qygl.php');
require_once(APPLICATION_PATH."/jqgrid/qygl/yw_tool.php");
require_once(APPLICATION_PATH."/jqgrid/qygl/yw/yw.php");
//下采购单
class qygl_zzvw_yw_jfp extends qygl_yw{
	protected function init($params){
// print_r($params);
		parent::init($params);
		$this->options['list']['hb_id']['label'] = '供应商';
		$this->options['list']['hb_id']['data_source_table'] = 'zzvw_gys';
		$this->options['list']['yw_fl_id']['type'] = 'hidden';
		$this->options['list']['yw_fl_id']['defval'] = YW_FL_JFP;
		$this->options['list']['happen_date']['label'] = '接到发票日期';
		// $this->options['edit']['yw_fl_id']['type'] = 'hidden';
		// $this->options['edit']['yw_fl_id']['defval'] = YW_FL_JFP;
		// $this->options['add']['yw_fl_id']['type'] = 'hidden';
		// $this->options['add']['yw_fl_id']['defval'] = YW_FL_JFP;
		$this->options['caption'] = '接到发票';
		$this->options['linkTables'] = array(
			'one2one'=>array(
				'fp'=>array('table'=>'fp', 'self_link_field'=>'yw_id')
				)
		);

		// $this->options['add'] = array('yw_fl_id'=>array('type'=>'hidden', 'defval'=>YW_FL_XD), 'hb_id', 'zzvw_dingdan', 
			// 'note', 'jbr_id', 'happen_date');
		// $this->options['edit'] = array('yw_fl_id'=>array('type'=>'hidden', 'defval'=>YW_FL_XD), 'hb_id'=>array('editable'=>false), 'zzvw_dingdan', 
			// 'note', 'jbr_id', 'happen_date');
			
		// $this->options['linkTables'] = array(
			// 'one2m'=>array(
				// 'zzvw_dingdan'=>array('table'=>'zzvw_dingdan', 'self_link_field'=>'yw_id')
				// )
		// );
		// $this->options['parent'] = array('table'=>'zzvw_gys', 'field'=>'hb_id');
	}
	
	protected function getDetailListColumns(){
		$ret = array(
			'fp_fl_id'=>array('label'=>'发票类型', 'from'=>'qygl.fp', 'editable'=>true),
			'from_date'=>array('label'=>'业务起始日期', 'from'=>'qygl.fp', 'editable'=>true),
			'to_date'=>array('label'=>'业务结束日期', 'from'=>'qygl.fp', 'editable'=>true),
			'amount'=>array('label'=>'总金额', 'post'=>'元', 'from'=>'qygl.fp', 'editable'=>true),
			'code'=>array('label'=>'编号', 'from'=>'qygl.fp', 'editable'=>true),
			'cyr_id'=>array('label'=>'寄送人', 'from'=>'qygl.fp', 'editable'=>true, 'data_source_table'=>'zzvw_cyr'),
			'yunfei'=>array('label'=>'寄送费用', 'post'=>'元', 'from'=>'qygl.fp', 'editable'=>true)
		);
		return $ret;
	}
	
}
