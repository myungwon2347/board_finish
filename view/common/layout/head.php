<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

	mobileHandler();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<title><?=$SEO['TITLE']?></title>

    <meta name="title" content="<?=$SEO['TITLE']?>">
    <meta name="description" content="<?=$SEO['DESCRIPTION']?>">
    <meta name="keywords" content="<?=$SEO['KEYWORD']?>">

	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' />

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?=$SEO['TITLE']?> ">
    <meta property="og:description" content="<?=$SEO['DESCRIPTION']?>">
    <meta property="og:image" content="<?=$SITE['LINK']?>/og_image.jpg">
    <meta property="og:locale" content="<?=$SEO['LOCALE']?>">
    <meta property="og:site_name" content="<?=$SITE['NAME']?>">
    <meta property="og:url" content="<?=$SITE['LINK']?>/">

    <meta property="al:web:url" content="<?=$SITE['LINK']?>/">

    <meta name="author" content="<?=$SITE['NAME']?>">	
    <meta name="content-language" content="<?=explode('_', $SEO['LOCALE'])[0]?>">
	<meta name="format-detection" content="telephone=no" />

    <link rel="canonical" href="<?=$SITE['LINK']?>/">	
    <!-- Favicons -->
	<link rel="shortcut icon" href="/favicon.ico">
	
	<!-- Resources Import -->
	<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
	<link rel="stylesheet" type="text/css" href="<?=$PATH["RESOURCES"]?>/plugins/css/slick.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="<?=$PATH['RESOURCES']?>/plugins/js/jquery-3.3.1.min.js"></script>
	<script src="<?=$PATH['RESOURCES']?>/plugins/js/timeago.js"></script>
	<script src="<?=$PATH['RESOURCES']?>/plugins/js/slick.min.js"></script>
	
	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<!-- input file type 호환  -->
	<script src="https://cdn.polyfill.io/v3/polyfill.min.js"></script>
	<!-- formdata 호환 -->
	<script src="<?=$PATH['RESOURCES']?>/plugins/js/formdata.min.js"></script>	
	
	<script>
		// PHP 변수 동기화
		var FITSOFT = {};

		FITSOFT['DATA'] = {
			CONTEXT_PATH : "<?=$CONTEXT_PATH?>",

			PATH : {
				RESOURCES : "<?=$PATH['RESOURCES']?>",
				HTTP_ROOT : "<?=$PATH['HTTP_ROOT']?>",
			},
			SITE : {
				LINK : "<?=$SITE['LINK']?>",
			},
			PREFIX : {
				FILE : "<?=$PREFIX['FILE']?>",
				FRONT : "<?=$PREFIX['FRONT']?>",
				ADMIN : "<?=$PREFIX['ADMIN']?>",
			}
		};


		function check_no_image(file_url){

		if(empty(file_url) || file_url == false){
			return "/resources/image/no_image.svg";
		}else if(file_url.indexOf('data:') != -1){
			return file_url;
		}
		return '/upload' + file_url;
		}
	</script>

	<!-- Custom CSS & JS -->
	<link rel="stylesheet" type="text/css" href="<?=$PATH["RESOURCES"]?>/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?=$PATH["RESOURCES"]?>/css/main.css">
	
	<script src="<?=$PATH['RESOURCES']?>/js/fitsoft.js?<?=$SITE['UPDATEDATE']?>"></script>	
	<script src="<?=$PATH['RESOURCES']?>/js/common.js?<?=$SITE['UPDATEDATE']?>"></script>	

		
	
	
</head>

<body>