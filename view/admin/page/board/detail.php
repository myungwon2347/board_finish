<?php 
    namespace service;
    /*
        관리자 - 게시판 상세 페이지 
        2021.08.27 / By.Chungwon
    */

    /************************************************* 페이지 정보 세팅 *************************************************/
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    $page_type = "detail";              // 페이지 타입 (upload, detail, list)
    $api_url = "/" . $_SESSION['login_user']['auth_level'] . "/board";      // 요청 API 주소
    
	$target_flag = "Board"; 			// 편의상 API의 ID 키
	$target_table = 'board'; 			// 실제 DB 테이블 명
	$target_idx_name = "board_idx"; 	// 데이터 PK 키 이름
    $target_name = "게시판"; 				// API 명명 (로그 및 출력용)

    $target_idx = isset($_REQUEST[$target_idx_name]) ? $_REQUEST[$target_idx_name] : null;

    /************************************************* 접근권한 *************************************************/
    // 페이지 접근권한
    if(empty($_SESSION['login_user']))
    {// 비회원 - 로그인 페이지로 이동
        header("Location:{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['ADMIN']}/page/login.php");        
    }
    else if($_SESSION['login_user']['auth_level'] !== "admin")
    {// 회원 - 관리자 계정이 아닌 경우
        header("Location:{$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/login.php");        
    }
    
    /************************************************* 비즈니스 로직 *************************************************/


    /************************************************* 화면 노출 *************************************************/    
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/head.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/sidebar.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/header.php";


    /************************************************* UTIL PHP *************************************************/
    // require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . "/util/upload_excel.php";
?>




















<!-- STYLE -->
<style>
    

</style>
<!-- STYLE END -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">











<div id='container-<?=$page_type?>'>
    <div class='wrap02'>
        <!-- 페이지 인트로 -->
        <div class='intro'>
            <span class='intro-tit'>
                <?=$target_name?> 상세
            </span>
        </div>
        
        <div class='cont item-data' id='<?=$page_type?>-get<?=$target_flag?>'>
            <div class='upload-cont'>
                <p class='upload-sec-tit'>상태 정보</p>
                <div class='upload-list'>
                    <div class='upload-item'>
                        <span class='upload-tit'>공지 상태</span>
                        <label class='option-check'><input type='radio' required name='notice_status' data-<?=$page_type?>_key='notice_status' data-upload_key='notice_status' value='1'>공지</label>
                        <label class='option-check'><input type='radio' required name='notice_status' data-<?=$page_type?>_key='notice_status' data-upload_key='notice_status' value='0'>일반</label>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>노출 상태</span>
                        <label class='option-check'><input type='radio' required name='view_status' data-<?=$page_type?>_key='view_status' data-upload_key='view_status' value='1'>노출</label>
                        <label class='option-check'><input type='radio' required name='view_status' data-<?=$page_type?>_key='view_status' data-upload_key='view_status' value='0'>숨김</label>
                    </div>
                </div>

                <p class='upload-sec-tit'>기본 정보</p>
                <div class='upload-list'>
                    <div class='upload-item'>
                        <span class='upload-tit'>게시글 분류</span>
                        <span class='option-check' data-<?=$page_type?>_key='type_str'></span>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>작성자 닉네임</span>
                        <span class='option-check' data-<?=$page_type?>_key='reg_user_nickname'></span>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>조회수</span>
                        <span class='option-check' data-<?=$page_type?>_key='hit'></span>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>작성일</span>
                        <span class='option-check' data-<?=$page_type?>_key='insert_date'></span>
                    </div>
                </div>

                <p class='upload-sec-tit'>게시글 정보</p>
                <div class='upload-list'>
                    <div class='upload-item upload-thumb'>
                        <label class='upload-tit'>썸네일</label>
                        <div class='upload-input'>
                            <div class='dater-item image-bg_cont' style='margin-right:20px;'>
                                <button class='image-bg_set_btn bg' data-image_canvas='thumbnail' accept="image/*" data-file_key='thumbnail'></button>
                            </div>
                        </div>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>제목</span>
                        <span class='option-check' data-<?=$page_type?>_key='title'></span>
                    </div>
                    <div class='upload-item upload-detail'>
                        <span class='upload-tit'>내용</span>
                        <div class='upload-input' type='text' data-<?=$page_type?>_key='content'> </div>
                        <!-- <div class='upload-input' id='editor-content' type='text' data-<?=$page_type?>_key='content'> </div> -->
                    </div>
                </div>
                <div class='write-sub'>
                    <button class='wb-cancel' data-method='page_cancel' data-move_type='list' data-method_event='click' type='button'>목록</button>
                    <button class='wb-submit' data-method='page_submit' data-method_event='click' type='button'>수정</button>
                </div>
            </div>
        </div>
    </div>
