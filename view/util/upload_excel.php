<style>
    #global-excel_form_cont{
        position:absolute;
        top:0;
        left:0;
        display:none;
        justify-content:center;
        align-items:center;
        background-color:rgba(0,0,0,0.8);
        width:100%;
        height:100vh;
        z-index: 11;
    }
    #global-excel_form_cont.active{
        display:flex;
    }
    #global-excel_form_cont.active .excel_form_wrap{
        width:480px;
        height:580px;
        background-color:#fff;
        border-radius:10px;
        padding:30px;
        position:relative;
    }
    #global-excel_form_cont.active .excel_result{
        display:none;
    }
    #global-excel_form_cont.active .excel_result.active{
        display:flex; justify-content:space-between; margin-bottom:15px;
    }
    #global-excel_form_cont.active .excel_error_log_cont{
        display:none;
    }
    #global-excel_form_cont.active .excel_error_log_cont.active{
        display:flex;
        color:red;
    }
    #global-excel_form_cont.active .excel_result button {display: inline-block; padding:3px 5px; border-radius:5px; border:1px solid #0d2ea3; color:#0d2ea3; margin-left:3px;}
    #global-excel_form_cont.active .excel_result button:hover {background-color:#0d2ea3; color:#fff;}
    #global-excel_form_cont.active .excel_form_wrap .excel_form_tit {text-align:center; margin:10px 0; font-size:2rem;}
    #global-excel_form_cont.active .excel_form_wrap .excel_form_cont {line-height:1.8; padding:20px 0;}
    #global-excel_form_cont.active .excel_form_wrap .btn-area {position:absolute; bottom:30px; left:50%; transform:translateX(-50%);}
    #global-excel_form_cont.active .excel_form_wrap .upload-btn {display: inline-block; padding:8px 10px; background-color:#0d2ea3; border-radius:5px; color:#fff; margin-right:5px;}
    #global-excel_form_cont.active .excel_form_wrap .cancel-btn {display: inline-block; padding:8px 10px; border-radius:5px; border:1px solid #0d2ea3; color:#0d2ea3;}
    /* 바디에 스크롤 막는 방법 */
.not_scroll{
    position: fixed;
    overflow: hidden;
    width: 100%;
    height: 100%
}
</style>

<script>
    var g_excel_form = {
        api_url : "",
        action : "",

        upload_result : {
            request : 0, // 요청 건 수
            insert : 0, // 등록 건 수
            error : 0, // 에러 건 수
            error_log : [], // 에러 로그
        },
        create : function(canvas_selector, api_url, action)
        {// 엑셀 폼 생성하기 (2021.08.05 / By.Chungwon)
            g_excel_form['api_url'] = api_url;
            g_excel_form['action'] = action;

            // HTML 붙이기
            $(canvas_selector).append(this.html());

            // FORM 보여주기
            $("#global-excel_form_cont").addClass('active');
            $('html, body').css({'overflow': 'hidden', 'height': '100%'}).bind('touchmove', function(e){
                e.preventDefault()
                
                return false;
            });

        },
        upload : function(e)
        {// 엑셀 데이터 업로드하기 (2021.08.05 / By.Chungwon)
            sendAPI("/common/excel", "upload", { excel : e.files[0] }, function(excel_datas){
                    var sync_count = 0;
                    var datas = excel_datas['excel_info'];
                    g_excel_form['upload_result']['request'] = datas.length;
                    
                    for(var i = 0; i < datas.length; i++)
                    {
                        var data = datas[i];

                        sendAPI(g_excel_form['api_url'], g_excel_form['action'], data, 
                            function(res)
                            {// 성공
                                // 등록 건 수 증가
                                g_excel_form['upload_result']['insert']++;                    
                            },
                            function(res)
                            {// 실패
                                // 에러 건 수 증가
                                g_excel_form['upload_result']['error']++;
                                // 에러 로그 추가
                                var error_log = JSON.parse(res.responseText).result;
                                g_excel_form['upload_result']['error_log'].push(error_log);
                            },
                            function(res)
                            {// 마무리
                                if(++sync_count === datas.length)
                                {// for가 모두 끝난 경우 (비동기 처리)
                                    g_excel_form['view_result']();
                                }
                            },
                        );
                    }
                },
                function(error){
                    alert(JSON.parse(error['responseText'])['result']);
                },
            );
        },
        view_result : function()
        {// 엑셀 결과 확인하기 (2021.08.05 / By.Chungwon)
            $("[data-excel_result='request']").text(g_excel_form['upload_result']['request']);
            $("[data-excel_result='insert']").text(g_excel_form['upload_result']['insert']);
            $("[data-excel_result='error']").text(g_excel_form['upload_result']['error']);
            $("[data-excel_result='error_log']").html(g_excel_form['upload_result']['error_log'].join("<br/>"));

            $("#global-excel_form_cont .excel_result").addClass('active');
        },        
        view_error_log : function(result_data)
        {// 엑셀 에러로그 확인하기 (2021.08.05 / By.Chungwon)
            $("#global-excel_form_cont .excel_error_log_cont").addClass('active');
        },        
        remove : function()
        {// 엑셀 폼 삭제하기 (2021.08.05 / By.Chungwon)
            $("#global-excel_form_cont").removeClass('active');
            $('html, body').css({'overflow': 'auto', 'height': '100%'}); //배경 스크롤방지
        },
        html : function()
        {// 엑셀 폼 html 생성하기 (2021.08.05 / By.Chungwon)
            return StringFormat("\
                <div id='global-excel_form_cont'>\
                    <div class='excel_form_wrap'>\
                        <div class='excel_form'>\
                            <p class='excel_form_tit'>안내사항</p>\
                            <div class='excel_form_cont'>\
                                <p>- 중간에 취소가 불가능합니다.</p>\
                                <p>- 엑셀 파일 확장자는 xls만 지원합니다.</p>\
                                <p>- 엑셀 파일이 편집 가능한 상태여야 합니다.</p>\
                                <p>- 이미 등록된 데이터를 추가 등록할 경우 중복으로 등록될 수 있습니다.</p>\
                                <p>- 명시된 데이터 타입에 유의해주세요. (ex.Number - 숫자만 가능, Text - 문자만 가능)</p>\
                            </div>\
                            <div class='btn-area'>\
                                <label for='excel-upload1' class='upload-btn'>+ 엑셀 업로드</label>\
                                <input id='excel-upload1' class='fit-hide' type='file' onchange=g_excel_form['upload'](this);>\
                                <button class='cancel-btn' onclick=g_excel_form['remove']();>취소</button>\
                            </div>\
                        </div>\
                        <div class='excel_result'>\
                            <div>\
                                <p>요청 건 수: <span data-excel_result='request'></span></p>\
                                <p>등록 건 수: <span data-excel_result='insert'></span></p>\
                                <p>에러 건 수: <span data-excel_result='error'></span></p>\
                            </div>\
                            <div>\
                                <button onclick=g_excel_form['view_error_log']();>에러 로그보기</button>\
                                <button onclick=g_excel_form['remove']();>확인</button>\
                            </div>\
                        </div>\
                        <div class='excel_error_log_cont' data-excel_result='error_log'>\
                        </div>\
                    </div>\
                </div>\
            ");
        }
    }
</script>