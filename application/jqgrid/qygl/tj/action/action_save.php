<?php
require_once(APPLICATION_PATH.'/jqgrid/action/action_save.php');
require_once('exporterfactory.php');

class qygl_tj_action_save extends action_save{
	protected function handlePost(){
	// print_r($this->params);
	// return;
		$exporter = exporterFactory::get('qygl_tj', $this->params);
		$exporter->setOptions($this);
// print_r($exporter)		;
		$this->params['pdf'] = $exporter->export();
		// $this->
// print_r($this->params);		
		return parent::handlePost();
	}
	
}

?>