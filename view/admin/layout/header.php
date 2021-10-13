<style>
	.top-nav{position:fixed;width:100%; z-index:1;}
	nav.nav-top-bar {position:relative; z-index:5; display:flex; align-items:center; justify-content : space-between; padding :25px; margin-left:230px;  background : #fff; border-bottom:1px solid #e5e5e5;}
	nav.nav-top-bar .nav-l {}
	nav.nav-top-bar .nav-l a {display:flex; align-items:center; font-size:1rem; font-weight:600;}
	nav.nav-top-bar .nav-l a i {font-size:1.1rem; font-weight:600;}
	nav.nav-top-bar .nav-r {display:flex; align-items:center;}
	nav.nav-top-bar .nav-icon {padding:0 12px;}
	nav.nav-top-bar .nav-icon:last-child {padding-right:0;}
	nav.nav-top-bar .nav-icon button {display:flex; align-items:center;}
	nav.nav-top-bar .nav-icon button i {font-size:1.3rem;}
	nav {position : relative}
	.nav-notice-list {position : absolute ; top : 50px; right : 120px; display : none; background-color : #fff;}
	.nav-notice-list.active {display:block;}
	.nav-notice-list ul {padding : 5px 15px; border : 1px solid #ddd ; border-radius : 5px;}
	.nav-notice-list ul li {border-bottom :1px solid #eee ; padding :8px; font-size : 0.8rem;}
	.nav-notice-list ul a:last-child li {border-bottom : none;}

	@media screen and (max-width: 768px) {
		nav.nav-top-bar {padding-left:10px;display :none; }
        .wrap {padding-left:140px !important; max-width:calc(140px + 768px) !important;}
    }

</style>

<div class='top-nav'>
	<nav class='nav-top-bar'>
        <div class='nav-l title-home'>
            <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['ADMIN']?>/page/dashboard.php' target='_blank'><i class='xi-home'></i>&nbsp; Dashboard</a>
        </div>
        <div class='nav-r'>
            <!-- <div class='nav-icon nav-notice'><button type='button'><img src="<?=$PATH['HTTP_ROOT']?>/resources/image/notification.png" alt=""><i class='xi-bell-o'></i></button></div>
            <div class='nav-notice-list'>
                <ul>
                    <a href="#none"><li>작성하신 글에 댓글이 달렸습니다.</li></a>
                </ul>
            </div>
            <div class='nav-icon nav-message'><button type='button'><i class='xi-message-o'></i></button></div> -->
            <?=isset($_SESSION['login_user']) && ($_SESSION['login_user']['idx'] === "1074") ? "<div class='nav-icon nav-message'><button type='button' onclick='cacheReset();'><i class='xi-catched'></i></button></div>" : "";?>
            <div class='nav-icon nav-logout'><button type='button' onclick="logout();"><!--<img src="<?=$PATH['HTTP_ROOT']?>/resources/image/logout.png" alt="">--> <i class='xi-log-out'></i> </button></div>
        </div>
	</nav>
</div>

<script>
	var g_login_level_type = 'admin';
    var g_is_auto_login = localStorage.getItem("<?=$SITE['E_NAME']?><?=$PREFIX['FRONT']?><?=$PREFIX['ADMIN']?>_auto_login") === "true" ? true : false;

	var g_saved_password = "saved_password";                      // 저장된 비밀번호
	var g_saved_auto_login_check = "saved_auto_login_check";      // 저장된 
	
	$(function(){
        // 알림 리스트
		$('.nav-notice button').on('click', function(e){
			$('.nav-notice-list').slideToggle();
		});
    });

	// 로그아웃
	function logout()
	{
        sendAPI("/admin/member", "logout", {}, function(res){
            // 자동 로그인 초기화
            // localStorage.setItem('email', '');
            localStorage.setItem('<?=$SITE['E_NAME']?><?=$PREFIX['ADMIN']?>_' + g_saved_password, '');
            localStorage.setItem('<?=$SITE['E_NAME']?><?=$PREFIX['ADMIN']?>_' + g_saved_auto_login_check, false);

            location.href = '<?=$PATH['HTTP_ROOT']?><?=$PREFIX['ADMIN']?>/page/dashboard.php';
        });	
	}

	function cacheReset()
	{
        sendAPI("/admin/member", "cache_reset", {}, function(res){
			if(res.result)
			{
				location.reload();
			}
        }, null);
    }
</script>