<?php
require_once('action_jqgrid.php');

class xt_zzvw_cycle_detail_action_get_jira_labels extends action_jqgrid{
	protected function _execute(){
		$ret = array();
		$params = $this->params;
		$username = $params['submit_username']; 
		$password = $params['submit_password'];  
		$restcurl = 'http://sw-jira.freescale.net/rest/api/1.0';
		$url =  $restcurl.'/labels/suggest?query='.$params['term'];
		$headers = array( 
			"Accept: application/json",  
			"Content-Type: application/json"  
		); 
		$curl_params = array(
			CURLOPT_HEADER => false, //Ϊʲô��httpheader����headerȷʵfalse��
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPGET => true,
			CURLOPT_USERPWD => "$username:$password",
			CURLOPT_VERBOSE => true, //�����Ϊtrue, ���ӡ���й�����Ϣ
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
		) ; 
		$ch = curl_init();  
		curl_setopt_array($ch, $curl_params);  
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch); 
		$ch_error = curl_error($ch); 
		curl_close($ch);
		if ($ch_error) { 
			print_r("~~~~~~~~~~~~~~~~~~~~~~"); 
			echo "cURL Error: $ch_error"; 
		} else { 
			$result = json_decode($result, true);
// print_r($result);	
			foreach($result as $key=>$value){
				if('token' == $key)
					continue;
				foreach($value as $val){
					$ret[] = $val['label'];
				}
			}
			if(!empty($ret))
				return json_encode($ret);
		}
	}
}
?>