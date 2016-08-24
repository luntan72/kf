<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/zzvw_cycle/exporter/exporter_excel_testplan_detail.php');

class xt_zzvw_cycle_exporter_cycle_result extends xt_zzvw_cycle_exporter_excel_testplan_detail{
	protected function getInfoForCase($result){
			return $result;
	}
};

?>
