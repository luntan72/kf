<?php
require_once('kf_form.php');
require_once('kf_db.php');
require_once('kf_tool_file.php');
function _P($s){
	print_r("\n<br>");
	print_r($s);
	print_r("<br>\n");
}

class kf_tool extends kf_object{
	protected $o_file;
	protected $o_db;
	protected $keys = array(); //存放生成Sql时的关键字搜索，主用用于界面高亮
	public function init($params){
		parent::init($params);
		$this->o_file = new kf_tool_file();
		$this->o_db = new kf_db();
	}
	
	public function get_db_handle(){
		return $this->o_db;
	}
	
	public function p_t($tip){
		static $lastMicroSec = 0;
		$currentMicroSec = microtime(true);
		$str = ">>>>>".$tip.":".$currentMicroSec;
		if($lastMicroSec == 0)
			$lastMicroSec = $currentMicroSec;
		else{
			$str .= ", gap from the last is ".($currentMicroSec - $lastMicroSec);
			$lastMicroSec = $currentMicroSec;
		}
		$str .= "<<<<<<<\n<BR>";
		print_r($str);
	}
	
	public function vsprintf($str, $v){
// print_r($str);
// print_r($v);
		//%(module_id)s has %(question_type_id)s question
		$patterns = array();
		$replace = array();
		preg_match_all('/(\%\((.*?)\).)/', $str, $matches, PREG_SET_ORDER);
		if(count($matches) > 0){
			foreach($matches as $val){
				$val[0] = preg_replace(array('/\(/', '/\)/'), array('\(', '\)'), $val[0]);
				$patterns[] = '/'.$val[0].'/';
				$replace[] = isset($v[$val[2]]) ? $v[$val[2]] : '';
			}
		}
		$str = preg_replace($patterns, $replace, $str);
		return $str;
	}
	
	public function insertLink($str){
		$pattern = array('/(https?:\/\/.*?)([ ,.]?\s)/', '/mailto:(.*?)([ ,.]?\s)/');
		$replace = array('<a href="${1}">${1}</a>${2}', 'mailto:<a href="mailto:${1}">${1}</a>${2}');
		return preg_replace($pattern, $replace, $str);
	}
	
	public function getWeekStartEndDay($gdate = "", $first = 0){
		if(!$gdate) $gdate = date("Y-m-d");
		$w = date("w", strtotime($gdate));
		$dn = $w ? $w - $first : 6;
		$st = date("Y-m-d", strtotime("$gdate -".$dn." days"));
		$en = date("Y-m-d", strtotime("$st +6 days"));
		return array($st, $en);
	}
	
    public function createDirectory($directory){
		$this->o_file->createDirectory($directory);
    }
	
	public function uniformFileName($fileName){
		return $this->o_file->uniformFileName($fileName);
	}
    
    public function formatFileName($fileName, $suffix = ''){
		return $this->o_file->formatFileName($fileName, $suffix);
    }

    public function moveFile($fileName, $dest){
		return $this->o_file->moveFile($fileName, $dest);
    }
	
    public function moveDir($source, $target){
		$this->o_file->moveDir($source, $target);
	}
	
    public function copyDir($source, $target){
		$this->o_file->copyDir($source, $target);
	}
	
    public function copyFile($source, $target){
		$this->o_file->copyFile($source, $target);
	}
	
	function saveFile($str, $fileName = '', $dir = ''){
		return $this->o_file->saveFile($str, $fileName, $dir);
	}
	
	function hilitWords($str, $words){
		$patterns = array();
		$replaces = array();
		foreach($words as $word){
	//        $word = str_replace("\"", "\\\"", $word);
			$patterns[] = "/(".$word.")/i";
			$replaces[] = '<span style="color:#FF0000;background-color:#CCCCCC">${1}</span>';
		}
		$str = preg_replace($patterns, $replaces, $str);
		return $str;
	}

	function replaceParam($str, $ds = null){
		$pattern = array();
		$replacements = array();
		if (preg_match_all("/<%(\w*?)%>/", $str, $matches) !== FALSE){
			foreach($matches[1] as $param){
				$pattern[] = "/<%".$param."%>/";
				$replacements[] = isset($ds[$param]) ? $ds[$param] : '';
			}
		}
		$ret = preg_replace($pattern, $replacements, $str);
		return $ret;
	}
	
	public function array_dup($a){
		$dup = array();
		$uni = array_unique($a);
		if (count($a) > count($uni)){
			$uni_key = array_keys($uni);
			$key = array_keys($a);
			$diff_key = array_diff($key, $uni_key);
//print_r($a);
//print_r($uni);		
//print_r($diff_key);
			foreach($diff_key as $key)
				$dup[] = $a[$key];
		}
		return $dup;
	}

    public function array_extends($output, $default){
		if(is_array($default)){
			foreach($default as $key=>$v){
				if (isset($output[$key])){
					if (is_array($v)){
						$output[$key] = $this->array_extends($output[$key], $v);
					}            
					else
						$output[$key] = $v;
				}
				else{
					$output[$key] = $v;
				}
			}
		}
        return $output;
    } 
	
	public function extractItems($keys, $vs){
		$ret = array();
		foreach($keys as $k=>$v){
			$hasDefaultValue = !is_int($k);
			if (is_int($k))$k = $v;
			if(isset($vs[$k]))
				$ret[$k] = $vs[$k];
			elseif ($hasDefaultValue){
				$ret[$k] = $v;
			}
		}
		return $ret;
	}
	
	public function getYearMonthList($off, $length, $blank_item = true){ //$off：和当前日期的变差月数，负数为以前月，正数为未来月
		$list = array(0=>'');
		$today = getdate();
		$base = $today['mon'] + $off - $length + 1;
		for($i = 0; $i < $length; $i ++){
			$ym = date('Y-m', mktime(0, 0, 0, $base + $i, 1, $today['year']));
			$list[$ym] = $ym;
		}
		return $list;
	}
	
	public function getWeekList($preWeek = 8, $postWeek = 10){
		$currentYear = (int)date('y');
		$currentWorkWeek = (int)date('W');
		$refData = array();
		for($i = $currentWorkWeek - $preWeek; $i < $currentWorkWeek + $postWeek; $i ++){
			$j = $i;
			$year = $currentYear;
			if ($i > 52){
				$j = $i - 52;
				$year = $currentYear + 1;
			}
			else if ($i <= 0){
				$j = $i + 52;
				$year = $currentYear - 1;
			}
			if ($j < 10)
				$week = $year.'WK0'.$j;
			else
				$week = $year.'WK'.$j;
			$refData[$week] = $week;
			// if ($j < 10)
				// $refData[$i] = $year.'WK0'.$j;
			// else
				// $refData[$i] = $year.'WK'.$j;
		}
		return $refData;
	}
	
	public function array2Str($arr, $sep = ':'){
		$str = array();
		$displayField = '';
		foreach($arr as $id=>$name){
			if(is_array($name)){
				if(empty($displayField)){
					$displayField = $this->getDisplayField($name);
				}
				$str[$name['id']] = $name['id'].$sep.$name[$displayField];
			}
			else
				$str[$id] = $id.$sep.$name;
		}
		return implode(';', $str);
	}
	
	public function str2Array($str){
		if(is_array($str))
			return $str;
		$ret = array();
		$a = explode(';', $str);
		foreach($a as $v){
			$b = explode(':', $v);
			$ret[$b[0]] = $b[1];
		}
// print_r($str);
// print_r($ret);
		return $ret;
	}
    
