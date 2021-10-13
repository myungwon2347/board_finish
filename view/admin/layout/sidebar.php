<?php
    namespace service;
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

	use util\Log;

	if(isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] === 'admin')
    {
    }else
    {
        // 관리자가 아닌 경우 로그인 페이지로 이동
		header('Location:' . $PATH['HTTP_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . '/page/login.php');
		return;
	}
?>
<style>

/* Chrome, Safari용 스크롤 바 */
nav::-webkit-scrollbar {display:none;}

nav.nav-side-bar{position:fixed; z-index:3;top:0; background: #0D2EA3; color: #626b7a; height: 100%; width: 230px; box-shadow:-1px 0 0 #d8dddf inset; overflow-y:scroll; padding-bottom:60px;}
nav.nav-side-bar li{cursor:pointer;}
nav.nav-side-bar li:hover{font-weight:600;}
nav.nav-side-bar .deep1 > li{color:#818a91; font-size:13px; color:#2c333e; }
nav.nav-side-bar .deep1 > li a{width:100%;height:100%;display:inline-block;padding:15px 32px;}
nav.nav-side-bar .deep1 > li.active{font-weight:600;background-color:#042188; width : 99%}
nav.nav-side-bar .deep2{display:none;}
nav.nav-side-bar .deep2.active{display:block;}
nav.nav-side-bar .deep2 > li{color:#818a91;font-size:12px;}
nav.nav-side-bar .deep2 > li a{padding : 15px 0 15px 42px; display:inline-block;width:99%;height:100%; color :#fff;}
nav.nav-side-bar .deep2 > li.active{width:99%;}
.nav-cont.active{background-color:#042188;}
nav.nav-side-bar .deep2 > li:hover{}
nav.nav-side-bar .deep3 > li{color:#818a91; border-bottom:1px solid gainsboro; font-size:12px;background-color:#fff; padding-left:88px; padding-top: 8px; padding-bottom: 8px; border-right:1px solid gainsboro;}
nav.nav-side-bar .deep3 > li.active{ }
nav.nav-side-bar .deep3 > li:hover{box-shadow:2px 2px 4px lightgray; padding-left:91px !important;}
nav.nav-side-bar .fa, nav.nav-side-bar .far, nav.nav-side-bar .fas{padding-right:8px;}
.nav-side-bar .deep1 .show-nav span {color : #fff; font-weight : 500;}
.nav-side-bar .deep1 .show-nav a {display: flex;align-items: center;justify-content: space-between;}
.nav-side-bar .deep1 .show-nav a i {color : #fff; transition:all .2s;}
.nav-side-bar .deep1 .show-nav a i.xi-angle-right-thin.active {transform:rotate(90deg);}
.nav-side-bar .side-bar-title-m {display:none;}
.nav-side-bar .side-bar-title {display : flex ; justify-content : space-between ; padding : 20px; align-items : center; margin-bottom : 20px;}
.nav-side-bar .side-bar-title .title-company {display : flex ; align-items : center;}
.nav-side-bar .side-bar-title .title-company .company-name span {color:#fff; padding-left:8px; font-size:.7rem; letter-spacing:1px;}
.nav-side-bar .side-bar-title .title-company .company-name a {font-size : 1.4rem ; color : #fff; font-weight:600;}
.nav-side-bar .side-bar-title .title-company .company-logo{display : flex; align-items : center; background : #1dd6d0 ; border : 1px solid #1dd6d0; border-radius : 5px ; width : 30px; height : 30px;}
.nav-side-bar .side-bar-title .title-company .company-logo img {width :70% ; height :auto; margin-left :4px;}
.nav-side-bar .side-bar-title .title-home {}
.nav-side-bar .side-bar-title .title-home a button i {color : #fff ; font-size : 1.4rem}
.nav-cont img {width : 15px ; height : auto ; margin-right : 20px;}
@media screen and (max-width: 768px) {
    nav.nav-side-bar {display:none; width:100%; overflow-y:scroll; padding-bottom:60px;}
    nav.nav-side-bar .deep2 > li a {padding-left:20px;}
    
}

.tit-main{position: relative;padding: 10px 20px;display: flex;color: #fff;align-items: center;margin-top:30px;}
.horizon-bar {height: 1px;background-color: #fff;position: absolute;top: 50%;right: 20px;width: 50%;}


.today-interview {display:flex; align-items:center; color:#444; padding:12px 22px;margin:20px; background-color:#fff; border-radius:30px; border:1px solid #ddd;    justify-content: center;}
.today-interview i {}
.today-interview .interview-count {margin-left:8px; display:flex; align-items:center;}
.today-interview .interview-count span {font-size:.9rem;}
.today-interview .interview-count span.today-count {margin-left:4px; display:flex; align-items:center; justify-content:center; width:19px; height:19px; border-radius:50%; background-color:#1a7ff0;}
.today-interview .interview-count span.today-count i {color:#fff; font-size:.8rem;}

</style>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">

<nav class='nav-side-bar'>
<div class='side-bar-title'>
        <div class='title-company'>
            <!-- <div class='company-logo'>
                <img src="<?=$PATH['HTTP_ROOT']?>/resources/image/fit-logo.png" alt="">
            </div> -->
            <p class='company-name'>
                <a href='/admin/page/dashboard.php'><?=isset($_SESSION['login_user']) ? $_SESSION['login_user']['name'] : "로그인을 해주세요."?></a>
                <span>
                    실적스토어
                    <a href='/' target='_blank'><i class='xi-home'></i>&nbsp;</a>
                </span>
                
            </p>
        </div>
    </div>
    <div class='side-bar-title-m'>
        <div class='title-company'>
            <div class='company-logo'>
                <img src="<?=$PATH['HTTP_ROOT']?>/resources/image/fit-logo.png" alt="">
            </div>
            <p class='company-name'><a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/index.php'>실적스토어</a></p>
            <div class='company-logout'><button type='button' onclick="logout();"><i class='xi-log-out'></i></button></div>
        </div>
    </div>
    <ul class="deep1 sub-nav">
        <?php
            for($i = 0; $i < count($cache_manage_page_list); $i++) {
                $page = $cache_manage_page_list[$i];
                $menu_list = $page['sub_menu'];

                if(count($menu_list) < 1)
                {// 1차원인 경우 (숫자)
                    echo createMenu($page, "1");
                }
                else{// 2차원인 경우 (문자열)
                    $str_menu = "";
                    
                    for($j = 0; $j < count($menu_list); $j++) {
                        $menu = $menu_list[$j];

                        $str_menu .= createMenu($menu, "2");
                    }

                    if($str_menu !== "")
                        echo createFolder($page, $str_menu);
                }
            }

            function createFolder($folder, $str_menu)
            {// 사이드바 폴더 생성
                $active_class2 = "";

                if(isset($folder['import_folder']) && $folder['import_folder'] !== "준비중")
                {
                    $folder['import_folder'] = explode(",", $folder['import_folder']);

                    for($i = 0; $i < count($folder['import_folder']); $i++)
                    {
                        if(empty($folder['import_folder'][$i])) { continue; }
                        if(strpos($_SERVER['REQUEST_URI'], "{$folder['import_folder'][$i]}") !== false ) { $active_class2 = "active"; }
                    }
                }
                    
                return "
                    <li class='show-nav {$active_class2}'>
                        <a>
                            <span>{$folder['name']}</span>
                            <i class='xi-angle-right-thin {$active_class2}'></i>
                        </a>
                    </li>
                    <ul class='deep2 sub-nav {$active_class2}'>
                        {$str_menu}
                    </ul>
                ";
            }

            function createMenu($page, $type = "1")
            {// 사이드바 메뉴 생성
                
                $is_access = checkUserPageAccess($page);

                if($is_access === false)
                {// 접근 권한이 없으면 생성하지 않음.
                    return "";
                }

                $active_class = "";
                
                if(isset($page['import_folder']) && $page['import_folder'] !== "준비중")
                {
                    $import_list = explode(",", $page['import_folder']);
                    
                    for($i = 0; $i < count($import_list); $i++)
                    {
                        $import = $import_list[$i];
                        if(empty($import) === false && strpos($_SERVER['REQUEST_URI'], $import) !== false) 
                        {// import가 비어있거나 현재 URL에 포함된 경우 active
                            $active_class = "active"; 
                        }
                    }
                }
                $link = "";
                if(strpos($page['file_link'], "https://") !== false || strpos($page['file_link'], "http://") !== false)
                {// http가 들어있는 경우
                    $link = "href='{$page['file_link']}' target='_blank'";
                }else
                {
                    $link = $page['file_link'] === "준비중" ? "" : "href='{$GLOBALS['PATH']['HTTP_ROOT']}{$page['prefix']}{$page['file_link']}'";
                }
                $onclick = $page['file_link'] === "준비중" ? "onclick='alert(&#39;준비중입니다.&#39;);'" : "";

                if($type === "1")
                {
                    return "
                            <li class='show-nav {$active_class}'>
                                <a {$link} {$onclick}>
                                    <span>{$page['name']}</span>
                                </a>
                            </li>
                        ";
                }else
                {
                    return "
                        <li class='nav-cont {$active_class}'>
                            <a {$link} {$onclick}>
                                <img src='{$page['icon']}' alt=''>
                                <span class='nav-name {$active_class}'>
                                    {$page['name']}
                                </span>
                            </a>
                        </li>
                    ";
                }
            }
        ?>
    </ul>

    <!-- <div class='today-interview' id='go_interview_list'>
        <i class='xi-bell'></i>
        <p class='interview-count'>
            02-6959-8468
        </p>
    </div> -->
</nav>

<script>

    $(function(){
        sideMenuSet();
        // 사이드바 클릭했을 때 방향 아이콘 변경
        $('.show-nav').on('click', clickSideMenu);
    })


    function sideMenuSet(){
        var nav = $('.show-nav');
        var target = nav.has('.active');
        
        
    }


    // 사이드바 클릭했을 때 방향 아이콘 변경
    function clickSideMenu(e){
        var target = $(e.currentTarget);
        var arrow = target.find('i.xi-angle-right-thin');

        arrow.toggleClass('active');
    }



    $('.show-nav').on('click', function(e){
        e.stopPropagation();
        
        $(e.currentTarget).next('.sub-nav').toggle(250, function(){
        });
    });
</script>