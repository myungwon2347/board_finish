<?php	
namespace util;

use service\Etc;
use service\Board;
use service\Category;
use service\CountryCode;
use service\ManagePage;
use service\Banner;
use service\Location;
use service\commerce\Product;
use service\commerce\ProductClassify;
use service\commerce\ProductCategory;

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";

class Cache
{
    public static function setCacheFile($cache_file_path, $data_list)
    {// 캐시 설정하기

        // $data_list = str_replace('"', '\"', json_encode($data_list));        
        $data_list = addslashes(json_encode($data_list, JSON_UNESCAPED_UNICODE));
        $fp = fopen($cache_file_path, 'w+');

        flock($fp, LOCK_EX); // 쓰기 락
        fwrite($fp, "<?php \$cache_data = json_decode(stripslashes('{$data_list}'), true); ?>");
        flock($fp, LOCK_UN); // 락 해제
        fclose($fp);
    }
	public static function getCacheData($cache_info, $query_option, $is_only_data = false, $data_list = null)
    {// 캐시 얻어오기

        // 파라미터 세팅
        $service = ucfirst($cache_info['folder']);  // 첫글자는 대문자
        $folder = "{$_SERVER['DOCUMENT_ROOT']}/cache/{$service}";
        $file_name = $cache_info['file_name'];
        $cache_type = empty($cache_info['cache_type']) || $cache_info['cache_type'] == "" ? "" : "_" . $cache_info['cache_type'];
        $ttl = empty($cache_info['ttl']) ? 3600 : $cache_info['ttl'];

        // 저장 경로 폴더 생성
		if(!is_dir($folder)){
			mkdir($folder, 0777, true);
        }


        // 캐싱 파일 위치
        // $cache_file_path = str_replace("/", "\\", "{$folder}/{$file_name}{$cache_type}.php");
        $cache_file_path = "{$folder}/{$file_name}{$cache_type}.php";

        if(!file_exists($cache_file_path) || (filemtime($cache_file_path) + $ttl) < time())
        {   // 파일 캐시가 존재하지 않는 경우
            if(empty($data_list))
            {// 전달받은 캐싱 데이터가 없다면 DB 실행 (있다면 바로 저장)
                if($service === "Etc")
                {   // Etc
                    if($file_name === "getList")
                    {// 목록 가져오기
                        $data_list = Etc::$file_name($query_option['category'], $query_option['type'], $query_option['status']);
                    }
                    else if($file_name === "getTypeList")
                    {// ETC 중분류 목록 가져오기
                        $data_list = Etc::$file_name($query_option['deep'], NULL, NULL, $query_option['value']);
                    }
                }
                else if($service === "Category")
                {// Category                    
                    if($file_name === "getList")
                    {// 목록 가져오기
                        $data_list = Category::$file_name($query_option['type'], $query_option['deep'], $query_option['parent_idx']);
                    }
                }
                else if($service === "Product")
                {// Product                    
                    if($file_name === "getSumPrice")
                    {// 최소, 최대 값 가져오기
                        $data_list = Product::$file_name();
                    }
                }
                else if($service === "Board")
                {// Board                    
                    if($file_name === "getListType")
                    {// 게시판 타입 가져오기
                        $data_list = Board::$file_name($query_option['sort_list'])['list'];
                    }
                }
                else if($service === "ProductClassify")
                {// ProductClassify                    
                    if($file_name === "getListAll")
                    {// 모든 목록 가져오기           

                        /* 대분류 가져오기 */
                        $product_classify_list = ProductClassify::getList(1, NULL)['list'];

                        /* 중분류 가져오기 */
                        for($i = 0; $i < count($product_classify_list); $i++)
                        {
                            $product_classify_list[$i]['deep2'] = ProductClassify::getList(2, $product_classify_list[$i]['idx'])['list'];
                            /* 소분류 가져오기 */
                            for($j = 0; $j < count($product_classify_list[$i]['deep2']); $j++)
                            {
                                $product_classify_list[$i]['deep2'][$j]['deep3'] = ProductClassify::getList(3, $product_classify_list[$i]['deep2'][$j]['idx'])['list'];
                            }
                        }
                        $data_list = $product_classify_list;
                    }
                    else if($file_name === "getList")
                    {// 목록 가져오기
                        $data_list = ProductClassify::$file_name($query_option['deep'], NULL, NULL, $query_option['value']);
                    }
                }
                else if($service === "ProductCategory")
                {// ProductCategory
                    if($file_name === "getList")
                    {// 모든 목록 가져오기
                        $data_list = ProductCategory::$file_name();
                    }
                }

                
                else if($service === "CountryCode")
                {// CountryCode
                    if($file_name === "getList")
                    {// 목록 가져오기
                        $data_list = CountryCode::$file_name();
                    }
                }


                else if($service === "ManagePage")
                {// ManagePage
                    if($file_name === "getListAll")
                    {// 목록 가져오기
                        /* 부모 페이지 가져오기 */
                        $manage_page_list = ManagePage::getList(NULL, 1, "order_num")['list'];

                        /* 자식 페이지 가져오기 */
                        for($i = 0; $i < count($manage_page_list); $i++)
                        {
                            $manage_page_list[$i]['sub_menu'] = ManagePage::getList($manage_page_list[$i]['idx'], 1, "cmp.order_num, cmp2.order_num")['list'];
                        }
                        $data_list = $manage_page_list;
                    }
                }


                else if($service === "Banner")
                {// Banner
                    if($file_name === "getBanner")
                    {// 상세 가져오기
                        $data_list = Banner::$file_name($query_option);
                    }
                }


                else if($service === "Location")
                {// Location
                    if($file_name === "getListLocation")
                    {// 목록 가져오기
                        $data_list = Location::$file_name($query_option, $query_option['sort_list'], $query_option['limit'], $query_option['offset']);
                    }
                }

                if($data_list === false)
                {// 오류인 경우 캐싱X
                    return;
                }
            }            
            // 캐싱
            Cache::setCacheFile($cache_file_path, $data_list);
        }
        else
        {   // 캐시가 존재하는 경우
        }


        // 캐싱 데이터 반환
        $cache_data;
        require_once $cache_file_path;
        return $cache_data;
    }

    ////////////////////////////////// 캐싱 사용법 //////////////////////////////////
	// $cache_info = array(
	// 	"folder" => "User",                                           // 서비스명
	// 	"file_name" => $action,                 					// 서비스 메소드
	// 	"cache_type" => "{$_REQUEST['type']}_{$_REQUEST['sortColumn']}_{$_REQUEST['selectedCountry']}",                                      // 타입에 따른 캐싱 파일 분기
	// 	"ttl" => 21600                                                    // 캐싱 유효 시간
	// );
	// $query_option = array(
	// 	"sortColumn" => $_REQUEST['sortColumn'],
	// 	"selectedCountry" => $_REQUEST['selectedCountry'],
	// 	"offset" => $offset,
	// 	"limit" => $limit,
	// );
	// $userList = Cache::getCacheData($cache_info, $query_option);
	////////////////////////////////// 캐싱 끝 //////////////////////////////////
}