    public function getDisplayField($desc){
        $ret = 'id';
        $candidates = array('code', 'nickname', 'name', 'username', 'subject', 'title', 'content', 'ver');
        foreach($candidates as $candidate){
            if (isset($desc[$candidate])){
                $ret = $candidate;
                break;
            }
        }
        return $ret;
    }
	
	public function genSelect($data, $options = array('blank'=>false, 'blank_item'=>false)){
		$list = array();
		$list[] = "<select>";
		if ($options['blank'])
			$list[] = "<option id='option_0' value='0'></option>";
		if ($options['blank_item'])
			$list[] = "<option id='option_blank_item' value='-1'>===Blank===</option>";
		foreach($data as $k=>$v){
			$list[] = "<option id='option_{$k}' value='$k'>$v</option>";
		}
		$list[] = "</select>";
		return implode(',', $list);
	}
	
	public function RC2LN($row, $col){ //(col, row)=>'A'n
    	$ret = array();
		$loop = (int)($col / 26);
		$rest = $col % 26;
		if ($loop > 0){
			$ret[] = chr(ord('A') + $loop - 1);
		}
		$ret[] = chr(ord('A') + $rest);
//print_r("col=$col, loop = $loop, ret =");
//print_r($ret);
		return implode('', $ret).$row;
	}
	
	public function genPattern($id){
		return "^$id$|^$id,|,$id,|,$id$";
	}
	
    public function getExportDir(){
        return EXPORT_ROOT;        
    }
    
    public function getExportFileName($fileName, $ext = '', $format = true){
        $exportDir = $this->getExportDir();
        if ($format)
            $file = $this->formatFileName($exportDir .'/'.$fileName, $ext);
        else
            $file = $exportDir .'/'.$fileName;
//print_r($file);
        return $file;
    }

    // send an appointment
    /*
    params: 
        0. from
        1. subject
        2. description
        3. email
        4. start_time
        5. end_time
        6. location
    */
    function sendAppointment($params){
        $dtStart = date("Ymd\THis", strtotime($params['start_time']));
        $dtEnd = date("Ymd\THis", strtotime($params['end_time']));
        //--------------------
        //create text file
        $ourFileName = $this->formatFileName("icsFile/calendar.txt", "txt");
        $fh = fopen($ourFileName, 'w') or die("can't open file2");
        
        $stringData = "
            BEGIN:VCALENDAR\n 
            PRODID:-//Microsoft Corporation//Outlook 11.0 MIMEDIR//EN\n 
            VERSION:2.0\n 
            METHOD:REQUEST\n 
            BEGIN:VEVENT\n 
            ORGANIZER:MAILTO:organizer@domain.com\n 
            DTSTAMP:".date('Ymd').'T'.date('His')."\n
            DTSTART:$dtStart\n 
            DTEND:$dtEnd\n 
            TRANSP:OPAQUE\n 
            SEQUENCE:0\n 
            UID:".date('Ymd').'T'.date('His')."-".rand()."-domain.com\n 
            SUMMARY:{$params['subject']}\n 
            DESCRIPTION:{$params['description']}\n
            PRIORITY:5\n 
            X-MICROSOFT-CDO-IMPORTANCE:1\n 
            CLASS:PUBLIC\n 
            END:VEVENT\n 
            END:VCALENDAR";
        fwrite($fh, $stringData);
        fclose($fh);
        
        //email temp file
        $fileatt = "icsFile/calendar.txt"; // Path to the file
        $fileatt_type = "application/octet-stream"; // File Type
        $fileatt_name = "ical.ics"; // Filename that will be used for the file as the attachment
        
        $email_from = $params['from'];//"fromPerson@domain.com"; // Who the email is from
        $email_subject = $params['subject']; //"Email test"; // The Subject of the email
        $email_message = $params['description']; //"this is a sample message \n\n next line \n\n next line"; // Message that the email has in it
        
        $email_to = $params['email'];//"toPerson@domain.com"; // Who the email is too
        
        $headers = "From: ".$email_from;
        
        $file = fopen($fileatt,'rb');
        $data = fread($file,filesize($fileatt));
        fclose($file);
        
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
        
        $headers .= "\nMIME-Version: 1.0\n" .
        "Content-Type: multipart/mixed;\n" .
        " boundary=\"{$mime_boundary}\"";
        
        $email_message .= "This is a multi-part message in MIME format.\n\n" .
        "--{$mime_boundary}\n" .
        "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" .
        $email_message . "\n\n";
        
        $data = chunk_split(base64_encode($data));
        
        $email_message .= "--{$mime_boundary}\n" .
        "Content-Type: {$fileatt_type};\n" .
        " name=\"{$fileatt_name}\"\n" .
        //"Content-Disposition: attachment;\n" .
        //" filename=\"{$fileatt_name}\"\n" .
        "Content-Transfer-Encoding: base64\n\n" .
        $data . "\n\n" .
        "--{$mime_boundary}--\n";
        
        $ok = @mail($email_to, $email_subject, $email_message, $headers);
        
        if($ok) {
        
        } else {
            die("Sorry but the email could not be sent. Please go back and try again!");
        } 
         
    }
	
