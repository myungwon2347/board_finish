<?php 
    /*
        관리자 메인 접근 권한페이지
        2020.03.29 By.Chungwon
    */
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 
    

    
    if(isset($_SESSION['login_user']) && $_SESSION['login_user']['auth_level'] !== 'admin')
    {
        // 로그인 된 계정이 관리자인 경우 대시보드로 이동
        header("Location:" . $PATH['HTTP_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/page/dashboard.php");
    }else
    {
        // 아닌 경우 로그인 페이지로 이동
        header('Location:' . $PATH['HTTP_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . '/page/login.php');
    }
?>