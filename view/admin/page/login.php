<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; ?>

<!DOCTYPE html>
<html lang="<?=isset($SITE['LOCALE']) ? explode('_', $SITE['LOCALE'])[0] : 'ko'?>">
<head>
    <!-- CSS Custom -->
    <link rel="stylesheet" href="<?=$PATH['RESOURCES']?>/css/default.css?<?=$SITE['UPDATEDATE']?>" />
    <link rel="stylesheet" href="<?=$PATH['RESOURCES']?>/css/custom.css?<?=$SITE['UPDATEDATE']?>" />


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    
    <style>
        
        @import url('https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap');

        html, body, .canvas {height:100%; margin:0; font-family:'Noto Sans KR', sans-serif !important;}
        /* .canvas {background:url('<?=$PATH['RESOURCES']?>/image/vendor/bg_login.jpg') no-repeat center; height:100%; position:relative;} */
        .canvas-wrap {position:absolute; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,.0 );}
        
        /* 로그인폼 */
        .login-cont {width:100%;}
        .login-cont #login_form {position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); height:100%; display:flex; width:50%;flex-direction: column; align-items:center; justify-content:center;}
        .login-cont #login_form .cont-left {display:flex; flex-direction:column; width:480px;}

        .login-cont #login_form .cont-left .id-wrap, .login-cont #login_form .cont-left .pw-wrap {display:flex; align-items:center; margin-bottom:10px; justify-content: space-between;}
        .login-cont #login_form .cont-left .id-wrap .id-tit, .login-cont #login_form .cont-left .pw-wrap .pw-tit {font-size:.9rem; font-weight:600;}
        .login-cont #login_form .cont-left .input-box {width:75%; padding:6px 20px; border-radius:5px; background-color:rgba(255, 255, 255, 1); border:1px solid #ddd; font-size:1rem;}
        .login-cont #login_form .cont-left .input-box i {font-size:1rem; color:#444; padding-right:6px; /*border-right:1px solid #888;*/}
        .login-cont #login_form .cont-left .input-box input {background-color:transparent; border:none; width:calc(100% - 30px); padding:6px; font-size:.8rem;}
        .login-cont #login_form .cont-left .name-box {}
        .login-cont #login_form .cont-left .pass-box {}
        .login-cont #login_form .cont-left .login-btn {margin-top:10px; padding:10px 0; border-radius:5px; font-size:1.1rem; background-color:#ddd;border:1px solid #ddd; color:#000; font-weight:500; cursor:pointer;}
        .login-cont #login_form .cont-left .login-btn:hover {background-color:#0D2EA3; color:#fff; border:1px solid #0D2EA3;}
        .login-cont #login_form .cont-left .login-save {color:#000; margin-top:6px;}
        .login-cont #login_form .cont-left .login-save input, label {cursor:pointer;}
        .checks.etrans input[type="checkbox"] + label:before {opacity:1; border-color:#888;}
        .login-cont #login_form .cont-left .login-save .save-id {}
        .login-cont #login_form .cont-left .login-save .save-login {margin-left:6px;}
        .login-cont #login_form .cont-left .login-txt {text-align: center; margin-bottom:70px;}
        .login-cont #login_form .cont-left .login-txt p {font-size: 1.4rem; font-weight:700; color: #000; margin-top:15px;}
        /* .login-cont #login_form .cont-right {width:82%; margin-right:30px; display:flex; align-items:center;}
        .login-cont #login_form .cont-right .login-logo {display:inline-block; width:400px; height:53px;}
        .login-cont #login_form .cont-right .login-logo div {height:100%;}
        .login-cont #login_form .cont-right .login-txt {color:#000; margin-top:10px; font-size:1.1rem;}         */

        
        .bg {background-position:center; background-repeat:no-repeat; background-size:cover;}



        @media screen and (max-width: 1024px) {
            .login-cont #login_form .cont-left {width:80%;}
        }

    /* 로그인 폼 */
    .canvas .center-wrapper .main-form{ max-width:300px; margin-top:40px; width:100%;}
    .canvas .center-wrapper .main-form > .form-control{ width:100%; margin-bottom:10px;}
    .canvas .center-wrapper .main-form > .form-control input{ width:100%; height:40px; border:none; padding-left:8px; font-size:1rem;}
    .canvas .center-wrapper .main-form > .form-control .auto-login{ height:28px; display:flex; align-items:center; font-size:0.9rem; color:#fff;}
    .canvas .center-wrapper .main-form > .form-control .auto-login input{ height:100%;}
    
    .form-control .login-btn{ width:100%; border:none; height:40px; border-radius:4px; color:#333; font-weight:600; background-color:#abe545; font-size:1rem;}
    .form-control.other-btn-cont{ color:#000; font-size:0.8rem; display:flex; margin-top:6px;}
    .form-control.other-btn-cont > p { display:flex; flex-grow:1; justify-content:center; padding:10px; border-radius:30px; font-weight:600; background-color:#fff; border:1px solid #ddd; font-size:.8rem; cursor:pointer;}
    .form-control.other-btn-cont > p:hover{background-color:#1f1f1f; color:#fff;}
    .form-control.other-btn-cont > p:nth-child(1){margin-right:4px;}
    .form-control.other-btn-cont > p:nth-child(2){}

    .sns-tit {position:relative; width:50%; margin: 50px 0 10px;}
    .sns-tit span {display:block;}
    .sns-tit span:nth-child(1) {font-size:1.1rem; font-weight:600;}
    .sns-tit span:nth-child(2) {font-size:.9rem;}
    /* .sns-tit:before {content:''; position:absolute; display:block; width:150%; height:1px; top:50%; right:110%; background-color:#000;} */
    /* .sns-tit:after {content:''; position:absolute; display:block; width:150%; height:1px; top:50%; left:110%; background-color:#000;} */
    .login-sns {display:flex; width:50%;}
    .login-sns button {margin:6px 0 0 6px; width:100%; padding:15px 0; border-radius:30px; color:#fff; border:none; text-align:center;}
    .login-sns button img {margin-right:5px; width:16px; height:16px;}
    .login-sns button span {display:inline-block; font-size:.8rem; font-weight:600;}
    .login-sns button span:hover {text-decoration:underline;}
    .login-sns .sns-facebook {background-color:#3b5998; margin-left:0;}
    .login-sns .sns-naver {background-color:#2db400;}
    .login-sns .sns-google {background-color:#e34033;}

    </style>
</head>

<body>
    <div class='canvas bg'>
        <div class='canvas-wrap'>
            <div class='login-cont'>
                <form id='login_form' onsubmit='return false;'>
                    <div class='cont-left'>
                        <div class='login-txt'>
                            <img src='<?=$PATH['RESOURCES']?>/image/siljeok/siljeoklogo.png' alt='logo'>
                            <p>로그인</p>
                        </div>
                        <!-- 아이디 -->
                        <div class="id-wrap">
                            <div class="id-tit">아이디</div>
                            <div class='input-box name-box'>
                                <input type="text" name="id" data-key='id' required placeholder="아이디를 입력하세요"/>
                            </div>
                        </div>
                        <!-- 패스워드 -->
                        <div class="pw-wrap">
                            <div class="pw-tit">비밀번호</div>
                            <div class='input-box pass-box'>
                                <input type="password" name="password" data-key='password' required placeholder="비밀번호를 입력하세요" title="패스워드를 입력하세요."/>
                            </div>
                        </div>
                        <!-- 전송 -->                        
                        <button class='login-btn' id='btn-login' type="submit">로그인</button>
                        <!-- <div class="form-control other-btn-cont">
                            <p onclick="location.href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['ADMIN']?>/page/join.php'"><span>회원가입</span></p>
                            <p onclick="location.href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['ADMIN']?>/page/find.php'"><span>ID찾기/PW재설정</span></p>
                        </div> -->
                    </div>                
                </form>
            </div>
        </div>
    </div>









<script src="<?=$PATH['RESOURCES']?>/plugins/js/jquery-3.3.1.min.js"></script>
<script src="<?=$PATH['RESOURCES']?>/js/fitsoft.js?<?=$SITE['UPDATEDATE']?>"></script>	
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<script src="<?=$PATH['RESOURCES']?>/js/api.js?<?=$SITE['UPDATEDATE']?>"></script>

<script>
/**************************************************** GLOBAL VARIABLE VALUE DEFINED START ****************************************************/
    var g_id = "id";                                              // 아이디 TAG NAME
    var g_password = "password";                                  // 패스워드 TAG NAME
    
    var g_saved_id = "saved_id";                                  // 저장된 아이디
    var g_saved_id_check = "saved_id_check";                      // 아이디 저장 상태
    var g_saved_password = "saved_password";                      // 저장된 비밀번호
    var g_saved_auto_login_check = "saved_auto_login_check";      // 저장된 
/**************************************************** GLOBAL VARIABLE VALUE DEFINED END ****************************************************/
/**************************************************** INITIALIZE FUNCTION START ****************************************************/   
    $(function(){
        // 자동 로그인 (2020.01.08 By.Chungwon)
        autoLogin();

        // 아이디 자동으로 세팅하기 (2020.01.08 By.Chungwon)
        setID();
        
        // 로그인 클릭 바인딩 (2020.01.08 By.Chungwon)
        $('#login_form').on('submit', clickLogin);        
    });
/**************************************************** INITIALIZE FUNCTION END ****************************************************/
/***************************************************** SET FUNCTION START *****************************************************/    
    // 아이디 자동으로 세팅하기 (2020.01.08 By.Chungwon)
    function setID(){
        var saved_session = getLoginSession();

        if(saved_session['id_check'] === 'true'){
            // 아이디 저장 상태 반영
            $('input[name=' + g_saved_id_check + ']').prop("checked", true);
            $('input[name=' + g_id + ']').attr("value", saved_session['id']);
        }
    }
    // 쿠키에 값 저장하기 (2020.01.08 By.Chungwon)
    function setLoginSession(key, value){
        localStorage.setItem('<?=$SITE['E_NAME']?><?=$PREFIX['ADMIN']?>_' + key, value);
    }
/***************************************************** SET FUNCTION END *****************************************************/
/***************************************************** GET FUNCTION START *****************************************************/   
    // 쿠키에 저장된 값 가져오기 (2020.01.08 By.Chungwon)
    function getLoginSession(){
        return {
            id : localStorage.getItem('<?=$SITE['E_NAME']?><?=$PREFIX['ADMIN']?>_' + g_saved_id),
            password : localStorage.getItem('<?=$SITE['E_NAME']?><?=$PREFIX['ADMIN']?>_' + g_saved_password),
            id_check : localStorage.getItem('<?=$SITE['E_NAME']?><?=$PREFIX['ADMIN']?>_' + g_saved_id_check),
            login_check : localStorage.getItem('<?=$SITE['E_NAME']?><?=$PREFIX['ADMIN']?>_' + g_saved_auto_login_check),
        };
    }
/***************************************************** GET FUNCTION END *****************************************************/
/***************************************************** BINDING FUNCTION START *****************************************************/   
    // 로그인 버튼 클릭 이벤트 (2020.01.08 By.Chungwon)
    function clickLogin(e){
        var id = $('input[name=' + g_id + ']').val();
        var password = $('input[name=' + g_password + ']').val();

        sendAPI("/admin/member", "login", { id : id, password : password }, function(res){
            // 아이디 저장
            setSessionID(id);
            // 자동 로그인 저장
            setSessionAutoLogin(password);

            // 대시보드로 이동
            location.href = '<?=$PATH['HTTP_ROOT']?><?=$PREFIX['ADMIN']?>/page/dashboard.php';
        });
    }
/***************************************************** BINDING FUNCTION END *****************************************************/
/***************************************************** ACTION FUNCTION START *****************************************************/    
    // 아이디 저장 (2020.01.08 By.Chungwon)
    function setSessionID(id){
        var saved_id_check = $('input[name=' + g_saved_id_check + ']').prop("checked");

        if(saved_id_check){
            // 아이디 저장이 눌렸다면, 상태와 아이디를 로컬 세션에 저장
            setLoginSession(g_saved_id_check, "true");
            setLoginSession(g_saved_id, id);
        }else{
            // 아이디 저장이 눌리지 않았다면, 상태와 아이디를 로컬 세션에서 삭제
            setLoginSession(g_saved_id_check, "false");
            setLoginSession(g_saved_id, "");
        }
    }
    // 자동 로그인 저장 (2020.01.08 By.Chungwon)
    function setSessionAutoLogin(pw){
        var saved_auto_login_check = $('input[name=' + g_saved_auto_login_check + ']').prop("checked");
        
        if(saved_auto_login_check){
            // 자동 로그인이 눌렸다면, 패스워드를 로컬 세션에 저장
            setLoginSession(g_saved_auto_login_check, "true");
            setLoginSession(g_saved_password, pw);
        }else{
            // 자동 로그인이 눌리지 않았다면, 패스워드를 로컬 세션에서 삭제
            setLoginSession(g_saved_auto_login_check, "false");
            setLoginSession(g_saved_password, "");
        }
    }
/***************************************************** ACTION FUNCTION END *****************************************************/
/**************************************************** UTILITY FUNCTION START *****************************************************/
    // 자동 로그인 (2020.01.08 By.Chungwon)
    function autoLogin(){
        var saved_session = getLoginSession();

        if(saved_session['login_check'] === 'true'){
            // 자동 로그인이 선택된 경우
            var login_params = { 
                id : saved_session['id'], 
                password : saved_session['password']
            };

            sendAPI("/admin/member", "login", login_params, function(res){
               
                // 로그인 성공
                setLoginSession(g_saved_auto_login_check, "true");
                location.href = '<?=$PATH['HTTP_ROOT']?><?=$PREFIX['ADMIN']?>/page/dashboard.php';
           });

        }
    }
    // input text 클릭 시 값 비우기 (2020.01.13 By.Chungwon)
    function setInputText(e){
        var target = $(e.currentTarget);
        target.val("");
    }
/**************************************************** UTILITY FUNCTION END *****************************************************/
</script>
</body>
</html>
