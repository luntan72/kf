<?php
require_once('const_def.php');

class kf_object{
	protected $params = array(); //外部传入的参数
	protected $options = array();//内部产生的数据
	protected $tool = null;
	protected $debug = false;
	public function __construct($params = array()){
		$this->init($params);
	}
	
	protected function init($params){
		$this->params = $params;
	}
	
	public function post_init(){
		//在工厂里被调用，在初始化之后进行一些其他设置
		
	}
	
	public function get($name){
		$ret = array();
		$onlyOneName = false;
		if(empty($name))
			return $this->params;
		if(is_string($name)){
			$name = explode(',', $name);
			if(count($name) == 1)
				$onlyOneName = true;
		}
		foreach($name as $e){
			//如果存在方法：get_$e，则调用该方法
			$method = "get_$e";
			if(method_exists($this, $method)){
				$ret[$e] = $this->$method();
			}
			else
				$ret[$e] = isset($this->params[$e]) ? $this->params[$e] : (isset($this->options[$e]) ? $this->options[$e] : null);
		}
		if($onlyOneName){
			return $ret[$name[0]];
		}
		return $ret;
	}
	
	public function set($vp){
		foreach($vp as $name=>$v){
			$method = "set_$name";
			if(method_exists($this, $method))
				$this->$method($v);
			else
				$this->params[$name] = $v;
		}
	}
	
	function     say($msg=""){ echo $msg."\n"; }  
	function     log($msg=""){ if($this->debug){ echo $msg."\n"; } }  
}
?>