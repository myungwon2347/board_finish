<?php
namespace util; 

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

use service\Files;

class File 
{	
    // $format = array('jpg','jpeg','png','gif','bmp','BMP','JPG','JPEG','PNG','GIF', 'webp', 'WEBP', 'jfif', 'heic', 'JFIF', 'HEIC');
	// $format = array('zip','ppt','doc','xls', 'pdf', 'hwp', 'txt');
	public static function move($originUrl, $moveUrl)
	{	// 파일 이동
		$originUrl = $GLOBALS['PATH']['SERVER_ROOT'] . $GLOBALS['PREFIX']['FILE'] . '/' . $originUrl;
		$moveUrl = $GLOBALS['PATH']['SERVER_ROOT'] . $GLOBALS['PREFIX']['FILE'] . '/' . $moveUrl;

		if(file_exists($originUrl)) {
			rename($originUrl, $moveUrl);	
		}
	}
	public static function insert($file_list, $type, $ref_table, $ref_key, $ref_idx, $order_num = null, $value = null)
	{
		$file_info_list = File::getInfo($file_list, $type, $ref_table, $ref_key, $ref_idx, $order_num, $value);
        $file_insert_idx_list = array();
        
        for($i = 0; $i < count($file_info_list); $i++)
        {
            $info = $file_info_list[$i];
            $file_insert_idx = Files::insert($info['type'], $info['ref_table'], $info['ref_key'], $info['ref_idx'], $info['n_name'], $info['o_name'], $info['size'], $info['ext'], $order_num, $value);    

            if(apiErrorCheck($file_insert_idx, "[{$info['o_name']}]\n파일 데이터 저장 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
    
            $upload_state = File::upload($info);

            if(apiErrorCheck($upload_state, "[{$info['o_name']}]\n파일 저장 과정에서 에러가 발생했습니다.\n고객센터에 문의주세요.")){ return; }
            if($upload_state)
            {
                array_push($file_insert_idx_list, $file_insert_idx);
            }
        }
        return implode(',', $file_insert_idx_list);
	}
	public static function delete($file_url) 
	{	// 파일 삭제
		$real_file_path = $GLOBALS['PATH']['SERVER_ROOT'] . $GLOBALS['PREFIX']['FILE'] . '/' . $file_url;

		if(is_file($real_file_path)){
			unlink($real_file_path);
		}else{
			Log::write("파일명:[{$real_file_path}]\n파일삭제에 실패했습니다.");
		}
		return true;
	}	
	public static function setFiles($file_list)
	{	// 파일 객체 array로 변경 (php는 파일 구조가 이상하게 옴..)
		$result = array();
		
		for($i = 0; $i < count($file_list['name']); $i++)
		{
			$temp = array(
				'name' => $file_list['name'][$i],
				'type' => $file_list['type'][$i],
				'tmp_name' => $file_list['tmp_name'][$i],
				'error' => $file_list['error'][$i],
				'size' => $file_list['size'][$i],
			);

			array_push($result, $temp);
		}
		return $result;
	}
	public static function upload($file)
	{	// 파일 업로드
		$file['ref_key'] = strtolower($file['ref_key']);

		// 폴더 및 파일 경로 설정
		$base_dir = $GLOBALS['PATH']['SERVER_ROOT'] . $GLOBALS['PREFIX']['FILE'];
		$uploads_dir = "{$base_dir}/{$file['type']}/{$file['ref_table']}/{$file['ref_key']}";
		$file_location = "{$uploads_dir}/{$file['n_name']}";
		
		
		// if(!chmod($full_upload_dirname.$newfile,0744)) {
		// 	error("PERMISSION_DENIED");
		// 	exit;
		// }

		// 저장 경로 폴더 생성 및 파일 이동
		if(!is_dir($uploads_dir))
		{
			umask(0);
			mkdir($uploads_dir, 0777, true);
		}
		if(isset($file['tmp_name']))
		{
			move_uploaded_file($file['tmp_name'], $file_location);
			chmod($file_location, 0777);

		}else
		{
			// 임시 폴더에 파일이 없습니다.
			return false;
		}

		return true;
	}
	public static function getInfo($file_list, $type, $table, $ref_key, $ref_idx, $order_num = "NULL", $value = "NULL")
	{	// 파일 정보 얻기
		$now = date("YmdHis");
		$key = Text::generateRandomStr(3);

		$result = array();

		for($i = 0; $i < count($file_list); $i++)
		{
			$file = $file_list[$i];
			$file_name = explode('.', $file['name']);
			$ext = array_pop($file_name);

			$n_name = substr($file['name'], 0, - (strlen($ext) + 1));
			$n_name = "{$n_name}_{$now}{$key}.{$ext}";

			$file = array(
				'type' 		=> 	$type,
				'ref_table' => 	$table,
				'ref_key' 	=> 	$ref_key,
				'ref_idx' 	=> 	$ref_idx,
				'order_num' => 	$order_num,
				'value' 	=> 	$value,
				'n_name' 	=> 	$n_name,
				'o_name' 	=> 	$file['name'],
				'size' 		=> 	$file['size'],
				'ext' 		=> 	$ext,
				'tmp_name'	=> 	$file['tmp_name']
			);

			array_push($result, $file);
		}
		return $result;
	}

	/* 
		아래부터는 에러 체크 모듈입니다. 
		2020.04.07 / By.Chungwon
	*/
	
    public static function originErrorCheck($file)
	{	// 파일 자체의 에러 체크
		$name = $file['name'];
		$error = $file['error'];

		if($error == UPLOAD_ERR_OK)
		{	// 에러가 없는 경우
			return false;

		}else if($error == 1) 
		{
			return "[{$name}] 업로드 최대 용량 초과을 초과했습니다.";

		}else if($error == 2)
		{
			return "[{$name}] 최대 용량을 초과했습니다.";

		}else if($error == 3)
		{
			return "[{$name}] 파일이 부분만 업로드됐습니다.";

		}else if($error == 4)
		{
			return "[{$name}] 파일을 선택해 주세요.";

		}else if($error == 5)
		{
			return "[{$name}] 임시 폴더가 존재하지 않습니다.";

		}else if($error == 6)
		{
			return "[{$name}] 임시 폴더에 파일을 쓸 수 없습니다. 퍼미션을 살펴 보세요.";

		}else if($error == 7)
		{
			return "[{$name}] 확장에 의해 파일 업로드가 중지되었습니다.";

		}else
		{
			return "[{$name}] 파일이 제대로 업로드되지 않았습니다.";
		}

		return "[{$name}] 파일이 제대로 업로드되지 않았습니다.";
	}
	public static function sizeCheck($file, $limit)
	{	// 파일 사이즈 필터링 (limit은 MB 단위)
		$name = $file['name'];
		$size = $file['size'];
		$total_size = $limit * 1024 * 1024;

		if($total_size <= $size)
		{
			return "[{$name}] {$limit}MB보다 작은 파일을 등록해주세요.";
		}
		return false;
	}
	public static function extCheck($file, $format)
	{	// 확장자 필터링
		$name = $file['name'];

		$ext = explode('.', $name);
		$ext = array_pop($ext);

        if(isset($format)){
			if(!in_array($ext, $format)){
				return "파일명[{$name}]은 허용되지 않는 확장자입니다.";
			}
		}
		return false;
	}

	public static function errorCheck($file_list, $limit, $format)
	{	// 전체 에러 체크
		if(isset($file_list) === false)
		{
			return false;
		}
		for($i = 0; $i < count($file_list); $i++)
		{
			$file = $file_list[$i];

			$error_state = File::originErrorCheck($file);
			if($error_state != false)
			{	// 파일 자체의 에러 체크
				return $error_state;
			}
			
			$size_state = File::sizeCheck($file, $limit);
			if($size_state != false)
			{	// 파일 사이즈 필터링 (limit은 MB 단위)
				return $size_state;
			}


			$ext_state = File::extCheck($file, $format);
			if($ext_state != false)
			{	// 확장자 필터링
				return $ext_state;
			}
		}
		
		return false;
	}
}
