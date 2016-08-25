<?php
	$columnMap = array(
		'AACLCDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'audio_duration'=>'C',
				'a_codec'=>'D',
				'v4cc'=>'E',
				'audio_profile'=>'F',
				'audio_bitrate_mode'=>'G',
				'a_bitrate'=>'H',
				'a_channel'=>'I',
				'a_samplerate'=>'J',
			)
		),
		'AACPlusDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'audio_duration'=>'C',
				'v4cc'=>'D',
				'a_codec'=>'E',
				'audio_profile'=>'F',
				'audio_bitrate_mode'=>'G',
				'a_bitrate'=>'H',
				'a_channel'=>'I',
				'a_samplerate'=>'J',
			)
		),
		'MP3Dec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'audio_duration'=>'C',
				'v4cc'=>'D',
				'a_codec'=>'E',
				'audio_profile'=>'F',
				'audio_bitrate_mode'=>'G',
				'a_bitrate'=>'H',
				'a_channel'=>'I',
				'a_samplerate'=>'J',
				'channel_model'=>'K'
			)
		),
		'WAVDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'audio_duration'=>'C',
				'v4cc'=>'D',
				'a_codec'=>'E',
				'audio_bitrate_mode'=>'F',
				'a_bitrate'=>'G',
				'a_channel'=>'H',
				'a_samplerate'=>'I',
				'audio_bit_depth'=>'J',
				'endianness'=>'K'
			)
		),
		'FLACDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'audio_duration'=>'C',
				'v4cc'=>'D',
				'a_codec'=>'E',
				'audio_bitrate_mode'=>'F',
				'a_bitrate'=>'G',
				'a_channel'=>'H',
				'a_samplerate'=>'I',
				'audio_bit_depth'=>'J',
			)
		),
		'OGGDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'v4cc'=>'D',
				'a_codec'=>'E',
				'audio_duration'=>'F',
				'audio_bitrate_mode'=>'G',
				'a_bitrate'=>'H',
				'a_channel'=>'I',
				'a_samplerate'=>'J',
			)
		),
		'WMAStdDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'audio_duration'=>'C',
				'v4cc'=>'D',
				'a_codec'=>'E',
				'audio_bitrate_mode'=>'F',
				'a_bitrate'=>'G',
				'a_channel'=>'H',
				'a_samplerate'=>'I',
				'audio_bit_depth'=>'J'
			)
		),
		'WMAProDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'audio_duration'=>'C',
				'v4cc'=>'D',
				'a_codec'=>'E',
				'audio_profile'=>'F',
				'audio_bitrate_mode'=>'G',
				'a_bitrate'=>'H',
				'a_channel'=>'I',
				'a_samplerate'=>'J',
				'audio_bit_depth'=>'K'
			)
		),
		'WMALosslessDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'audio_duration'=>'C',
				'v4cc'=>'D',
				'a_codec'=>'E',
				'audio_profile'=>'F',
				'audio_bitrate_mode'=>'G',
				'a_bitrate'=>'H',
				'a_channel'=>'I',
				'a_samplerate'=>'J',
				'audio_bit_depth'=>'K'
			)
		),
		'AC3Dec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'audio_duration'=>'C',
				'v4cc'=>'D',
				'a_codec'=>'E',
				'audio_bitrate_mode'=>'F',
				'a_bitrate'=>'G',
				'a_channel'=>'H',
				'a_samplerate'=>'I',
				'endianness'=>'J',
				'audio_bit_depth'=>'K',
				'channel_mode'=>'L'
			)
		),
		'DD plus'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_profile'=>'E',
				'video_duration'=>'F',
				'video_bit_rate_mode'=>'G',
				'v_bitrate'=>'H',
				'v_width'=>'I',
				'v_height'=>'J',
				'display_aspect_ratio'=>'K',
				'v_framerate'=>'L',
				'color_space'=>'M',
				'chroma_subsampling'=>'N',
				'video_bit_depth'=>'O',
				'scan_type'=>'P',
				
				'a_codec'=>'Q',
				'audio_duration'=>'R',
				'audio_bitrate_mode'=>'S',
				'a_bitrate'=>'T',
				'a_channel'=>'U',
				'a_samplerate'=>'V',
				'audio_bit_depth'=>'W',
				'endianness'=>'X',
			)
		),
		'XvidDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_profile'=>'E',
				'video_duration'=>'F',
				'v_bitrate'=>'G',
				'v_width'=>'H',
				'v_height'=>'I',
				'display_aspect_ratio'=>'J',
				'v_framerate'=>'K',
				'color_space'=>'L',
				'video_bit_depth'=>'M',
				'scan_type'=>'N',
				'chroma_subsampling'=>'O',
				
				'a_codec'=>'P',
				'audio_profile'=>'Q',
				'audio_duration'=>'R',
				'audio_bitrate_mode'=>'S',
				'a_bitrate'=>'T',
				'a_channel'=>'U',
				'a_samplerate'=>'V',
				'audio_bit_depth'=>'W',
				'endianness'=>'X',
				'channel_mode'=>'Y'
			)
		),
		'VC1Dec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_duration'=>'E',
				'v_bitrate'=>'F',
				'v_width'=>'G',
				'v_height'=>'H',
				'display_aspect_ratio'=>'I',
				'v_framerate'=>'J',
				'video_bit_depth'=>'K',
				'scan_type'=>'L',
				
				'a_codec'=>'M',
				'audio_profile'=>'N',
				'audio_duration'=>'O',
				'audio_bitrate_mode'=>'P',
				'a_bitrate'=>'Q',
				'a_channel'=>'R',
				'a_samplerate'=>'S',
				'channel_mode'=>'T',
			)
		),
		'MPEG4Dec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_duration'=>'E',
				'v_bitrate'=>'F',
				'v_width'=>'G',
				'v_height'=>'H',
				'display_aspect_ratio'=>'I',
				'v_framerate'=>'J',
				'color_space'=>'K',
				'video_bit_depth'=>'L',
				'scan_type'=>'M',
				'video_profile'=>'N',
				'video_bitrate_mode'=>'O',
				'chroma_subsampling'=>'P',
				
				'a_codec'=>'Q',
				'audio_profile'=>'R',
				'audio_duration'=>'S',
				'audio_bitrate_mode'=>'T',
				'a_bitrate'=>'U',
				'a_channel'=>'V',
				'a_samplerate'=>'W',
				'channel_mode'=>'X',
			)
		),
		'MPEG2Dec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_duration'=>'E',
				'v_bitrate'=>'F',
				'v_width'=>'G',
				'v_height'=>'H',
				'display_aspect_ratio'=>'I',
				'v_framerate'=>'J',
				'color_space'=>'K',
				'video_bit_depth'=>'L',
				'scan_type'=>'M',
				'video_profile'=>'N',
				'video_bitrate_mode'=>'O',
				'chroma_subsampling'=>'P',
				
				'a_codec'=>'Q',
				'audio_profile'=>'R',
				'audio_duration'=>'S',
				'audio_bitrate_mode'=>'T',
				'a_bitrate'=>'U',
				'a_channel'=>'V',
				'a_samplerate'=>'W',
				'endianness'=>'X',
				'audio_bit_depth'=>'Y'
			)
		),
		'MJPEGDec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_duration'=>'E',
				'v_bitrate'=>'F',
				'v_width'=>'G',
				'v_height'=>'H',
				'display_aspect_ratio'=>'I',
				'v_framerate'=>'J',
				'color_space'=>'K',
				'chroma_subsampling'=>'L',
				'video_bit_depth'=>'M',
				'scan_type'=>'N',
				
				'a_codec'=>'O',
				'audio_duration'=>'P',
				'audio_bitrate_mode'=>'Q',
				'a_bitrate'=>'R',
				'a_channel'=>'S',
				'a_samplerate'=>'T',
				'audio_bit_depth'=>'U',
				'endianness'=>'V'
			)
		),
		'H264Dec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_profile'=>'E',
				'video_duration'=>'F',
				'video_bitrate_mode'=>'G',
				'v_bitrate'=>'H',
				'v_width'=>'I',
				'v_height'=>'J',
				'display_aspect_ratio'=>'K',
				'v_framerate'=>'L',
				'color_space'=>'M',
				'chroma_subsampling'=>'N',
				'video_bit_depth'=>'O',
				'scan_type'=>'P',
				
				'a_codec'=>'Q',
				'audio_duration'=>'R',
				'audio_bitrate_mode'=>'S',
				'a_bitrate'=>'T',
				'a_channel'=>'U',
				'a_samplerate'=>'V',
				'audio_profile'=>'W',
			)
		),
		'H263Dec'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_profile'=>'E',
				'video_duration'=>'F',
				'v_bitrate'=>'G',
				'v_width'=>'H',
				'v_height'=>'I',
				'display_aspect_ratio'=>'J',
				'video_bitrate_mode'=>'K',
				'v_framerate'=>'L',
				'color_space'=>'M',
				'chroma_subsampling'=>'N',
				'video_bit_depth'=>'O',
				
				'a_codec'=>'P',
				'audio_duration'=>'Q',
				'audio_bitrate_mode'=>'R',
				'a_bitrate'=>'S',
				'a_channel'=>'T',
				'a_samplerate'=>'U',
				'audio_bit_depth'=>'V',
				'audio_profile'=>'W',
			)
		),
		'FLV'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_profile'=>'E',
				'video_duration'=>'F',
				'video_bitrate_mode'=>'G',
				'v_bitrate'=>'H',
				'v_width'=>'I',
				'v_height'=>'J',
				'display_aspect_ratio'=>'K',
				'v_framerate'=>'L',
				'color_space'=>'M',
				'chroma_subsampling'=>'N',
				'video_bit_depth'=>'O',
				'scan_type'=>'P',
				
				'a_codec'=>'Q',
				'audio_duration'=>'R',
				'audio_bitrate_mode'=>'S',
				'a_bitrate'=>'T',
				'a_channel'=>'U',
				'a_samplerate'=>'V',
				'audio_profile'=>'W',
			)
		),
		'F4V'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_profile'=>'E',
				'video_duration'=>'F',
				'v_bitrate'=>'G',
				'v_width'=>'H',
				'v_height'=>'I',
				'display_aspect_ratio'=>'J',
				'v_framerate'=>'K',
				'color_space'=>'L',
				'chroma_subsampling'=>'M',
				'video_bit_depth'=>'N',
				'scan_type'=>'O',
				
				'a_codec'=>'P',
				'audio_profile'=>'Q',
				'audio_duration'=>'R',
				'audio_bitrate_mode'=>'S',
				'a_bitrate'=>'T',
				'a_channel'=>'U',
				'a_samplerate'=>'V',
			)
		),
		'WebM'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'demuxer_format'=>'B',
				'v4cc'=>'D',
				'video_duration'=>'E',
				'v_width'=>'F',
				'v_height'=>'G',
				'display_aspect_ratio'=>'H',
				'v_framerate'=>'I',
				'v_bitrate'=>'J',
				
				'a_codec'=>'K',
				'audio_duration'=>'L',
				'audio_bitrate_mode'=>'M',
				'a_bitrate'=>'N',
				'a_channel'=>'O',
				'a_samplerate'=>'P',
			)
		),
		'Customer streams'=>array(
			'start_row'=>2,
			'columns'=>array(
				'complete_name'=>'A',
				'container'=>'B',
				'v4cc'=>'D',
				'video_profile'=>'E',
				'video_duration'=>'F',
				'v_bitrate'=>'G',
				'v_width'=>'H',
				'v_height'=>'I',
				'display_aspect_ratio'=>'J',
				'v_framerate'=>'K',
				'color_space'=>'L',
				'chroma_subsampling'=>'M',
				'video_bit_depth'=>'N',
				'scan_type'=>'O',
				'video_bitrate_mode'=>'P',
				
				'a_codec'=>'Q',
				'audio_profile'=>'R',
				'audio_duration'=>'S',
				'audio_bitrate_mode'=>'T',
				'a_bitrate'=>'U',
				'a_channel'=>'V',
				'a_samplerate'=>'W',
				'channel_mode'=>'X',
				'endianness'=>'Y'
			)
		),
	);
?>