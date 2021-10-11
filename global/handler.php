<?php
	// 현재페이지
	$current_page = $_SERVER['PHP_SELF'];

	// 유저의 현재페이지 접근 권한 확인
	$is_access = checkPageAccess($current_page, $cache_manage_page_list);

    if($is_access === false)
	{
		$auth_level = strtoupper($_SESSION['login_user']['role']);
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $GLOBALS['PATH']['HTTP_ROOT'] . $GLOBALS['PREFIX'][$auth_level];
        session_destroy();

		echo "
			<script>
				alert('접근권한이 없습니다.');
				location.href = '{$referer}';
			</script>
		";

		return;
	}

	