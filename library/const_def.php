<?php
defined('ROOT_PATH')
    || define('ROOT_PATH', realpath(dirname(__FILE__) . '/..'));
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));
defined('SCRIPT_ROOT')
    || define('SCRIPT_ROOT', realpath(APPLICATION_PATH . '/script'));

defined('LOG_ROOT')
    || define('LOG_ROOT', realpath(APPLICATION_PATH . '/log'));
defined('UPLOAD_ROOT')
    || define('UPLOAD_ROOT', 	ROOT_PATH . '/public/upload');


defined("DISPLAY_STATUS_VIEW") || define("DISPLAY_STATUS_VIEW", 1);
defined("DISPLAY_STATUS_EDIT") || define("DISPLAY_STATUS_EDIT", 2);
defined("DISPLAY_STATUS_NEW") || define("DISPLAY_STATUS_NEW", 3);
defined("DISPLAY_STATUS_QUERY") || define("DISPLAY_STATUS_QUERY", 4);


defined('ERROR_OK') || define('ERROR_OK', 0);
defined('ERROR_UNKNOWN') || define('ERROR_UNKNOWN', 1);
defined('ERROR_INVALID_FILE') || define('ERROR_INVALID_FILE', 2);
defined('ERROR_INVALID_AUTHORITY') || define('ERROR_INVALID_AUTHORITY', 3);
defined('ERROR_INVALID_PRIVILEGE') || define('ERROR_INVALID_PRIVILEGE', 4);
defined('ERROR_INVALID_DATA') || define('ERROR_INVALID_DATA', 5);

defined('ERROR_APP_BASE') || define('ERROR_APP_BASE', 1000);
defined('ERROR_DATA_DUPLICATE') || define('ERROR_DATA_DUPLICATE', ERROR_APP_BASE + 1);
defined('ERROR_DATA_CANTEMPTY') || define('ERROR_DATA_CANTEMPTY', ERROR_APP_BASE + 2);
defined('ERROR_RESPONSE_INVALID_MODULE') || define('ERROR_RESPONSE_INVALID_MODEL', ERROR_APP_BASE + 3);
defined('ERROR_RESPONSE_INVALID_ACTION') || define('ERROR_RESPONSE_INVALID_ACTION', ERROR_APP_BASE + 4);
defined('WARNING_EXIST_EDITING_VERSION') || define('WARNING_EXIST_EDITING_VERSION', ERROR_APP_BASE + 5);

defined('ROLE_TESTER') || define('ROLE_TESTER', 1);
defined('ROLE_COORDINATOR') || define('ROLE_COORDINATOR', 2);
defined('ROLE_ADMIN') || define('ROLE_ADMIN', 3);
defined('ROLE_REVIEWER') || define('ROLE_REVIEWER', 4);
defined('ROLE_PM') || define('ROLE_PM', 5);
defined('ROLE_DEV') || define('ROLE_DEV', 6);
defined('ROLE_VISITOR') || define('ROLE_VISITOR', 7);
defined('ROLE_GUEST') || define('ROLE_GUEST', 8);

defined('BOOL_TRUE') || define('BOOL_TRUE', 1);
defined('BOOL_FALSE') || define('BOOL_FALSE', 2);

defined('ISACTIVE_ACTIVE') || define('ISACTIVE_ACTIVE', 1);
defined('ISACTIVE_INACTIVE') || define('ISACTIVE_INACTIVE', 2);

defined('PRJ_STATUS_ONGOING') || define('PRJ_STATUS_ONGOING', 1);
defined('PRJ_STATUS_COMPLETED') || define('PRJ_STATUS_COMPLETED', 2);

