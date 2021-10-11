<?php
	function varDump($val)
	{// var_dump를 상단에 줄바꿈해서 보여주기
		echo '<pre>' . var_export($val, true) . '</pre>';
	}
    function setVal($value)
	{	// PHP에서 저장할 때
		return htmlspecialchars_decode(addslashes($value));
    }
    
	function getVal($value)
	{	// JS에서 사용할 때 (ex. Textarea)
		return get_magic_quotes_gpc() ? stripslashes($value) : $value;
    }
    
	function getHtml($value)
	{	// HTML 엘리먼트 안에 넣을 때 (ex. span, p 내부)
		$value = get_magic_quotes_gpc() ? stripslashes($value) : $value;

		return nl2br(htmlspecialchars($value));
    }
	function apiErrorCheck($status, $msg)
	{   /* API 에러 반환 로직 */
		if($status === false)
		{	// SQL 에러 발생
			http_response_code(400); 
			echo json_encode(array("result" => $msg)); 
			return true;
		}
		return false;
    }
	function paramVaildCheck($column, $default, $include_space = true)
	{   /* 파라미터 유효성 검사 (보안) */
		if(isset($_REQUEST[$column]))
		{	// 파라미터가 있는 경우
			$value = $_REQUEST[$column];
			
			if($include_space === false && $value === "")
			{
				return $default;
			}
			return setVal($value);
		}
		return $default;
    }
	function getListFromTotal($data_list, $get_list = NULL, $set_null = NULL)
	{/* 
		data_list 배열 리스트 중, get_list에 명시된 데이터만 반환
		JSON으로 오는 경우, paramVaildCheck 재정의 필요

		2021.06.04 / By.Chungwon
	*/
		$total_list = array();

		if(isset($get_list))
		{// 가져올 목록이 있는지 확인
	        foreach($get_list as $key) {

				if(isset($data_list[$key]))
				{// data_list 배열에 값이 있는 경우
					$value = setVal($data_list[$key]);
					$total_list[$key] = $value;
				}
				else if($set_null)
				{// data_list 배열에 값이 없는 경우 (default 값으로 '' 설정)
					$value = "";
					$total_list[$key] = $value;
				}
			}
		}

    	return $total_list;
    }
    
	function stringIncludeWrap($str, $wrap)
	{// 전달받은 배열(str)에 모두 쉼표 wrap 넣기
		$arr = explode(",", $str);
		$arr_temp = array();

		for($i = 0; $i < count($arr); $i++)
		{
			$item = $arr[$i];

			array_push($arr_temp, $wrap . $item . $wrap);
		}
		return join(",", $arr_temp);
    }
    
	function getSessionUserIdx()
    {   // 세션 유저 키 값 가져오기 (2020.06.13 / By.Chungwon)

        // 비어있다면 false
        if(empty($_SESSION['login_user'])) { return false; }

        // 기본값은 파라미터로 수신한 user_common_idx
        $user_common_idx = paramVaildCheck("user_common_idx", NULL);
        $user_company_idx = paramVaildCheck("user_company_idx", NULL);
        $user_admin_idx = paramVaildCheck("user_admin_idx", NULL);

        if($_SESSION['login_user']['auth_level'] === "common")
        {   // 개인회원 인 경우 -> 자신의 세션 키 값
            $user_common_idx = $_SESSION['login_user']['idx'];
        }
        else if($_SESSION['login_user']['auth_level'] === "company")
        {   // 기업회원 인 경우 -> 자신의 세션 키 값
            $user_company_idx = $_SESSION['login_user']['idx'];
		}
		else if($_SESSION['login_user']['auth_level'] === "admin")
        {   // 관리자 인 경우 -> 자신의 세션 키 값
            $user_admin_idx = $_SESSION['login_user']['idx'];
        }

        return array(
			"auth_level" => $_SESSION['login_user']['auth_level'],
            "user_common_idx" => $user_common_idx,
            "user_company_idx" => $user_company_idx,
            "user_admin_idx" => $user_admin_idx,
        );
	}

	function setLogFile()
	{// 로그 파일 리셋
		$log_path = "{$GLOBALS['PATH']['SERVER_ROOT']}{$GLOBALS['PREFIX']['LOG']}/database.log";
	
		if(file_exists($log_path))
		{
			unlink($log_path);
		}
	}
	function rrmdir($dir) 
	{// 디렉토리 삭제 (재귀) 
		foreach(glob($dir . '/*') as $file) 
		{ 
			if(is_dir($file)) 
			{
				rrmdir($file); 
			}
			else 
			{
				unlink($file); 
			}
		} 
		rmdir($dir);
		
		return true;
   	}

	function checkPageAccess($current_page, $manage_page_list)
    {// 현재페이지 접근 권한 확인 (2021.05.25 / By.chungwon)
		$is_access = true;
		
		for($i = 0; $i < count($manage_page_list); $i++) 
		{// 페이지 순회
            $temp_page = $manage_page_list[$i];
			$page_list = $temp_page['sub_menu'];

			if(count($page_list) < 1)
			{// 서브 메뉴가 없는 경우 (1차원 구조인 경우)
				// 로직 편의를 위해 2차원 구조로 변경
				$page_list = array($temp_page);
			}

			for($j = 0; $j < count($page_list); $j++) 
			{// 메뉴 순회
				$page = $page_list[$j];
				$page_link = $GLOBALS['PATH']['HTTP_ROOT'] . $page['prefix'] . $page['file_link'];

				if($current_page === $page_link)
				{// 현재 페이지인 경우
					// 유저의 페이지 권한 확인
					$is_access = checkUserPageAccess($page);

					// 페이지 권한이 없는 경우
					if($is_access === false){ break; }
				}
			}
        }
		return $is_access;
    }
	
	function checkUserPageAccess($page)
	{// 유저의 메뉴 접근 권한 확인 (2021.05.25 / By.chungwon)
		// 현재 페이지가 전달된 
		$is_access = true;

		if($page['auth_list'] !== "")
		{// 모든 접근 허용이 아닌 경우
			$is_access = false;

			if(isset($_SESSION['login_user']))
			{// 로그인이 된 경우 (비회원인 경우 접근금지)
				if(isset($_SESSION['login_user']['role']))
				{// role이 있는 경우
					$auth_list = explode(",", $page['auth_list']);

					if(count($auth_list) > 0)
					{// 접근 권한이 설정된 경우
						for($i = 0; $i < count($auth_list); $i++)
						{// 접근권한 확인
							$auth = $auth_list[$i];      

							if($_SESSION['login_user']['role'] === $auth)
							{// 역할에 권한이 있는 경우 접근 가능
								$is_access = true;
								break;
							}
						}
					}
				}
			}
			else
			{// 비회원인 경우, 경고창 띄우지않고 대쉬보드로 이동
				$auth_level = strtoupper($_SESSION['login_user']['role']);
				// $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $GLOBALS['PATH']['HTTP_ROOT'] . $GLOBALS['PREFIX'][$auth_level];
				header("Location: {$GLOBALS['PATH']['HTTP_ROOT']}{$GLOBALS['PREFIX'][$auth_level]}/page/login.php");
				// header("Location: {$GLOBALS['PATH']['HTTP_ROOT']}/");	
			}
		}
		return $is_access;
	}

	require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
	
	use util\Log;

	function mobileHandler()
	{
		$url = $_SERVER['REQUEST_URI'];

		$pc_url = $GLOBALS['PREFIX']['FRONT'] . $GLOBALS['PREFIX']['COMMON'];
		$m_url = $GLOBALS['PREFIX']['FRONT'] . $GLOBALS['PREFIX']['MOBILE'] . $GLOBALS['PREFIX']['COMMON'];

		// 올바른 URL
		$origin_url = $GLOBALS['CLIENT']['device_type'] === "MOBILE" ? $m_url : $pc_url;
		// 현재 URL
		$check_url = $GLOBALS['CLIENT']['device_type'] === "MOBILE" ? $pc_url : $m_url;

		if(strpos($url, $check_url) !== false)
		{// 모바일 접두사가 포함되지 않은 경우

			// 모바일 접두사 포함
			$url = str_replace($check_url, $origin_url, $url);
			// 리다이렉션
			header("Location: {$GLOBALS['PATH']['HTTP_ROOT']}{$url}");	
		}
		// if($GLOBALS['CLIENT']['device_type'] === "MOBILE")
		// {// 모바일로 접속한 경우

		// 	if(strpos($url, $check_url) !== false)
		// 	{// 모바일 접두사가 포함되지 않은 경우

		// 		// 모바일 접두사 포함
		// 		$url = str_replace($check_url, $origin_url, $url);
		// 		// 리다이렉션
		// 		header("Location: {$GLOBALS['PATH']['HTTP_ROOT']}{$url}");	
		// 	}
		// }else
		// {

		// 	if(strpos($url, $m_url) !== false)
		// 	{// 모바일 접두사가 포함되지 않은 경우

		// 		// 모바일 접두사 포함
		// 		$url = str_replace($m_url, $pc_url, $url);
		// 		// 리다이렉션
		// 		header("Location: {$GLOBALS['PATH']['HTTP_ROOT']}{$url}");	
		// 	}
		// }
	}




