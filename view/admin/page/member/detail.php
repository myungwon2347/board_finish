<?php 
    namespace service;
    /*
        관리자 - 회원 상세 페이지 
        2021.08.25 / By.Chungwon
    */

    /************************************************* 페이지 정보 세팅 *************************************************/
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 


    $page_type = "detail";              // 페이지 타입 (upload, detail, list)
    $api_url = "/" . $_SESSION['login_user']['auth_level'] . "/member";      // 요청 API 주소
    
	$target_flag = "Member"; 			// 편의상 API의 ID 키
	$target_table = 'member'; 			// 실제 DB 테이블 명
	$target_idx_name = "member_idx"; 	// 데이터 PK 키 이름
    $target_name = "회원"; 				// API 명명 (로그 및 출력용)
    
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
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . "/util/upload_excel.php";
?>




















<!-- STYLE -->
<style>
    body {}
    .wrap02{padding:70px 0 60px 230px;}
    
    /* 페이지 인트로 */
    .wrap02 .intro{padding:30px;}
    .wrap02 .intro .intro-tit {font-size:1.3rem; font-weight:600; display:inline-block; margin-left:15px; position:relative;}
    .wrap02 .intro .intro-tit:before {content:''; position:absolute; top:50%; left:-11px; display:block; width:4px; height:80%; background-color:#0D2EA3; transform:translateY(-50%);}
    .cont {max-width:1600px; margin:0 30px 30px;}
    
    .cont .upload-cont {}
    .cont .upload-cont .upload-list {width:100%; padding : 10px 0 ;border-bottom : 1px solid #ddd;}
    /* .cont .upload-cont .upload-list .upload-item-sub .upload-add-list .item-category {padding :3px ; border-radius : 5px;} */
    .cont .upload-cont .upload-list .upload-item-sub .upload-add-list .item-category span {font-size : 0.9rem;}
    /* .cont .upload-cont .upload-list .upload-item-sub .upload-add-list .item-classify {padding : 3px ; border-radius : 5px;} */
    .cont .upload-cont .upload-list .upload-item-sub .upload-add-list .item-classify span {font-size : 0.9rem;}
    .cont .upload-cont .upload-list .upload-item{display : flex; align-items:center; margin-bottom:14px;}
    .cont .upload-cont .upmemo {border-bottom : none}
    .cont .upload-cont .upload-item:first-child {}
    .cont .upload-cont .upload-item-sub {width:100%; display:flex; align-items:center; margin : 7px 0 0 5px;}
    .cont .upload-cont .upload-item .upload-tit {width:12%; font-weight:600; cursor : unset;}
    .cont .upload-cont .upload-item .upload-input {width:50%; display:flex; align-items:center;}
    .cont .upload-cont .upload-item.upload-thumb .upload-input {align-items:flex-end;}
    .cont .upload-cont .upload-item .upload-input input, select {padding:6px 10px; border:1px solid #e4e3e3; width:15%; border-radius : 6px; margin-right :5px;}
    .cont .upload-cont .upload-item.upload-cate .upload-input select {width:50%;}
    .cont .upload-cont .upload-item.upload-prio .upload-input input {width:25%;}
    .cont .upload-cont .upload-item.upload-tag .upload-input input, .cont .upload-cont .upload-item.upload-title .upload-input input {width:100%;}
    .cont .upload-cont .upload-item.upload-detail {display : flex ; flex-direction:column;}
    .cont .upload-cont .upload-detail .upload-tit {display:flex; align-items:center; height:100%;}
    .cont .upload-cont .upload-detail .upload-input {width:50%; display:flex; align-items:center;}
    .cont .upload-cont .upload-cate {height:auto;}
    .cont .upload-cont .upload-cate .upload-input {width:40%; display:flex; align-items:center;}
    .cont .upload-cont .upload-cate .upload-input select {width:40%;}
    .cont .upload-cont .upload-cate .upload-input button {padding:6px 10px; background-color:#3d4a5d; border-radius:6px; color:#fff;}
    .cont .upload-cont .upload-classify .upload-input {width:52.5%;}
    .cont .upload-cont .upload-classify .upload-input select {width:30%;}
    .cont .upload-cont .upload-classify .upload-input button {padding:6px 10px; background-color:#3d4a5d; border-radius:6px; color:#fff;}
    .cont .upload-cont .upload-item-sub .upload-add-list {}
    .cont .upload-cont .upload-item-sub .upload-add-list .item-category, .item-classify {padding:3px 8px; border:1px solid #ddd; display:inline-flex; align-items:center; margin-right:5px; border-radius:5px;}
    .cont .upload-cont .upload-item-sub .upload-add-list .item-category button.btn-delete, .item-classify button.btn-delete {margin-left:12px; color:red; display:inline-flex; align-items:center;}
    .cont .upload-cont .img-add {border-bottom : none; display : flex; flex-direction:column; align-items:flex-start;}
    .cont .upload-cont .img-add .upload-tit {display:flex; align-items:center; height:100%; width : 12%; padding:0 0 6px;}
    .cont .upload-cont .img-add .add-area {display:flex; align-items:center;}
    .cont .upload-cont .img-add .add-area button.img-add-btn {padding:6px 10px; background-color:#3d4a5d; border-radius:6px; color:#fff; margin-right:10px; }
    .cont .upload-cont .img-add .add-area div span {font-size:.8rem; color:red;}
    .cont .upload-cont #cont-image {display:flex; margin-top:15px;}
    .cont .upload-cont #cont-image .cont-item {margin-left:8px;}
    .cont .upload-cont #cont-image .cont-item:first-child {margin-left:0;}
    .cont .upload-cont #cont-image .cont-frame {width:120px;height:120px;display:flex;align-items:center;justify-content:center;}
    .cont .upload-cont #cont-image .cont-frame .thumb {position:relative; width:120px; height:120px; border:1px solid #eee;}
    .cont .upload-cont #cont-image .cont-frame .thumb.active {border:2px solid Red;}
    .cont .upload-cont #cont-image .cont-item .check-rep {display:none; background-color:rgba(0,0,0,.6); width:100%; height:100%; align-items:center; justify-content:center;}
    .cont .upload-cont #cont-image .cont-item.active .check-rep {display:flex;}
    /* .cont .upload-cont #cont-image .cont-item.active .btn-set_res_image {background-color:red;} */
    .cont .upload-cont #cont-image .cont-frame .thumb .check-rep i {padding:4px; font-size:3rem;color:#fff;}
    .cont .upload-cont #cont-image .cont-frame .thumb button.btn-delete {display:flex; align-items:center; justify-content:center; position:absolute; top:0; right:0; width:20px; height:20px; color:#fff; background-color:rgba(0,0,0,.5);}
    .cont .upload-cont #cont-image .btn-set_res_image {width:100%; padding:4px 0; margin-top:4px; border:1px solid #ddd; border-radius:4px; font-size:.9rem;}
    .cont .upload-cont #cont-image .cont-item.active .btn-set_res_image {background-color:#3d4a5d; color:#fff;}
    .cont .upload-cont #cont-image .btn-set_res_image:hover {background-color:#3d4a5d; color:#fff;}
    .note-editor {margin:0 5px; min-height:500px;}
    .note-editor .note-editable {min-height:500px !important;}
    
    #form-add_option {margin-top:10px;}
    #form-add_option input {border : 1px solid #ddd ; border-radius : 5px; padding : 6px 10px; width : 25.5%}
    #form-add_option span {width : 12% ; display : inline-block ; font-weight : 500;}
    #form-add_option label {width:10%; display:inline-flex; align-items:center; justify-content:flex-start;}
    #form-add_option label > input {width:auto; margin-right:4px;}
    #form-add_option .option-add {display:none; margin:10px 12%;}
    #form-add_option .option-add.active {display:block;}
    #form-add_option .option-add > input {width:30%;}
    #form-add_option button {padding: 6px 10px; background-color: #3d4a5d; border-radius: 3px; color: #fff; margin-left : 2px;}
    #form-add_option input::placeholder {font-size : 0.9rem ; color :#bbb; padding-left : 5px;}

    #cont-board_option .child-none .child-list{display:none;}
    #cont-board_option .child-none.active .child-list{display:block;}
    #cont-board_option .cont-common{display:none;}
    #cont-board_option .cont-update{display:none;}
    #cont-board_option .cont-update .edit-btn {display:flex; align-items:center; margin-left:4px;}
    #cont-board_option .cont-common.active{display:flex; align-items : center; padding : 3px; margin-bottom : 5px;}
    #cont-board_option .cont-common.active .btn-create_child {width : 5%}
    #cont-board_option .cont-common.active .btn-change {width :50%;}
    #cont-board_option .cont-common.active .btn-delete {width :50%; color : #d74444;}
    #cont-board_option .cont-common.active .common-align {display :flex; align-items : center;  width :85%}
    #cont-board_option .cont-common.active .common-btn {display :flex; align-items : center; width :10%; }
    #cont-board_option .cont-update.active{display:flex;}
    #cont-board_option .item-child{display:none;}
    #cont-board_option .item-child.active{display:flex; margin : 10px 0px;}
    #cont-board_option .item-child input {border: 1px solid #ddd;border-radius: 5px;padding: 3px; width: 20%; margin-left :45px;}
    #cont-board_option .item-child input::placeholder {font-size : 0.9rem; padding-left :5px;}
    #cont-board_option .item-child input:nth-child(2) {margin-left : 5px;}
    .cont-insert button {padding: 5px 10px;background-color: #3d4a5d;border-radius: 3px;color: #fff;font-size: .8rem;margin-left: 5px;}
    .cont-insert button:nth-child(2) {margin-left :0px;}

    .fi-name {display: flex;align-items: center;border: 1px solid #ddd;border-radius: 5px;width: 70%;padding: 6px 10px;}
    .cont-update.btn-update.active div button {padding: 6px 10px; background-color: #3d4a5d; border-radius: 6px; color: #fff;}
    .cont-update.btn-update.active div button:last-child {margin-left:3px;}
    
    /* 210608 */
    label.option-check {display:inline-flex; align-items:center; margin-right:24px;}
    label.option-check input[type='radio'] {margin-right:5px;}
    /* #event-add_btn {width:100%;padding:10px; background-color:#3d4a5d; color:#fff; border-radius:4px;} */
    .upload-thumb .image-bg_cont{position:relative; width:120px; height:120px; border:1px solid #e5e5e5; background-color:#f8f8f8; border-radius:5px;}
    /* .upload-thumb .image-bg_cont > i {position:absolute; right:10px; top:10px;} */
    .upload-thumb .image-bg_set_btn {position:absolute; top:0; left:0; width:100%; height:100%;}
    /* .upload-thumb .image-bg_reset_btn {position:absolute; top:0; right:-50px; display:inline-block; padding:4px 10px; font-size:.8rem; background-color:#fff; border:1px solid #0D2EA3; color:#0D2EA3; border-radius:5px;} */
    .upload-thumb .img-txt p {font-size:.8rem; color:#8F8F8F;}
    .upload-thumb .thumb-btn {position:absolute; top:5px; left:100%; margin-left:20px; display:flex; width:100%; align-items:center;}
    .upload-thumb .thumb-btn .img-add-btn {position:relative; width:auto; height:auto; display:inline-block; padding:4px 10px; font-size:.9rem; font-weight:600; background-color:#fff; border:1px solid #0D2EA3; color:#0D2EA3; border-radius:5px; margin-right:3px; transition:.3s;}
    .upload-thumb .thumb-btn .img-add-btn:hover {background-color:#0D2EA3; color:#fff;}
    .upload-thumb .thumb-btn .image-bg_reset_btn {position:relative; display:inline-block; padding:4px 10px; font-size:.9rem; font-weight:600; background-color:#fff; border:1px solid #0D2EA3; color:#0D2EA3; border-radius:5px; transition:.3s; cursor:pointer;}
    .upload-thumb .thumb-btn .image-bg_reset_btn:hover {background-color:#0D2EA3; color:#fff;}
    .note-editor.note-airframe, .note-editor.note-frame {margin:0;}

    .write-sub {display:flex; align-items:center; justify-content:flex-end; margin-top:20px;}
    .write-sub button {font-weight:600; padding:6px 25px; border:1px solid #0D2EA3; border-radius:5px;}
    .write-sub .wb-cancel {color:#0D2EA3; margin-right:4px;}
    .write-sub .wb-submit {background-color:#0D2EA3; color:#fff; margin-right:3px;}


    @media screen and (max-width: 768px) {
        .wrap {padding-left:140px; max-width:calc(140px + 768px);}
    }

    /* 이미지 업로드 */
    .upload-item .is_update > span{display:none;}
    .upload-item .is_update.active > span{display:inline-block;}
    
    .board_classify:nth-child(n+2){display:none;}
    .btn-show{cursor:pointer;transform:rotate(180deg);}
    .btn-show.move {transform:rotate(360deg);}

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
                <div class='upload-sec-tit'>기본 정보</div>
                <div class='upload-list'>
                    <div class='upload-item'>
                        <span class='upload-tit'>가입상태</span>
                        <select data-<?=$page_type?>_key='reg_status' data-upload_key='reg_status' data-event_type='change'>
                            <option value='1'>가입</option>
                            <option value='0'>탈퇴</option>
                            <option value='2'>휴면</option>
                        </select>
                    </div>
                    <!-- <div class='upload-item'>
                        <span class='upload-tit'>공지글 작성 권한</span>
                        <label class='option-check'>
                            <input type="radio" name='write-power'>부여
                        </label>
                        <label class='option-check'>
                            <input type="radio" name='write-power'>미부여
                        </label>
                    </div> -->
                    <div class='upload-item'>
                        <span class='upload-tit'>유저등급</span>
                        <select data-<?=$page_type?>_key='rank' data-upload_key='rank' data-event_type='change'>
                            <option value='common'>일반회원</option>
                            <option value='sales'>영업사원</option>
                            <option value='confirm-sales'>인증된 영업사원</option>
                        </select>
                    </div>
                </div>
                <div class='upload-sec-tit'>기본 정보</div>
                <div class='upload-list'>
                    <div class='upload-item'>
                        <span class='upload-tit'>아이디</span>
                        <label class='option-check' data-<?=$page_type?>_key='id'></label>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>이름</span>
                        <label class='option-check' data-<?=$page_type?>_key='name'></label>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>닉네임</span>
                        <label class='option-check' data-<?=$page_type?>_key='nickname'></label>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>활동지역</span>
                        <label class='option-check' data-<?=$page_type?>_key='location1_name'></label>
                        <label class='option-check' data-<?=$page_type?>_key='location2_name'></label>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>가입일</span>
                        <label class='option-check' data-<?=$page_type?>_key='insert_date'></label>
                    </div>
                    <div class='upload-item'>
                        <span class='upload-tit'>최근 로그인 날짜</span>
                        <label class='option-check' data-<?=$page_type?>_key='latest_login_date'></label>
                    </div>
                </div>
            </div>
            <div class='write-sub'>
                <button class='wb-cancel' data-method='page_cancel' data-move_type='list' data-method_event='click'>목록</button>
                <button class='wb-submit' data-method='page_submit' data-method_event='click'>수정</button>
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
            var validity_list = $("[data-upload_key]");
            var skip_key_list = []; // 생략 가능한 값 목록 (입력 불가능한 초기값이 있는 경우)
            
            for(var i = 0; i < validity_list.length; i++)
            {
                var validity = $(validity_list[i]);
                var key = validity.data('upload_key');

                if(empty(glist[key]) && skip_key_list.indexOf(key) == -1)
                {// 값이 없고 스킵 목록에도 없는 경우, 경고 출력 후 리턴
                    var alert_msg = empty(validity.attr('title')) ? validity.attr('placeholder') : validity.attr('title');
                    alert_msg = empty(alert_msg) ? key : alert_msg;

                    alert(alert_msg); 
                    
                    validity.focus(); 
                    return;
                }
            }

            // 에디터 및 파일처리
            params = setEditAndFile(params);

            /*************************** 데이터 파싱 ***************************/ 
            // params.delete('budget');
            // params.append('budget', deleteComa(glist['budget']));

            sendAPI("<?=$api_url?>", "upload<?=$target_flag?>", params, function(res){
                if(res.update_status || res.insert_idx || res.insert_idx_list){

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
            
            autoSetItem(g_res[api_name], "<?=$page_type?>_key"); // 세팅 값
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
