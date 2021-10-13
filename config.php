<?php
	date_default_timezone_set('Asia/Seoul');

	// 배포 상태
	$GLOBALS['IS_DEVELOP'] = true;
	// 컨텍스트 패스
	$GLOBALS['CONTEXT_PATH'] = ''; 
	// DB 정보
	$GLOBALS['DB'] = array(	
		'HOST' => 'localhost',
		'ID' => 'root',
		'PW' => '6a48ppsc.,',
		'NAME' => 'test'
	);
	// 접두사 정보
	$GLOBALS['PREFIX'] = array(	
		'FRONT' => '/view',
		'MOBILE' => '/m',

		'ADMIN' => '/admin',
		'COMMON' => '/common',
		'COMPANY' => '/company',

		'MYPAGE' => '/mypage',
		'FILE' => '/upload',
		'LOG' => '/log',
	);
	// PATH 정보
	$GLOBALS['PATH'] = array(	
		'RESOURCES' => $CONTEXT_PATH . "/resources",
		'HTTP_ROOT' => $CONTEXT_PATH . "",
		'SERVER_ROOT' => $_SERVER['DOCUMENT_ROOT'] . $CONTEXT_PATH,
	);

	require_once $PATH['SERVER_ROOT'] . "/init.php";

	require_once $PATH['SERVER_ROOT'] . "/global/method.php";
	require_once $PATH['SERVER_ROOT'] . "/global/variable.php";
	require_once $PATH['SERVER_ROOT'] . "/global/handler.php";