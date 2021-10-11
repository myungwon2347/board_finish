<?php	
namespace util; 
	
use service\marketing\Client;
use service\marketing\Action;

class Visit
{
	
	public static function getClientIP()
	{// 클라이언트 IP 얻기 (2021.09.14 / By.Chungwon)
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	public static function getClientInfo()
	{// 클라이언트 정보 얻기 (2020.04.02 / By.Chungwon)
		return array(
			'ip' => $_SERVER['REMOTE_ADDR'], // 공인 아이피
			'inside_ip' => Visit::getClientIP(), // 사설 아이피
			'current_url' => $_SERVER['REQUEST_URI'],
			'lang' => Visit::getUserLang(),
			'device_type' => Visit::getUserDeviceType(),
			'device_name' => Visit::getUserDeviceName(),
			'browser' => Visit::getUserBrowser(),
			'referer' => Visit::getReferer(),
		);
	}
	public static function getUserLang()
	{// 접속 유저 브라우저의 언어셋 확인 (2020.04.02 / By.Chungwon)
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) === false)
		{
			return "NO LANGUAGE";
		}
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

		if(stristr($lang, 'ko')){
			return "한국어";
			
		}else if(stristr($lang, 'jp')){
			return "일본어";

		}else if(stristr($lang, 'zh')){
			return "중국어";

		}else{
			return "영어";

		}
	}
	public static function getUserDeviceType()
	{// 접속 유저의 디바이스 타입 확인 - PC인지 모바일인지 (2020.04.02 / By.Chungwon)
		$devices = ["Ipad", "Iphone", "Blackberry", "Android", "iPod", "Opera Mini", "Windows ce", "Nokia", "sony"];

		foreach($devices as $device) {
			if(stristr($_SERVER['HTTP_USER_AGENT'], $device)){
				return "MOBILE";
			}   
		}
		return "PC";
	}
	public static function getUserDeviceName()
	{// 접속 유저의 디바이스 이름 확인 - 디바이스 이름 (2020.04.02 / By.Chungwon)
		$devices = ["Ipad", "Iphone", "Blackberry", "Android", "Windows", "Mac"];
			 
		foreach($devices as $device) {
			if(stristr($_SERVER['HTTP_USER_AGENT'], $device)){
				return $device;
			}   
		}
	
		return 'ETC';
		// return $_SERVER['HTTP_USER_AGENT'];
	}
	public static function getUserBrowser()
	{// 접속 유저의 브라우저 확인 (2020.04.02 / By.Chungwon)

		$browsers = ["Firefox", "Chrome", "Safari", "Opera", "MSIE", "Trident", "Edge", "SamsungBrowser"];
 		
		foreach($browsers as $browser) {
			if(stristr($_SERVER['HTTP_USER_AGENT'], $browser)) {
				return stristr($browser, 'MSIE') || stristr($browser, 'Trident') || stristr($browser, 'Edge') ? 'Internet Explorer' : $browser;
			}   
		}
		return 'ETC';
		// return $_SERVER['HTTP_USER_AGENT'];
	}
	public static function getReferer()
	{// 접속 유저의 브라우저 확인
		return isset($_SERVER['HTTP_REFERER']) ? substr($_SERVER['HTTP_REFERER'], 0, 300) : 'Access by URL';
	}
	
	public static function log()
	{// 방문자 저장 (2021.02.25 / By.Chungwon)

		// 필터 검사
		$ip_block_list = array('192.', 'localhost', '127.', '::1');
		// $ip_block_list = array();
		$bot_block_list = array("bot", "Spider", "Yeti", "Slupr", "YATS", "Yahoo", "Daumoa", "libcurl", "Python", "facebookexternalhit", "Go-http-client", "CheckMarkNetwork");

		if(Text::isInclude($ip_block_list, $_SERVER['REMOTE_ADDR']) || Text::isInclude($bot_block_list, $_SERVER['HTTP_USER_AGENT'])){
			// 필터링에 걸린 경우
			return 0;
		}else{
			// 필터링을 통과한 경우
			$client = Visit::getClientInfo();

			// if($client['ip'] === "NO" || $client['device_name'] === "ETC" || $client['browser'] === "ETC OR Crawler")
			// {	// 2차 필터링
			// 	return 1;
			// }
			// DB 저장
			$insert_idx = Client::insertClient($client['ip'], $client['lang'], $client['device_type'], $client['device_name'], $client['browser'], $client['referer'], $client['current_url']);
			
			return $insert_idx;
			// 하루동안 쿠키 설정
			// setcookie('fd_visiter_idx', $insert_idx, time() + 86400);
		}

		if(empty($_COOKIE['fd_visiter_idx']))
		{// 오늘 접속을 안했다면, 접속자 정보 얻기
			
			
		}else
		{// 재방문자
			// setcookie('todayIP', true, time() - 86400);
		}
	}

	public static function insertAction($visiter_idx)
	{// 방문자 전환 저장 (2021.02.25 / By.Chungwon)
		
		$media_key = isset($_REQUEST['mk']) ? $_REQUEST['mk'] : "NULL";
		$redirect_key = isset($_REQUEST['rk']) ? $_REQUEST['rk'] : "NULL";

		// 매체 정보 가져오기
		$media_info = Action::getMedia($media_key);
		// 리다이렉트 정보 가져오기
		$redirect_info = Action::getRedirect($redirect_key);

		if(empty($media_info) || empty($redirect_info))
		{
			return false;
		}
		// 전환 기록하기
		$insert_idx = Action::insert($visiter_idx, $media_info['idx'], $redirect_info['idx']);

		return $redirect_info['url'];
	}
}