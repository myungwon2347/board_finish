<?php
    session_start();
    
    require_once $PATH['SERVER_ROOT'] . "/library/vendor/autoload.php";

	spl_autoload_register(function($path)
	{
		require_once str_replace('\\', '/', $path) . '.php';
	});

	use util\Log;
	use util\Visit;
	use util\Client;

	$GLOBALS['CLIENT'] = Visit::getClientInfo();

	// if($ISDEVELOP)
	// {	// 개발 모드일 경우 로깅 파일 초기화
	// 	$deleteFile = "{$PATH['SERVER_ROOT']}{$PREFIX['LOG']}/check.log";
	// 	file_exists($deleteFile) ? unlink($deleteFile) : null;
	// }