defined('EDIT_STATUS_PUBLISHED') || define('EDIT_STATUS_PUBLISHED', 1);
defined('EDIT_STATUS_GOLDEN') || define('EDIT_STATUS_GOLDEN', 2);
defined('EDIT_STATUS_EDITING') || define('EDIT_STATUS_EDITING', 3);
defined('EDIT_STATUS_REVIEWING') || define('EDIT_STATUS_REVIEWING', 4);
defined('EDIT_STATUS_REVIEWED') || define('EDIT_STATUS_REVIEWED', 5);
defined('EDIT_STATUS_REVIEW_WAITING') || define('EDIT_STATUS_REVIEW_WAITING', 6);

defined('LINK_STATUS_ADD') || define('LINK_STATUS_ADD', 1);
defined('LINK_STATUS_UPDATED') || define('LINK_STATUS_UPDATED', 2);
defined('LINK_STATUS_REMOVE') || define('LINK_STATUS_REMOVE', 3);

defined('REL_CATEGORY_OFFICIAL') || define('REL_CATEGORY_OFFICIAL', 1);
defined('REL_CATEGORY_DAILY') || define('REL_CATEGORY_DAILY', 2);
defined('REL_CATEGORY_TEMPLE') || define('REL_CATEGORY_TEMPLE', 3);

defined('CYCLE_STATUS_ONGOING') || define('CYCLE_STATUS_ONGOING', 1);
defined('CYCLE_STATUS_FROZEN') || define('CYCLE_STATUS_FROZEN', 2);

defined('CYCLE_TYPE_SMOKE') || define('CYCLE_TYPE_SMOKE', 1);
defined('CYCLE_TYPE_BAT') || define('CYCLE_TYPE_BAT', 2);
defined('CYCLE_TYPE_FUNCTION') || define('CYCLE_TYPE_FUNCTION', 3);
defined('CYCLE_TYPE_FULL') || define('CYCLE_TYPE_FULL', 4);
defined('CYCLE_TYPE_SANITY') || define('CYCLE_TYPE_SANITY', 5);
defined('CYCLE_TYPE_DAILY') || define('CYCLE_TYPE_DAILY', 6);
defined('CYCLE_TYPE_TEMPLATE') || define('CYCLE_TYPE_TEMPLATE', 7);

defined('RESULT_TYPE_BLANK') || define('RESULT_TYPE_BLANK', 0);
defined('RESULT_TYPE_PASS') || define('RESULT_TYPE_PASS', 1);
defined('RESULT_TYPE_FAIL') || define('RESULT_TYPE_FAIL', 2);
defined('RESULT_TYPE_NT') || define('RESULT_TYPE_NT', 3);
defined('RESULT_TYPE_NA') || define('RESULT_TYPE_NA', 4);
defined('RESULT_TYPE_NS') || define('RESULT_TYPE_NS', 5);
defined('RESULT_TYPE_SKIP') || define('RESULT_TYPE_SKIP', 6);
defined('RESULT_TYPE_TIMEOUT') || define('RESULT_TYPE_TIMEOUT', 7);
defined('RESULT_TYPE_NO_LOG') || define('RESULT_TYPE_NO_LOG', 8);
defined('RESULT_TYPE_WARNING') || define('RESULT_TYPE_WARNING', 9);
defined('RESULT_TYPE_OPEN_SERIAL_ERROR') || define('RESULT_TYPE_OPEN_SERIAL_ERROR', 10);
defined('RESULT_TYPE_NO_SERIAL_OUTPUT') || define('RESULT_TYPE_NO_SERIAL_OUTPUT', 11);
defined('RESULT_TYPE_DOWNLOAD_IMAGE_ERROR') || define('RESULT_TYPE_DOWNLOAD_IMAGE_ERROR',12);
defined('RESULT_TYPE_INTERACT_FILE_ISSUE') || define('RESULT_TYPE_INTERACT_FILE_ISSUE', 13);
defined('RESULT_TYPE_RW_SERIAL_ERROR') || define('RESULT_TYPE_RW_SERIAL_ERROR', 14);
defined('RESULT_TYPE_ABORT') || define('RESULT_TYPE_ABORT', 15);