	function model2e($model, $value, $display_status){
		$p = array('required'=>false, 'cols'=>4, 'colspan'=>1, 'init_type'=>'single', 
			'limit'=>'', 'displayField'=>'', 
			//'rows'=>3, 
			// 'addoptions'=>array(), 'editoptions'=>array(), 'queryoptions'=>array(), 'searchoptions'=>array(),
			'name'=>'', 'id'=>'', 'caption'=>'', 'label'=>'', 'editable'=>true, 'type'=>'', 'unique'=>false,
			'post'=>array(), 'class'=>array(), 'placeholder'=>'', 'DATA_TYPE'=>'varchar', 'invalidChar'=>'', 'email'=>0,
			'force_readonly'=>false, 'ignored'=>false, 'from'=>'',
			'temp'=>array(), 'legend'=>'', 'prefix'=>'', 'data_source_db'=>'', 'data_source_table'=>'',
			'cart_db'=>'', 'cart_table'=>'', 'cart_data'=>array(),
			);
// if($model['name'] == 'steps'){
	// print_r($model);
// }
		$e = $this->array_extends($p, $model);
// if($e['name'] == 'steps'){
	// print_r($e);
// }
		if(empty($e['id']) && !empty($e['name']))
			$e['id'] = $e['name'];
		if(empty($e['name']) && !empty($e['id']))
			$e['name'] = $e['id'];
		if(empty($e['label'])){
			if(isset($e['caption']))
				$e['label'] = $e['caption'];
			else
				$e['label'] = g_str($e['name']); //ucfirst($e['name'])
		}
		if(empty($e['required']))
			$e['required'] = isset($model['editrules']['required']) ? $model['editrules']['required'] : false;
		if(empty($e['prefix']) && !empty($e['id']))
			$e['prefix'] = $e['id'];
			
// $l = 0;
		if($display_status == DISPLAY_STATUS_NEW && !empty($model['addoptions'])){
// $l = 1;			
			$e['editoptions'] = $model['addoptions'];
		}
		elseif($display_status == DISPLAY_STATUS_QUERY && !empty($model['searchoptions'])){
// $l = 2;			
			$e['editoptions'] = $model['searchoptions'];
		}
		else{
// $l = 3;			
			$e['editoptions'] = isset($model['editoptions']) ? $model['editoptions'] : 
			(isset($model['addoptions']) ? $model['addoptions'] : 
				(isset($model['formatoptions']) ? $model['formatoptions'] : array())
			);
		}
// if($e['name'] == 'prj_id'){
	// print_r($l);
	// print_r($model);
	// print_r($e);
// }

		if(!empty($e['editoptions']['value']) && is_string($e['editoptions']['value'])){
			$e['editoptions']['value'] = $this->str2Array($e['editoptions']['value']);
		}
		if (empty($e['type'])){
			if($display_status == DISPLAY_STATUS_QUERY && !empty($model['queryoptions']['querytype']))
				$e['type'] = $model['queryoptions']['querytype'];
			else if (!empty($model['edittype']))
				$e['type'] = $model['edittype'];
			else if (!empty($model['stype']))
				$e['type'] = $model['stype'];
			else
				$e['type'] = 'text';
		}
// if($model['name'] == 'summary')		
	// print_r($e);
		switch($e['type']){
			case 'textarea':
				if($display_status == DISPLAY_STATUS_QUERY)
					$e['type'] = 'text';
				break;
			case 'select':
				if($display_status != DISPLAY_STATUS_QUERY){
					$cc = 0;
					if(!empty($e['editoptions']['value']))
						$cc = count($e['editoptions']['value']);
					if(!empty($e['editoptions']['multiple'])){
						if($cc < 10 || empty($e['cart_db']) || empty($e['cart_table']))
							$e['type'] = 'checkbox';
						else
							$e['type'] = 'cart';
					}
					// elseif($cc < 4){
						// $e['type'] = 'radio';
					// }
				}
				break;
			case 'single_multi':
				if($e['init_type'] == 'single'){
					// $e['type'] = 'select';
					$e['editoptions']['multiple'] = false;
					$e['editoptions']['size'] = 1;
					// $e['post'] = array('type'=>'button', 'value'=>'+', 'id'=>'single_to_multi', 'title'=>'Change to multe-selction', 
						// 'event'=>array('onclick'=>'XT.single_or_multi(this)'), 'class'=>array('single-multi'));
				}
				else{
					// $e['type'] = 'cart';
					$e['editoptions']['multiple'] = false;
					$e['editoptions']['size'] = 1;
					// $e['post'] = array('type'=>'button', 'value'=>'-', 'id'=>'multi_to_single', 'title'=>'Change to single selection',
						// 'event'=>array('onclick'=>'XT.single_or_multi(this)'), 'class'=>array('single-multi'));
				}
				if(!isset($e['single_multi']['options']))
					$e['single_multi']['options'] = $e['editoptions'];
				break;
			case 'multi_row_edit':
			case 'embed_table':
				$e['editable'] = true;
				break;
			
		}

		if (!$e['editable'] || !empty($model['readonly']))
			$e['readonly'] = 'readonly';
			
		if (isset($value[$e['name']])){
			$e['value'] = $value[$e['name']];
		}
		elseif(isset($value[$e['id']])){
			$e['value'] = $value[$e['id']];
		}
		elseif(isset($model['value']))
			$e['value'] = $model['value'];
		elseif(isset($e['defval']) && $display_status != DISPLAY_STATUS_QUERY)
			$e['value'] = $e['defval'];

		if(!isset($e['value']))
			$e['value'] = '';
		if(!is_array($e['value']))
			$e['value'] = htmlentities($e['value'], ENT_QUOTES);
			
		$e['original_value'] = $e['value'];
		if ($display_status == DISPLAY_STATUS_QUERY){
			$e['unique'] = false;
			$e['required'] = false;
			if (!empty($e['force_readonly'])){
				$e['editable'] = false;
				$e['readonly'] = true;
			}
			else{
				$e['editable'] = true;
				$e['readonly'] = false;
			}
		}
		if(empty($e['required']))
			unset($e['required']);
		else
			$e['class'][] = 'required';

		if(empty($e['unique']))
			unset($e['unique']);
		else
			$e['class'][] = 'unique_unknown';
			
		// invalidChar
		if(empty($e['invalidChar'])){
			switch($e['DATA_TYPE']){
				case 'int':
					$e['invalidChar'] = '[^\d-]';
					break;
				case 'float':
				case 'double':
					$e['invalidChar'] = '[^0-9\.-]';
					break;
				
			}
		}
		if($e['name'] == 'email')
			$e['email'] = 1;
		elseif($e['name'] == 'progress'){
			if(!isset($e['min']))
				$e['min'] = "min='0'";
			if(!isset($e['max']))
				$e['max'] = "max='100'";
		}
		if(empty($e['placeholder']))
			$e['placeholder'] = g_str("placeholder").$e['label'];
		if(!in_array($e['type'], array('text', 'textarea')))
			unset($e['placeholder']);
		//处理editoptions里的Value，主要是要将id和name转换名称
// print_r($e['editoptions']);		
		if(!empty($e['editoptions']['value'])){
			foreach($e['editoptions']['value'] as $k=>&$v){
				if(is_array($v)){
					$displayField = $this->getDisplayField($v);
					if($displayField != 'id'){
						$v['label'] = $v[$displayField];
						unset($v[$displayField]);
					}
					if(isset($v['id'])){
						$v['value'] = $v['id'];
						unset($v['id']);
					}
				}
			}
		}
		if(!empty($e['post'])){
			if(!is_array($e['post'])){
				$e['post'] = array('type'=>'text', 'value'=>$e['post']);
			}
			if(!isset($e['post']['type']))
				$e['post']['type'] = 'text';
		}
		if($e['DATA_TYPE'] == 'date' || $e['DATA_TYPE'] == 'date_time'){
			// $e['type'] = 'date';
			// if($display_status == DISPLAY_STATUS_EDIT || $display_status == DISPLAY_STATUS_NEW){
				// if(empty($e['post']['value']))
					// $e['post']['value'] = '(yyyy-mm-dd)';
				// if(empty($e['post']['type']))
					// $e['post']['type'] = 'text';
			// }
			$e['date'] = 'date';
		}
// print_r($e);			
		return $e;		
	}
	
	function cf($colModels, $v = null, $column = 1, $display_status = DISPLAY_STATUS_VIEW){
		$form = new kf_form($colModels, $v, $display_status);
		$str = $form->display($column);
		print_r($str);
	}
	
	function generateEmbed_table($comp){//}$temp, $prefix, $legend, $values = array(), $edit = true){ //用模板生成多行编辑界面
		if(empty($comp['temp']))
			return 'No Detail Yet';
// print_r($comp);
		$temp = $comp['temp'];
		$prefix = $comp['prefix'];
		$legend = $comp['legend'];
		$values = $comp['value'];
		$editable = $comp['editable'];
		$db = $comp['data_source_db'];
		$table=$comp['data_source_table'];
		
		$str = array();
		foreach($temp as $k=>$e){
			// $temp[$k]['ignored'] = 'ignored';
			$name = $temp[$k]['name'];
			$temp[$k]['name'] = $prefix.'['.$name.']';
			$temp[$k]['id'] = $name;
// print_r($temp[$k]);
		}
// print_r($values);
		$str[] = "<fieldset><legend>$legend</legend>";
		$str[] = "<div embed_table='embed_table' id='{$prefix}' >";//" onmouseout='$onMouseOut' $onMouseOver>";
		$str[] = $this->_cf($temp, $editable, $values, 1, false, array('db'=>$db, 'table'=>$table), true);
		$str[] = "</div>";
		$str[] = "</fieldset>";
		return implode('', $str);
	}
	
