<?php
    use util\Cache;
	use util\Other;
	use util\Visit;

	$GLOBALS['CLIENT'] = Visit::getClientInfo();

    /************************* 사이트 전역 정보 (캐싱) *************************/
	$cache_info = array(
		"folder" => "Etc",                                          // 서비스명
		"file_name" => "getList",                 					// 서비스 메소드
		"cache_type" => "",                                   		// 타입에 따른 캐싱 파일 분기
		"ttl" => 3600 * 24                                          // 캐싱 유효 시간
	);
	$query_option = array(
		"category" => NULL,	
		"type" => NULL,	
		"status" => "1",	
	);
	$cache_etc_list = Cache::getCacheData($cache_info, $query_option);

    for($i = 0; $i < count($cache_etc_list); $i++)
	{
		$etc = $cache_etc_list[$i];
		$type = $etc['type'];

		if(empty($GLOBALS[$type]))
		{	// 부모 배열이 비어있는 경우 생성
			$GLOBALS[$type] = array();
		}
		$key = $etc['key'];
		$GLOBALS[$type][$key] = $etc['value'];
    }
    

    /************************* ETC 중분류 목록 가져오기 - 관리자 사이드바 (캐싱) *************************/
    $cache_info = array(
		"folder" => "Etc",                                          // 서비스명
		"file_name" => "getTypeList",                 					// 서비스 메소드
		"cache_type" => "",                                   		// 타입에 따른 캐싱 파일 분기
		"ttl" => 3600 * 24                                          // 캐싱 유효 시간
	);
	$cache_etc_type_list = Cache::getCacheData($cache_info, NULL);



	/************************* 관리자 페이지 목록 가져오기 *************************/
	$cache_info = array(
		"folder" => "ManagePage",                                          // 서비스명
		"file_name" => "getListAll",                 					// 서비스 메소드
		"cache_type" => "",                                   		// 타입에 따른 캐싱 파일 분기
		"ttl" => 3 * 3600 * 24                                          // 캐싱 유효 시간
	);
	$cache_manage_page_list = Cache::getCacheData($cache_info, NULL);



	// 접근관련 변수입니다. --------------------------------------------
	$session_user_idx = getSessionUserIdx();
	$reg_user_auth = $session_user_idx['auth_level'];
    $user_idx = empty($_SESSION['login_user']) ? false : $session_user_idx["user_{$reg_user_auth}_idx"];
	
	// [파라미터] 개인회원 키, 기업회원 키
	$user_common_idx = $session_user_idx['user_common_idx'];
	$user_company_idx = $session_user_idx['user_company_idx'];
	$user_admin_idx = $session_user_idx['user_admin_idx'];

	$is_common = isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === "common" ? true : false;
	$is_company = isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === "company" ? true : false;
	$is_admin = isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === "admin" ? true : false;




