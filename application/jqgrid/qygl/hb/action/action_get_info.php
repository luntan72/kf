<?php
require_once('action_jqgrid.php');
require_once("const_def_qygl.php");
/*
defined('YW_FL_CG') || define('YW_FL_CG', 1); //下采购单
defined('YW_FL_YUNRU') || define('YW_FL_YUNRU', 2); //运入
defined('YW_FL_XIEZAI') || define('YW_FL_XIEZAI', 3); //卸载
defined('YW_FL_ZHUANGZAI') || define('YW_FL_ZHUANGZAI', 17); //装载
defined('YW_FL_RUKU') || define('YW_FL_RUKU', 4); //入库
defined('YW_FL_SCDJ') || define('YW_FL_SCDJ', 5);//生产登记
defined('YW_FL_ZJOUT') || define('YW_FL_ZJOUT', 6); //资金转出
defined('YW_FL_ZJIN') || define('YW_FL_ZJIN', 7); //资金转入
defined('YW_FL_ZHUANZHANG') || define('YW_FL_ZHUANZHANG', 8); //转账
defined('YW_FL_TUIHUO') || define('YW_FL_TUIHUO', 9); //退货
defined('YW_FL_JIESHOUTUIHUO') || define('YW_FL_JIESHOUTUIHUO', 10); //接收退货
defined('YW_FL_YIKU') || define('YW_FL_YIKU', 11); //移库
defined('YW_FL_JIESHOUDINGDAN') || define('YW_FL_JIESHOUDINGDAN', 12); //接收订单
defined('YW_FL_CHUKU') || define('YW_FL_CHUKU', 13); //出库
defined('YW_FL_YUNCHU') || define('YW_FL_YUNCHU', 14); //运出
defined('YW_FL_PANKU') || define('YW_FL_PANKU', 15); //盘库
defined('YW_FL_TX') || define('YW_FL_TX', 16); //贴息
*/
class qygl_hb_action_get_info extends action_jqgrid{
	protected function handlePost(){
		// if(empty($this->params['id']) && !empty($this->params['field']) && !empty($this->params['value']))
			// $this->params['id'] = $this->params['value'];
		$res = $this->tool->query("SELECT account_receivable FROM hb WHERE id={$this->params['id']}");
		$row = $res->fetch();
		return $row['account_receivable'];
	}
}