	function showInfo($fields, $v, $cols = 2){
		$es = array();
		foreach($fields as $k=>$l){
			if (is_int($k))
				$k = $l;
			$l = ucwords($l);
			$es[] = array('type'=>'text', 'label'=>$l, 'value'=>$v[$k], 'disabled'=>'disabled');
		}
		$this->createElements($es, true, $cols);
	}
	
	function showTable($fields, $vs){
//print_r($fields);		
		print_r("<table class='alt_table_border' style='width:100%'>");
		print_r("<tr class='ui-jqgrid-htable'>");
		$fs = array();
		foreach($fields as $k=>&$l){
			if (is_int($k))
				$k = $l;
			if (!is_array($l))
				$l = array('label'=>ucwords($l));
			if (empty($l['label']))$l['label'] = ucwords($k);
			if (empty($l['field']))
				$l['field'] = $k;
			if (empty($l['type']))
				$l['type'] = 'text';
			print_r("<th>{$l['label']}</th>");
			$fs[$k] = $l;
		}
		print_r("</tr>");
//print_r($fields);		
		$i = 0;
		foreach($vs as $v){
			$alt_row = '';
			if ($i ++ % 2)
				$alt_row = " ui-priority-secondary td_grey";
			print_r("<tr class='ui-widget-content jqgrow ui-row-ltr $alt_row'>");
			foreach($fs as $l){
				if (empty($v[$l['field']]))
					print_r("<td></td>");
				else if ($l['type'] == 'checkbox'){
					print_r("<td><input type='checkbox' class='cbox' value='{$v[$l['field']]}' name='{$l['field']}' ></td>");
				}
				else if ($l['type'] == 'link'){
					$href = $this->replaceParam($l['href'], $v);
					print_r("<td><a href='$href'>{$v[$l['field']]}</a></td>");
				}
				else
					print_r("<td name='{$l['field']}'>{$v[$l['field']]}</td>");
			}
			print_r("</tr>");
		}
		print_r("</table>");
	}
	
	//style:default(row for property)/json( merge all properties in one line)
	function showTree($tree, $levels, $style = 'default'){//levels存放节点间父子关系，如levels = array('peripheral'=>array('sub'=>'register', 'label'=>'name'))
		//表头
		$lc = count($levels) + 1;
		$header = $levels['root']['header'];
// print_r($header);	
		$str = "<table class='alt_table_border' style='width:100%'>";
		$str .= $this->treeHeader($lc, $header, $style);
		$node = $tree['root'][0];
		$diff = false;
		$str .= $this->genLevel(0, $node, $levels, $style, $header, $lc, 'root', 0, $tree, $diff, false);
		$str .= "</table>";
		print_r($str);
	}
	
	function genLevelToArray($id, $node, $levels, $style, $header, $lc, $currentLevel, $deep, $tree, &$diff = false){
		//先处理子节点
		$subNodes = array();
		$sub_levels = array();
		if(!empty($levels[$currentLevel]['sub'])){
			$sub_levels = explode(',', $levels[$currentLevel]['sub']);
			foreach($sub_levels as $sub_level){
				if(isset($tree[$sub_level][$id]))
					$subNodes[$sub_level] = $tree[$sub_level][$id];
			}
		}
		$sub_lines = array();
		$sub_diff = false;
		$i = 0;
		if(!empty($subNodes)){
			foreach($subNodes as $type=>$subNode){
				$sub_lines[$i] = $this->staffTDToArray($type."(s)", $lc, $deep, true, $toArray);
				$rem_line = $i ++;
				foreach($subNode as $sub_id=>$n){
					$tmp = $this->genLevelToArray($sub_id, $n, $levels, $style, $header, $lc, $type, $deep + 1, $tree, $sub_diff);
					foreach($tmp as $line){
						$sub_lines[$i ++] = $line;
					}
				}
				$sub_lines[$rem_line]['is_same'] = !$sub_diff;
			}
		}
		
		//再处理自身属性
		$node_name = $currentLevel;
		$getName = false;
		$ignore = isset($levels[$currentLevel]['ignore']) ? $levels[$currentLevel]['ignore'] : array();
		$lines = array();
		$i = 0;
		foreach($node as $p=>$v){
			if(in_array($p, $ignore))
				continue;
			$orig = null;
			$p_diff = false;
			$line = '';
			foreach($header as $k=>$name){
				$value = isset($v[$k]) ? $v[$k] : '';
				if(is_null($orig))
					$orig = $value;
				else{
					if($orig != $value)
						$p_diff = true;
				}
				if($p == 'name'){
					if(!$getName && !empty($value)){
						$node_name .= '#'.$value;
						$getName = true;
					}
				}
				$line .= $value;
			}
			$lines[$i] = $this->staffTDToArray($currentLevel, $lc, $deep, false);
			$lines[$i]['is_same'] = !$p_diff;
			// $lines[$i][]
			// ."<td>$p</td>$line</tr>";
			
			$i ++;
		}
		$class = 'ui-widget-content jqgrow ui-row-ltr';
		if($p_diff || $sub_diff)
			$diff = true;
// if($diff)		
// print_r(">>node_name = $node_name, diff = $diff<<");		
		if($p_diff || $sub_diff)
			$class .= ' hilight';
		else
			$class .= ' thesame';
		$str = '';
		if($currentLevel != 'root')
			$str .= "<tr class='$class'>".$this->staffTDToArray($node_name, $lc, $deep, true)."</tr>";
		// array_($str, $lines, $sub_lines);
		
		$str .= implode('', $lines).
			implode('', $sub_lines); 
		return $str;
	}
	
	function genLevel($id, $node, $levels, $style, $header, $lc, $currentLevel, $deep, $tree, &$diff = false, $toArray = false){
		//先处理子节点
		$subNodes = array();
		$sub_levels = array();
		if(!empty($levels[$currentLevel]['sub'])){
			$sub_levels = explode(',', $levels[$currentLevel]['sub']);
			foreach($sub_levels as $sub_level){
				if(isset($tree[$sub_level][$id]))
					$subNodes[$sub_level] = $tree[$sub_level][$id];
			}
		}
		$sub_lines = array();
		$sub_diff = false;
		$i = 0;
		if(!empty($subNodes)){
			foreach($subNodes as $type=>$subNode){
				$sub_lines[$i] = "<tr>".$this->staffTD($type."(s)", $lc, $deep, true, $toArray)."</tr>";
				$rem_line = $i ++;
				//应该将subnode放到一个Div里，便于收缩展开管理
				// $sub_lines[$i ++ ] = "<tr><td><table>";
				foreach($subNode as $sub_id=>$n){
					$sub_lines[$i ++] = $this->genLevel($sub_id, $n, $levels, $style, $header, $lc, $type, $deep + 1, $tree, $sub_diff, $toArray);
				}
				$class = 'ui-widget-content jqgrow ui-row-ltr';
				if ($sub_diff)
					$class .= ' hilight';
				else
					$class .= ' thesame';
				$sub_lines[$rem_line] =  "<tr class='$class'>".$this->staffTD($type."(s)", $lc, $deep, true, $toArray)."</tr>";
				// $sub_lines[$i ++ ] = "</table></td></tr>";
			}
		}
		
		//再处理自身属性
		$node_name = $currentLevel;
		$getName = false;
		$ignore = isset($levels[$currentLevel]['ignore']) ? $levels[$currentLevel]['ignore'] : array();
		$lines = array();
		foreach($node as $p=>$v){
			if(in_array($p, $ignore))
				continue;
			$orig = null;
			$p_diff = false;
			$line = '';
			foreach($header as $k=>$name){
				$value = isset($v[$k]) ? $v[$k] : '';
				if(is_null($orig))
					$orig = $value;
				else{
					if($orig != $value)
						$p_diff = true;
				}
				if($p == 'name'){
					if(!$getName && !empty($value)){
						$node_name .= '#'.$value;
						$getName = true;
					}
				}
				$line .= "<td>$value</td>";
			}
			$class = 'ui-widget-content jqgrow ui-row-ltr';
			if ($p_diff)
				$class .= ' hilight';
			else
				$class .= ' thesame';
			$lines[] = "<tr class='$class'>".$this->staffTD($currentLevel, $lc, $deep, false, $toArray)."<td>$p</td>$line</tr>";
		}
		$class = 'ui-widget-content jqgrow ui-row-ltr';
		if($p_diff || $sub_diff)
			$diff = true;
// if($diff)		
// print_r(">>node_name = $node_name, diff = $diff<<");		
		if($p_diff || $sub_diff)
			$class .= ' hilight';
		else
			$class .= ' thesame';
		$str = '';
		if($currentLevel != 'root')
			$str .= "<tr class='$class'>".$this->staffTD($node_name, $lc, $deep, true, $toArray)."</tr>";
		// array_($str, $lines, $sub_lines);
		
		$str .= implode('', $lines).
			implode('', $sub_lines); 
		return $str;
	}
	
