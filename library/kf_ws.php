<?php
require_once('kf_object.php');
//demo.php
Class kf_ws extends kf_object {
    var $master;  // 连接 server 的 client
    var $sockets = array(); // 不同状态的 socket 管理
    var $handshake = false; // 判断是否握手
    var $users = array();

	protected function init($params){
		parent::init($params);
		$address = $this->params['address'];
		$port = $this->params['port'];
        // 建立一个 socket 套接字
        $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)  
            or die("socket_create() failed");
        socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1) 
            or die("socket_option() failed");
        socket_bind($this->master, $address, $port)                   
            or die("socket_bind() failed");
        socket_listen($this->master, 128)                              
            or die("socket_listen() failed");

		socket_set_nonblock($this->master);
		$this->sockets[] = $this->master;

        // debug
        echo("Master socket  : ".$this->master."\n");

        while(true) {
            //自动选择来消息的 socket 如果是握手 自动选择主机
			$read = $this->sockets;
            $write = array();
            $except = array();
		// print_r("before select\n");	
            if(socket_select($read, $write, $except, 0) === 0)
				continue;

            foreach ($read as $socket) {
                //连接主机的 client
print_r($socket);				
                if ($socket == $this->master){
		print_r("before accept\n");			
                    $client = socket_accept($this->master);
                    if ($client < 0) {
                        // debug
                        echo "socket_accept() failed";
                        continue;
                    } 
					else {
                        $this->connect($client);
                        echo "connect client\n";
                    }
                } 
				else {
                    $bytes = @socket_recv($socket,$buffer,2048,0);
// print_r($buffer);					
                    if($bytes == 0){
						$this->disconnect($socket);
						break;
					}
					$user_index = $this->getUserIndexBySocket($socket);//$this->users[$socket];
                    if (!$this->users[$user_index]['handshaked']) {
                        // 如果没有握手，先握手回应
                        $this->doHandShake($user_index, $buffer);
                        echo "shakeHands\n";
                    } 
					else {
                        // 如果已经握手，直接接受数据，并处理
                        $buffer = $this->decode($buffer);
						// $this->send($socket, $buffer);
                        //$this->process($socket, $buffer);
                        echo "send file:$buffer\n";
                    }
                }
            }
			sleep(1);
        }
    }
	
	function connect($socket){ 
		$this->users[] = array('handshaked'=>false, 'socket'=>$socket);  
		array_push($this->sockets, $socket);  
		$this->log($socket." CONNECTED!");  
		$this->log(date("d/n/Y ")."at ".date("H:i:s T"));  
	}  
	
	function disconnect($socket){
		foreach($this->users as $i=>$user){
			if($user['socket'] == $socket){
				unset($this->users[$i]);
				break;
			}
		}
		socket_close($socket);  
		$this->log($socket." DISCONNECTED!");  
		$index = array_search($socket,$this->sockets);  
		if($index>=0){ 
			array_splice($this->sockets,$index,1); 
		}  
	}  

	function getUserIndexBySocket($socket){
		$found = null;
		foreach($this->users as $i=>$user){
			if($user['socket'] == $socket){
				$found = $i;
				break;
			}
		}
		return $i;
	}
	
	  // 解析数据帧
	function decode($buffer)  {
// print_r($buffer);		
		$len = $masks = $data = $decoded = null;
		$len = ord($buffer[1]) & 127;

		if ($len === 126)  {
			$masks = substr($buffer, 4, 4);
			$data = substr($buffer, 8);
		} else if ($len === 127)  {
			$masks = substr($buffer, 10, 4);
			$data = substr($buffer, 14);
		} else  {
			$masks = substr($buffer, 2, 4);
			$data = substr($buffer, 6);
		}
		for ($index = 0; $index < strlen($data); $index++) {
			$decoded .= $data[$index] ^ $masks[$index % 4];
		}
// print_r($decoded);		
		return $decoded;
	}
	
	// 返回帧信息处理
	function frame($s) {
		$a = str_split($s, 125);
		if (count($a) == 1) {
			return "\x81" . chr(strlen($a[0])) . $a[0];
		}
		$ns = "";
		foreach ($a as $o) {
			$ns .= "\x81" . chr(strlen($o)) . $o;
		}
		return $ns;
	}

	// 返回数据
	function send($client, $msg){
		$msg = $this->frame($msg);
		socket_write($client, $msg, strlen($msg));
	}	
	
	function getKey($req) {
		$key = null;
		if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $req, $match)) {
			$key = $match[1];
		}
		return $key;
	}
	
	function encry($req){
		$key = $this->getKey($req);
		$mask = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";

		return base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
	}
	
	function dohandshake($user_index, $req){
		// 获取加密key
		$user = $this->users[$user_index];
		$socket = $user['socket'];
		$acceptKey = $this->encry($req);
		$upgrade = "HTTP/1.1 101 Switching Protocols\r\n" .
				   "Upgrade: websocket\r\n" .
				   "Connection: Upgrade\r\n" .
				   "Sec-WebSocket-Accept: " . $acceptKey . "\r\n" .
				   "\r\n";

		// 写入socket
		socket_write($socket,$upgrade.chr(0), strlen($upgrade.chr(0)));
		// 标记握手已经成功，下次接受数据采用数据帧格式
		$this->users[$user_index]['handshaked'] = true;
	}	
}

$ws = new kf_ws(array('address'=>'localhost', 'port'=>4000));
?>