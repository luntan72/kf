<?php
class state{
	protected $context = null;
	public function __construct($context){
		$this->context = $context;
	}
	
	public function handle($data){
		return array();
	}
}

?>