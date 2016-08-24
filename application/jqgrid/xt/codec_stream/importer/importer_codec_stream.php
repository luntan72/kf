<?php
require_once('importer_excel.php');

class xt_codec_stream_importer_codec_stream extends importer_excel{
	protected $total = 0;
	
	protected function process(){
		parent::process();
		print_r("total = ".$this->total);
	}
	
	protected function processSheetData($title, $sheet_data){
		//$stream_format_id = $this->tool->getElementId('codec_stream_format', array('name'=>$title));
		$total = 0;
		foreach($sheet_data as $stream){
			//$stream['codec_stream_format_id'] = $stream_format_id;
			$this->processStream($stream, $title);
			$total ++;
		}
print_r("sheet $title, total = $total\n");		
	}
	
	protected function processStream($stream, $title){
		$vp = $stream;
// print_r("title = $title, stream = ");
// print_r($stream);
		$complete_name = $stream['complete_name'];
		preg_match('/^(.*?)([^\/]+)$/', $complete_name, $matches);
		if (!empty($matches)){
			$vp['name'] = $matches[2];
			$vp['location'] = $matches[1];
			$vp['location'] = '\\'.str_ireplace('/', '\\', $vp['location']);
// print_r($matches);			
		}
		else{
			$vp['name'] = $complete_name;
			$vp['location'] = '';
		}
		if ($title == "Customer streams"){
			// $vp['name'] = $complete_name;
			$vp['location'] = '\\Customer_Streams_FSL_Analysis\\';
// print_r($vp['location']);
		}
		unset($vp['complete_name']);
		
		$fields = array('container'=>'codec_stream_container', 'v4cc'=>'codec_stream_v4cc', 'demuxer_format'=>'stream_demuxer_format',
			'video_bitrate_mode'=>'stream_video_bitrate_mode', 'audio_bitrate_mode'=>'stream_audio_bitrate_mode',
			'chroma_subsampling'=>'chromasubsampling', 'video_bit_depth'=>'stream_video_bit_depth', 'audio_bit_depth'=>'stream_audio_bit_depth',
			'a_codec'=>'codec_stream_a_codec', 'video_profile'=>'stream_video_profile', 'audio_profile'=>'stream_audio_profile',
			'scan_type'=>'stream_scan_type', 'color_space'=>'stream_color_space', 'display_aspect_ratio'=>'stream_display_aspect_ratio',
			'audio_duration'=>'a_duration', 'video_duration'=>'v_duration', 'channel_mode'=>'stream_channel_mode', 'endianness'=>'stream_endianness'
			
		);
		foreach($fields as $o=>$f){
			if(isset($stream[$o])){
				$vp[$f] = $stream[$o];
				unset($vp[$o]);
			}
		}
		$fields = array('codec_stream_container', 'codec_stream_v4cc', 'stream_demuxer_format',
			'stream_video_bitrate_mode', 'stream_audio_bitrate_mode', 'stream_channel_mode', 'stream_endianness',
			'stream_video_bit_depth', 'stream_audio_bit_depth', 'stream_audio_profile', 'stream_video_profile',
			'codec_stream_a_codec', 'stream_scan_type', 'stream_color_space', 'stream_display_aspect_ratio',
			
		);
		foreach($fields as $f){
			if(isset($vp[$f])){
				$vp[$f.'_id'] = 0;
				if($f.'_id' == 'codec_stream_type_id')
					$vp[$f.'_id'] = 6;
				if (!empty($vp[$f])){
					$vp[$f.'_id'] = $this->tool->getElementId($f, array('name'=>$vp[$f]));
					unset($vp[$f]);
				}
			}
		}

		// handle width, height;
		$fields = array('v_width', 'v_height');
		foreach($fields as $f){
			if (isset($vp[$f])){
				$vp[$f] = str_ireplace (' ', '', $vp[$f]);
			}
		}
		// handle the bitrate, framerate, 
		$fields = array('a_bitrate', 'v_framerate', 'a_samplerate', 'a_channel');
		foreach($fields as $f){
			if (isset($vp[$f])){
				$vp[$f] = str_ireplace (' ', '', $vp[$f]);
				preg_match('/([\.\d]*)/', $vp[$f], $matches);
				if (!empty($matches)){
					$vp[$f] = $matches[1];
				}
			}
		}
		
		// handle v_bitrate, attention the Kbps and Mbps
		$fields = array('v_bitrate');
		foreach($fields as $f){
			if (isset($vp[$f])){
				$vp[$f] = str_ireplace (' ', '', $vp[$f]);
				preg_match('/([\.\d]*)(.)bps/i', $vp[$f], $matches);
				if (!empty($matches)){
					$vp[$f] = $matches[1];
					if(strtolower($matches[2]) == 'm')
						$vp[$f] *= 1000;
				}
			}
		}
		
		// handle the duration
		$fields = array('a_duration', 'v_duration');
		foreach($fields as $f){
			if (isset($vp[$f])){
				$pattern = '/^(([\d]*)h)?\s*(([\d]*)mn)?\s*(([\d]*)s)?\s*(([\d]*)ms)?/';
				preg_match($pattern, $vp[$f], $matches);
				if (!empty($matches)){
					for($i = 0; $i < 9; $i ++){
						if (!isset($matches[$i]))
							$matches[$i] = 0;
					}
// print_r($matches);				
					$vp[$f] = sprintf("%02d:%02d:%02d.%03d", $matches[2], $matches[4], $matches[6], $matches[8]);
				}
			}
		}
		$vp['wheres'] = 'From Excel and Umbrella';
		// get the streamId from XiaoTian
		unset($vp['streamid']);
		$umb = dbFactory::get('umbrella');
		$location = $vp['location'];
		$keys = array('name', 'location');
		// $location = str_ireplace('/', '\\', $vp['location']);
		// $location = '\\'.$location;
		$res = $umb->query("SELECT tc.id as tc_id, module.name as module, tc.testcaseid, ver.steps, ver.environment".
			" FROM tms_tc_testcase tc".
			" left join tms_tc_version ver on tc.id=ver.tcid".
			" left join tms_pf_module module on tc.moduleid=module.id".
			" where tc.name=:name and ver.resourcelink=:location ORDER BY ver.id DESC limit 0, 1", 
			array('name'=>$vp['name'], 'location'=>$location));
		if ($row = $res->fetch()){
// print_r($row);		
			$vp['code'] = $row['testcaseid'];
			$vp['steps'] = $row['steps'];
			$vp['environment'] = $row['environment'];
			$format = $row['module'];
			
			$s_res = $umb->query("SELECT * FROM resource_stream WHERE tc_id={$row['tc_id']}");
			if ($s_row = $s_res->fetch()){
				$fields = array('v_width', 'v_height', 'v_framerate', 'v_bitrate', 'v_track',
					'a_codec', 'a_samplerate', 'a_bitrate', 'a_channel', 'a_track');
				foreach($fields as $f){//不为空的以老xiaotian为主
					if (!empty($s_row[$f]))
						$vp[$f] = $s_row[$f];
				}
			}
			
			$keys[] = 'code';
		}
		if(!empty($format)){
			$vp['codec_stream_format_id'] = $this->tool->getElementId('codec_stream_format', array('name'=>$format));
		}
		$stream_id = $this->tool->getElementId('codec_stream', $vp, $keys, $isNew);
		
// print_r($vp);		
		if (!$isNew){
			$this->total ++;
			// print_r($vp);
			// print_r(">>>>>>>>>$stream_id<<<<<<<\n");
			// print_r("<<<<".$vp['name']."==".$vp['location']."==".$stream_id.">>>>\n");
		}
 // print_r($vp);		
	}
};

?>