defined('TESTCASE_TYPE_LINUX_BSP') || define('TESTCASE_TYPE_LINUX_BSP', 1);
defined('TESTCASE_TYPE_CODEC') || define('TESTCASE_TYPE_CODEC', 2);
defined('TESTCASE_TYPE_WINCE_BSP') || define('TESTCASE_TYPE_WINCE_BSP', 3);
defined('TESTCASE_TYPE_ANDROID_APPLICATION') || define('TESTCASE_TYPE_ANDROID_APPLICATION', 4);
defined('TESTCASE_TYPE_ANDROID_USER_CASE') || define('TESTCASE_TYPE_ANDROID_USER_CASE', 5);
defined('TESTCASE_TYPE_AROBOT_CASE') || define('TESTCASE_TYPE_AROBOT_CASE', 6);
defined('TESTCASE_TYPE_WIRELESS_CHARGE') || define('TESTCASE_TYPE_WIRELESS_CHARGE', 7);
defined('TESTCASE_TYPE_MQX') || define('TESTCASE_TYPE_MQX', 8);
defined('TESTCASE_TYPE_GENERAL') || define('TESTCASE_TYPE_GENERAL', 9);
defined('TESTCASE_TYPE_USB') || define('TESTCASE_TYPE_USB', 10);
defined('TESTCASE_TYPE_FAS') || define('TESTCASE_TYPE_FAS', 14);
defined('TESTCASE_TYPE_KSDK_KSV') || define('TESTCASE_TYPE_KSDK_KSV', 15);
defined('TESTCASE_TYPE_KSDK_DEMO') || define('TESTCASE_TYPE_KSDK_DEMO', 16);
defined('TESTCASE_TYPE_GDL_ERIK') || define('TESTCASE_TYPE_GDL_ERIK', 17);
defined('TESTCASE_TYPE_KIBBLE') || define('TESTCASE_TYPE_KIBBLE', 18);
defined('TESTCASE_TYPE_PEXIMX') || define('TESTCASE_TYPE_PEXIMX', 19);
defined('TESTCASE_TYPE_DDR') || define('TESTCASE_TYPE_DDR', 20);
defined('TESTCASE_TYPE_KSDK_EXAMPLE') || define('TESTCASE_TYPE_KSDK_EXAMPLE', 21);
defined('TESTCASE_TYPE_AVB') || define('TESTCASE_TYPE_AVB', 22);
defined('TESTCASE_TYPE_KSDK_RTOS') || define('TESTCASE_TYPE_KSDK_RTOS', 23);

defined('TESTCASE_SOURCE_FSL_MAD') || define('TESTCASE_SOURCE_FSL_MAD', 1);
defined('TESTCASE_SOURCE_FSL_OTHER') || define('TESTCASE_SOURCE_FSL_OTHER', 2);
defined('TESTCASE_SOURCE_MSFT') || define('TESTCASE_SOURCE_MSFT', 3);
defined('TESTCASE_SOURCE_LTP') || define('TESTCASE_SOURCE_LTP', 4);
defined('TESTCASE_SOURCE_WEB') || define('TESTCASE_SOURCE_WEB', 5);
defined('TESTCASE_SOURCE_ISO') || define('TESTCASE_SOURCE_ISO', 6);
defined('TESTCASE_SOURCE_SUMSUNG') || define('TESTCASE_SOURCE_SUMSUNG', 7);
defined('TESTCASE_SOURCE_OPEN_SOURCE') || define('TESTCASE_SOURCE_OPEN_SOURCE', 8);
defined('TESTCASE_SOURCE_SHARP') || define('TESTCASE_SOURCE_SHARP', 9);
defined('TESTCASE_SOURCE_CUSTOMER') || define('TESTCASE_SOURCE_CUSTOMER', 10);

