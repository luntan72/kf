<?php
require_once("state.php");
class state_start extends state{
	public function handle($line){
		$ret = array();
		$params = $this->context->getParams();
// print_r($params);			
		foreach($params['keyword'] as $i=>$keyword){
			if($i == 'start')
				continue;
			
			if(preg_match($keyword, $line, $matches)){
// print_r("$i:$keyword\n");
				$ret[$i] = $matches;
				if($i == 'stop'){
					$this->context->shiftToState('undefined');
					break;
				}
			}
		}
		return $ret;
	}
}
?>