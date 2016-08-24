<?php
class factory{
	static function get($params = array()){
		static $elements = array();
		$index = $this->getIndex($params);
		if(!isset($elements[$index])){
			$element = $this->newElement($params);
			$element->post_init();
			$elements[$index] = $element;
		}
		return $elements[$index];
	}

	static function getIndex($params){
		return 'index';
	}
	
	static function newElement($params){
		return null;
	}
}
?>