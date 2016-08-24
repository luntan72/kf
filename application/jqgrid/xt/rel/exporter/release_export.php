<?php
require_once(APPLICATION_PATH.'/jqgrid/xt/xt_export.php');
require_once('base_report.php');

class release_export extends base_report{
	public function __construct($params = array()){
//		print_r($params);
		$sheetTitles = array(
			'Cover',
			'Projects',
			'Modules',
			'Detail'
		);
		foreach($params['projectInfo'] as $project){
			$sheetTitles[] = $project;
		}
//print_r($sheetTitles);		
		parent::__construct($sheetTitles, $params);
		// set column header
		$this->setSheetHeaders();
	}
	
    protected function calcStyle(&$content, $sheetIndex, $defaultStyle = array()){
		$style = array();
		$i = count($this->columnHeaders[$sheetIndex]['rows']) - 1;
        foreach($this->columnHeaders[$sheetIndex]['rows'][$i] as $columnHeader){
            if (!isset($columnHeader['index']))
                continue;
            $key = $columnHeader['index'];
			if ($sheetIndex < 3){
				//check if the passrate cell
				if (stripos($key, '_passrate') > 0 && isset($content[$key])){
					if ($content[$key] >= 0.8)
						$style[$key] = 'high_percent';
					elseif ($content[$key] >= 0.6)
						$style[$key] = 'middle_percent';
					elseif ($content[$key] >= 0)
						$style[$key] = 'low_percent';
				}
				else{
//print_r("key = $key, content =".$content[$key]);
					if($key == 'board' && isset($content[$key]) && $content[$key] == 'subtotal'){
						$style[$key] = 'subtotal';
					}
					else
						$style[$key] = isset($defaultStyle[$key]) ? $defaultStyle[$key] : 
							(isset($columnHeader['style']) ? $columnHeader['style'] : 'normal');
				}
			}
			elseif (stripos($key, '_result_type') > 0 && isset($content[$key]) && strtolower($content[$key]) == 'fail')
				$style[$key] = 'warning';
			else{
				$style[$key] = isset($defaultStyle[$key]) ? $defaultStyle[$key] : 
					(isset($columnHeader['style']) ? $columnHeader['style'] : 'normal');
			}
		}
//		if ($sheetIndex == 0)
			//print_r($style);		
		return $style;
	}
	

	protected function getData($sheetIndex){
		switch($sheetIndex){
			case 0:
				return $this->params['cover'];
			case 1:
				return $this->params['projects'];
			case 2:
				return $this->params['module'];
			case 3:
				return $this->params['detail'];
			default:
				return $this->params['project'];
		}
	}
	
