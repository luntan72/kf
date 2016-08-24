<?php
require_once('const_def.php');
// require_once('toolfactory.php');
require_once('cellfactory.php');

class kf_form{
	protected $elements = array();
	protected $value = array();
	protected $display_status = DISPLAY_STATUS_VIEW;
	protected $tool = null;
	function __construct($elements, $value = array(), $display_status = ''){
		$this->init($elements, $value, $display_status);
	}
	
	function init($elements, $value, $display_status){
// print_r("displayStatus = $display_status");		
		$this->display_status = empty($display_status) ? DISPLAY_STATUS_EDIT : $display_status;
		$this->value = $value;
		$this->tool = toolFactory::get('kf');
		foreach($elements as $k=>$cell){
			$this->elements[$k] = $this->tool->model2e($cell, $this->value, $this->display_status);
		}
	}
	
	function display($colsInRow = 1, $colWidth = array()){
// print_r("Before display, cols = $colsInRow");
		$hidden = array();
		$class = 'ces';
		if($this->display_status == DISPLAY_STATUS_QUERY)
			$class = 'ces_query';
		$normal = array("<table id='normal_elements' class='$class' style='width:100%'>");
		$normal[] = "<tr>";
		if(empty($colsInRow))
			$colsInRow = 1;
		$w1 = 25 / $colsInRow;
		$w2 = 75 / $colsInRow;
		if($colsInRow == 1){
			$w1 = 10;
			$w2 = 90;
		}
		for($i = 0; $i < $colsInRow; $i ++){
			$normal[] = "<th class='ces' style='width:$w1%' /><th class='ces' style='width:$w2%' />";
		}
		$normal[] = "</tr>";
		
		$currentCol = 0;
		$evenRow = true;
		$display_status = $this->display_status;
		if($display_status != DISPLAY_STATUS_VIEW)
			$display_status = DISPLAY_STATUS_EDIT;
// print_r($this->elements);		
		foreach($this->elements as $k=>$cell){
// print_r($cell);			
			if($cell['type'] == 'hidden'){
				$hidden[] = "<input type='hidden' name='{$cell['name']}' id='{$cell['id']}' value='{$cell['value']}'>";
			}
			else{
				if($currentCol ++ == 0){
					$rowClass = $evenRow ? 'evenRow' : 'oddRow';
					$normal[] = "<tr id='ces_tr_{$cell['id']}' class='ces $rowClass'>";
					$evenRow = !$evenRow;
				}
				
				$e = cellFactory::get($cell, $this->value);//new kf_cell($cell);
				$params = $e->getParams();
// print_r($params);				
				$normal[] = "<td id='td_label_{$params['id']}' class='pre-td'>";
				$normal[] = $e->pre($display_status);
				$normal[] = "</td>";
				$width = '';
				$hasPost = false;
				if(!empty($params['post'])){
					if($display_status == DISPLAY_STATUS_EDIT)
						$hasPost = true;
					else{ //如果是一维的，则判断是否type=text，否则判断每个post的type是否有为text的
						if(!array_key_exists(0, $params['post'])){
							$params['post'] = array($params['post']);
						}
						foreach($params['post'] as $post){
							if($post['type'] == 'text'){
								$hasPost = true;
								break;
							}
						}
					}
				}
				$postClass = '';
				if($hasPost && $display_status != DISPLAY_STATUS_VIEW){
					$normal[] = "<td><table style='width:100%'><tr style='width:100%'>";
					$width = "style='width:100%;'";
					$postClass = 'post-td';
				}
				$normal[] = "<td id='td_{$params['id']}' class='$postClass cont-td' $width>";
				$normal[] = $e->display($display_status);
				// if($hasPost && $display_status == DISPLAY_STATUS_VIEW)
					// $normal[] = $e->post($display_status);
				// $normal[] = "</td>";
				// display the post
				if($hasPost && $display_status != DISPLAY_STATUS_VIEW){
					// $normal[] = "<td id='post_{$params['id']}' class='$postClass' style='width:auto' style='white-space: nowrap' nowrap='nowrap'>";
					// $normal[] = $e->post($display_status);
					// $normal[] = "</td>";
					$normal[] = "</tr></table></td>";
				}

				if($currentCol == $colsInRow){
					$normal[] = "</tr>";
					$currentCol = 0;
				}
			}
		}
		if($currentCol != 0)
			$normal[] = "</tr>";
		$normal[] = '</table>';
// print_r($hidden)		;
// print_r($normal);
// $this->tool->p_t("After display");

		return implode("\n", $hidden)."\n".implode("\n", $normal);
	}
}
?>