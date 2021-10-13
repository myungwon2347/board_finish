<?php	
	namespace service;
	/*
		관리자 - 유저 컨트롤러
		2021.08.24 / By. Chungwon
	*/
    use util\DB;
	use util\Log;
	use util\JWT_S;

    /************************************************* 페이지 정보 세팅 *************************************************/
	require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    $response_params = array();
	$action = $_REQUEST['action'];

    /************************************************* API 정보 세팅 *************************************************/
	$target_flag = "Member"; 			// 편의상 API의 ID 키
	$target_table = 'member'; 			// 실제 DB 테이블 명
	$target_idx_name = "member_idx"; 	// 데이터 PK 키 이름
    $target_name = "회원"; 				// API 명명 (로그 및 출력용)
    
	/************************************************* 접근권한 *************************************************/
	if($action !== "login")
	{
		// 로그인 유저만 접근 가능
		if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
		// 관리자 유저만 접근 가능
		if(apiErrorCheck($is_admin, "관리자 유저만 접근 가능합니다.")) { return; }
	}
	


/*****************************************************************************************************************************************************************************/
if($action === "")
{// 액션 파라미터가 비어있는 경우
	if(apiErrorCheck(true, "액션 파라미터가 없습니다.\n고객센터에 문의주세요.")){ return; }
}
/**************************************************************************** INSERT & UPDATE ****************************************************************************/
else if($action === "upload{$target_flag}")
{   /*	
		등록 및 수정
		2021.08.25 / rank.Chungwon
    */

    /************************************************* 파라미터 수신 및 파싱 *************************************************/
	// 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		$target_idx_name, "reg_status", "rank", 
	));
    // 검색 변수 중, 숫자 값 넣기 (자동 쿼리문 생성에 쓰임)
    $request['numberList'] = array("reg_status");

    // 테이블 메인 값 설정
    $request['table'] = $target_table;
	$request['target_idx'] = isset($request[$target_idx_name]) ? $request[$target_idx_name] : NULL;
    /************************************************* 파라미터 수신 및 파싱 끝 *************************************************/



    /************************************************* 필터링 *************************************************/
	// if(apiErrorCheck(isset($request['name']) && $request['name'] !== "", "영화 이름을 입력해주세요.")) { return; }
	// if(apiErrorCheck(isset($request['price']) && $request['price'] !== "", "가격을 입력해주세요.")) { return; }
    /************************************************* 필터링 끝 *************************************************/



    /************************************************* DB - 분기 처리 *************************************************/
	unset($request[$target_idx_name]); // 수신 키 제거 (ex. board_idx)

	if(isset($request['target_idx']) && $request['target_idx'] !== "null")
	{/********** UPDATE 로직 **********/ 

        // 쿼리에 들어가는 값을 제외하고 모두 unset
        // unset($request['plan_check']);

		// DB UPDATE 쿼리 실행
		$update_state = Member::action("update", $request);
		if(apiErrorCheck($update_state, "{$target_name} 데이터 수정 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	}else
	{/********** INSERT 로직 **********/ 

        // 쿼리에 들어가는 값을 제외하고 모두 unset
        // unset($request['plan_check']);

		// DB INSERT 쿼리 실행
		$request['target_idx'] = Member::action("insert", $request);
		if(apiErrorCheck($request['target_idx'], "{$target_name} 데이터 등록 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	}
    /************************************************* DB - 분기 처리 끝 *************************************************/
	
    
	/************************************************* 결과 값 리턴 *************************************************/
	$response_params["insert_idx"] = $request['target_idx'];
    $response_params[$target_idx_name] = $request['target_idx'];
}
else if($action === "findPasswordReset")
{   /*	
		등록 및 수정
		2021.08.25 / rank.Chungwon
    */

	// 최근 로그인 날짜 변경
	$update_status = Member::action("update", array(
		'latest_login_date' => "NOW()",
		'target_idx' => $login_user['idx'],
		'numberList' => array("latest_login_date"),
	));

    /************************************************* 파라미터 수신 및 파싱 *************************************************/
	// 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		$target_idx_name, "reg_status", "rank", 
	));
    // 검색 변수 중, 숫자 값 넣기 (자동 쿼리문 생성에 쓰임)
    $request['numberList'] = array("reg_status");

    // 테이블 메인 값 설정
    $request['table'] = $target_table;
	$request['target_idx'] = isset($request[$target_idx_name]) ? $request[$target_idx_name] : NULL;
    /************************************************* 파라미터 수신 및 파싱 끝 *************************************************/



    /************************************************* 필터링 *************************************************/
	// if(apiErrorCheck(isset($request['name']) && $request['name'] !== "", "영화 이름을 입력해주세요.")) { return; }
	// if(apiErrorCheck(isset($request['price']) && $request['price'] !== "", "가격을 입력해주세요.")) { return; }
    /************************************************* 필터링 끝 *************************************************/



    /************************************************* DB - 분기 처리 *************************************************/
	unset($request[$target_idx_name]); // 수신 키 제거 (ex. board_idx)

	if(isset($request['target_idx']) && $request['target_idx'] !== "null")
	{/********** UPDATE 로직 **********/ 

        // 쿼리에 들어가는 값을 제외하고 모두 unset
        // unset($request['plan_check']);

		// DB UPDATE 쿼리 실행
		$update_state = Member::action("update", $request);
		if(apiErrorCheck($update_state, "{$target_name} 데이터 수정 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	}else
	{/********** INSERT 로직 **********/ 

        // 쿼리에 들어가는 값을 제외하고 모두 unset
        // unset($request['plan_check']);

		// DB INSERT 쿼리 실행
		$request['target_idx'] = Member::action("insert", $request);
		if(apiErrorCheck($request['target_idx'], "{$target_name} 데이터 등록 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	}
    /************************************************* DB - 분기 처리 끝 *************************************************/
	
    
	/************************************************* 결과 값 리턴 *************************************************/
	$response_params["insert_idx"] = $request['target_idx'];
    $response_params[$target_idx_name] = $request['target_idx'];
}

	
	
/**************************************************************************** DELETE ****************************************************************************/

/**************************************************************************** SELECT DETAIL ****************************************************************************/
else if($action === "login")
{   /*
		로그인
		2021.08.23 / By.Chungwon
	*/

    /************************************************* 파라미터 수신 *******************************************/
	// 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		"id", "password",
	));
	
	if($request['id'] === "fitsoft0319" && $request['password'] === "fit0319!")
	{// 슈퍼관리자 모드
		$_SESSION['login_user'] = array(
			"name" => "슈퍼관리자",
			"auth_level" => "admin",
		);

		$response_params['login_status'] = true;
		return;
	}
	/************************************************* 재정의 *************************************************/
	$request['password'] = JWT_S::encode($request['password']);

	/************************************************* 필터링 *************************************************/
	if(apiErrorCheck(isset($request['id']) && isset($request['password']), "아이디와 패스워드를 입력해주세요.")){ return; }


	/************************************************* DB - SELECT DETAIL *************************************/
	$login_user = Member::login($request);

	// SQL 에러
    if(apiErrorCheck($login_user, "로그인 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	// 회원없음
	if(apiErrorCheck(empty($login_user) === false && $login_user, "등록된 정보가 없습니다.\n아이디나 비밀번호를 다시 확인해주세요.")) { return; }

	// 세션 저장
	$_SESSION['login_user'] = $login_user;

	if($_SESSION['login_user']['auth_level'] === "admin")
	{// 관리자 유저인 경우

		// 관리자 로그인 이력 기록하기
		$insert_idx = Member::action("insert", array(
			'table' => "login_history",
			'login_user_idx' => $login_user['idx'],
			'ip' => $_SERVER['REMOTE_ADDR'],
			'auth_level' => $_SESSION['login_user']['auth_level'],
		));
	}
	
	// 최근 로그인 날짜 변경
	$update_status = Member::action("update", array(
		'latest_login_date' => "NOW()",
		'target_idx' => $login_user['idx'],
		'numberList' => array("latest_login_date"),
	));

	/************************************************* 리턴 *************************************************/
	$response_params['login_status'] = true;
}
else if($action === "get{$target_flag}")
{   /*
		상세 조회
		2021.08.25 / By.Chungwon
	*/

    /************************************************* 파라미터 수신 *****************************************/
	// 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		$target_idx_name,
	));
	$request['target_idx'] = $request[$target_idx_name];	


    /************************************************* 필터링 *************************************************/    
	if(apiErrorCheck(isset($request[$target_idx_name]), "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }


	/************************************************* DB - SELECT LIST ***************************************/
	$action = $action . "Admin";
	$sql_result = Member::$action($request);
    if(apiErrorCheck($sql_result, "{$target_name} 상세 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* 리턴 **************************************************/
	$response_params['data_list'] = array($sql_result);
}



/**************************************************************************** SELECT LIST ****************************************************************************/
else if($action === "getList{$target_flag}")
{   /*
        목록 조회
        2021.08.24 / By.Chungwon
    */

    /************************************************* 파라미터 수신 *************************************************/
	// 페이징 및 정렬
    $limit = intval(paramVaildCheck("data_render_count", 20));
	$offset = (intval(paramVaildCheck("page_selected_idx", 1)) - 1) * $limit;
    $sort_list = paramVaildCheck("sort_list", "idx desc");

    // 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		"id", "nickname", "reg_status", "rank", "insert_date_start", "insert_date_end",
	));

	/************************************************* DB - SELECT LIST *************************************************/
	$action = $action . "Admin";
    $sql_result = Member::$action($request, $sort_list, $limit, $offset);
    if(apiErrorCheck($sql_result, "{$target_name} 목록 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* 리턴 *************************************************/
    $response_params['data_list'] = $sql_result['list'];
    $response_params['data_count'] = $sql_result['count'];
}



/**************************************************************************** UTIL ****************************************************************************/
else if($action === 'logout')
{	// 로그아웃 (2020.06.04 / By.Chungwon)
	session_destroy();
}
else if($action === "cache_reset")
{	/*	캐시 초기화
		2020.12.05 / By.Chungwon
	*/

	/************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }
	// 관리자 유저만 접근 가능
	if(apiErrorCheck($is_admin, "관리자만 접근 가능한 서비스입니다.")) { return; }

    /************************************************* 파라미터 수신 *************************************************/
    // 페이징 및 정렬
	$dir_name = paramVaildCheck("dir_name", "");

    $log_path = "{$GLOBALS['PATH']['SERVER_ROOT']}/cache{$dir_name}";
	
	$result = rrmdir($log_path);

    /************************************************* DB - UPDATE *************************************************/
	// $response_params['update_state'] = UserAdmin::updateUser($target_idx, $user_type, $reg_status);
	// if(apiErrorCheck($response_params['update_state'], "{$target_name} 데이터 수정 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	$response_params['result'] = $result;
}

/********************************************************************************************************************************************/
else{
	if(apiErrorCheck(true, "액션 파라미터가 없습니다.\n고객센터에 문의주세요.")){ return; }
}
http_response_code(200);
echo json_encode($response_params);


