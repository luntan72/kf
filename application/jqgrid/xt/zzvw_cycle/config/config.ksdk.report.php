<?php
	$columnMap = array(
		'Cover'=>array(
			'columns'=>array(
				'cycle_name'=>'4,C',
				'creator'=>'4,G',
				'start_date'=>'6,G',
				'end_date'=>'7,G',
				'release'=>'8,G'
			)
		),
		'default'=>array(
			'start_row'=>2,
			'columns'=>array(
				'code'=>'B',
				'testcase_priority'=>'C',
				'summary'=>'D',
				'testcase_module'=>'E',
				'steps'=>'F',
				'precondition'=>'G',
				'expected_result'=>'H',
				'platform'=>'I',
				'os'=>'J',
				'compiler'=>'K',
				'build_target'=>'L',
				'build_result'=>'Q',
				'result_type'=>'R',
				'tester'=>'S',// from cte
				'jiraInfo'=>'T',
				'comment'=>'U',
				'finish_time'=>'V'
			),
			'merge_columns'=>array(
				'code'=>'B',
				'testcase_priority'=>'C',
				'summary'=>'D',
				'testcase_module'=>'E',
				'steps'=>'F',
				'precondition'=>'G',
				'expected_result'=>'H',
			)
		)
	)

?>