	function treeHeader($lc, $header, $style){
		$class = 'ui-widget-content jqgrow ui-row-ltr';
		$colspan = $lc + 2;
		$str = "<tr class='ui-jqgrid-htable'>".
			"<th colspan='$colspan'><input type='checkbox' name='op' id='hide_same' value='hide' class='cbox'><label for='hide_same'>Hide the same</label></th>";
		foreach($header as $id=>$name){
			$str .= "<th class='$class'>$name</th>";
		}
		$str .= "</tr>";
		return $str;
	}
	
	function staffTD($node_name, $lc, $deep, $summary = false, $toArray = false){
		$class = 'ui-widget-content jqgrow ui-row-ltr';
	
		$str = '';
			
		for($i = 0; $i < $deep; $i ++){
			$str .= "<td></td>";
		}
		if($summary){
			$str .= "<td>-</td><td>$node_name</td>";
		}
		else{
			$str .= "<td /><td />";
		}
		for($i = $deep; $i < $lc - 1; $i ++)
			$str .= "<td />";
		return $str;
	}
	
	function showDiff($fields, $vs, $field_options = array()){
//print_r($fields);		
		print_r("<table class='alt_table_border' style='width:100%'>");
		print_r("<tr class='ui-jqgrid-htable'>");
		print_r("<th><input type='checkbox' name='op' id='hide_same' value='hide' class='cbox'><label for='hide_same'>Hide the same</label></th>");
		foreach($vs as $k=>$v){
			print_r("<th>$k</th>");
		}
		print_r("</tr>");

		$i = 0;
		foreach($fields as $field=>$label){
			if(is_int($field)) {
				$field = $label;
			}
			if(!empty($field_options) && empty($field_options[$field]))
				continue;
				
			$option = isset($field_options[$field]) ? $field_options[$field] : array();
			
			if(isset($option['label']))
				$label = $option['label'];
			
			$lastV = null;
			$start = false;
			$same = true;
			$str = '';
			foreach($vs as $k=>$v){
				if (!isset($v[$field]))
					$v[$field] = '';
				else{
					if(is_array($v[$field])){
						$str .= "<table>";
						foreach($v as $v_k=>$v_v){
							if (!$start){
								$lastV = array($v_k=>$v_v);
							}
							else{
								if (!isset($lastV[$v_k]) || $lastV[$v_k] != $v_v)
									$same = false;
								$lastV[$v_k] = $v_v;
							}
							$class = 'ui-widget-content jqgrow ui-row-ltr';
							if (!$same)
								$class .= ' hilight';
							else
								$class .= ' thesame';
							if ($i ++ % 2)
								$class .= ' ui-priority-secondary td_grey';
							$str .= "<tr><td>$v_k</td><td class='$class'>$v_v</td></tr>";
						}
						$start = true;
						$str .= "</table>";
					}
					else{
					// check if the value the same
						if (!$start){
							$lastV = $v[$field];
							$start = true;
						}
						else{
							if ($lastV != $v[$field])
								$same = false;
							$lastV = $v[$field];
						}
						//可能需要转换
						$value = $v[$field];
						if(!empty($option))
							$value = $this->translate($v[$field], $option);
						
						$str .= "<td name='{$k}'>".nl2br($value)."</td>";
					}
				}
			}
			$class = 'ui-widget-content jqgrow ui-row-ltr';
			if (!$same)
				$class .= ' hilight';
			else
				$class .= ' thesame';
			if ($i ++ % 2)
				$class .= ' ui-priority-secondary td_grey';
			print_r("<tr class='$class'>");
			print_r("<td name='{$field}'>$label</td>");
			print_r($str);
			print_r("</tr>");
		}
		print_r("</table>");
	}
	
	function translate($v, $option){
// print_r($option);	
		$value = $v;
		if(in_array($option['edittype'], array('select', 'ids'))){
			$value = array();
			if(is_string($v)){
				$v = explode(',', $v);
			}
			elseif(is_int($v))
				$v = array($v);
			foreach($v as $item){
				if(empty($item))
					continue;
				if(!empty($option['editoptions']['value'][$item])){
					$e = $option['editoptions']['value'][$item];
					if(is_array($e)){
						$name_field = $this->getDisplayField($e);
						$value[] = $e[$name_field];
					}
					else
						$value[] = $e;
				}
				else{
					$value[] = $item;
				}
			}
// print_r($value);			
			$value = implode(',', $value);
		}
		return $value;
	}
	
	function getDB_Table($table, $db){
		$a = explode('.', $table);
		if(count($a) == 2){
			$table = $a[1];
			$db = $a[0];
		}
		return array($table, $db);
	}
	
	public function setDb($db){
		$this->o_db->setDb($db);
	}
	
	public function getDb($db, &$real_db = ''){
		$this->o_db->setDb($db, $real_db);
		// $real_db = $this->get('real_db_name');
		return $this->o_db;
	}
	
	public function query($sql, $params = array(), $db_name = ''){
		return $this->o_db->query($sql, $params, $db_name);
	}
	
	public function freeRes(&$res){
		return $this->o_db->freeRes($res);
	}
	
	function update($table_name, $row, $conditions = '', $db_name = ''){
		return $this->o_db->update($table_name, $row, $conditions, $db_name);
	}
	
	function insert($table_name, $row, $db_name = ''){
		return $this->o_db->insert($table_name, $row, $db_name);
	}
	
	function insertRows($table_name, $rows, $db_name = ''){
		return $this->o_db->insertRows($table_name, $rows, $db_name);
	}
	
	function delete($table, $conditions, $db = ''){
		return $this->o_db->delete($table, $conditions, $db);
	}
	
	public function beginTransaction(){
		return $this->o_db->beginTransaction();
	}
	
	public function commit(){
		return $this->o_db->commit();
	}
	
	public function rollback(){
		return $this->o_db->rollback();
	}
	
