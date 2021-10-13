<?php 
    namespace service;
    /*
        관리자 - 게시판 상세 페이지 
        2021.08.27 / By.Chungwon
    */

    /************************************************* 페이지 정보 세팅 *************************************************/
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    $page_type = "upload";              // 페이지 타입 (upload, detail, list)
    $api_url = "/" . $_SESSION['login_user']['auth_level'] . "/board";      // 요청 API 주소
    
	$target_flag = "Board"; 			// 편의상 API의 ID 키
	$target_table = 'board'; 			// 실제 DB 테이블 명
	$target_idx_name = "board_idx"; 	// 데이터 PK 키 이름
    $target_name = "게시판"; 				// API 명명 (로그 및 출력용)

    $target_idx = isset($_REQUEST[$target_idx_name]) ? $_REQUEST[$target_idx_name] : "null";

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










<div id='container-<?=$page_type?>'>
    <div class='wrap02'>
        <!-- 페이지 인트로 -->
        <div class='intro'>
            <span class='intro-tit'>
                <?=$target_name?>
                <?= isset($target_idx) && $target_idx !== "null" ? "수정" : '등록' ?>
            </span>
            <!-- <a class='upload-btn' data-method='download_excel_form' data-method_event='click'><i class='xi-download'></i>엑셀 폼 다운로드</a> -->
            <label class='fit-hide excel_item' data-excel_default='기본값' data-excel_type='데이터 타입' data-excel_key='키' data-excel_name='항목' data-excel_intro='설명'></label>
        </div>
        
        <div class='cont item-data' id='<?=$page_type?>-get<?=$target_flag?>'>
            <div class='upload-cont'>
                <p class='upload-sec-tit'>상태 정보</p>
                <div class='upload-list'>
                    <div class='upload-item'>
                        <span class='upload-tit excel_item' data-excel_default='0' data-excel_type='number' data-excel_key='notice_status' data-excel_name='공지 상태' data-excel_intro=''>공지 상태</span>
                        <label class='option-check'><input type='radio' required name='notice_status' data-<?=$page_type?>_key='notice_status' value='0' title='공지 상태를 선택해주세요.' checked>일반</label>
                        <label class='option-check'><input type='radio' required name='notice_status' data-<?=$page_type?>_key='notice_status' value='1' title='공지 상태를 선택해주세요.'>공지</label>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit excel_item' data-excel_default='0' data-excel_type='number' data-excel_key='view_status' data-excel_name='노출 상태' data-excel_intro=''>노출 상태</span>
                        <label class='option-check'><input type='radio' required name='view_status' data-<?=$page_type?>_key='view_status' value='1' title='노출 상태를 선택해주세요.' checked>노출</label>
                        <label class='option-check'><input type='radio' required name='view_status' data-<?=$page_type?>_key='view_status' value='0' title='노출 상태를 선택해주세요.'>숨김</label>
                    </div>
                    <!-- <div class='upload-item upload-title'>
                        <label class='upload-tit excel_item' data-excel_default='0' data-excel_type='number' data-excel_key='order_num' data-excel_name='노출 우선순위' data-excel_intro='숫자가 클수록 우선 노출'>
                            노출 우선순위
                        </label>
                        <div class='upload-input'>
                            <input type='text' data-upload_key='order_num' value='' title='노출 우선순위를 입력해주세요.' placeholder='노출 우선순위를 입력해주세요.' style='width:80px;'/>
                            <p class='file-noti'>* 숫자가 클수록 우선 노출됩니다.</p>
                        </div>
                    </div> -->
                </div>

                <p class='upload-sec-tit'>기본 정보</p>
                <div class='upload-list'>
                    <div class='upload-item'>
                        <span class='upload-tit excel_item' data-excel_default='common' data-excel_type='text' data-excel_key='type' data-excel_name='게시글 분류' data-excel_intro='common(자유), notice(공지), profile(프로필)'>게시글 분류</span>
                        <select data-<?=$page_type?>_key='type' data-<?=$page_type?>_key='type' data-event_type='change' title='게시글 분류를 선택해주세요'>
                            <option value='common'>자유게시판</option>
                            <option value='notice'>공지사항</option>
                            <option value='profile'>프로필</option>
                        </select>
                    </div>
                    <div class='upload-item upload-thumb'>
                        <label class='upload-tit'  data-excel_default='' data-excel_type='image' data-excel_key='thumbnail' data-excel_name='썸네일' data-excel_intro='사진은 등록페이지에서 등록해주세요.'>썸네일</label>
                        <div class='upload-input' > <!--id='list-getFileList'-->
                            <div class='dater-item image-bg_cont' style='margin-right:20px;'>
                                <button class='image-bg_set_btn bg' data-image_canvas='thumbnail'></button>
                                <input id='file-form1' accept="image/*" class='fit-hide' data-view_type='bg' data-file_key='thumbnail' type='file' data-method='change_thumbnail' data-method_event='change'/>
                                <div class='thumb-btn'>
                                    <label class='img-add-btn' for='file-form1'>등록</label>
                                    <i class='image-bg_reset_btn' data-method='click_delete_thumbnail' data-method_event='click'>삭제</i>
                                </div>
                            </div>
                            <div class='img-txt'>
                                <p>* 최적 사이즈 200x200px</p>
                                <p>* 파일 형식 JPG, GIF, PNG</p>
                            </div>
                        </div>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit excel_item' data-excel_default='' data-excel_type='text' data-excel_key='title' data-excel_name='제목' data-excel_intro=''>제목</span>
                        <div class='upload-input'>
                            <input type='text' data-<?=$page_type?>_key='title' value='' title='제목을 입력해주세요' placeholder='제목을 입력하세요.'/>
                        </div>
                    </div>
                    <div class='upload-item upload-detail excel_item' data-excel_default='' data-excel_type='text' data-excel_key='content' data-excel_name='내용' data-excel_intro=''>
                        <span class='upload-tit excel_item' data-excel_default='' data-excel_type='text' data-excel_key='title' data-excel_name='내용' data-excel_intro=''>내용</span>
                        <div class='upload-input' id='editor-content' type='text' data-<?=$page_type?>_key='content'> </div>
                    </div>
                </div>
            </div>
            <div class='write-sub'>
                <button class='wb-cancel' data-method='page_cancel' data-move_type='list' data-method_event='click'>취소</button>
                <button class='wb-submit' data-method='page_submit' data-method_event='click'>
                    <?= isset($target_idx) && $target_idx !== "null" ? "수정" : '등록' ?>
                </button>
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
    var g_file_list = []; // 파일이 리스트로 뿌려지는 경우, key 삽입하기
    var g_res = {}; // 수신 파라미터
    var g_multi_list = {}; // 참조하는 데이터
    var g_image_list = {// 이미지 컨테이너
        editor_upload_image_idx : [],   // 에디터 이미지 목록
        delete_file_idx_list : [],   // delete idx list (전체 통합)
    };
    /**************************************************** 초기화 *********************************************/
    $(function(){
        FITSOFT['REST_API']['getInit']({
            func_list : empty(<?=$target_idx?>) ? [] : [ 
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
        // 에디터 연동
        createEditorForContent({
            selecter        :   "#editor-content",
            file_path       :   "<?=$PREFIX['FILE']?>",
            target_table    :   "<?=$target_table?>",            
        });
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

        
        else if(api_info['method'] === "download_excel_form")
        {// 엑셀 폼 다운로드 (2021.08.04 / By.Chungwon)

            var key_type = "";
            var key_name = "";
            var key_column = "";
            
            var excel_head = createExcelHead('.excel_item');

            var intro = StringFormat("\
                <th colspan='{0}' style='height:200px;text-align:left;padding-left:20px;'>\
                    사진규격은 등록페이지를 참고해주세요.<br/><br/>\
                    라디오/셀렉트 : 키 값\
                </th>\
                \
            ", $(".excel_item").length);
            var table_str = StringFormat("\
                <div id='temp-excel_cont'>\
                    <table id='temp-excel'>\
                        <thead>\
                            <tr>{0}</tr>\
                            <tr>{1}</tr>\
                            <tr>{2}</tr>\
                            <tr>{3}</tr>\
                            <tr style='background-color:yellow;'>{4}</tr>\
                        </thead>\
                        <tbody>\
                            <tr><td>데이터1</td></tr>\
                            <tr><td>데이터2</td></tr>\
                            <tr><td>데이터3</td></tr>\
                            <tr><td>데이터4</td></tr>\
                        </tbody>\
                    </table>\
                    <a id='text-excel_btn' href='#' download=''></a>\
                </div>\
            ", excel_head['default'], excel_head['key'], excel_head['type'], excel_head['intro'], excel_head['name']);

            if($("#temp-excel_cont").length > 0){
                $("#temp-excel_cont").remove();
            }
            $("body").append(table_str);
            
            downloadExcel("#text-excel_btn", '<?=$target_name?> 업로드 폼', 'temp-excel');

            $("#text-excel_btn")[0].click();
            $("#text-excel_cont").remove();
        }

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

                    // if(image['ref_key'] === 'te')
                    if(g_file_list.indexOf(image['ref_key']) !== -1)

                    {//  (다중 이미지 객체) 썸네일, 설치 파일
                        
                        var $canvas = $("[data-image_canvas='" + image['ref_key'] + "']");
                        
                        var param =  
                        { 
                            item : image_list[i], 
                            is_end : image_list.length === (i+1),
                            canvas : $canvas
                        }
                    }
                    // else if(image['ref_key'] === 'thumbnail' || image['ref_key'] === 'thumbnail_col')
                    // {// (단일 이미지 객체) 썸네일
                    //     // file 객체
                    //     var $file = $(".image-bg_cont [data-file_key='" + image['ref_key'] + "']");

                    //     // file 객체의 컨테이너
                    //     var $file_parent = $file.closest(".image-bg_cont");
                    //     // 캔버스 (버튼)
                    //     var $canvas = $file_parent.find('.image-bg_set_btn');
                    //     // 이미지 경로
                    //     var path = FITSOFT['IMAGE']['setLink'](image['path']);
                    //     // 백그라운드 설정
                    //     $canvas.css('background-image', "url('" + path + "')");
                    //     // data 매핑
                    //     $file_parent.data('idx', image['idx']);
                    //     $file_parent.data('ref_key', image['ref_key']);
                    //     // 디자인은 위해 active 처리                        
                    //     $file_parent.addClass('active');
                    // }
                    else if(g_file_list.indexOf(image['ref_key']) === -1)
                    {// (단일 이미지 객체) 썸네일
                        // file 객체
                        var $file = $(".image-bg_cont [data-file_key='" + image['ref_key'] + "']");

                        // file 객체의 컨테이너
                        var $file_parent = $file.closest(".image-bg_cont");
                        // 캔버스 (버튼)
                        var $canvas = $file_parent.find('.image-bg_set_btn');
                        // 이미지 경로
                        var path = FITSOFT['IMAGE']['setLink'](image['path']);
                        // 백그라운드 및 이미지
                        var tag_name = $canvas.prop('tagName').toLowerCase();

                        if(tag_name === "img")
                        {// 이미지인 경우
                            $canvas.attr('src', path);
                        }else
                        {// 그 외
                            $canvas.css('background-image', "url('" + path + "')");
                        }
                        
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



        else if(api_info['method'] === "page_cancel")
        {// 취소 버튼 클릭 (2021.06.22 / By.Chungwon)
            
            if(confirm("저장하지 않고 이동할 경우 모든 내용이 초기화됩니다.\n이동하시겠습니까?"))
            {
                location.href = document.location.pathname.replace("<?=$page_type?>.php", "list.php");
            }
        }


        else if(api_info['method'] === "page_submit")
        {// 등록 버튼 클릭 (2021.06.22 / By.Chungwon)
            var params = autoGetItem("upload_key");
            var glist = getFormData(params);
        
            // insert인 경우에는 빈 값
            params.append("<?=$target_idx_name?>", <?=$target_idx?>);

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
            

            // 값 바인딩
            g_res[api_name] = item;
            
            // 에디터 생성
            $('#editor-content').summernote('code', g_res[api_name]['content']);// content는 에디터에 삽입            

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