</div>




















<!-- SCRIPT -->
<script src="<?=$PATH['RESOURCES']?>/plugins/js/summernote-lite.min.js"></script>	
<script src="<?=$PATH['RESOURCES']?>/plugins/js/lang/summernote-ko-KR.min.js"></script>	
<link href="<?=$PATH['RESOURCES']?>/plugins/css/summernote-lite.min.css" rel="stylesheet">

<script>
    /**************************************************** 전역 변수 *********************************************/
    var g_req = {// 요청 파라미터
        "get<?=$target_flag?>" : {// 상세 조회
            <?=$target_idx_name?> :   <?=$target_idx?>,
        },
        "getFileList<?=$target_flag?>" : {// 파일 목록 조회
            target_idx                  :   <?=$target_idx?>,
            ref_table                   :   "<?=$target_table?>",
        },
    };
    var g_res = {}; // 수신 파라미터
    var g_multi_list = {}; // 참조하는 데이터
    var g_image_list = {// 이미지 컨테이너
        editor_upload_image_idx : [],   // 에디터 이미지 목록
        delete_file_idx_list : [],   // delete idx list (전체 통합)
    };
    /**************************************************** 초기화 *********************************************/
    $(function(){
        FITSOFT['REST_API']['getInit']({
            func_list : [ 
                "get<?=$target_flag?>", // 상세 조회
                "getFileList<?=$target_flag?>", // 파일 목록 조회
            ],
            complete : function(api_name)
            {// 모든 비동기 함수 종료
            },
        });
        // 데이트피커 연동
        searchDatepicker(".datepicker");
        // 이벤트 연동
        setEventBinding($("[data-method]"));
    });
    /**************************************************** 초기화 끝 *********************************************/



    /**************************************************** 정적 바인딩 이벤트 *********************************************/
    function staticMethodHandler(e)
    {// 정적 메소드 핸들러

        // 이벤트 핸들러인 경우
        var target = $(e.currentTarget);
        var api_info = target.data();
        var item = target.closest(".item-data");
        var item_info = item.data();
        var item_siblings = item.parent().find("[data-method=" + api_info['method'] + "]"); // 동일 선상의 형제 값


        if(false){}

        
        else if(api_info['method'] === "check_thumbnail")
        {// 썸네일 클릭인 경우 (2020.12.03 / By.Chungwon)
            setAutoFileEvent(e);
        }


        else if(api_info['method'] === "click_delete_thumbnail")
        {// 썸네일 삭제인 경우 (2020.12.03 / By.Chungwon)
            var edata = getEventData(e, 'image-bg_cont');
            var type = edata['ref_key'];
            
            edata['parent'].removeClass("active");

            if(empty(edata['idx']) === false)
            {// DB에 존재하는 이미지인 경우
                g_image_list['delete_file_idx_list'].push(edata['idx']);
            }else
            {// 프론트엔드에서만 존재하는 이미지인 경우 file 데이터 제거하기

                // 객체가 없는 경우 생성 (자동화)
                if(empty(g_image_list[type]))
                {
                    g_image_list[type] = [];
                }

                var temp = Array.from(g_image_list[type]);
                deleteListFromValue2(temp, "name", edata['o_name']);
                g_image_list[type] = temp;    
            }

            var $canvas = $("[data-image_canvas='" + edata['ref_key'] + "']");

            $canvas.css('background-image', "");
            deleteInputFile($("[data-method=change_thumbnail]"));

        }


        else if(api_info['method'] === "change_thumbnail")
        {// 썸네일 변경 검사 (2020.12.03 / By.Chungwon)
            
            // 썸네일 유효성검사 
            FITSOFT['IMAGE']['changeEvent'](e, function(image_list)
            {
                if(image_list === false)
                {// 유효성 검사 실패
                    return;
                }
                // 기존 이미지 파일은 삭제 배열에 넣기
                var edata = getEventData(e, 'image-bg_cont');
                var type = edata['file_key'];
                
                edata['parent'].removeClass("active");
                
                if(empty(edata['idx']) === false)
                {// DB에 존재하는 이미지인 경우
                    g_image_list['delete_file_idx_list'].push(edata['idx']);
                }else
                {// 프론트엔드에서만 존재하는 이미지인 경우 file 데이터 제거하기

                    // 객체가 없는 경우 생성 (자동화)
                    if(empty(g_image_list[type]))
                    {
                        g_image_list[type] = [];
                    }

                    var temp = Array.from(g_image_list[type]);
                    deleteListFromValue2(temp, "name", edata['o_name']);
                    g_image_list[type] = temp;    
                }
                
                for(var i = 0; i < image_list.length; i++)
                {// 등록된 이미지 반복
                    var image = image_list[i];

                    if(image['ref_key'] === 'te')
                    {//  (다중 이미지 객체) 썸네일, 설치 파일
                        
                        var $canvas = $("[data-image_canvas='" + image['ref_key'] + "']");
                        
                        var param =  
                        { 
                            item : image_list[i], 
                            is_end : image_list.length === (i+1),
                            canvas : $canvas
                        }
                    }
                    else if(image['ref_key'] === 'thumbnail' || image['ref_key'] === 'thumbnail_col')
                    {// (단일 이미지 객체) 썸네일
                        // file 객체
                        var $file = $(".image-bg_cont [data-file_key='" + image['ref_key'] + "']");
                        console.log($file);
                        // file 객체의 컨테이너
                        var $file_parent = $file.closest(".image-bg_cont");
                        // 캔버스 (버튼)
                        var $canvas = $file_parent.find('.image-bg_set_btn');
                        // 이미지 경로
                        var path = FITSOFT['IMAGE']['setLink'](image['path']);
                        // 백그라운드 설정
                        $canvas.css('background-image', "url('" + path + "')");
                        // data 매핑
                        $file_parent.data('idx', image['idx']);
                        $file_parent.data('ref_key', image['ref_key']);
                        // 디자인은 위해 active 처리                        
                        $file_parent.addClass('active');
                    }
                }
            });
        }


        else if(api_info['method'] === "click_delete_file")
        {// 파일 삭제인 경우 (2021.07.17 / By.Chungwon)
            var api_name = item_info['api_name'];
            var ref_key = item_info['ref_key'];

            if(empty(item_info['idx']) === false)
            {// DB에 존재하는 이미지인 경우
                g_image_list['delete_file_idx_list'].push(item_info['idx']);
            }

            // 객체가 없는 경우 생성 (자동화)
            if(empty(g_image_list[ref_key]))
            {
                g_image_list[ref_key] = [];
            }

            // file 데이터 제거하기
            var temp = Array.from(g_image_list[ref_key]);
            deleteListFromValue2(temp, "name", item_info['o_name']);
            g_image_list[ref_key] = temp;

            item.remove();
        }


        else if(api_info['method'] === "change_file_list")
        {// 파일 목록 추가 이벤트 (2021.07.17 / By.Chungwon)

            fileChangeEvent(e, {// 썸네일 유효성검사 
                format : ['zip','ppt','doc','xls', 'pdf', 'hwp', 'txt', 'jpg','jpeg','png','gif','bmp','BMP','JPG','JPEG','PNG','GIF', 'webp', 'WEBP', 'jfif', 'heic', 'JFIF', 'HEIC'],
                success_callback : function(image_list)
                {// 성공 콜백          
                    $(image_list).each(function(i, image){// 등록된 이미지 반복                        
                        image['view_type'] = api_info['view_type'];

                        add("getFileList<?=$target_flag?>", {
                            item : image,
                            is_end : image_list.length === (i+1),
                        });
                    });
                },
            });
        }


        else if(api_info['method'] === "move")
        {// 페이지 이동 (2021.07.06 / By.Chungwon)
            var target_idx = item_info['idx'];
            var move_type = api_info['move_type'];
        
            if(move_type === "delete")
            {// 삭제인 경우, 페이지 이동없음.
                if(confirm("정말로 삭제하시겠습니까?"))
                {
                    sendAPI("<?=$api_url?>", "delete<?=$target_flag?>", { "<?=$target_idx_name?>" : target_idx }, function(res){
                        location.reload();
                    });
                }
                return;
            }
            else
            {// 등록/수정 또는 상세 화면 이동

                // 현재 URL
                var current_url = document.location.pathname;

                // list 페이지를 변경되는 페이지로 변경
                var change_url = current_url.replace("<?=$page_type?>.php", move_type + ".php");

                // 페이지로 이동
                location.href = StringFormat("{0}?<?=$target_idx_name?>={1}", change_url, target_idx);
            }
        }


        else if(api_info['method'] === "page_cancel")
        {// 취소 버튼 클릭 (2021.06.22 / By.Chungwon)
            
            location.href = document.location.pathname.replace("<?=$page_type?>.php", "list.php");

        }


        else if(api_info['method'] === "page_submit")
        {// 등록 버튼 클릭 (2021.06.22 / By.Chungwon)
            var params = autoGetItem("upload_key");
            var glist = getFormData(params);
        
            // insert인 경우에는 빈 값
            params.append("<?=$target_idx_name?>", <?=$target_idx?>);
            params.append("type", g_res["get<?=$target_flag?>"]['type']);

            /*************************** 유효성 검사 ***************************/ 
            var skip_key_list = [];

            if(formValidity("upload_key", skip_key_list, glist) === false){
                return false;
            }


            // 에디터 및 파일처리
            params = setEditAndFile(params);

            /*************************** 데이터 파싱 ***************************/ 
            // params.delete('budget');
            // params.append('budget', deleteComa(glist['budget']));

            sendAPI("<?=$api_url?>", "upload<?=$target_flag?>", params, function(res){
                if(res.target_idx){

                    spinnerOff();

                    // 목록 페이지로 이동
                    location.href = document.location.pathname.replace("<?=$page_type?>.php", "list.php");
                }
            });
        }
    }
    /**************************************************** 정적 바인딩 이벤트 끝 *********************************************/



    /**************************************************** GET 메소드 *********************************************/
    function get(api_name, opt)
    {

        var api_type = "getList";
        var is_init = empty(opt) || empty(opt['is_init']) ? false : opt['is_init'];
        var api_url = empty(opt) || empty(opt['api_url']) ? "<?=$api_url?>" : opt['api_url'];
        var params = empty(opt) || empty(opt['params']) ? g_req[api_name] : opt['params'];

        FITSOFT['REST_API'][api_type]({ 
            api_url : api_url,
            api_name : api_name,
            is_init : is_init,
            params : params,

            callback : function(res)
            {// 리스트 API 콜백
                if(empty(opt) === false && empty(opt['init']) === false){
                    opt['init']();
                }

                for(var i = 0; i < res['data_list'].length; i++){
                    add(api_name, { 
                        item : res['data_list'][i], 
                        is_end : res['data_list'].length === (i+1) ,
                    });
                }
                
            }
        });
    }
    /**************************************************** GET 메소드 끝 ******************************************/


    /**************************************************** 셋팅 ******************************************/
    function add(api_name, res)
    {// 데이터 추가 메소드 (2021.06.18 / By.Chungwon)

        /******************** 변수세팅 ********************/
        var item = res['item'];
        var is_end = empty(res['is_end']) ? true : res['is_end'];
        var attach = empty(res['attach']) ? 'append' : res['attach'];
        var $canvas = empty(res['canvas']) ? $("#list-" + api_name) : res['canvas'];
        /******************** 변수세팅 끝 ********************/


        /******************** 액션별 분기처리 *******************/
        if(false) {}


        else if(api_name === "get<?=$target_flag?>")
        {// 상세 정보 조회 (2021.07.21 / By.Chungwon)

            // 상세 메인 데이터 설정 
            $("#<?=$page_type?>-" + api_name).data(item);


            /********** 데이터 파싱 **********/
            // item['view_status'] = item['view_status'] === "0" ? "숨김" : "노출";

            // 값 바인딩
            g_res[api_name] = item;
            
            if(item['type'] === "common") { item['type_str'] = "자유게시판"; }
            else if(item['type'] === "notice") { item['type_str'] = "공지사항"; }
            else if(item['type'] === "profile") { item['type_str'] = "프로필"; }


            // [날짜] - 시/분/초 짜르기
            item['open_date'] = empty(item['open_date']) ? "" : item['open_date'].split(" ")[0];
            item['close_date'] = empty(item['close_date']) ? "" : item['close_date'].split(" ")[0];            

            // 값 바인딩
            g_res[api_name] = item;
            
            autoSetItem(g_res[api_name], "<?=$page_type?>_key"); // 세팅 값
        }
        else if(api_name === "getFileList<?=$target_flag?>")
        {// 이미지 일괄처리 예제
            
            /***** 이미지 일괄 등록인 경우 *****/
            if(empty(item['idx']))
            {// 새로 추가된 아이템 인 경우 -> 유효성 검사

                /***** 유효성 검사 및 필터링 *****/
                var img_list = $canvas.find('.item-data');

                for(var i = 0; i < img_list.length; i++)
                {
                    var o_name = $(img_list[i]).data('o_name');
                    var type = $(img_list[i]).data('ref_key');

                    if(item['o_name'] === o_name)
                    {// 새로 추가된 아이템 인 경우
                        alert("이미 등록된 파일명이 존재합니다.");

                        if(g_image_list[type] !== undefined)
                        {// file 데이터 제거하기
                            var temp = Array.from(g_image_list[type]);
                        
                            deleteListFromValue2(temp, "name", o_name);
                            g_image_list[type] = temp;
                        }

                        spinnerOff();
                        return;
                    }
                }
            }
            else
            {// DB 데이터 인 경우 -> 데이터 동기화
                // g_multi_list[api_name]['origin'].push(item['idx']);

                // 에디터 변수에 값 동기화
                if(item['ref_key'] === "editor")
                {
                    g_image_list['editor_upload_image_idx'].push(item['idx']);
                }
            }
            /***** 이미지 일괄 등록인 경우 끝 *****/


            if(item['view_type'] === 'bg' 
                || item['ref_key'] === 'thumbnail'
            )
            {// 배경화면에 추가하는 경우 (1개)
                var $file = $(".image-bg_cont [data-file_key='" + item['ref_key'] + "']");
                var $file_parent = $file.closest(".image-bg_cont");
                
                $file_parent.data(item);
                $file_parent.addClass('active');
                $file_parent.find('.image-bg_set_btn').css('background-image', "url('" + FITSOFT['IMAGE']['setLink'](item['path']) + "')");
            }
            else if(item['view_type'] === 'list' 
                || item['ref_key'] === 'director'
                )
            {// 컨테이너에 추가하는 경우 (N개)

                api_name += "-" + item['ref_key'];

                var html = create(api_name, item);
                $canvas = $("#list-" + api_name);
                $canvas[attach](html);

                // 이벤트 자동 연동 (캔버스가 바뀌는 경우에는 이벤트 연동을 다시 해줘야함.)
                setEventBinding($canvas.find("[data-method]"));
            }
        }
        

        else
        {// 그 외 액션 !!!! create가 필요한 경우에는 여기에 !!!!
            var html = create(api_name, item);
            $canvas[attach](html);
        }
        /******************** 액션별 분기처리 끝 *******************/



        /******************** 동적 이벤트 바인딩 *******************/
        if(is_end){ if(false){}

            // 이벤트 자동 연동
            setEventBinding($canvas.find("[data-method]"));                
        }
        /******************** 동적 이벤트 바인딩 끝 *******************/
        
    }
    /**************************************************** 셋팅 끝 ******************************************/


    /**************************************************** HTML 생성 *********************************************/    
    function create(api_name, data)
    {// HTML 생성 - 수신값은 전부 문자열
        if(false){}
        
        
    }
    /**************************************************** HTML 생성 끝 *********************************************/    

</script>
<!-- SCRIPT END -->
<?php require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/footer.php"; ?>
