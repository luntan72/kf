<?php

class kf_tool_file{
    public function createDirectory($directory){
        if (!file_exists($directory)){
            if (strtoupper(substr(PHP_OS,0,3))=='WIN'){
            	mkdir($directory, 0700, true);
        	}
        	else
        		system('/bin/mkdir -p '.escapeshellarg($directory) . ' -m 777');
        }
    }
	
	public function uniformFileName($fileName){
		return str_replace(' ', '_', $fileName);
	}
    
    public function formatFileName($fileName, $suffix = ''){
        $ret = $this->uniformFileName($fileName); // replace with "_"
		$pathInfo = pathInfo($ret);
		$dir = $pathInfo['dirname'];
		$suffix = ".".$pathInfo['extension'];
		$baseName = $pathInfo['filename'];
		// if (empty($suffix) && preg_match('/(.*?)(\.+.*)$/', $ret, $matches) !== FALSE){
			// if (!empty($matches[2]))
				// $suffix = $matches[2];
		// }
		// $baseName = basename($ret, $suffix);
		$this->createDirectory($dir);
		$i = 1;
		while(file_exists($ret)){
			$ret = $dir.'/'.$baseName.'_'.$i.$suffix;
			$i ++;
		}
		return $ret;
    }

    public function moveFile($fileName, $dest){
    	$dest = $this->uniformFileName($dest);
        $path_parts = pathinfo($dest);
        $this->createDirectory($path_parts['dirname']);  
        if (file_exists($dest)){
            $base = $path_parts['dirname'].'/'.$path_parts['filename'];
            $i = 1;
            do{
                $dest = $base.'_'.$i ++;
            }while(file_exists($dest.'.'.$path_parts['extension']));
            $dest .= '.'.$path_parts['extension'];
        }
        move_uploaded_file($fileName, $dest);
//			copy($fileName, $dest);
        return $dest;
    }
	
    public function moveDir($source, $target){
		$this->createDirectory($target);
		$files = scandir($source);
		unset($files[0]); 	// .
		unset($files[1]);	// ..
		foreach($files as $file){
			if(is_dir($file))
				continue;
			rename($source.'/'.$file, $target.'/'.$file);
		}
	}
	
    public function copyDir($source, $target){
		$this->createDirectory($target);
		$files = scandir($source);
		unset($files[0]); 	// .
		unset($files[1]);	// ..
		foreach($files as $file){
			if(is_dir($file))
				continue;
			copy($source.'/'.$file, $target.'/'.$file);
		}
	}
	
    public function copyFile($source, $target){
		$this->createDirectory($target);
		$pathInfo = pathinfo($source);
		$file = $pathInfo['filename'];
		copy($source.'/'.$file, $target.'/'.$file);
	}
	
	function saveFile($str, $fileName = '', $dir = ''){
		if (empty($dir))
			$dir = EXPORT_ROOT;
		if (empty($fileName))
			$fileName = "tmp.txt";
		$suffix = '.txt';
		if (preg_match('/^.*(\..*?)$/', $fileName, $matches))
			$suffix = $matches[1];
//print_r($suffix);			
		$fileName = $this->formatFileName($dir.'/'.$fileName, $suffix);
		$fp = fopen($fileName, 'wb');
		fwrite($fp, $str);
		fclose($fp);
		return $fileName;
	}
}
?>
