<?php
require_once('keyword_file_parse.php');
require_once('context.php');

class skywalker_logfile_parse extends keyword_file_parse{
	// protected $tool = null;
	protected $lines = array();
	protected $started = false;
	protected $cycle_id = 0;
	protected $last_case = array();
	
	protected function _parseFileName($pathInfo){
		$dt = filectime($this->filename);
		$tmp = explode('_', $pathInfo['filename']);
		$tmp = explode('-', $tmp[0]);
		$chip = $tmp[0];
		$board_type = $tmp[1];
		$this->data['cycle'] = array('chip'=>$chip, 'board_type'=>$board_type, 'start_date'=>date('Y-m-d', $dt), 'auto_created'=>1);
	}

	protected function handleKeywords($keyword, $value, $line){
// if(!empty($keyword)){
// print_r("keyword = $keyword, ");
// print_r($value);
// }
		if(!$this->started && !empty($keyword) && $keyword == 'start'){
			$this->started = true;
			$this->lines = array();
		}
		if($this->started){
			$this->lines[] = $line;
			
			switch($keyword){
				case 'case_and_starttime':
					$case = $value[1];
					$start_time = $value[2];
					$this->last_case = array('testcase'=>$case, 'start_time'=>$start_time);
					break;
				case 'case_result':
					$duration = $value[1];
					$termination_type = $value[2];
					$termination_id = $value[3];
					$this->last_case['duration_minutes'] = $duration;
					if($termination_type == 'exited' && $termination_id == '0'){
						$this->last_case['result_type_id'] = RESULT_TYPE_PASS;
					}
					else
						$this->last_case['result_type_id'] = RESULT_TYPE_FAIL;
					break;
				case 'release':
// print_r("Release : ".$value[1]."\n");
					// $this->last_case['realease'] = $value[1];
					if(!isset($this->data['cycle']['rel']))
						$this->data['cycle']['rel'] = $value[1];
					break;
				case 'stop':
					$this->started = false;
					$this->last_case['logfile'] = implode("\n", $this->lines);
					$this->data['cycle_detail'][$this->last_case['testcase']] = $this->last_case;
					break;
			}
		}
	}
	
}


?>