	public function getFieldValues($field, $table, $condition){
		$ret = array();
		$res = $this->query("SELECT $field FROM $table Where $condition");
		while($row = $res->fetch()){
			if(!empty($row[$field]))
				$ret[] = $row[$field];
		}
		return $ret;
	}
	
	public function getSqlKeys(){
// print_r($this->keys);	
		return $this->keys;
	}
	
	public function describe($table, $db = ''){
		return $this->o_db->describe($table, $db);
	}
	
	public function getAllTables($db = '', $emptyFirst = false){
		return $this->o_db->getAllTables($db, $emptyFirst);
	}
	
    public function tableExist($table, $db = ''){
		return $this->o_db->tableExist($table, $db);
    }
    
	public function fieldExist($table, $field, $db = ''){
		return $this->o_db->fieldExist($table, $field, $db);
	}
	
	public function getTableFields($table, $db = ''){
		return $this->o_db->getTableFields($table, $db);
	}
	
	public function getElementId($table, $valuePair, $keyFields = array(), &$is_new = true, $db = ''){
		return $this->o_db->getElementId($table, $valuePair, $keyFields, $is_new, $db);
	}

	function save($row, $table_name, $db_name = '', &$is_new = true){
		return $this->o_db->save($row, $table_name, $db_name, $is_new);
	}
	
	public function extractData($vs, $table_name = '', $db_name = ''){
		$cols = $this->describe($table_name, $db_name);
//print_r($cols);
		$ret = array();
		foreach($vs as $field=>$v){
			if (isset($cols[$field]))
				$ret[$field] = $v;
		}
		return $ret;
	}

	function rowExist($row, $table_name, $db_name = '', &$data = array()){
		$dbAdapter = $this->getDb($db_name);
		$where = array(1);
		$whereV = array();
		$realVP = array();
		$desc = $this->describ($table_name, $db_name);
		foreach($desc as $k=>$v){
			if (isset($row[$v['Field']]))
				$realVP[$v['Field']] = $row[$v['Field']];
		}
		foreach($realVP as $k=>$v){
			$where[] = "$k=:$k";
			$whereV[$k] = $v;
		}
		$res = $dbAdapter->query("SELECT * FROM $table_name where ".implode(' AND ', $where), $whereV);
		if ($ret = $res->fetch()){
			$data = $ret;
			return $ret['id'];
		}
		return false;
	}		
	
	public function fetch_tags($db, $table){
		$userAdmin = useradminFactory::get();
		$userList = $userAdmin->getUserList();
		$userInfo = $userAdmin->getUserInfo();
		$userList[0] = 'Unknown';
		$tags = array();
		$base_sql = "SELECT id, name, creater_id FROM tag ".
			" WHERE `db_table`='$db.$table'";
		if(!empty($userInfo->id)){
			$sql = $base_sql . " and creater_id={$userInfo->id} ORDER BY name ASC";
			$res = $this->db->query($sql);
			while($row = $res->fetch()){
	// print_r($row);		
				$row['name'] = $row['name'].' (--By '.$userList[$row['creater_id']].')';
				$tags[] = $row;
			}
		}
		$sql = $base_sql . " and `public`=1";
		if (!empty($userInfo->id))
			$sql .= " AND creater_id!={$userInfo->id}";
		$sql .= " ORDER BY name ASC";
		$res = $this->db->query($sql);
		while($row = $res->fetch()){
//print_r($row);		
			$row['name'] = $row['name'].' (--By '.$userList[$row['creater_id']].')';
			$tags[] = $row;
		}
		return $tags;
	}
	
	public function getSql($components){
		$sql = "SELECT {$components['main']['fields']} FROM {$components['main']['from']} WHERE {$components['where']}";
		if (!empty($components['group']))
			$sql .= ' GROUP BY '.$components['group'];
		if (!empty($components['order']))
			$sql .= " ORDER BY {$components['order']}";
			
		if (!empty($components['limit']))
			$sql .= " LIMIT ".$components['limit'];
//print_r($sql);
		return $sql;
	}
	
    public function calcSql($params, $doLimit = true){
		$this->keys = array();
		$main = $this->generateMainSql($params);
// print_r($params);
        $where = $this->generateWhere($params['searchConditions']);
		$order = $this->getOrderSql($params);
		$group = isset($params['group']) ? $params['group'] : '';
		$limit = '';
        if ($doLimit)
            $limit = $this->getLimitSql($params['limit']);

		return compact('main', 'where', 'order', 'limit', 'group');
    }
    
    public function generateMainSql($params){
//print_r($params);
		if (empty($params['fields']))
			$params['fields'] = array($params['table'].'.*');
		$main['fields'] = implode(',', $params['fields']);
		if(!empty($params['from']))
			$main['from'] = $params['from'];
		else
			$main['from'] = $params['table'];
		return $main;
    }
    
    function generateWhere($criteria){
		if (empty($criteria))
			return 1;
        $whereSql = '';
        $cond = $this->formatWhere($criteria);
		
        if (!empty($cond)){
//print_r("Not Empty\n");        
            if ($this->isLeaf($cond))
                $whereSql = $this->generateLeafWhere($cond);
            else{
                $whereSql .= $this->generateFormatWhere($cond);
            }
        }
//print_r("wherersql = $whereSql\n");        
        if (empty($whereSql))
            $whereSql = 1;
        return $whereSql;
    }
    
    function getOrderSql($params){
//print_r($params);
		$order = '';
        if (!empty($params['order'])){
			if (is_array($params['order'])){
			    $tmp = array();
				foreach($params['order'] as $field=>$dir){
					if (is_int($field)){
						$tmp[] = $dir.' ASC';
					}
					else{
						$tmp[] = $field.' '.$dir;
					}
				}
				$order = implode(',', $tmp);
//print_r($order);        
			}
			else
				$order = $params['order'];
        }
        return $order;        
    }
    
    function getLimitSql($limit){
        if (!empty($limit)){
            if (is_array($limit)){
                $start = 0;
                $rows = 0;
                foreach($limit as $k=>$v){
                    if (is_int($k)){
                        if (empty($start))
                            $start = $v;
                        else
                            $rows = $v;
                    }
                    else if ($k == 'start')
                        $start = $v;
                    else if ($k == 'rows')
                        $rows = $v;
                }
                if ($rows == 0)
                    $limit = '';
                else
                    $limit = $start.','.$rows;
            }
        }
        return $limit;
    }
    
    public function generateFilterConditions($rules){
        $conditions = array();
    	//['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc']
        $qopers = array(
    				  'eq'=>"=",
    				  'ne'=>"<>",
    				  'lt'=>"<",
    				  'le'=>"<=",
    				  'gt'=>">",
    				  'ge'=>">=",
    				  'bw'=>"BW",
    				  'ew'=>'EW',
    				  'bn'=>"NOTBW",
    				  'en'=>"NOTEW",
    				  'cn'=>"LIKE" ,
    				  'nc'=>"NOT LIKE", 
    				  'in'=>"IN",
    				  'ni'=>"NOT IN",
            );
        $i =0;
        foreach($rules as $key=>$val) {
//print_r($val);
			$field = $val['field'];
            $op = $qopers[$val['op']];
            $value = isset($val['data']) ? $val['data'] : '';
			if((!empty($value) || $value == 0)&& $op)
                $conditions[] = compact('field', 'op', 'value');
        }
//print_r($conditions);		
        return $conditions;
    }
    