defined('TESTCASE_CATEGORY_FUNCTION') || define('TESTCASE_CATEGORY_FUNCTION', 1);
defined('TESTCASE_CATEGORY_PERFORMANCE') || define('TESTCASE_CATEGORY_PERFORMANCE', 2);
defined('TESTCASE_CATEGORY_STRESS') || define('TESTCASE_CATEGORY_STRESS', 3);
defined('TESTCASE_CATEGORY_CONFORMANCE') || define('TESTCASE_CATEGORY_CONFORMANCE', 4);
defined('TESTCASE_CATEGORY_EXCEPTIONAL') || define('TESTCASE_CATEGORY_EXCEPTIONAL', 5);
defined('TESTCASE_CATEGORY_SCENARIO') || define('TESTCASE_CATEGORY_SCENARIO', 6);
defined('TESTCASE_CATEGORY_FUZZ') || define('TESTCASE_CATEGORY_FUZZ', 7);
defined('TESTCASE_CATEGORY_SYSTEM') || define('TESTCASE_CATEGORY_SYSTEM', 8);
defined('TESTCASE_CATEGORY_CERTIFICATION') || define('TESTCASE_CATEGORY_CERTIFICATION', 9);
defined('TESTCASE_CATEGORY_COMPATIBILITY') || define('TESTCASE_CATEGORY_COMPATIBILITY', 10);
defined('TESTCASE_CATEGORY_SMOKE') || define('TESTCASE_CATEGORY_SMOKE', 11);
defined('TESTCASE_CATEGORY_KEYSTRESS') || define('TESTCASE_CATEGORY_KEYSTRESS', 12);
defined('TESTCASE_CATEGORY_POWERCONSUMPTION') || define('TESTCASE_CATEGORY_POWERCONSUMPTION', 13);

defined('TESTCASE_MODULE_UNIFORMED_CODEC_TRICKMODES') || define('TESTCASE_MODULE_UNIFORMED_CODEC_TRICKMODES', 507);
defined('TESTCASE_MODULE_FAS_TRICKMODES') || define('TESTCASE_MODULE_FAS_TRICKMODES', 467);

defined('TESTCASE_PRIORITY_P1') || define('TESTCASE_PRIORITY_P1', 1);
defined('TESTCASE_PRIORITY_P2') || define('TESTCASE_PRIORITY_P2', 2);
defined('TESTCASE_PRIORITY_P3') || define('TESTCASE_PRIORITY_P3', 3);
defined('TESTCASE_PRIORITY_P4') || define('TESTCASE_PRIORITY_P4', 4);

defined('PRJ_TYPE_MQX') || define('PRJ_TYPE_MQX', 1);

defined('BOARD_TYPE_TOWER') || define('BOARD_TYPE_TOWER', 1);

defined('AUTO_LEVEL_AUTO') || define('AUTO_LEVEL_AUTO', 1);
defined('AUTO_LEVEL_MANUAL') || define('AUTO_LEVEL_MANUAL', 2);
defined('AUTO_LEVEL_PARTIAL_AUTO') || define('AUTO_LEVEL_PARTIAL_AUTO', 3);
defined('AUTO_LEVEL_PARTIAL_TO_AUTO') || define('AUTO_LEVEL_PARTIAL_TO_AUTO', 4);

defined('ASSIGN_TYPE_ASSIGN') || define('ASSIGN_TYPE_ASSIGN', 1);
defined('ASSIGN_TYPE_FORWARD') || define('ASSIGN_TYPE_FORWARD', 2);

defined('TASK_RESULT_NORESULT') || define('TASK_RESULT_NORESULT', 0);
defined('TASK_RESULT_SUCCESS') || define('TASK_RESULT_SUCCESS', 1);
defined('TASK_RESULT_FAIL') || define('TASK_RESULT_FAIL', 2);
defined('TASK_RESULT_DEADLINE') || define('TASK_RESULT_DEADLINE', 3);

defined('TASK_TYPE_REVIEW') || define('TASK_TYPE_REVIEW', 1);
defined('TASK_TYPE_TEST') || define('TASK_TYPE_TEST', 2);