	private function setSheetHeaders(){
		$header = array();
		$result_type = $this->params['result_type'];
		
		$releaseHeader = array();
		$this->resultHeader = array();
		foreach($this->params['cover'] as $rel_id=>$rel){
			$releaseHeader[] = array('label'=>$rel['name'], 'width'=>150, 'index'=>'', 'columns'=>count($result_type) + 3);
			$this->resultHeader[] = array('label'=>'Total', 'width'=>80, 'index'=>$rel_id.'_total');
			foreach($result_type as $k=>$rt){
				$this->resultHeader[] = array('label'=>ucwords($rt), 'width'=>80, 'index'=>$rel_id.'_'.$rt);
			}
			$this->resultHeader[] = array('label'=>'CR', 'width'=>100, 'index'=>$rel_id.'_cr');
			$this->resultHeader[] = array('label'=>'Pass Rate', 'width'=>150, 'index'=>$rel_id.'_passrate', 'style'=>'percent');
		}
//print_r($resultHeader);		
		$prjHeader = array(
			array('label'=>'Project', 'width'=>200, 'index'=>'prj'),
			array('label'=>'Board', 'width'=>120, 'index'=>'board'),
			array('label'=>'Chip', 'width'=>120, 'index'=>'chip'),
			array('label'=>'OS/Compiler', 'width'=>180, 'index'=>'os'),
		);
		
		$moduleHeader = array(					
			array('label'=>'Module', 'width'=>150, 'index'=>'module', 'style'=>'right'),
		);
		
		$caseHeader = array(
			array('label'=>'Module', 'width'=>160, 'index'=>'testcase_module'),
			array('label'=>'Testpoint', 'width'=>200, 'index'=>'testcase_testpoint'),
			array('label'=>'Testcase ID', 'width'=>500, 'index'=>'code'),
		);
	
		$mergeColumns = 5;
		$headers = array(
			array( // cover header
				'rows'=>array(
					array(
						array('label'=>'Release Name', 'width'=>150, 'index'=>'name'),
						array('label'=>'Release Time', 'width'=>150, 'index'=>'release_time'),
						array('label'=>'Projects', 'width'=>150, 'index'=>'projects', 'style'=>'right'),
					)
				)
			),
			array( // projects header
				'merge'=>array('cols'=>5),
				'rows'=>array(
					array_merge($prjHeader, $moduleHeader, $releaseHeader),
					array_merge($prjHeader, $moduleHeader, $this->resultHeader),
				)
			),
			array( // module header
				'merge'=>array('cols'=>5),
				'rows'=>array(
					array_merge($moduleHeader, $prjHeader, $releaseHeader),
					array_merge($moduleHeader, $prjHeader, $this->resultHeader),
				)
			)
		);
		$header1 = array_merge($caseHeader, $prjHeader);
		foreach($this->params['cover'] as $rel){
			$header1[] = array('label'=>$rel['name'], 'width'=>150, 'index'=>'', 'columns'=>2);
		}
		$header2 = array_merge($caseHeader, $prjHeader);
		$header2[] = array('label'=>'Build Result', 'width'=>150, 'index'=>$rel['id'].'_build_result_type');
		$header2[] = array('label'=>'Test Result', 'width'=>150, 'index'=>$rel['id'].'_result_type');
		$headers[] = array(
			'merge'=>array('cols'=>7),
			'rows'=>array($header1, $header2)
		);
		
		$header1 = $caseHeader;
		foreach($this->params['cover'] as $rel){
			$header1[] = array('label'=>$rel['name'], 'width'=>150, 'index'=>'', 'columns'=>2);
		}
		$header2 = $caseHeader;
		$header2[] = array('label'=>'Build Result', 'width'=>150, 'index'=>$rel['id'].'_build_result_type');
		$header2[] = array('label'=>'Test Result', 'width'=>150, 'index'=>$rel['id'].'_result_type');
		foreach($this->params['projectInfo'] as $project){
			$headers[] = array(
				'merge'=>array('cols'=>3),
				'rows'=>array($header1, $header2)
			);
		}
//print_r($headers);	
		foreach($headers as $k=>$v)
			$this->setColumnHeader($v, $k);
	}
		
    protected function writeRow($content, $sheetIndex = 0, $defaultStyle = array(), $contentKey = null){
		switch($sheetIndex){
			case 0:
				parent::writeRow($content, $sheetIndex, $defaultStyle, $contentKey);
				break;
			case 1:
				$this->writeProjectRow($contentKey, $content, $defaultStyle);
				break;
			case 2:
				$this->writeModuleRow($contentKey, $content, $defaultStyle);
				break;
			case 3:
				$this->writeDetailRow($contentKey, $content, $defaultStyle);
				break;
			default:
//				$prj = array_search($sheetIndex, $this->sheetIndexMap);
				//$this->writeDetailRow($contentKey, $content, $defaultStyle, $prj);
		}
	}
	
	private function writeDetailRow($contentKey, $content, $defaultStyle){
		$sheetIndex = 3;
//print_r($content);
		$row = array();
		$module_start_row = $this->nextRow[$sheetIndex];
		foreach($content as $testpoint_id=>$tp){
			if ($testpoint_id == 'testcase_module'){
				$row[$testpoint_id] = $tp;
				continue;
			}
			$testpoint_start_row = $this->nextRow[$sheetIndex];
			foreach($tp as $testcase_id=>$tc){
				if ($testcase_id == 'testcase_testpoint'){
					$row[$testcase_id] = $tc;
					continue;
				}
				$tc_start_row = $this->nextRow[$sheetIndex];
//print_r($tc);				
				foreach($tc as $prj_id=>$prj){
					if ($prj_id == 'code'){
						$row['code'] = $prj;
						continue;
					}
					foreach($prj as $k=>$v)
						$row[$k] = $v;
//print_r($row);					
					parent::writeRow($row, $sheetIndex, $defaultStyle);
					// get the prj name and write a row to relative sheet
					$prj_sheet = $this->sheetIndexMap[$row['prj']];
					parent::writeRow($row, $prj_sheet, $defaultStyle);
				}
				$this->mergeCells($sheetIndex, $tc_start_row, 2, $this->nextRow[$sheetIndex] - 1, 2);
			}			
			$this->mergeCells($sheetIndex, $testpoint_start_row, 1, $this->nextRow[$sheetIndex] - 1, 1);
		}
		$this->mergeCells($sheetIndex, $module_start_row, 0, $this->nextRow[$sheetIndex] - 1, 0);
	}
	
