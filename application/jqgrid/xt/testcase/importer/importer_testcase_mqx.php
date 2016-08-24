<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/testcase/importer/importer_testcase.php');

class xt_testcase_importer_testcase_mqx extends xt_testcase_importer_testcase{
	
	protected function default_analyze_sheet($sheet, $title){
		$sheet_title = strtolower($title);
		if($sheet_title == 'cover' || $sheet_title == 'test case list' || $sheet_title == 'test report')
			return;
		parent::default_analyze_sheet($sheet, $title);
	}

}
?>
