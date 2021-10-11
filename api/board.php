<?php	
	namespace service;
	/*
		사용자 - 게시판 컨트롤러
		2021.08.31 / By. Chungwon
	*/
    use util\DB;
	use util\Log;
	use util\File;
	use util\Text;
	
    /************************************************* 페이지 정보 세팅 *************************************************/
	require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    $response_params = array();
	$action = $_REQUEST['action'];

    /************************************************* API 정보 세팅 *************************************************/
	$target_flag = "Board"; 			// 편의상 API의 ID 키
	$target_table = 'board'; 			// 실제 DB 테이블 명
	$target_idx_name = "board_idx"; 	// 데이터 PK 키 이름
    $target_name = "게시판"; 				// API 명명 (로그 및 출력용)
    

/*****************************************************************************************************************************************************************************/
if($action === "")
{// 액션 파라미터가 비어있는 경우
	if(apiErrorCheck(true, "액션 파라미터가 없습니다.\n고객센터에 문의주세요.")){ return; }
}
/**************************************************************************** INSERT & UPDATE ****************************************************************************/
else if($action === "upload{$target_flag}")
{   /*	
		등록 및 수정
		2021.08.27 / rank.Chungwon

		요청페이지: 게시판 > 상세페이지
    */

	/************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }

	/************************************************* 파라미터 수신 및 파싱 *************************************************/
	// 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		$target_idx_name, "type", "title", "content", "notice_status", "view_status", 
	));
    // 검색 변수 중, 숫자 값 넣기 (자동 쿼리문 생성에 쓰임)
    $request['numberList'] = array("notice_status", "view_status");

    // 테이블 메인 값 설정
    $request['table'] = $target_table;
	$request['target_idx'] = isset($request[$target_idx_name]) ? $request[$target_idx_name] : NULL;
	$request['reg_user_idx'] = $user_idx;	
    /************************************************* 파라미터 수신 및 파싱 끝 *************************************************/

	// 공지사항 접근권한
	if($_SESSION['login_user']['auth_level'] === "common")
	{// 일반유저인 경우
		// 공지상태 무조건 0
		$request['notice_status'] = 0;
	}

	// 게시글 접근권한
	if($request['type'] === "notice")
	{// 공지사항 게시글 (관리자만 작성 가능)

		// 관리자가 아닌 경우 접근제한
		if(apiErrorCheck($_SESSION['login_user']['auth_level'] === "admin", "관리자만 작성 가능합니다.")){ return; }
	}
	else if($request['type'] === "profile")
	{// 프로필 게시글 (관리자, 영업사원, 인증된 영업사원만 가능)

		// 관리자가 아닌 경우 접근제한
		if(apiErrorCheck($_SESSION['login_user']['rank'] !== "common", "일반유저는 작성할 수 없습니다.")){ return; }
	}


    /************************************************* 필터링 *************************************************/
	if(apiErrorCheck(isset($request['type']) && $request['type'] !== "", "게시판 분류를 선택해주세요.")) { return; }
	if(apiErrorCheck(isset($request['title']) && $request['title'] !== "", "제목을 입력해주세요.")) { return; }
	if(apiErrorCheck(isset($request['content']) && $request['content'] !== "", "내용을 입력해주세요.")) { return; }
    /************************************************* 필터링 끝 *************************************************/



    /************************************************* DB - 분기 처리 *************************************************/
	unset($request[$target_idx_name]); // 수신 키 제거 (ex. board_idx)

	if(isset($request['target_idx']) && $request['target_idx'] !== "null")
	{/********** UPDATE 로직 **********/ 
		
		$update_state = Board::action("update", $request);
		if(apiErrorCheck($update_state, "{$target_name} 데이터 수정 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	}else
	{/********** INSERT 로직 **********/ 

		$request['target_idx'] = Board::action("insert", $request);
		if(apiErrorCheck($request['target_idx'], "{$target_name} 데이터 등록 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
	}
    /************************************************* DB - 분기 처리 끝 *************************************************/
	

	/************************************************* 파일 업로드 *************************************************/
    $res_image_status = false;
	foreach($_FILES as $img_key => $files) 
	{// 수신한 파일 동적처리
		// 이미지 존재 확인
		$img_list = File::setFiles($files);

		if(isset($img_list))
		{   // 아래는 꼭 입력해주세요.
			$file_type = "image";
			$ref_table = $target_table;
			$ref_key = $img_key;
			$ref_idx = $request['target_idx'];
			
			// 유효성 검사 (파일크기 (24mb), 포맷 검사)
			// $img_error_msg = File::errorCheck($img_list, 24, array('png', 'jpg', 'jpeg', 'gif'));
			$img_error_msg = File::errorCheck($img_list, 24, array('zip', 'png', 'jpg', 'txt', 'ppt', 'pptx', 'pdf', 'doc', 'hwp', 'xlsx','jpeg','gif','bmp','BMP','JPG','JPEG','PNG','GIF', 'webp', 'WEBP', 'jfif', 'heic', 'JFIF', 'HEIC'));
			if(apiErrorCheck(gettype($img_error_msg) != "string", "{$img_error_msg}")){ return; }
			// 파일 DB 등록
			$img_insert_idx_list = File::insert($img_list, $file_type, $ref_table, $ref_key, $ref_idx);

            
            if(empty($img_insert_idx_list))
			{ 
				return; 
			}else
            {// ex. detail_iil - detail insert idx list
                if($ref_key === 'thumbnail' && $res_image_status === false)
                {// 썸네일 인 경우

                    // 대표 이미지 세팅
                    // Product::update($request['target_idx'], null, null, null, null, null, null, null, null, null, null, null, null, explode(",", $img_insert_idx_list)[0], null, null, null);
                    
                    $res_image_status = true;
                }
                $response_params[$img_key . '_iil'] = $img_insert_idx_list;
			}
		}
	}
    /************************************************* 파일 업로드 끝 *************************************************/



    /************************************************* 기존 파일 삭제 *************************************************/
    // 파일 삭제 리스트
    $delete_file_idx_list = explode(',', paramVaildCheck("delete_file_idx_list", NULL));

    // [데이터베이스] DELETE (파일) */
    for($i = 0; $i < count($delete_file_idx_list); $i++)
    {
        $delete_idx = $delete_file_idx_list[$i];
        if(empty($delete_idx) || $delete_idx === ""){ continue; }

		// [데이터베이스 & 서버] 파일 삭제
        $delete_status = Files::delete($delete_idx, $request['target_idx'], $is_admin);
        // if(apiErrorCheck($delete_status, "파일 삭제 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
    }
	/************************************************* 기존 파일 삭제 끝 *************************************************/


    
	/************************************************* 결과 값 리턴 *************************************************/
	$response_params["target_idx"] = $request['target_idx'];
}
/**************************************************************************** DELETE ****************************************************************************/
else if($action === "delete{$target_flag}")
{   /*
		삭제
		2021.09.03 / By.Chungwon

		요청페이지: 게시물 > 상세
	*/
	/************************************************* 접근권한 *************************************************/
	// 로그인 유저만 접근 가능
	if(apiErrorCheck($session_user_idx, "로그인 후 이용해주세요.")) { return; }

	/************************************************* 파라미터 수신 및 파싱 *************************************************/
	$request = getListFromTotal($_REQUEST, array(
		$target_idx_name
	));

    // 테이블 메인 값 설정
	$request['target_idx'] = isset($request[$target_idx_name]) ? $request[$target_idx_name] : NULL;

	/************************************************* 필터링 *************************************************/
	if(apiErrorCheck(isset($request['target_idx']) && $request['target_idx'] !== "", "식별 값이 누락됐습니다.")) { return; }
	
	/************************************************* DB - 분기 처리 *************************************************/
	// 중복 데이터 삭제
	$delete_status = Board::action("delete", array(
		'where_query' => "
			idx = {$request[$target_idx_name]}
			AND reg_user_idx = {$user_idx}			
		",
	));

	/************************************************* 결과 값 리턴 *************************************************/
	$response_params['delete_state'] = $delete_status;
}

/**************************************************************************** SELECT DETAIL ****************************************************************************/

else if($action === "get{$target_flag}")
{   /*
		상세 조회
		2021.08.27 / By.Chungwon

		요청페이지: 게시판 > 상세페이지
	*/

    /************************************************* 파라미터 수신 *****************************************/
	// 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		$target_idx_name,
	));
	$request['target_idx'] = $request[$target_idx_name];	

	$request['like_user_idx'] = $user_idx;
	$request['view_status'] = "1";


    /************************************************* 필터링 *************************************************/    
	if(apiErrorCheck(isset($request[$target_idx_name]), "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }


	/************************************************* IP 조회 후 조회수 증가 *************************************************/    
	if(Text::isInclude(array(), $_SERVER['REMOTE_ADDR']) === false || $is_admin === false)
	{// 
		// 조회수 증가
		DB::increaseHit($target_table, $request['target_idx'], $user_idx);
	}
	
	/************************************************* DB - SELECT LIST ***************************************/
	$sql_result = Board::$action($request);
    if(apiErrorCheck($sql_result, "{$target_name} 상세 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

    /************************************************* 리턴 **************************************************/
	$response_params['data_list'] = array($sql_result);
}



/**************************************************************************** SELECT LIST ****************************************************************************/
else if($action === "getList{$target_flag}" || $action === "getList{$target_flag}Notice")
{   /*
        목록 조회
        2021.08.31 / By.Chungwon

		요청페이지: 게시판 > 목록페이지
    */

    /************************************************* 파라미터 수신 *************************************************/
	// 페이징 및 정렬
    $limit = intval(paramVaildCheck("data_render_count", 20));
	$offset = (intval(paramVaildCheck("page_selected_idx", 1)) - 1) * $limit;
    $sort_list = paramVaildCheck("sort_list", "idx desc");

    // 검색 변수
	$request = getListFromTotal($_REQUEST, array(
		"search_keyword", "type", "notice_status"
	));
	$request['view_status'] = "1";

	/************************************************* DB - SELECT LIST *************************************************/
    // $sql_result = Board::$action($request, $sort_list, $limit, $offset);
    $sql_result = Board::getListBoard($request, $sort_list, $limit, $offset);
	
    if(apiErrorCheck($sql_result, "{$target_name} 목록 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

	

    /************************************************* 리턴 *************************************************/
    $response_params['data_list'] = $sql_result['list'];
    $response_params['data_count'] = $sql_result['count'];
}



else if($action === "getFileList{$target_flag}")
{	/*
		파일 목록 조회
		2021.08.31 / By.Chungwon

		요청페이지: 게시판 > 상세페이지
	*/

	/************************************************* 파라미터 수신 *************************************************/

	// 검색 필터    
	$request = getListFromTotal($_REQUEST, array(
		"type", "ref_table", "ref_key", "target_idx"
	));
	$request['type'] = empty($request['type']) ? "" : $request['type'];
	$request['ref_key'] = empty($request['ref_key']) ? "" : $request['ref_key'];
	$request['ref_table'] = empty($request['ref_table']) ? "" : $target_table;

	/************************************************* 검색 필터 - 분기 *************************************************/    

	/************************************************* 필터링 *************************************************/    
	if(apiErrorCheck(isset($request['target_idx']), "{$target_name} 식별정보가 누락됐습니다.\n고객센터에 문의주세요.")){ return; }

	/************************************************* DB - SELECT LIST *************************************************/
	$data_info = Files::getList($request['type'], $request['ref_table'], $request['ref_key'], $request['target_idx']);
	if(apiErrorCheck($data_info, "{$target_name} 파일 목록 조회 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }

	$response_params['data_list'] = $data_info['list'];
	$response_params['data_count'] = $data_info['count'];
}


/**************************************************************************** UTIL ****************************************************************************/
/********************************************************************************************************************************************/
else{
	if(apiErrorCheck(true, "액션 파라미터가 없습니다.\n고객센터에 문의주세요.")){ return; }
}
http_response_code(200);
echo json_encode($response_params);