	private function writeProjectRow($contentKey, $content, $defaultStyle){
		$sheetIndex = 1;
		$board_start_row = $this->nextRow[$sheetIndex];
		$subtotalStyle = $this->fillInStyle($sheetIndex, 'subtotal');
//print_r($content);
		$subtotal = array();
		foreach($content['module'] as $vs){
			$row = array('prj'=>$content['prj'], 'board'=>$content['board'], 'chip'=>$content['chip'], 'os'=>$content['os']);
			foreach($vs as $k=>$v){
				$row[$k] = $v;
				if (!isset($subtotal[$k]))
					$subtotal[$k] = $v;
				else
					$subtotal[$k] += $v;
			}
//print_r($row);			
			foreach($this->params['cover'] as $rel_id=>$rel){
				if(isset($row[$rel_id.'_Pass']))
					$row[$rel_id.'_passrate'] = $row[$rel_id.'_Pass'] / $row[$rel_id.'_total'];
				else
					$row[$rel_id.'_passrate'] = 0;
			}
			parent::writeRow($row, $sheetIndex, $defaultStyle);
			$this->objExcel->getActiveSheet()->getRowDimension($this->nextRow[$sheetIndex] - 1)->setOutlineLevel(1);
			$this->objExcel->getActiveSheet()->getRowDimension($this->nextRow[$sheetIndex] - 1)->setVisible(false);
		}
		$row['module'] = 'Sub total';
//print_r($subtotal);		
		foreach($subtotal as $k=>$v){
			if ($k != 'module'){
				$row[$k] = $v;
				if (preg_match('/^(.+)_pass$/i', $k, $matches)){
//print_r($matches);
					if(isset($subtotal[$matches[1].'_Pass']))
						$row[$matches[1].'_passrate'] = $subtotal[$matches[1].'_Pass'] / $subtotal[$matches[1].'_total'];
					else
						$row[$matches[1].'_passrate'] = 0;
						
				}
			}
		}
//print_r($row);		
		parent::writeRow($row, $sheetIndex, $subtotalStyle);
		$this->mergeCells($sheetIndex, $board_start_row, 0, $this->nextRow[$sheetIndex] - 1, 0);
		$this->mergeCells($sheetIndex, $board_start_row, 1, $this->nextRow[$sheetIndex] - 1, 1);
		$this->mergeCells($sheetIndex, $board_start_row, 2, $this->nextRow[$sheetIndex] - 1, 2);
		$this->mergeCells($sheetIndex, $board_start_row, 3, $this->nextRow[$sheetIndex] - 1, 3);
		$this->objExcel->getActiveSheet()->getRowDimension($this->nextRow[$sheetIndex] - 1)->setCollapsed(true);
	}	
	
	private function writeModuleRow($contentKey, $content, $defaultStyle){
//print_r($content);
		$sheetIndex = 2;
		$module_start_row = $this->nextRow[$sheetIndex];
		$subtotalStyle = $this->fillInStyle($sheetIndex, 'subtotal');
		$row = array('module'=>$content['testcase_module']);
		foreach($content['_prj'] as $prjs){
			foreach($prjs as $k=>$v){
				$row[$k] = $v;
			}
			foreach($this->params['cover'] as $rel_id=>$rel){
				if(isset($row[$rel_id.'_Pass']))
					$row[$rel_id.'_passrate'] = $row[$rel_id.'_Pass'] / $row[$rel_id.'_total'];
				else
					$row[$rel_id.'_passrate'] = 0;
			}
			parent::writeRow($row, $sheetIndex, $defaultStyle);
		}
		$this->mergeCells($sheetIndex, $module_start_row, 0, $this->nextRow[$sheetIndex] - 1, 0);
//		parent::writeRow(array(), $sheetIndex);
	}
}
?>
