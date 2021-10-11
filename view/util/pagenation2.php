<?php
    $pagingOptions = json_decode($_REQUEST['pagingOptions'], true);
?>

<!-- 페이징 -->
<style>

    .pagenation-state-container{ text-align:center;}
    .pagenation-index-container { text-align:center;}

    .pagenation-state-container > div{ text-align:center; }
    .pagenation-index-container ul > li { display:inline-block; cursor:pointer; vertical-align: top; color:#0B0B0B;}
    .pagenation-index-container ul > li.disabled {color:#8F8F8F;} 
    .pagenation-index-container ul > li.arrow-btn {width:50px !important; margin:0 3px;}
    .pagenation-index-container ul > li > a {width:100%; height:100%; display:inline-table; text-decoration:none;transition:.2s ease-in;}
    .pagenation-index-container ul > li > a:hover {color:#2A62FF; font-weight:700;}
    .pagenation-index-container ul > li.active > a {color:#2A62FF;}
    .pagenation-index-container ul > li > a > span { width:100%; height:100%; display:table-cell; vertical-align:middle;line-height: 1;}
    .pagenation-index-container ul > li.active > a span {font-weight:700;}
    .pagenation-index-container ul > li i.fa-angle-double-left, .pagenation-index-container ul > li i.fa-angle-left {margin-right:5px;}
    .pagenation-index-container ul > li i.fa-angle-right, .pagenation-index-container ul > li i.fa-angle-double-right {margin-left:5px;}

    /* PC */
    @media all and (min-width: 1023px){
        .pagenation-index-container ul > li { width:30px; height:30px; font-size:0.8rem;}
    }

    /* Mobile */
    @media all and (max-width: 768px) {
        .pagenation-index-container ul > li.arrow-btn {width:auto !important;}
        .pagenation-index-container ul > li { width:30px; height:30px; font-size:0.8rem;}
    }
</style>

<div class='pagenation'>
<?php
    if(isset($pagingOptions['pageState']) && $pagingOptions['pageState']){
        // 페이지 Showing
        $end = intval($pagingOptions['page_selected_idx']) * intval($pagingOptions['data_render_count']);
        $start = $end - (intval($pagingOptions['data_render_count']) - 1);
        $total = $pagingOptions['page_render_count'];
                                
        $start = $start > $end ? $total : $start;
        $end = $end > $total ? $total : $end;

        echo "
            <div class='pagenation-state-container'>
                <div>
                    Showing {$start} 
                    to {$end} 
                    of {$total} entries
                </div>
            </div>
        ";
    }
    
    // 페이지 Indexing
    $end = intval($pagingOptions['page_selected_sheet']) * intval($pagingOptions['page_render_count']);
    $start = $end - (intval($pagingOptions['page_render_count']) - 1);
    $total = $pagingOptions['total_page_count'];
                                
    $start = $start > $end ? $total : $start;
    $end = $end > $total ? $total : $end;
                            
    $firstState = intval($pagingOptions['page_selected_idx']) === 1 ? 'disabled' : '';
    $endState = intval($pagingOptions['page_selected_idx']) === intval($total) ? 'disabled' : '';

    echo "
        <div class='pagenation-index-container'>
            <div>
                <ul>
                    <li class='{$firstState} arrow-btn'>
                        <a>
                            <span class='pageElement' data-method='paging' data-method_event='click' data-flag='btn' data-state='first'><i class='fas fa-angle-double-left'></i>맨 앞</span>
                        </a>
                    </li>
                    <li class='{$firstState} arrow-btn'>
                        <a>
                            <span class='pageElement' data-method='paging' data-method_event='click' data-flag='btn' data-state='prev'><i class='fas fa-angle-left'></i>이전</span>
                        </a>
                    </li>
    ";
    $end = $end === 0 ? 1 : $end;
    
    for($i = $start; $i <= $end; $i++){
        $active = intval($pagingOptions['page_selected_idx']) === $i ? 'active' : '';                

        if($end === 0)
        {
            $active = "active";
            $i = "1";
        }

        echo "
            <li class='{$active}'>
                <a>
                    <span class='pageElement' data-method='paging' data-method_event='click' data-flag='index' data-state='{$i}'>{$i}</span>
                </a>
            </li>
        ";
    }
        
    echo "
                        <li class='{$endState} arrow-btn'>
                            <a>
                                <span class='pageElement' data-method='paging' data-method_event='click' data-flag='btn' data-state='next'>다음<i class='fas fa-angle-right'></i></span>
                            </a>
                        </li>
                        <li class='{$endState} arrow-btn'>
                            <a>
                                <span class='pageElement' data-method='paging' data-method_event='click' data-flag='btn' data-state='end'>맨 뒤<i class='fas fa-angle-double-right'></i></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        ";
    ?>
</div>