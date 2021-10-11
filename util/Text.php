<?php
namespace util; 

class Text
{	
	// 임의의 문자열 생성
	public static function generateRandomStr($length = 10, $only_number = false) {
		$characters = $only_number ? '0123456789' : '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
	
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
	
		return $randomString;
	}

	// 말 줄임
	public static function textSkip($totalText, $boundary, $replaceText){
		return iconv_substr($totalText, 0, $boundary, "utf-8") . (mb_strlen($totalText, 'utf-8') > $boundary ? $replaceText : '');
	}

	// byte 변환
	public static function StringToByte($str){
		$sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB"); 
		
		if ($str == 0) { 
			return('n/a'); 
		} else { 
			return (round($str/pow(1024, ($i = floor(log($str, 1024)))), 2) . $sizes[$i]); 
		} 
    }
    
	// 시작과 끝 값 사이 문자열 추출 (처음에 나오는 문자열만)
	public static function getAmongString($start, $end, $total){
		// 시작 인덱스
		$startIdx = $start === "" ? 0 : strpos($total, $start);
		// 시작 인덱스를 제외한 문자열
		$splitCount = $startIdx + strlen($start);
		$substr = substr($total, $splitCount, strlen($total)-1);

		// 시작 인덱스를 제외하고 다음에 나오는 끝 문자열 인덱스
		$endIdx = strpos($substr, $end);
		
		return substr($substr, 0, $endIdx);
	}

	// 시작과 끝 값 사이 문자열 추출 (전체 문자열에서 모두 추출 - 배열)
	public static function getAmongStringArray($start, $end, $total){
		
		$totalArray = array();
		$totalString = $total;
		
		// 시작 값이 존재하지 않을때까지 반복
		while(strpos($totalString, $start) !== false){
			// 1. 시작 스트링 위치 찾기
			$startIdx = strpos($totalString, $start);

			// 2. 서치를 시작 할 길이 찾이
			$splitCount = $startIdx + strlen($start);
			
			// 3. 전체 문자열의 2번 위치 값부터 전체 문자열까지 추출
			$substr = substr($totalString, $splitCount, strlen($totalString)-1);
	
			// 4. 3번의 시작 위치부터, 끝 문자열까지의 인덱스 추출
			$endIdx = strpos($substr, $end);	

			// 5. 추출된 문자열부터 끝 문자열 인덱스까지 추출
			$element = substr($substr, 0, $endIdx);

			// 6.  배열에 담기
			array_push($totalArray, $element);

			// 7. 전체 문자열에서 추출한 문자열까지 삭제
			$deleteIdx = $splitCount + $endIdx + 1;
			$totalString = substr($totalString, $deleteIdx, strlen($totalString)-1);
		}

		return $totalArray;
	}
	// 해당 배열에 아이템이 속해있는지 검사
	public static function isInclude($array, $target)
	{	
		foreach($array as $item){
			if(stripos($target, $item) !== false){
				return true;
			}
		}
	
		return false;
	}
	
	// 파일 이름 검색 (마지막에 나오는 target 이하 문자열 검색)
	public static function getTargetUnderText($totalStr, $target){
		return strrchr($totalStr, $target);
	}


	public static function getMaskingString($str, $len1, $len2=0, $limit=0, $mark='*')
	{// 마스킹 문자
		$arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
		$str_len = count($arr_str);

		$len1 = abs($len1);
		$len2 = abs($len2);

		if($str_len <= ($len1 + $len2))
			return $str;

		$str_head = '';
		$str_body = '';
		$str_tail = '';

		$str_head = join('', array_slice($arr_str, 0, $len1));
		if($len2 > 0)
			$str_tail = join('', array_slice($arr_str, $len2 * -1));

		$arr_body = array_slice($arr_str, $len1, ($str_len - $len1 - $len2));

		if(!empty($arr_body)) {
			$len_body = count($arr_body);
			$limit    = abs($limit);

			if($limit > 0 && $len_body > $limit)
				$len_body = $limit;

			$str_body = str_pad('', $len_body, $mark);
		}

		return $str_head.$str_body.$str_tail;
	}
}