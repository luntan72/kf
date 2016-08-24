<?php
require_once('dbfactory.php');
require_once('exporter_txt.php');

class xt_zzvw_cycle_detail_stream_exporter_codec_playlist_android_old extends exporter_txt{

	protected function _export(){
		$db = dbFactory::get($this->params['db']);
		$str = "<?xml version='1.0'?>"."\n";
		$str .= "<!--This playlist is auto generated by Apollo interface tool with xt, don't edit it unless you know what you are doing.-->"."\n";
		$str .= "\n";
		$str .= "<playlist>"."\n";;
		$str .= '	<trickModeConfig desc="defaultConfig">'."\n";
		$str .= '		<trickmode desc="performance">'."\n";
		$str .= '		  	<operation tag="open">'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="OpenMedia"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			</operation>'."\n";
		$str .= "\n";			  
		$str .= '			<operation tag="play">'."\n";
		$str .= '				<cmd action="SetLooping" IsLooping="true"/>'."\n";          	
		$str .= '				<cmd action="StartPlayback"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";
		$str .= '				<cmd action="Sleep" time="20"/>'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="Sleep" time="20"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";          	
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			</operation>'."\n";
		$str .= "\n";			  
		$str .= '			<operation tag="stop">'."\n";
		$str .= '				<cmd action="StartCatchFrameDropRate"/>'."\n";
		$str .= '				<cmd action="StopPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="StopCatchFrameDropRate"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";            	
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="release">'."\n";
		$str .= '				<cmd action="Release"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";        	            	
		$str .= '			  </operation>'."\n";          
		$str .= '		</trickmode>'."\n";
		$str .= "\n";
		$str .= '		<trickmode desc="playToEnd1">'."\n";
		$str .= "\n";			
		$str .= '		</trickmode>'."\n";
		$str .= "\n";				
		$str .= '		<trickmode desc="playToEnd">'."\n";
		$str .= '			  <operation tag="open">'."\n";
		$str .= '				<cmd action="OpenMedia"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= '			  <operation tag="playUntilEnd">'."\n";
		$str .= '				<cmd action="StartPlaybackUntilEnd"/>'."\n";
		$str .= '				<cmd action="CheckPlayTime"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= '			  <operation tag="stop">'."\n";
		$str .= '				<cmd action="StopPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";             	
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="release">'."\n";
		$str .= '				<cmd action="Release"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";            	
		$str .= '			  </operation>'."\n";          
		$str .= '		</trickmode>'."\n";	
		$str .= "\n";
		$str .= '		<trickmode desc="loopPlay">'."\n";
		$str .= '			  <operation tag="open">'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="OpenMedia"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="play">'."\n";
		$str .= '				<cmd action="SetLooping" IsLooping="true"/>'."\n";
		$str .= '				<cmd action="StartPlayback"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";
		$str .= '				<cmd action="Sleep" time="259200"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";          	
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '				<cmd action="CheckIsPlaying"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="stop">'."\n";
		$str .= '				<cmd action="StopPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";            	
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="release">'."\n";
		$str .= '				<cmd action="Release"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";        	            	
		$str .= '			  </operation>'."\n";     	    	
		$str .= '		</trickmode>'."\n";	
		$str .= "\n";
		$str .= '		<trickmode desc="loopPlay12H">'."\n";
		$str .= '			  <operation tag="open">'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="OpenMedia"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="play">'."\n";
		$str .= '				<cmd action="SetLooping" IsLooping="true"/>'."\n";
		$str .= '				<cmd action="StartPlayback"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";
		$str .= '				<cmd action="Sleep" time="43200"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";          	
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '				<cmd action="CheckIsPlaying"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="stop">'."\n";
		$str .= '				<cmd action="StopPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";            	
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="release">'."\n";
		$str .= '				<cmd action="Release"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";        	            	
		$str .= '			  </operation>'."\n";     	    	
		$str .= '		</trickmode>'."\n";
		$str .= "\n";
		$str .= '		<trickmode desc="loopPlay24H">'."\n";
		$str .= '			  <operation tag="open">'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="OpenMedia"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="play">'."\n";
		$str .= '				<cmd action="SetLooping" IsLooping="true"/>'."\n";
		$str .= '				<cmd action="StartPlayback"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";
		$str .= '				<cmd action="Sleep" time="86400"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";          	
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '				<cmd action="CheckIsPlaying"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="stop">'."\n";
		$str .= '				<cmd action="StopPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";            	
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="release">'."\n";
		$str .= '				<cmd action="Release"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";        	            	
		$str .= '			  </operation>'."\n";     	    	
		$str .= '		</trickmode>'."\n";
		$str .= "\n";
		$str .= '		<trickmode desc="loopPlay48H">'."\n";
		$str .= '			  <operation tag="open">'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="OpenMedia"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="play">'."\n";
		$str .= '				<cmd action="SetLooping" IsLooping="true"/>'."\n";
		$str .= '				<cmd action="StartPlayback"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";
		$str .= '				<cmd action="Sleep" time="172800"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";          	
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '				<cmd action="CheckIsPlaying"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="stop">'."\n";
		$str .= '				<cmd action="StopPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";            	
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="release">'."\n";
		$str .= '				<cmd action="Release"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";        	            	
		$str .= '			  </operation>'."\n";     	    	
		$str .= '		</trickmode>'."\n";
		$str .= "\n";	 
		$str .= '		 <trickmode desc="loopPlay72H">'."\n";
		$str .= '			  <operation tag="open">'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="OpenMedia"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="play">'."\n";
		$str .= '				<cmd action="SetLooping" IsLooping="true"/>'."\n";
		$str .= '				<cmd action="StartPlayback"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";
		$str .= '				<cmd action="Sleep" time="259200"/>'."\n";
		$str .= '				<cmd action="GetCPUStat"/>'."\n";          	
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '				<cmd action="CheckIsPlaying"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="stop">'."\n";
		$str .= '				<cmd action="StopPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";            	
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="release">'."\n";
		$str .= '				<cmd action="Release"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetMEMStat"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";        	            	
		$str .= '			  </operation>'."\n";     	    	
		$str .= '		</trickmode>'."\n";
		$str .= "\n";				   
		$str .= '		<trickmode desc="simple">'."\n";
		$str .= '			  <operation tag="open">'."\n";
		$str .= '				<cmd action="OpenMedia"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";					
		$str .= '			  <operation tag="play">'."\n";
		$str .= '				<cmd action="StartPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="5"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="seek">'."\n";
		$str .= '				<cmd action="SeekToPercentage" pos="50"/>'."\n";
		$str .= '				<cmd action="Sleep" time="5"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="pause">'."\n";
		$str .= '				<cmd action="PausePlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";       	
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="resume">'."\n";
		$str .= '				<cmd action="StartPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="5"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";         	
		$str .= '			  </operation>'."\n";
		$str .= "\n";			  
		$str .= '			  <operation tag="seek">'."\n";
		$str .= '				<cmd action="SeekToMsec" pos="-10"/>'."\n";
		$str .= '				<cmd action="Sleep" time="5"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";
		$str .= '			  </operation>'."\n";
		$str .= "\n";	
		$str .= '			  <operation tag="stop">'."\n";
		$str .= '				<cmd action="StopPlayback"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";          	
		$str .= '			  </operation>'."\n";
		$str .= "\n";
		$str .= '			  <operation tag="release">'."\n";
		$str .= '				<cmd action="Release"/>'."\n";
		$str .= '				<cmd action="Sleep" time="1"/>'."\n";
		$str .= '				<cmd action="GetAllErrors"/>'."\n";         	
		$str .= '			  </operation>'."\n";
		$str .= '		</trickmode>'."\n";
		$str .= "\n";
		$str .= '	</trickModeConfig>'."\n";
		
		//$this->params['id'] = json_decode($this->params['id']);
		$sql = "SELECT stream.code, stream.location, module.name as module, stream.name as s_name,".
			" priority.name as priority, stream.precondition as environment, stream.steps, v4cc.name as v4cc, stream.v_width,".
			" stream.v_height, stream.v_framerate, stream.v_bitrate, stream.chromasubsampling, stream.v_track,".
			" a_codec.name as a_codec, stream.a_samplerate, stream.a_bitrate, stream.a_channel, stream.v_bitrate, stream.a_track,".
			" stream.v_duration, stream.a_duration, stream.duration, type.name type, container.name container, stream.subtitle".
			" FROM cycle_detail detail".
			" LEFT JOIN codec_stream stream ON stream.id = detail.codec_stream_id".
			" LEFT JOIN codec_stream_format module ON module.id = stream.codec_stream_format_id".
			" LEFT JOIN testcase_priority priority ON priority.id = stream.testcase_priority_id".
			" LEFT JOIN codec_stream_v4cc v4cc ON v4cc.id = stream.codec_stream_v4cc_id".
			" LEFT JOIN codec_stream_a_codec a_codec ON a_codec.id = stream.codec_stream_a_codec_id".
			" LEFT JOIN codec_stream_type type ON type.id = stream.codec_stream_type_id".
			" LEFT JOIN codec_stream_container container ON container.id = stream.codec_stream_container_id".
			" WHERE detail.id in (".implode(",", $this->params['id']).")";
		$res = $db->query($sql);
		while ($row = $res->fetch()){
			if(!empty($row['code'])){
				$str .= '  <teststream id = "'.$row['code'].'">'."\n";
				$str .= '		<caseinfo>'."\n";
				$str .= '			<type>CODEC</type>'."\n";
				$str .= '			<module>'.$row['module'].'</module>'."\n";
				$str .= '			<srs>NA</srs>'."\n";
				$str .= '			<testpoint>NA</testpoint>'."\n";
				$str .= '			<category>NA</category>'."\n";
				$str .= '			<priority>'.$row['priority'].'</priority>'."\n";
				$str .= '			<source>NA</source>'."\n";
				$str .= '			<autolevel>NA</autolevel>'."\n";
				$str .= '			<teststreamid>'.$row['code'].'</teststreamid>'."\n";
				$str .= '			<objective><![CDATA[NA]]></objective>'."\n";
				$str .= '			<environment><![CDATA['.$row['environment'].']]></environment>'."\n";
				$str .= '			<steps><![CDATA['.str_replace('&', ' and ', $row['steps']).']]></steps>'."\n";
				$str .= '			<expected><![CDATA[NA]]></expected>'."\n";
				$str .= '			<cmdline><![CDATA[NA]]></cmdline>'."\n";
				if($row['location'] && $row['location'] != ''){
					$location = str_replace("\\", "/", $row['location']);
					if(preg_match("/.*(\/)$/", $location, $matches))
						$row['location'] = $row['location'];
					else
						$row['location'] .= "\\";
				}
				$str .= '			<location><![CDATA['.$row['location'].']]></location>'."\n";
				$str .= '		</caseinfo>'."\n";
				$str .= '		<streaminfo>'."\n";
				$str .= '			<clipname><![CDATA['.trim($row['s_name']).']]></clipname>'."\n";
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
				//纯audio的用audio_duration， 纯video的用video_duration，剩下的选择duration
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
		}
		$str .= '</playlist>';
		$index = strrpos($this->fileName, ".txt");
		$name = substr($this->fileName, 0, $index);
		$this->fileName = $name.".xml";
		$this->str  = $str;
	}
};
?>