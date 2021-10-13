<?php	
namespace service;

use util\DB;
use util\Log;

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';


class Board
{// 게시판
	static $service_name = "board";
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









	// 관리자 SQL 영역입니다.
	/************************************** SELECT DETAIL **************************************/
    public static function getBoardAdmin($datas)
	{// 관리자 - 상세 조회 (2021.08.25 / By.Chungwon)
		$service_name = self::$service_name;

		$result = DB::Execute("
			SELECT
				mt.*
			FROM
				(

					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						idx = {$datas['target_idx']}
				)
			AS mt
		");

		return $result ? $result[0] : false;
	}
	/************************************** SELECT LIST **************************************/
	public static function getListBoardAdmin($request, $sort_list, $limit, $offset)
    {/* 
		관리자 - 목록 조회 (2021.08.27 / By.Chungwon)
	*/
		$service_name 			= self::$service_name;
        $order 				= empty($sort_list) 	? "" : "ORDER BY " . stripslashes($sort_list);		

		$title         		= empty($request['title']) 				? "" : "AND (`title` LIKE '%{$request['title']}%' OR replace(`title`, ' ', '') LIKE '%{$request['title']}%')";
		$type 				= empty($request['type']) 				? "" : "AND `type` = '{$request['type']}'";
		$notice_status 		= empty($request['notice_status']) 		? "" : "AND `notice_status` = {$request['notice_status']}";

		$rank 				= empty($request['rank']) 				? "" : "AND `rank` = '{$request['rank']}'";
		$reg_user_nickname      = empty($request['reg_user_nickname']) 		? "" : "AND (`nickname` LIKE '%{$request['reg_user_nickname']}%' OR replace(`nickname`, ' ', '') LIKE '%{$request['reg_user_nickname']}%')";
		// 파라미터 값 중 0이 유효한 경우, empty 대신 (=== "") 처리
		// $reg_status 		= $request['reg_status'] === "" 		? "" : "AND `reg_status` IN ({$request['reg_status']})";
		// $rank 				= empty($request['rank']) 				? "" : "AND `rank` = '{$request['rank']}'";
		// $insert_date_start 	= empty($request['insert_date_start']) 	? "" : "AND DATE('{$request['insert_date_start']}') <= insert_date";
        // $insert_date_end 	= empty($request['insert_date_end']) 	? "" : "AND DATE_ADD(DATE('{$request['insert_date_end']}'), INTERVAL 1 DAY) > insert_date";

		return DB::Execute("
			SELECT SQL_CALC_FOUND_ROWS
				mt.*
				,	me.nickname AS reg_user_nickname
				,	IFNULL(fi1.count, 0) AS is_image
			FROM
				(
					SELECT
						*
					FROM
						{$service_name}
					WHERE
						1 = 1
						{$title}
						{$type}
						{$notice_status}
				)
			AS mt

			INNER JOIN
				(
					SELECT
						*
					FROM
						`member`
					WHERE
						1 = 1
						{$rank}
						{$reg_user_nickname}
				)
			AS me
			ON me.idx = mt.reg_user_idx
			
			LEFT OUTER JOIN
                (
                    SELECT
                        ref_idx
						,	COUNT(*) AS count
                    FROM
						`files`
                    WHERE
						ref_table = '{$service_name}'
					GROUP BY
						ref_idx
                )
            AS fi1
			ON fi1.ref_idx = mt.idx
						
			{$order}

			LIMIT {$limit}
			OFFSET {$offset}
		", TRUE);
    }















	// 사용자 SQL 영역입니다.
	/************************************** SELECT DETAIL **************************************/
    public static function getBoard($request)
	{// 사용자 - 상세 조회 (2021.09.01 / By.Chungwon)
		$service_name = self::$service_name;

		$like_user_idx 				= empty($request['like_user_idx']) 				? "" : "AND `like_user_idx` = '{$request['like_user_idx']}'";
		$view_status 		= empty($request['view_status']) 		? "" : "AND `view_status` IN ({$request['view_status']})";

		$result = DB::Execute("
			SELECT
				mt.*
				,	fi2.path AS thumbnail
				,	IFNULL(me.nickname, '-') AS reg_user_name
			FROM
				(

					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						idx = {$request['target_idx']}
						{$view_status}
				)
			AS mt

			LEFT OUTER JOIN
				(
					SELECT
						*
					FROM
						`member`
					WHERE
						1 = 1
				)
			AS me
			ON me.idx = mt.reg_user_idx
			
			LEFT OUTER JOIN
                (
                    SELECT
                        *
                        ,	CONCAT('/', type, '/', ref_table, '/', ref_key, '/', n_name) AS path
                    FROM
						`files`
                    WHERE
						ref_table = '{$service_name}'
						AND ref_key = 'thumbnail'
					GROUP BY
						ref_idx
                )
            AS fi2
			ON fi2.ref_idx = mt.idx
		");

		return $result ? $result[0] : false;
	}
	/************************************** SELECT LIST **************************************/
	public static function getListBoard($request, $sort_list, $limit, $offset)
    {/* 
		사용자 - 목록 조회 (2021.08.31 / By.Chungwon)
	*/
		$service_name 			= self::$service_name;
        $order 				= empty($sort_list) 	? "" : "ORDER BY " . stripslashes($sort_list);		


		$mt_where = DB::createWhereQuery($request, array(
			"in" => array(
				array("key"					=> "type"),
				array("key"					=> "view_status"),
				array("key"					=> "notice_status"),
			),
		));

		$type 				= empty($request['type']) 				? "" : "AND `type` = '{$request['type']}'";
		$notice_status 		= isset($request['notice_status']) === false || $request['notice_status'] === ""		? "" : "AND `notice_status` = {$request['notice_status']}";
		// isset($request['notice_status']) === false || $request['notice_status'] !== ""
		$view_status 		= empty($request['view_status']) 		? "" : "AND `view_status` IN ({$request['view_status']})";
		$rank 				= empty($request['rank']) 				? "" : "AND `rank` = '{$request['rank']}'";
		
		
		$search_sql         	= empty($request['search_keyword']) 		? "" : "AND
			(
				(`title` LIKE '%{$request['search_keyword']}%' OR replace(`title`, ' ', '') LIKE '%{$request['search_keyword']}%')
				OR (`nickname` LIKE '%{$request['search_keyword']}%' OR replace(`nickname`, ' ', '') LIKE '%{$request['search_keyword']}%')
			)
		";
		return DB::Execute("
			SELECT SQL_CALC_FOUND_ROWS
				mt.*
				,	me.nickname AS reg_user_nickname
				,	IFNULL(fi1.count, 0) AS is_image
				,	fi2.path AS thumbnail
			FROM
				(
					SELECT
						*
					FROM
						`{$service_name}`
					WHERE
						1 = 1
						{$type}
						{$notice_status}
						{$view_status}
				)
			AS mt

			INNER JOIN
				(
					SELECT
						*
					FROM
						`member`
					WHERE
						1 = 1
						{$rank}
				)
			AS me
			ON me.idx = mt.reg_user_idx
			
			LEFT OUTER JOIN
                (
                    SELECT
                        ref_idx
						,	COUNT(*) AS count
                    FROM
						`files`
                    WHERE
						ref_table = '{$service_name}'
					GROUP BY
						ref_idx
                )
            AS fi1
			ON fi1.ref_idx = mt.idx
			
			LEFT OUTER JOIN
                (
                    SELECT
                        *
                        ,	CONCAT('/', type, '/', ref_table, '/', ref_key, '/', n_name) AS path
                    FROM
						`files`
                    WHERE
						ref_table = '{$service_name}'
						AND ref_key = 'thumbnail'
					GROUP BY
						ref_idx
                )
            AS fi2
			ON fi2.ref_idx = mt.idx

			WHERE
				1 = 1
				{$search_sql}
						
			{$order}

			LIMIT {$limit}
			OFFSET {$offset}
		", TRUE);
    }
}