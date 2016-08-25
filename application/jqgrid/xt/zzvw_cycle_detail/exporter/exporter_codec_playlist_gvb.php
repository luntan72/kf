<?php
require_once('dbfactory.php');
require_once('exporter_txt.php');

class xt_zzvw_cycle_detail_exporter_codec_playlist_gvb extends exporter_txt{

	protected function _export(){
		$db = dbFactory::get($this->params['db']);
		$str = "<?xml version='1.0'?>"."\n";
		$str .= "<!--This playlist is auto generated by GVB interface tool with xt, don't edit it unless you know what you are doing.-->"."\n";
		$str .= "\n";
		$str .= "<playlist>"."\n";;
		$str .= '  <testconfig desc="default config">'."\n";
		$str .= '	  <trickmode desc="play">'."\n";
		$str .= '			<tm action="play" after="to_end">1</tm>'."\n";
		$str .= '			<tm action="release" after="0">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="pause">'."\n";
		$str .= '			<tm action="play" after="5">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="resume" after="1">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="resume" after="1">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="release" after="1">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="accurate_seek">'."\n";
		$str .= '			<tm action="play" after="5">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.5" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.8" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.15" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.98" after="to_end">1</tm>'."\n";
		$str .= '			<tm action="release" after="0">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="fast_seek">'."\n";
		$str .= '			<tm action="play" after="5">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="1">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="1">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.15" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.98" after="to_end">1</tm>'."\n";
		$str .= '			<tm action="release" after="0">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="rotate">'."\n";
		$str .= '			<tm action="play" after="5">1</tm>'."\n";
		$str .= '			<tm action="rotate" rotation="180" after="3">1</tm>'."\n";
		$str .= '			<tm action="rotate" rotation="270" after="3">1</tm>'."\n";
		$str .= '			<tm action="rotate" rotation="90" after="3">1</tm>'."\n";
		$str .= '			<tm action="rotate" rotation="0" after="3">1</tm>'."\n";
		$str .= '			<tm action="release" after="0">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="resize">'."\n";
		$str .= '			<tm action="play" after="5">1</tm>'."\n";
		$str .= '			<tm action="resize" resizedisp="3000400" after="10">1</tm>'."\n";
		$str .= '			<tm action="resize" resizedisp="10000800" after="10">1</tm>'."\n";
		$str .= '			<tm action="play" after="15">1</tm>'."\n";
		$str .= '			<tm action="resize" resizedisp="80008" after="10">1</tm>'."\n";
		$str .= '			<tm action="play" after="5">1</tm>'."\n";
		$str .= '			<tm action="release" after="0">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="fffb">'."\n";
		$str .= '			<tm action="play" after="5">1</tm>'."\n";
		$str .= '			<tm action="fffb" fffbvalue="2" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fffb" fffbvalue="4" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fffb" fffbvalue="8" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="fffb" fffbvalue="-2" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="fffb" fffbvalue="-4" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="fffb" fffbvalue="-8" after="2">1</tm>'."\n";
		$str .= '			<tm action="play" after="5">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.98" after="to_end">1</tm>'."\n";
		$str .= '			<tm action="release" after="0">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="fullscreen">'."\n";
		$str .= '			<tm action="play" after="5">1</tm>'."\n";
		$str .= '			<tm action="fullscreen" after="10">1</tm>'."\n";
		$str .= '			<tm action="restore" after="3">1</tm>'."\n";
		$str .= '			<tm action="fullscreen" after="10">1</tm>'."\n";
		$str .= '			<tm action="restore" after="3">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="release" after="1">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="thumbnail">'."\n";
		$str .= '			<tm action="thumbnail">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="metadata">'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="metadata" after="0">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="very_simple">'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="misc_simple">'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '	  <trickmode desc="misc_complex">'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="stop" after="2">1</tm>'."\n";
		$str .= '			<tm action="play" after="10">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.2" after="2">1</tm>'."\n";
		$str .= '			<tm action="pause" after="1">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.1" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.5" after="2">1</tm>'."\n";
		$str .= '			<tm action="resume" after="2">1</tm>'."\n";
		$str .= '			<tm action="fast_seek" pos="0.8" after="2">1</tm>'."\n";
		$str .= '			<tm action="accurate_seek" pos="0.9" after="2">1</tm>'."\n";
		$str .= '	  </trickmode>'."\n";
		$str .= '  </testconfig>'."\n";
		
		// $this->params['id'] = json_decode($this->params['id']);
		$sql = "SELECT testcase.*, testcase_ver.*, priority.name as priority, module.name as module from cycle_detail detail left join testcase on testcase.id = detail.testcase_id".
			" left join testcase_ver on testcase_ver.testcase_id = testcase.id".
			" LEFT join testcase_module module on module.id = testcase_module_id".
			" LEFT JOIN testcase_priority priority on priority.id = testcase_ver.testcase_priority_id".
			" WHERE detail.id in (".implode(",", $this->params['id']).") and testcase_module_id=218".
			" and testcase.id = detail.testcase_id and testcase_ver.id = detail.testcase_ver_id";//streaming
		$res = $db->query($sql);
		while ($info = $res->fetch()){
			if(empty($info['resource_link']))
				continue;
			$resource_link = str_replace("\\", "/", $info['resource_link']);
			$stream_name = strrchr($info['resource_link'], "/");
			
			if($stream_name != '/' && strlen($stream_name)>1 && stripos('/', $stream_name) === 0)
				$stream_name = substr($stream_name, 1);
			else
				$stream_name = '';
			if(empty($stream_name))
				$stream_name = $info['summary'];
			if(stripos($stream_name, "\\") !== false)
				$stream_name = $info['summary'];
			if(preg_match("/^(.*).html$/", $stream_name, $matches))
				$stream_name = $matches[1];
			$stream_name = trim($stream_name);
			$new_sql = "select stream.code, stream.location, stream.name as s_name,".
				" stream.steps, v4cc.name as v4cc, stream.v_width,".
				" stream.v_height, stream.v_framerate, stream.v_bitrate, stream.chromasubsampling, stream.v_track,".
				" a_codec.name as a_codec, stream.a_samplerate, stream.a_bitrate, stream.a_channel, stream.v_bitrate, stream.a_track,".
				" stream.v_duration, stream.a_duration, stream.duration, type.name type, container.name container, stream.subtitle".
				" FROM codec_stream stream".
				//" LEFT JOIN codec_stream_format module ON module.id = stream.codec_stream_format_id".
				//" LEFT JOIN testcase_priority priority ON priority.id = stream.testcase_priority_id".
				" LEFT JOIN codec_stream_v4cc v4cc ON v4cc.id = stream.codec_stream_v4cc_id".
				" LEFT JOIN codec_stream_a_codec a_codec ON a_codec.id = stream.codec_stream_a_codec_id".
				" LEFT JOIN codec_stream_type type ON type.id = stream.codec_stream_type_id".
				" LEFT JOIN codec_stream_container container ON container.id = stream.codec_stream_container_id".
				" where stream.name = '{$stream_name}' and stream.codec_stream_type_id in (1,2) and stream.isactive = ".ISACTIVE_ACTIVE;//video, audio
			$new_res = $db->query($new_sql);
			if($row = $new_res->fetch()){
				$str .= '  <teststream id = "'.$info['code'].'">'."\n";
				$str .= '		<caseinfo>'."\n";
				$str .= '			<type>CODEC</type>'."\n";
				$str .= '			<module>'.$info['module'].'</module>'."\n";
				$str .= '			<srs>NA</srs>'."\n";
				$str .= '			<testpoint>NA</testpoint>'."\n";
				$str .= '			<category>NA</category>'."\n";
				$str .= '			<priority>'.$info['priority'].'</priority>'."\n";
				$str .= '			<source>NA</source>'."\n";
				$str .= '			<autolevel>NA</autolevel>'."\n";
				$str .= '			<teststreamid>'.$info['code'].'</teststreamid>'."\n";
				$str .= '			<objective><![CDATA[NA]]></objective>'."\n";
				$str .= '			<environment><![CDATA['.$info['precondition'].']]></environment>'."\n";
				$str .= '			<steps><![CDATA['.str_replace('&', ' and ', $info['steps']).']]></steps>'."\n";
				$str .= '			<expected><![CDATA[NA]]></expected>'."\n";
				$str .= '			<cmdline><![CDATA[NA]]></cmdline>'."\n";
				if($info['resource_link'] && $info['resource_link'] != ''){
					$resource_link = str_replace("\\", "/", $info['resource_link']);
					if(preg_match("/.*(\/)$/", $resource_link, $matches))
						$info['resource_link'] = $info['resource_link'];
					else
						$info['resource_link'] .= "\\";
				}
				$str .= '			<location><![CDATA['.$info['resource_link'].']]></location>'."\n";
				$str .= '		</caseinfo>'."\n";
				$str .= '		<streaminfo>'."\n";
				$str .= '			<clipname><![CDATA['.trim($stream_name).']]></clipname>'."\n";
				$str .= '			<container>'.$row['container'].'</container>'."\n";
				$str .= '			<video>'."\n";
				$str .= '				<v4cc>'.$row['v4cc'].'</v4cc>'."\n";
				$str .= '				<v_width>'.$row['v_width'].'</v_width>'."\n";
				$str .= '				<v_height>'.$row['v_height'].'</v_height>'."\n";
				$str .= '				<v_framerate>'.$row['v_framerate'].'</v_framerate>'."\n";
				$str .= '				<v_bitrate>'.$row['v_bitrate'].'</v_bitrate>'."\n";
				$str .= '				<chromasubsampling>'.$row['chromasubsampling'].'</chromasubsampling>'."\n";
				$str .= '				<v_track>'.$row['v_track'].'</v_track>'."\n";
				$str .= '			</video>'."\n";
				$str .= '			<audio>'."\n";
				$str .= '				<a_codec>'.$row['a_codec'].'</a_codec>'."\n";
				$str .= '				<a_samplerate>'.$row['a_samplerate'].'</a_samplerate>'."\n";
				$str .= '				<a_bitrate>'.$row['a_bitrate'].'</a_bitrate>'."\n";
				$str .= '				<a_channel>'.$row['a_channel'].'</a_channel>'."\n";
				$str .= '				<a_track>'.$row['a_track'].'</a_track>'."\n";
				$str .= '			</audio>'."\n";
				if(strtolower($row['type']) == 'audio' && $row['a_duration'])
					$row['duration'] = $row['a_duration'];
				else if(strtolower($row['type']) == 'video' && $row['v_duration'])
					$row['duration'] = $row['v_duration'];
				$str .= '			<duration>'.$row['duration'].'</duration>'."\n";
				$str .= '			<others>'."\n";
				$str .= '				<subtitle>'.$row['subtitle'].'</subtitle>'."\n";
				$str .= '			</others>'."\n";
				$str .= '		</streaminfo>'."\n";
				$str .= '	</teststream>'."\n";
			}
			else{
				$str .= '  <teststream id = "'.$info['code'].'">'."\n";
				$str .= '		<caseinfo>'."\n";
				$str .= '			<type>CODEC</type>'."\n";
				$str .= '			<module>'.$info['module'].'</module>'."\n";
				$str .= '			<srs>NA</srs>'."\n";
				$str .= '			<testpoint>NA</testpoint>'."\n";
				$str .= '			<category>NA</category>'."\n";
				$str .= '			<priority>'.$info['priority'].'</priority>'."\n";
				$str .= '			<source>NA</source>'."\n";
				$str .= '			<autolevel>NA</autolevel>'."\n";
				$str .= '			<teststreamid>'.$info['code'].'</teststreamid>'."\n";
				$str .= '			<objective><![CDATA[NA]]></objective>'."\n";
				$str .= '			<environment><![CDATA['.$info['precondition'].']]></environment>'."\n";
				$str .= '			<steps><![CDATA['.str_replace('&', ' and ', $info['steps']).']]></steps>'."\n";
				$str .= '			<expected><![CDATA[NA]]></expected>'."\n";
				$str .= '			<cmdline><![CDATA[NA]]></cmdline>'."\n";
				if($info['resource_link'] && $info['resource_link'] != ''){
					$resource_link = str_replace("\\", "/", $info['resource_link']);
					if(preg_match("/.*(\/)$/", $resource_link, $matches))
						$info['resource_link'] = $info['resource_link'];
					else
						$info['resource_link'] .= "\\";
				}
				$str .= '			<location><![CDATA['.$info['resource_link'].']]></location>'."\n";
				$str .= '		</caseinfo>'."\n";
				$str .= '		<streaminfo>'."\n";
				$str .= '			<clipname><![CDATA['.trim($stream_name).']]></clipname>'."\n";
				$str .= '			<container><![CDATA[NA]]></container>'."\n";
				$str .= '			<video>'."\n";
				$str .= '				<v4cc><![CDATA[NA]]></v4cc>'."\n";
				$str .= '				<v_width><![CDATA[NA]]></v_width>'."\n";
				$str .= '				<v_height><![CDATA[NA]]></v_height>'."\n";
				$str .= '				<v_framerate><![CDATA[NA]]></v_framerate>'."\n";
				$str .= '				<v_bitrate><![CDATA[NA]]></v_bitrate>'."\n";
				$str .= '				<chromasubsampling><![CDATA[NA]]></chromasubsampling>'."\n";
				$str .= '				<v_track><![CDATA[NA]]></v_track>'."\n";
				$str .= '			</video>'."\n";
				$str .= '			<audio>'."\n";
				$str .= '				<a_codec><![CDATA[NA]]></a_codec>'."\n";
				$str .= '				<a_samplerate><![CDATA[NA]]></a_samplerate>'."\n";
				$str .= '				<a_bitrate><![CDATA[NA]]></a_bitrate>'."\n";
				$str .= '				<a_channel><![CDATA[NA]]></a_channel>'."\n";
				$str .= '				<a_track><![CDATA[NA]]></a_track>'."\n";
				$str .= '			</audio>'."\n";
				$str .= '			<duration><![CDATA[NA]]></duration>'."\n";
				$str .= '			<others>'."\n";
				$str .= '				<subtitle><![CDATA[NA]]></subtitle>'."\n";
				$str .= '			</others>'."\n";
				$str .= '		</streaminfo>'."\n";
				$str .= '	</teststream>'."\n";
			}
		}
		$str .= '</playlist>';
		$index = strrpos($this->fileName, ".txt");
		$name = substr($this->fileName, 0, $index);
		$this->fileName = $name.".xml";
		$this->str  = $str;
	}
};
?>