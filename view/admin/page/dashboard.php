<?php 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 
    use util\Log;

    header("Location:" . $PATH['HTTP_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/page/board/list.php");   

    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/head.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/sidebar.php";
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/header.php";
?>
<style>
    .wrap{padding-left:230px; padding-bottom:60px; padding-top:74px; max-width:100%;}
    body{background-color : #f4f8fb ;}
</style>

<div class='wrap'>
</div>

<?php
    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['ADMIN'] . "/layout/footer.php";
?>