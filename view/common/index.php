<?php
    namespace service;
   
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 

    header("Location: {$PATH['HTTP_ROOT']}{$PREFIX['FRONT']}{$PREFIX['COMMON']}/page/board/common/list.php");

    require_once $PATH['SERVER_ROOT'] . $PREFIX['FRONT'] . $PREFIX['COMMON'] . "/layout/head.php";
?>


<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/xeicon@2.3.3/xeicon.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" rel="stylesheet">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<style>
    .main-container {margin: 0 auto; padding-top:150px;}
    .main-container .s-left { width:30%;}
    .main-container .s-right { width:70%;}
    .main-container .s-left .sl-top .sub-tit {font-family:'Montserrat', sans-serif; font-weight:500; font-size:0.75rem; color:#f6b518;}
    .main-container .s-left .sl-top .main-tit {font-family: 'Noto Sans KR', sans-serif; font-weight:700; font-size:2.5rem;}

    .swiper-slide a {width:100%; height:100%; display:inline-block;}
    .sl-bottom a {width:115px; height:100%; display:inline-block;}

    .section1 {width:100%; position:relative; }
    .section1 .swiper-container {width:1440px; margin:25px auto 87px;}
    .section1 .swiper-container .swiper-slide img {width:272px; height:391px;}
    .section1 .swiper-container .swiper-slide .main-onfif-slide {margin-top:24px;}
    .section1 .swiper-container .swiper-slide .main-onfif-slide .main-onfif-tit {font-family:'Noto Sans KR',sans-serif; font-weight:700; font-size:1.5rem; padding-bottom:12px; width:272px;}
    .section1 .swiper-container .swiper-slide .main-onfif-slide .main-onfif-date {font-family:'Montserrat', sans-serif; font-size:1rem; }
    .section1 .swiper-prev {background-image:url(<?=$PATH['RESOURCES']?>/image/icon/left-arrow.png); width:50px; height:50px; position:absolute; top:40%; left:6.25vw; z-index:1; cursor: pointer;}
    .section1 .swiper-next {background-image:url(<?=$PATH['RESOURCES']?>/image/icon/right-arrow.png); width:50px; height:50px; position:absolute; top:40%; right:6.25vw; z-index:1; cursor: pointer;}

    .section1 .swiper-container .swiper-slide[aria-label="4 / 5"] {pointer-events:none;}
    .section1 .swiper-container .swiper-slide[aria-label="5 / 5"] {pointer-events:none;}


    .section2 {width:100%; background:url(<?=$PATH['RESOURCES']?>/image/onfif/main-bg.jpeg); display:flex;}
    .section2 .bg {width:100%; height:100%; padding:122px 0 86px; background:rgba(0,0,0,0.5); display:flex;}
    .section2 .s2-wrap {width:1360px; margin:0 auto; display:flex; align-items:center;justify-content:space-between;}
    .section2 .s-left .sl-top {margin-bottom:104px;}
    .section2 .s-left .sl-top .main-tit {color:#fff;}
    .section2 .s-right {display:flex;}
    .section2 .s-right .s2-box {margin-right:30px; width:220px;}
    .section2 .s-right .s2-box a {display:inline-block; width:100%;}
    .section2 .s-right .s2-imgArea {width:220px; height:220px; overflow:hidden; position:relative;border-radius:15px;}
    .section2 .s-right .s2-imgArea img {position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:100%; }
    .section2 .s-right .s2-txtArea {color:#fff;font-family:'Noto Sans KR',sans-serif; font-size:1rem; font-weight:700; margin-top:14px; width: 100%;}

    .section3 {width:1360px; padding:122px 0 128px; margin:0 auto; display:flex; justify-content:space-between; align-items:center; background-color:#fff;}
    .section3 .s-left .sl-top {margin-bottom:104px;}
    .section3 .s-right .s3-notice {border-bottom:1px dashed #dbdbdb; padding-bottom:11px; padding-top:15px; }
    .section3 .s-right .s3-notice .s3-notice-date {font-family:'Montserrat', sans-serif; font-size:1rem; color:#6f6f6f; margin-right:30px;}
    .section3 .s-right .s3-notice .s3-notice-tit {font-family:'Noto Sans KR',sans-serif; font-size:1.125rem; font-weight:500;}

    
    .section3 .s-right .s3-notice a {display:flex; align-items:center;}
    .section3 .s-right .s3-notice a .s3-notice-date {flex:.1;}
    .section3 .s-right .s3-notice a .s3-notice-tit {flex:.9;}

    

    .section4 {width:100%; padding:95px 0 75px; background-color:#ffb518; display:flex; align-items:center;}
    .section4 .s-left {padding-left:9.635vw; padding-right:9.6vw; width:unset;}
    .section4 .s-left .sl-top {min-width:202px;}
    .section4 .s-left .sl-top .main-tit {font-size:30px; font-weight:700;}
    .section4 .s-left .sl-top .sub-tit {font-size:16px; font-weight:500; color:#000;font-family:'Noto Sans KR',sans-serif; margin:16px 0 52px;}

    /* .section4 .swiper-container .swiper-slide {width:100% !important;} */
    .section4 .swiper-container .swiper-slide .imgArea {height:200px; width:100%; overflow:hidden; position:relative;}
    .section4 .swiper-container .swiper-slide .imgArea img {min-width:100%; height:100%; object-fit:cover; position:absolute; top:50%; left:50%;transform:translate(-50%,-50%);}
    .section4 .swiper-container .swiper-slide .txtArea {padding:25px 10px; width:100%; background-color:#fff; display:flex; align-items:center; flex-direction:column; justify-content:center;}
    .section4 .swiper-container .swiper-slide .txtArea .ta-movie-tit {font-family:'Noto Sans KR',sans-serif; text-align:center; font-weight:500; font-size:1.25rem;margin-bottom:4px; width:100%; padding:0 10px;}
    .section4 .swiper-container .swiper-slide .txtArea .ta-movie-info {font-family:'Montserrat', sans-serif; font-size:1rem; color:#3c3c3c;}
    .section4 .swiper-container .swiper-slide .txtArea .ta-movie-info span {padding-right:9px; }
    .section4 .swiper-container .swiper-slide .txtArea .ta-movie-info span:first-child:before {content:none;}
    .section4 .swiper-container .swiper-slide .txtArea .ta-movie-info span:before {content:""; height:11px; width:0.5px; background-color:#3c3c3c; display:inline-block;margin-right:9px;}

    .section4 .sl-bottom {display:flex; justify-content:space-between;}
    .section4 .sl-bottom .swiper-prev {background-image:url(<?=$PATH['RESOURCES']?>/image/icon/left-arrow.png); background-color:#fff; background-size:41px 41px; border-radius:50%; width:40px; height:40px; cursor: pointer; margin-right:4px;}
    .section4 .sl-bottom .swiper-next {background-image:url(<?=$PATH['RESOURCES']?>/image/icon/right-arrow.png); background-color:#fff; background-size:41px 41px; border-radius:50%; width:40px; height:40px; cursor: pointer;}

    .onfif-btn-more {padding:10px 20px; border:1px solid #d8d8d8; box-sizing:border-box; border-radius:50px; display:flex; align-items:center; justify-content:center; width:115px; height:40px; transition:all 0.25s ease-out;}
    .onfif-btn-more .btn-more-txt {font-family:'Noto Sans KR', sans-serif; font-weight:600; font-size:0.875rem; color:#676767; padding-right:8px;}
    .onfif-btn-more i {transition:all .25s ease-out; color:#353535;}
    .onfif-btn-more:hover i {transform:translateX(4px); color:#fff;}
    .onfif-btn-more.white:hover i {color:#000;}
    .onfif-btn-more:hover {background-color:#353535; color:#fff;}
    .onfif-btn-more:hover .btn-more-txt {color:#fff;}
    .onfif-btn-more.white {border:1px solid #fff;}    
    .onfif-btn-more.white i {color:#fff;}
    .onfif-btn-more.white .btn-more-txt, .btn-more.white i {color:#fff;}
    .onfif-btn-more.white:hover {background-color:#fff; color:#353535;}
    .onfif-btn-more.white:hover .btn-more-txt, .btn-more.white:hover i {color:#353535;}

    @media screen and (max-width: 768px) {

        
        .swiper-slide a {text-align:center;}
        
        .main-container {padding-top:70px;}
        .section1 .swiper-container {margin:25px auto; padding:0 50px; width:100%;}
        .section1 .swiper-container .swiper-slide .main-onfif-slide .main-onfif-tit {width:95%;}
        .section1 .swiper-container .swiper-slide img { height:100%;}

        /* 뉴스레터 */
        .section2 {background-image:none;}
        .section2 .bg {background:#fff; padding:40px 20px;}
        .section2 .s2-wrap {width:100%; flex-direction:column;}
        .main-container .s-left {width:100%; display:flex; align-items:center; justify-content:space-between; margin-bottom:40px;}
        .section2 .s-left .sl-top {margin-bottom:0;}
        .section2 .s-left .sl-top .main-tit {color:#000; font-size:2rem;}
        .section2 .main-container .s-left .sl-top .sub-tit {display:none;}
        .section2 .s-left .sl-bottom {}
        .section2 .s-left .sl-bottom a .btn-more {border-color:#d8d8d8;}
        .section2 .s-left .sl-bottom a .btn-more span, .section2 .s-left .sl-bottom a .btn-more i {color:#676767;}
        .main-container .s-right {width:100%; display:block;}
        .section2 .s-right .s2-box {display:inline-block; width:calc(100% / 2 - 9px); margin-right:14px;}
        .section2 .s-right .s2-box:nth-child(2n) {margin-right:0;}
        .section2 .s-right .s2-box:nth-child(2n)~.s2-box {margin-top:40px;}
        .section2 .s-right .s2-box a {display:inline-block;}
        .section2 .s-right .s2-box a .s2-imgArea {width:100%;}
        .section2 .s-right .s2-imgArea {height:auto;}
        .section2 .s-right .s2-imgArea img {position:relative; top:inherit; left:inherit; transform:inherit;}
        .section2 .s-right .s2-txtArea {color:#000;}

        /* 공지사항 */
        .section3 {width:100%; padding:40px 20px 80px; flex-direction:column;}
        .section3 .s-left .sl-top {margin-bottom:0;}
        .section3 .s-left .sl-top .main-tit {font-size:2rem;}
        .section3 .s-right .s3-notice {}
        .section3 .s-right .s3-notice a {display:flex; flex-direction:column-reverse; align-items:flex-start;}
        .section3 .s-right .s3-notice a .s3-notice-tit {width:100%;}
        .section3 .s-right .s3-notice a .s3-notice-date {width:100%; font-size:.9rem; margin-right:0; margin-top:3px;}

        /* 영화제 큐레이션 */
        .section4 {padding:40px 20px; flex-direction:column;}
        .section4 .s-left {padding-left:0; flex-direction:column; align-items:flex-start;}
        .section4 .s-left .sl-top .sub-tit {margin:0; padding:10px 0 20px;}
    }
</style>

<div class='main-container'>
    <div class="section1">
        <div class="swiper-container slider-pc">
            <div class="swiper-wrapper" id='list-getListFestival'>
            </div>
        </div>
        <div class="swiper-prev bg"></div>
        <div class="swiper-next bg"></div>
    </div>
    <div class="section2">
        <div class="bg">
            <div class="s2-wrap">
                <div class="s-left">
                    <div class="sl-top">
                        <p class="sub-tit">Newsletter</p>
                        <h3 class="main-tit">뉴스레터</h3>
                    </div>
                    <div class="sl-bottom">
                        <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/community/newsletter/list.php'>
                            <div class="onfif-btn-more white">
                                <span class="btn-more-txt">더 보기</span>
                                <i class="xi-long-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="s-right" id='list-getListBoard1'>
                </div>
            </div>
        </div>    
    </div>
    <div class="section3">
        <div class="s-left">
            <div class="sl-top">
                <p class="sub-tit">Notice</p>
                <h3 class="main-tit">공지사항</h3>
            </div>
            <div class="sl-bottom">
                <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/community/notice/list.php'>
                    <div class="onfif-btn-more">
                        <span class="btn-more-txt">더 보기</span>
                        <i class="xi-long-arrow-right"></i>
                    </div>
                </a>
            </div>
        </div>
        <div class="s-right">
            <div class="s3-box" id='list-getListBoard2'>
            </div>
        </div>
        
    </div>
    <div class="section4">
        <div class="s-left">
            <div class="sl-top">                
                <h3 class="main-tit">영화제 큐레이션</h3>
                <p class="sub-tit">서울국제대안영상페스티벌<br>프로그래머 추천 영화</p>
            </div>
            <div class="sl-bottom">
                <div class="swiper-prev bg"></div>
                <div class="swiper-next bg"></div>
            </div>            
        </div>

        <div class="s-right">
            <div class="swiper-container slider-pc">
                <div class="swiper-wrapper" id='list-getListMovieClassify'>
                    
                </div>
            </div>
        </div>
    </div>
    <div id='canvas-popup'></div>
    <style>
        #canvas-popup{position:fixed; top:80px; left:120px;z-index:10000;}
        #canvas-popup img{width:360px !important;/*border: 1px solid gainsboro;*/border-bottom:none;}
        #canvas-popup div{cursor:pointer;font-size:11px;}

        @media screen and (max-width: 768px) {
            
            #canvas-popup{position:fixed; top:10px; left:10px;right:10px;width:240px;}
            #canvas-popup img{width:100% !important;}
        }
    </style>
</div>

<script>

// 영화제 슬라이더
function mainSlider(){
    var mainSwiper = new Swiper('.section1 .swiper-container.slider-pc', {
        slidesPerView: 5,
        spaceBetween: 20,
        loop: true,
        navigation: {
            nextEl: '.section1 .swiper-next',
            prevEl: '.section1 .swiper-prev',
        },
        autoplay: {
          delay: 2000,
          disableOnInteraction: false,
        },
    });

    //큐레이션 슬라이더
    var movieSwiper = new Swiper('.section4 .swiper-container.slider-pc', {
        slidesPerView: 4,
        spaceBetween: 20,
        slidesToScroll : 4, //스크롤 한번에 움직일 컨텐츠 개수
        //loop: true,
        navigation: {
            nextEl: '.section4 .swiper-next',
            prevEl: '.section4 .swiper-prev',
        },
        // breakpoints: { //반응형 조건 속성
        // 768: {
        //   slidesPerView: 4,
        // },
    });
}

function mobileFunction(){
    var widthSize = $(window).width();
    // var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ? true : false;

    if(widthSize < 768){
        $('.swiper-container').removeClass('slider-pc');
        $('.swiper-container').addClass('slider-mo');
        $('.section2').find('.onfif-btn-more').removeClass('white');

        var mainSwiperM = new Swiper('.section1 .swiper-container.slider-mo', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            navigation: {
                nextEl: '.section1 .swiper-next',
                prevEl: '.section1 .swiper-prev',
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });

        var movieSwiperM = new Swiper('.section4 .swiper-container.slider-mo', {
            slidesPerView: 1,
            spaceBetween: 22,
            // loop: true,
            navigation: {
                nextEl: '.section4 .swiper-next',
                prevEl: '.section4 .swiper-prev',
            },
        });
    }
    else{
        $('.swiper-container').addClass('slider-pc');
        $('.swiper-container').removeClass('slider-mo');
        $('.section2').find('.onfif-btn-more').addClass('white');
    }

}

</script>




































<script>
    function createPopup1()
    {
        var cookie_check = getCookie("popup_idx1");

        if(cookie_check === "disable"){
            return;
        }

        $("#canvas-popup").append("\
            <div class='item-data' style='width:100%; height:100%;'>\
                <a href='#'>\
                    <img src='<?=$PATH['RESOURCES']?>/image/popup/app_notice.jpg' style='width:100%; height:100%;border:1px solid #000;' alt=''>\
                </a>\
                <br/>\
                <div style='display: flex; justify-content: space-between;background-color: black;color: #fff;padding: 6px 10px;'>\
                    <div class='btn-delete2'>24시간 동안 다시보지 않기</div>\
                    <div class='btn-delete1'>닫기</div>\
                </div>\
            </div>\
        ")
        
        // 팝업 닫기
        $("#canvas-popup").find('.btn-delete1').off('click').on('click', function(e){
            var edata = getEventData(e, 'item-data');
            edata['parent'].remove();
            console.log(edata);
        });
        // 24시간 닫기
        $("#canvas-popup").find('.btn-delete2').off('click').on('click', function(e){
            
            var edata = getEventData(e, 'popup-item.item-data');
            setCookie('popup_idx1', 'disable', 24);

            var edata = getEventData(e, 'item-data');
            edata['parent'].remove();
        });
    }
    /**************************************************** 초기화 *********************************************/
    $(function(){
        createPopup1();

        sendAPI("/onfif/festival", "getListFestival", {}, function(res)
        {// 영화제 목록 가져오기
            $(res['data_list']).each(function(index, data){
                add("getListFestival", { item : data });
            });

            mainSlider();
            // 모바일일 때
            mobileFunction();
            $(window).resize(mobileFunction);
        });
        sendAPI("/common/board", "getListBoard", { data_render_count : 4, type_idx : 1, category_idx : 5 }, function(res)
        {// 뉴스레터 목록 가져오기
            $(res['data_list']).each(function(index, data){
                add("getListBoard1", { item : data });
            });
        });
        sendAPI("/common/board", "getListBoard", { data_render_count : 5, type_idx : 1, category_idx : 6 }, function(res)
        {// 공지사항 목록 가져오기
            $(res['data_list']).each(function(index, data){
                add("getListBoard2", { item : data });
            });
        });
        sendAPI("/onfif/movie", "getListMovieClassify", { data_render_count : 10, type : 1 }, function(res)
        {// 추천 영화 목록 가져오기
            $(res['data_list']).each(function(index, data){
                add("getListMovieClassify", { item : data });
            });

            mainSlider();
            // 모바일일 때
            mobileFunction();
            $(window).resize(mobileFunction);
        });
    });
    /**************************************************** 초기화 끝 *********************************************/

    /**************************************************** 셋팅 ******************************************/
    function add(api_name, res)
    {// 데이터 추가 메소드

        /******************** 변수세팅 ********************/
        var item = res['item'];
        var is_end = empty(res['is_end']) ? true : res['is_end'];
        var attach = empty(res['attach']) ? 'append' : res['attach'];
        var $canvas = empty(res['canvas']) ? $("#list-" + api_name) : res['canvas'];
        /******************** 변수세팅 끝 ********************/
        
        var html = create(api_name, item);
        $canvas[attach](html);
    }
    /**************************************************** 셋팅 끝 ******************************************/
    
    /**************************************************** HTML 생성 *********************************************/    
    function create(api_name, data)
    {// HTML 생성 - 수신값은 전부 문자열
        data['api_name'] = api_name;

        if(false){}

        else if(api_name === "getListFestival")
        {// 영화제 목록 조회 (2021.07.27 / By.Chungwon)

            // [날짜] - 시/분/초 짜르기
            data['open_date'] = empty(data['open_date']) ? "" : data['open_date'].split(" ")[0];
            data['close_date'] = empty(data['close_date']) ? "" : data['close_date'].split(" ")[0];

            return StringFormat("\
                <div class='swiper-slide' {0}>\
                    <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/festival/main.php?festival_idx={1}'>\
                        <img src='{2}' alt='slide1'>\
                        <div class='main-onfif-slide'>\
                            <p class='main-onfif-tit keepText'>{3}</p>\
                            <span class='main-onfif-date'>{4} ~ {5}</span>\
                        </div>\
                    </a> \
                </div>\
            "
            ,   getlistToDataStr(['idx'], data)
            ,   data['idx']
            ,   FITSOFT['IMAGE']['setLink'](data['thumbnail_col'])
            ,   data['name']
            ,   data['open_date']
            ,   data['close_date']
            );
        }
        else if(api_name === "getListBoard1")
        {// 뉴스레터 목록 조회 (2021.07.27 / By.Chungwon)
            return StringFormat("\
                <div class='s2-box' {0}>\
                    <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/community/newsletter/detail.php?board_idx={1}'>\
                        <div class='s2-imgArea'><img src='{2}' alt='onpeople'></div>\
                        <div class='s2-txtArea multi-line2'>{3}</div>\
                    </a>\
                </div>\
            "
            ,   getlistToDataStr(['idx'], data)
            ,   data['idx']
            ,   FITSOFT['IMAGE']['setLink'](data['thumbnail'])
            ,   data['title']
            );
        }
        else if(api_name === "getListBoard2")
        {// 공지사항 목록 조회 (2021.07.27 / By.Chungwon)

            // [날짜] - 시/분/초 짜르기
            data['insert_date'] = empty(data['insert_date']) ? "" : data['insert_date'].split(" ")[0];

            return StringFormat("\
                <div class='s3-notice' {0}>\
                    <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/community/notice/detail.php?board_idx={1}'>\
                        <span class='s3-notice-date'>{3}</span>\
                        <span class='s3-notice-tit keepText'>{2}</span>\
                    </a>\
                </div>\
            "
            ,   getlistToDataStr(['idx'], data)
            ,   data['idx']
            ,   data['title']
            ,   data['insert_date']
            );
        }
        else if(api_name === "getListMovieClassify")
        {// 추천영화 목록 조회 (2021.07.27 / By.Chungwon)

            // [날짜] - 시/분/초 짜르기
            data['insert_date'] = empty(data['insert_date']) ? "" : data['insert_date'].split(" ")[0];

            return StringFormat("\
                <div class='swiper-slide'>\
                    <div class='swiper-box'>\
                        <a href='<?=$PATH['HTTP_ROOT']?><?=$PREFIX['FRONT']?><?=$PREFIX['COMMON']?>/page/movie/detail.php?movie_idx={1}'>\
                            <div class='imgArea'><img src='{2}' alt='movie'></div>\
                            <div class='txtArea'>\
                                <p class='ta-movie-tit keepText'>{3}</p>\
                                <p class='ta-movie-info'><span class='tmi-dir'>{4}</span><span class='tmi-year'>{5}</span><span class='tmi-time'>{6}</span></p>\
                            </div>\
                        </a> \
                    </div>\
                </div>\
            "
            ,   getlistToDataStr(['idx'], data)
            ,   data['idx']
            ,   FITSOFT['IMAGE']['setLink'](data['thumbnail'])
            ,   data['name']
            ,   data['director_name']
            ,   data['release_year']
            ,   data['running_time']
            );
        }
        
    }
    /**************************************************** HTML 생성 끝 *********************************************/    

</script>