    function formatWhere($criteria){
        $formatWhere = array();
		if (!empty($criteria)){
			foreach($criteria as $key=>$cond){
				if(is_int($key)){
					if ($this->isLeaf($cond)){
						$formatWhere[] = $cond;
					}
					else{
						$formatWhere[] = $this->formatWhere($cond);
					}
				}
				else{
					$key = strtolower(trim($key));
					$formatWhere[$key] = $this->formatWhere($cond);
				}
			}
		}
//print_r($formatWhere);        
        return $formatWhere;
    }
    
   	function isLeaf($cond){
        $ret = false;
        if (!is_array($cond)){
            $ret = true;
		}
        elseif (isset($cond['field']) && isset($cond['op'])){// && isset($cond['value'])){
            $ret = true;
		}
        return $ret;   
    }
    
    function generateFormatWhere($where){
        $whereSql = '';
        foreach($where as $key=>$cond){
            if (empty($cond))
                continue;
            if (is_int($key))
                $key = ' and ';
            if ($this->isLeaf($cond)){
                $addQuote = false;
                if (!empty($whereSql)){
                    $whereSql .= $key.' (';
                    $addQuote = true;
                }
                $whereSql .= $this->generateLeafWhere($cond);
                if ($addQuote)
                    $whereSql .= ')';
            }
            else{
                if (!empty($whereSql))
                    $whereSql .= " $key ";
                $whereSql .= ' ('.$this->generateFormatWhere($cond).') ';
            }
        }
        //print_r($whereSql);        
        return $whereSql;
    }

    function generateLeafWhere($cond){
//print_r("leaf where");
//print_r($cond);
        $whereSql = '';
        if (is_int($cond)){
			return 'id='.$cond;
        }
        if (is_string($cond))
            return $cond;

        if ($cond['field'] == '__interTag'){
			//$sql = "SELECT * FROM `{$this->get('db')}`.`tag` WHERE id=".$cond['value'];
			$sql = "SELECT * FROM `tag` WHERE id=".$cond['value'];
			$res = $this->db->query($sql);
			$row = $res->fetch();
			if(preg_match("/^(.*)\.(.*)$/", $row['db_table'], $matches)){
				$this->getDb($matches[1], $realDbName);
				$row['db_table'] = $realDbName.".".$matches[2];
			}
			$cond['field'] = $row['db_table'].'.'.$row['id_field'];
			$cond['value'] = $row['element_ids'];
			$cond['op'] = 'in';
		}
        $field = $cond['field'];
        $op = strtolower(trim($cond['op']));
        if (is_array($cond['value']) && $op != 'between'){
// print_r($cond);
			$cond['value'] = implode(',', $cond['value']);
			if ($op != 'in' && $op != 'not in')
				$op = 'in';
        }

        switch($op){
			case '=':
			case '<>':
			case '>':
			case '<':
			case '>=':
			case '<=':
                $whereSql = $this->cellWhere($field, $op, $cond['value']);//"$field $op ".$this->db->quote($cond['value']);
				break;
			case 'in':
				if (empty($cond['value']))
					$whereSql = " 0 ";
				else
					$whereSql = $this->cellWhere($field, $op, $cond['value']);//"$field $op ({$cond['value']})";
				break;
			case 'not in':
				if (!empty($cond['value']))
					$whereSql = $this->cellWhere($field, $op, $cond['value']);//"$field $op ({$cond['value']})";
				break;
			case 'like':
                $whereSql = $this->cellWhere($field, 'REGEXP', $cond['value']);//"$field REGEXP ".$this->db->quote($cond['value']);
				break;
			case 'not like':
                $whereSql = $this->cellWhere($field, $op, $cond['value']);//"$field $op ".$this->db->quote('%'.$cond['value'].'%');
				break;
			case 'bw':// begin with
                $whereSql = $this->cellWhere($field, 'LIKE', $cond['value'].'%');//"$field LIKE ".$this->db->quote($cond['value']."%");
				break;
			case 'ew':// end with
                $whereSql = $this->cellWhere($field, 'LIKE', '%'.$cond['value']);//"$field LIKE ".$this->db->quote("%".$cond['value']);
				break;
			default:
                $whereSql = $this->cellWhere($field, $op, $cond['value']);//"$field $op ".$this->db->quote($cond['value']);
				break;
		}
//print_r($whereSql);		
        return $whereSql;
    }

	public function cellWhere($field, $op, $value){
// print_r("field = $field, op = $op, value = $value\n");	
		$ret = array();
		$fields = explode(',', $field);
		foreach($fields as $f){
			if ($op == 'in' || $op == 'not in')
				$ret[] = "$f $op ($value)";
			else if($op == 'between'){
				$ret[] = "$f $op '{$value['min']}' and '{$value['max']}'";
			}
			else{
				$ret[] = "$f $op ".$this->o_db->quote($value);
				if ($op == 'like' || $op == 'REGEXP')
					$this->keys[$f] = $value;
			}
		}
		if (count($ret) > 1)
			$sql = '('.implode(' OR ', $ret).')';
		else
			$sql = $ret[0];
		return $sql;
	}
	
    public function log(){
    
    }  

    public function fillOptions(&$columnDef, $display_status = 0){
// if(!isset($columnDef['edittype'])){		
// print_r($columnDef);
// }
		$nameKey = isset($columnDef['nameKey']) ? $columnDef['nameKey'] : false;
        if (!empty($columnDef['data_source_db']) && !empty($columnDef['data_source_table'])){
			// $columnDef['formatter'] == 'select_showlink' || $columnDef['formatter'] == 'ids' ||
			// $columnDef['editable'] && $columnDef['edittype'] == 'select' || 
            // $columnDef['search'] && $columnDef['stype'] == 'select'){

			$columnDef['blank'] = true;//!$columnDef['editrules']['required'];
            $ret = $this->getDataOptions($columnDef, false);//$db, $table, $conditions, array('new'=>false, 'blank'=>true, 'blank_item'=>$blank_item), $allFields);
			$options = $ret['options'];
// if($columnDef['name'] == 'owner_id')
// print_r($options);
// print_r($columnDef);	
// print_r($options);		
// print_r($field_limit);			
			if (empty($options)){
                $columnDef['edittype'] = $columnDef['stype'] = 'text';
            }
            else{
                if ($nameKey){
					foreach($options as $key=>$option){
						unset($options[$key]);
						if(is_array($option)){
							$displayField = $this->getDisplayField($option);
							$options[$displayField] = $option;
						}
						else{
							$options[$option] = $option;
						}
					}
                }
				$searchOptionsValue = $this->array2Str($options);
				
				unset($options[-1]);
				$formatOptionsValue = $this->array2Str($options);
				
				$addoptions = array();
				foreach($options as $id=>$item){
					if(!isset($item['isactive']) || $item['isactive'] == ISACTIVE_ACTIVE || $item['isactive'] == 0)
						$addoptions[$id] = $item;
				}
// if($columnDef['name'] == 'period_id'){				
// print_r("\n>>>>>>name = {$columnDef['name']}\n");
// print_r($columnDef['addoptions']);	
// }

// print_r("display_status = $display_status");
                // if ($columnDef['edittype'] == 'select'){
                    if (empty($columnDef['addoptions']['value'])){//} && $display_status == DISPLAY_STATUS_NEW){
// print_r(">>>>>>>>>{$columnDef['name']}>>>>>>>>>>>>>>>");						
						$columnDef['addoptions']['value'] = $addoptions;
                    }
                    if (empty($columnDef['editoptions']['value'])){//} && ($display_status == DISPLAY_STATUS_EDIT || $display_status == DISPLAY_STATUS_VIEW)){
						$columnDef['editoptions']['value'] = $options;
                    }
                    if (empty($columnDef['formatoptions']['value'])){//} && $display_status == DISPLAY_STATUS_LIST){
                        $columnDef['formatoptions']['value'] = $formatOptionsValue;
                    }
                    if (empty($columnDef['searchoptions']['value'])){
						$columnDef['searchoptions']['value'] = $searchOptionsValue;
					}
                // }
                if (!empty($columnDef['stype']) && $columnDef['stype'] == 'select' && empty($columnDef['searchoptions']['value'])){
                    $columnDef['searchoptions']['value'] = $searchOptionsValue;
//                    $columnDef['searchoptions']['dataUrl'] = "/jqgrid/jqgrid/oper/getSelectList/db/$db/table/$table";
                }
// if($columnDef['name'] == 'period_id'){				
// print_r($columnDef['addoptions']);	
// print_r("<<<<<<<<<<<<<<<<<<<<BR>\n");
// }			
// print_r($columnDef);	
            }
        }
    }

