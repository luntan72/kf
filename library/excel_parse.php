<?php
require_once('file_parse.php');

class excel_parse extends file_parse{
	protected $columnMaps = array();
	
	protected function _parse(){
		/**  Identify the type of $inputFileName  **/
		$inputFileType = PHPExcel_IOFactory::identify($this->filename);
		/**  Create a new Reader of the type that has been identified  **/
		$reader = PHPExcel_IOFactory::createReader($inputFileType);
		$reader->setReadDataOnly(true);
		$objExcel = $reader->load($this->filename);
		$this->analyzeExcel($objExcel);
	}

	protected function analyzeExcel($excel){
		foreach($excel->getWorksheetIterator() as $index=>$sheet){
			$title = $sheet->getTitle();
			// $title = strtolower($title);
			$needParse = true;
//print_r($this->sheetsNeedParse);
			if (!empty($this->params['sheetsNeedParse']) && !in_array($title, $this->params['sheetsNeedParse']))
				$needParse = false;
//print_r("title = $title, need = $needParse\n");
			if ($needParse){
				$method = preg_replace('/[\s-=]/', '_', $title);
				$method = 'analyze_'.$method;
//print_r("title = $title, method = $method\n");				
				if (method_exists($this, $method)){
					$this->{$method}($sheet, $title);
				}
				else{
					$this->default_analyze_sheet($sheet, $title);
				}
			}
		}
		$excel->disconnectWorksheets();
	}
		
	protected function default_analyze_sheet($sheet, $title){
		$highestRow = $sheet->getHighestRow(); // e.g. 10
		$highestColumn = $sheet->getHighestColumn(); // e.g 'F'
 // print_r("title = $title, hiColumn = $highestColumn, highestRow = $highestRow\n");		
		$cm = $this->getColumnMap($title, $highestColumn);
// print_r($cm);
		if (!empty($cm)){
			for($row = $cm['start_row']; $row <= $highestRow; $row ++){
				foreach($cm['columns'] as $col=>$key){
					$current = $this->data[$title][$row][$key] = trim($this->getCell($sheet, $row, $col));
					if(empty($current) && !empty($cm['merge_columns']) && !empty($cm['merge_columns'][$key]) && !empty($this->parser_result[$title][$row - 1])) //可能有列合并的情况
						$this->data[$title][$row][$key] = $this->data[$title][$row-1][$key];
						
					// if(!empty($this->data[$title][$row][$key]))
						// continue;
					// if(empty($cm['merge_columns']))
						// continue;
					// if(empty($cm['merge_columns'][$key]))
						// continue;
					// if(empty($this->data[$title][$row-1]))
						// continue;
					// $this->data[$title][$row][$key] = $this->data[$title][$row-1][$key];
				}
			}
		}
	}
		
	protected function getColumnMap($title, $highestColumn){
// print_r($this->columnMaps);		
		if (!empty($this->columnMaps)){
			if (isset($this->columnMaps[$title]))
				return $this->columnMaps[$title];
			if (isset($this->columnMaps['default']))
				return $this->columnMaps['default'];
			// return array();
		}
		$column = array();
		for($i = 'A'; $i != $highestColumn; $i = $this->nextCol($i))
			$column[$i] = $i;
		$column[$highestColumn] = $highestColumn;
		return array('start_row'=>2, 'columns'=>$column);
	}
			
	public function nextCol($current){
		if (is_int($current))
			return $current + 1;
		$head = substr($current, 0, -1);
		$last = substr($current, -1);
//print_r("current = $current, head = $head, last = $last\n");
		if ($last < 'Z')
			return $head.chr(ord($last) + 1);
		else
			return $head.'AA';
	}
	
	public function RC2LN($row, $col){ //(col, row)=>'A'n
    	$ret = array();
		$loop = (int)($col / 26);
		$rest = $col % 26;
		if ($loop > 0){
			$ret[] = chr(ord('A') + $loop - 1);
		}
		$ret[] = chr(ord('A') + $rest);
//print_r("col=$col, loop = $loop, ret =");
//print_r($ret);
		return implode('', $ret).$row;
	}
	
	function excelTime($date, $time = false) {
		if (function_exists('GregorianToJD')) {
			if (is_numeric($date)) {
				$jd = GregorianToJD(1, 1, 1970);
				$gregorian = JDToGregorian($jd + intval($date) - 25569);
				$date = explode('/', $gregorian);
				$date_str = str_pad($date[2], 4, '0', STR_PAD_LEFT) . "-" . str_pad($date[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date[1], 2, '0', STR_PAD_LEFT) . ($time ? " 00:00:00" : '');
				return $date_str;
			}
		} else {
				$date = $date > 25568 ? $date + 1 : 25569;
				/*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
				$ofs = (70 * 365 + 17 + 2) * 86400;
				$date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
		}
		return $date;
	}

	protected function getCell($sheet, $row, $col){
		if (is_int($col))
			return $sheet->getCellByColumnAndRow($col, $row)->getFormattedValue();
		return $sheet->getCell("$col{$row}")->getFormattedValue();
	}
	
	protected function getComment($sheet, $row, $col){
//print_r("row = $row, col = ");
//print_r($col);
		if (is_int($col))
			$ln = $this->RC2LN($row, $col);
		else
			$ln = "$col{$row}";
		$comment = $sheet->getComments($ln);
//print_r("comment = ");
//print_r($comment);
		return $comment;
	}
				
}
?>
