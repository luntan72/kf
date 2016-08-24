<?php
require_once('statefactory.php');

class context{
	protected $params = array();
	protected $states = array();
	protected $currentState = null;
	
	public function __construct($params){
		$this->init($params);
	}
	
	protected function init($params){
		$this->params = $params;
		$this->shiftToState('undefined');
		// $this->currentState = stateFactory::get($this, 'undefined');
	}
	
	public function request($data){
		return $this->currentState->handle($data);
	}
	
	public function getParams(){
		return $this->params;
	}
	
	public function shiftToState($stateName){
// print_r("shift to $stateName\n");		
		if(empty($this->states[$stateName])){
			$this->states[$stateName] = stateFactory::get($this, $stateName);
		}
		$this->currentState = $this->states[$stateName];
	}
}
?>