defined('TASK_PRIORITY_HIGH') || define('TASK_PRIORITY_HIGH', 1);
defined('TASK_PRIORITY_MIDDLE') || define('TASK_PRIORITY_MIDDLE', 2);
defined('TASK_PRIORITY_LOW') || define('TASK_PRIORITY_LOW', 3);

defined('ACTION_TYPE_DIALOG') || define('ACTION_TYPE_DIALOG', 1);
defined('ACTION_TYPE_NEWTAB') || define('ACTION_TYPE_NEWTAB', 2);
defined('ACTION_TYPE_NEWPAGE') || define('ACTION_TYPE_NEWPAGE', 3);

defined('UNIT_TYPE_TIME') || define('UNIT_TYPE_TIME', 1);

defined('DAILY_NOTE_TYPE_GENERAL') || define('DAILY_NOTE_TYPE_GENERAL', 1);
defined('DAILY_NOTE_TYPE_PRJ_TRACE') || define('DAILY_NOTE_TYPE_PRJ_TRACE', 2);

defined('HT_FL_LD') || define('HT_FL_LD', 1); //劳动合同
defined('HT_FL_CG') || define('HT_FL_CG', 2); // 采购合同
defined('HT_FL_XS') || define('HT_FL_XS', 3); //销售合同

defined('HT_XZ_CHANGQI') || define('HT_XZ_CHANGQI', 1);
defined('HT_XZ_ZHOUQI') || define('HT_XZ_ZHOUQI', 2);
defined('HT_XZ_BUCHONG') || define('HT_XZ_BUCHONG', 3);

defined('TESTER_BLANK') || define('TESTER_BLANK', 0);
defined('TESTER_DP') || define('TESTER_DP', 132);
defined('TESTER_APOLLO') || define('TESTER_APOLLO', 148);
defined('TESTER_CTE') || define('TESTER_CTE', 116);
defined('TESTER_GVB') || define('TESTER_GVB', 117);
defined('TESTER_SKYWALKER') || define('TESTER_SKYWALKER', 63);
defined('TESTER_FPT_USB') || define('TESTER_FPT_USB', 144);

defined('GROUP_LINUXBSP') || define('GROUP_LINUXBSP', 1);
defined('GROUP_CODEC') || define('GROUP_CODEC', 3);
defined('GROUP_MQX') || define('GROUP_MQX', 6);
defined('GROUP_KSDK') || define('GROUP_KSDK', 7);
defined('GROUP_ANDROID') || define('GROUP_ANDROID', 8);
defined('GROUP_FAS') || define('GROUP_FAS', 9);
defined('GROUP_USB') || define('GROUP_USB', 10);
defined('GROUP_KIBBLE') || define('GROUP_KIBBLE', 12);

defined('WITHOUT_STREAM') || define('WITHOUT_STREAM', 0);

defined('STREAM_TYPE_UNKNOWN') || define('STREAM_TYPE_UNKNOWN', 6);
defined('STREAM_FORMAT_CUSTOM') || define('STREAM_FORMAT_CUSTOM', 56);

defined('REQUEST_STATUS_WAITING') || define('REQUEST_STATUS_WAITING', 1);
defined('REQUEST_STATUS_RUNNING') || define('REQUEST_STATUS_RUNNING', 2);
defined('REQUEST_STATUS_FINISH') || define('REQUEST_STATUS_FINISH', 3);
defined('REQUEST_STATUS_STOP') || define('REQUEST_STATUS_STOP', 4);
defined('REQUEST_STATUS_NA') || define('REQUEST_STATUS_NA', 5);
defined('REQUEST_STATUS_INIT') || define('REQUEST_STATUS_INIT', 6);
defined('REQUEST_STATUS_REQUESTTIMEOUT') || define('REQUEST_STATUS_REQUESTTIMEOUT', 7);
defined('REQUEST_STATUS_TRIGGERTIMEOUT') || define('REQUEST_STATUS_TRIGGERTIMEOUT', 8);
defined('REQUEST_STATUS_WRONGTOKEN') || define('REQUEST_STATUS_WRONGTOKEN', 9);

?>
