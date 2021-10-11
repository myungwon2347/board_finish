<?php
namespace util; 

require_once "{$_SERVER['DOCUMENT_ROOT']}/config.php";

use \Monolog\Logger;
use \Monolog\Formatter\LineFormatter;
use \Monolog\Handler\StreamHandler;

class Log 
{/*
	날짜별, 유저별(ERP), 액션별로 나눔.
	2021.05.25 / By.Chungwon	
*/
	public static function write($data, $log_type = "debug", $db_action_type = NULL)
	{
		/*********************** 기본 파라미터 세팅 ***********************/
		$logger = new Logger('log');
		// 액션 명
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "No Action";
		// 파라미터
		$params = array([$_REQUEST, $_FILES]);
		// 데이터 타입이 array인지 확인 - 타입에 따라 context or message 사용
		$is_context = strtolower(gettype($data)) === 'array' ? "%context%" : "%message%";
		/*********************** 기본 파라미터 세팅 끝 ***********************/
		/*********************** 접속유저 분기 (ERP) ***********************/
		// 접속 IP
		$user_ip = $GLOBALS['CLIENT']['ip'];
		// 유저 정보 (출력용) = ""인 경우 ERP 회원이 아님
		$str_user_info = "";
		
		if(isset($_SESSION['login_user']))
		{// 로그인 유저인 경우
			if($_SESSION['login_user']['auth_level'] === "admin")
			{// 권한이 관리자인 경우 (ERP 유저)
				$str_user_info = " [{$_SESSION['login_user']['name']}({$_SESSION['login_user']['idx']})]";
			}
		}else
		{// 로그인 유저가 아닌 경우
			// 사내 IP 목록
			if(isset($GLOBALS['SITE']['SERVER_IP_LIST']))
			{
				$inner_ip_list = explode('|', $GLOBALS['SITE']['SERVER_IP_LIST']);

				if(Text::isInclude($inner_ip_list, $user_ip))
				{// 사내 IP 목록에 필터링 된 경우
					// ERP 유저로 판별
					$str_user_info = " [내부직원(비로그인)]";
				}	
			}
		}
		// 유저 auth_level
		$user_auth_level = isset($_SESSION['login_user']) ? "[{$_SESSION['login_user']['auth_level']}]" : "";
		
		

		/*********************** 접속유저 분기 끝 (ERP) ***********************/

		$line_formatter = false;
		if($log_type === "error")
		{	// ERROR 로그
			$line_formatter = "[%datetime%] [\"{$_SERVER['PHP_SELF']}\"] [{$action}] [{$user_ip}]{$str_user_info}\n{$is_context}\n\n%context%\n\n\n";

		}else if($log_type === "database")
		{	// DB 로그
			if($GLOBALS['IS_DEVELOP'])
			{	// 개발 모드 인 경우에만
				$line_formatter = "[%datetime%] [\"{$_SERVER['PHP_SELF']}\"] [{$action}] [{$user_ip}]{$str_user_info}\n{$is_context}\n\n";
			}
		}else
		{	// DEBUG 로그
			if($GLOBALS['IS_DEVELOP'])
			{	// 개발 모드 인 경우에만
				$line_formatter = "[%datetime%] [\"{$_SERVER['PHP_SELF']}\"] [{$action}] [{$user_ip}]{$str_user_info}\n{$is_context}\n\n\n";
			}
		}
		if($line_formatter === false)
		{
			return false;
		}
		// 날짜별 분리
		$date_folder_link = "/" . date("Y년 m월 d일");
		// 유저별 분리 (ERP) - 비회원은 고객
		$erp_folder_link = $str_user_info === "" ? "" : "/erp";
		// 액션별 분리
		$db_action_folder_link = is_null($db_action_type) ? "/{$log_type}" : "/{$db_action_type}";

		$file_name = "{$GLOBALS['PATH']['SERVER_ROOT']}{$GLOBALS['PREFIX']['LOG']}{$date_folder_link}{$erp_folder_link}{$db_action_folder_link}.log";

		$handler = new StreamHandler($file_name);
		$handler -> setFormatter(new LineFormatter($line_formatter, null, true, true));
		$logger -> pushHandler($handler);		
		strtolower(gettype($data)) === 'array' ? $logger -> addDebug('', $data, $params) : $logger -> addInfo($data, $params);
	}
}