    /***
     * Get the select or checkbox options,通过action_list走，这样，所有的获取记录的通道都统一
     */                 
    public function getDataOptions($columnDef, $new =false){//}$db, $table, $conditions = null, $params = array('new'=>false, 'blank'=>false, 'blank_item'=>false), $allFields = false){
// print_r($columnDef);
		$ret = array();
		$options = array();
		if (!empty($columnDef['blank']))
			$options[0] = '';
		if (!empty($columnDef['blank_item']))
			$options[-1] = '==Blank==';
		$whereActive = "";
		$order = '';
        if(!empty($columnDef['data_source_sql'])){
			$sql = $columnDef['data_source_sql'];
			$dbAdapter = $this->db;
			$allFields = true;
			$res = $dbAdapter->query($sql);
			$rows = $res->fetchAll();
		}
		else{
			$allFields = isset($columnDef['data_source_all_fields']) ? $columnDef['data_source_all_fields'] : false;
			$db = isset($columnDef['data_source_db']) ? $columnDef['data_source_db'] : $this->dbName;
			$table = $columnDef['data_source_table'];

			$t = tableDescFactory::get($db, $table, array());
			$displayField = $t->getDisplayField();
			// if(0){
				// $list_params = array('db'=>$db, 'table'=>$table);
				// $list_params['sidx'] = $displayField;
				// $list_params['sord'] = 'asc';
				// $action_list = actionFactory::get(null, 'list', $list_params);
				// $rows = $action_list->getList();
			// }
			// else{
			$this->setDb($db);
			$realDbName = $this->o_db->get('real_db_name');
// print_r("allFields = $allFields\n<br />");			
			$sql = "SELECT ";
			if($allFields){
				$sql .= " * ";
			}
			else{	
				$sql .= " id, $displayField";
				if($this->fieldExist($table, 'isactive', $db))
					$sql .= ", isactive";
			}
			$sql .= " FROM $realDbName.$table";
			$conditions = isset($columnDef['data_source_condition']) ? $columnDef['data_source_condition'] : array();
			if(!empty($columnDef['searchConditions']))
				$conditions = array_merge($conditions, $columnDef['searchConditions']);
// if(!empty($conditions)			)
	// print_r($conditions);
			// if($columnDef['limit'] !== false){
// // print_r($columnDef['limit']);				
				// if(!empty($columnDef['limit'])){
					// $conditions[] = array('field'=>"$realDbName.$table.id", 'op'=>'IN', 'value'=>$columnDef['limit']);
				// }
				// else
					// $conditions[] = array('field'=>"1", 'op'=>'=', 'value'=>0);
			// }
			$sql .= " WHERE ".$this->generateWhere($conditions)." ORDER BY $displayField ASC";
			$res = $this->query($sql);
			$rows = $res->fetchAll();
			// }
		}
		foreach($rows as $row){
			// if($columnDef['limit'] !== false && in_array($row['id'], $columnDef['limit'])){
				if($allFields){
					$options[$row['id']] = $row;
				}
				else{
					$item = array('id'=>$row['id']);
					if (!empty($row[$displayField])){
						$item[$displayField] = $row[$displayField];
					}
					if (!empty($row['isactive'])){
						$item['isactive'] = $row['isactive'];
					}
					$options[$row['id']] = $item;//$row[$displayField];
				}
			// }
		}
		$ret['options'] = $options;
        return $ret;
    }
    
    public function getSelectList($params){ // return the dataUrl required data structure
		$columnDef = array('data_source_db'=>$params['db'], 'data_source_table'=>$params['table']);
		$columnDef['data_source_condition'] = isset($params['condition']) ? $params['condition'] : null;
		$ret = $this->getDataOptions($columnDef);
		$data = $ret['options'];
		// $db = $params['db'];
		// $table = $params['table'];
		// $conditions = isset($params['condition']) ? $params['condition'] : null;
		// $data = $this->getDataOptions($db, $table, $conditions);
		$selectList = '';
		if (!isset($params['selectTag']))
			$params['selectTag'] = true;
		if (!isset($params['blankItem']))
			$params['blankItem'] = false;
//print_r($params);
		if ($params['selectTag'])
			$selectList= "<SELECT>";
		if ($params['blankItem'])
			$selectList .= '<option value="0"/>';
		foreach($data as $k=>$v){
			$selectList .= '<option value="'.$k.'">'.$v.'</option>';
		}
		if ($params['selectTag'])
			$selectList .= "</SELECT>";
		return $selectList;
	}
	
	public function getMultiRowEditTemplate($data_source_db, $data_source_table, $value = array(), $params = array(), $removedFields = array(), $prefix = ''){
		$columnDef['formatter'] = $columnDef['edittype'] = 'multi_row_edit';
		$itemParams = $params;
		$itemParams['display_status'] = DISPLAY_STATUS_NEW;
		$itemParams['action_name'] = 'information';
		// $itemParams['fill'] = false;
// print_r($itemParams);
		$itemTable = tableDescFactory::get($data_source_db, $data_source_table, $itemParams, null);
		$itemOptions = $itemTable->getOptions();
// print_r($itemOptions);
		$columnDef['temp'] = $itemOptions['add'];
		foreach($columnDef['temp'] as $ik=>$iv){
			if(is_array($iv)){
				$field_name = $iv['index'];
			}
			else
				$field_name = $iv;
			
			if(/*empty($iv['editable']) || */in_array($field_name, $removedFields))
				unset($columnDef['temp'][$ik]);
		}
		$columnDef['legend'] = isset($itemParams['label']) ? $itemParams['label'] : $data_source_table;
		if(empty($prefix))
			$prefix = $data_source_table;
		$columnDef['prefix'] = $prefix;
		$columnDef['data_source_db'] = $data_source_db;
		$columnDef['data_source_table'] = $data_source_table;
		$columnDef['value'] = $value;
		$columnDef['editable'] = true;
		return $columnDef;
	}

	public function embed_table($data_source_db, $data_source_table, $value = array(), $params = array(), $removedFields = array(), $prefix = ''){
		$columnDef = $this->getMultiRowEditTemplate($data_source_db, $data_source_table, $value, $params, $removedFields, $prefix);
		$columnDef['formatter'] = $columnDef['edittype'] = 'embed_table';
		if(empty($prefix))
			$prefix = $data_source_table;
		$columnDef['prefix'] = $prefix;
		return $columnDef;
	}
		
}

?>
