<?php	
namespace service;

use util\DB;
use util\Log;

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';


class ManagePage
{// 관리자 페이지
	static $service_name = "manage_page";

	/************************************** INSERT **************************************/
	public static function insert($info_idx, $message, $reg_user_idx, $deposit_name)
	{// 데이터 추가 (2021.05.09 / By.Chungwon)
		$service_name = self::$service_name;

        // 숫자
		$info_idx = empty($info_idx) ? "NULL" : "{$info_idx}";
        // 문자열
		// $tel = empty($info_idx) ? "NULL" : "'{$tel}'";
		
		return DB::Execute("
			INSERT INTO `{$service_name}`
			(
				info_idx
                ,   reg_user_idx
                ,   message
                ,   deposit_name
			) 
            VALUES
			(
				{$info_idx}
				,   {$reg_user_idx}
                ,   '{$message}'
                ,   '{$deposit_name}'
			)
		");
	}
	/************************************** UPDATE **************************************/
	public static function update($target_idx, $info_idx, $message, $deposit_name)
	{// 데이터 수정 (2021.05.09 / By.Chungwon)
        $service_name = self::$service_name;

		$update_list = array();

		array_push($update_list, empty($info_idx) 		|| $info_idx 		=== "" ? "" : ",	 info_idx		= {$info_idx}");
		array_push($update_list, empty($message) 		|| $message 		=== "" ? "" : ",	 message		= '{$message}'");
		array_push($update_list, empty($deposit_name) 		|| $deposit_name 		=== "" ? "" : ",	 deposit_name		= '{$deposit_name}'");
		
		$update_list = join("\n", $update_list);

		return DB::Execute("
			UPDATE `{$service_name}` SET
				update_date = NOW()
				{$update_list}
			WHERE
				idx = {$target_idx}
		");
	}
	/************************************** DELETE **************************************/
	public static function delete()
	{
	}
	/************************************** SELECT DETAIL **************************************/
	public static function get()
	{
	}
	/************************************** SELECT STATISTICS **************************************/
	/************************************** SELECT LIST **************************************/
	public static function getList($parent_idx, $view_status, $sort_list)
    {// 목록 조회 (2021.05.20 / By.Chungwon)
		$service_name = self::$service_name;
        $order_sql 			= empty($sort_list) 	? "" : "ORDER BY " . stripslashes($sort_list);		

        $view_status_sql 	= empty($view_status) 	? "" : "AND view_status IN ({$view_status})";
        $parent_idx_sql     = empty($parent_idx) ? "" :
        "	INNER JOIN
                (
                    SELECT
                        *
						,	parent_idx AS parent_idx2
                    FROM
                        `{$service_name}`
                    WHERE
                        parent_idx = {$parent_idx}
                        {$view_status_sql}
                )
            AS cmp2
            ON cmp2.parent_idx = cmp.idx
        "; // 기본값은 부모만 가져오기
        

		return DB::Execute("
            SELECT SQL_CALC_FOUND_ROWS
                *
            FROM
                (
                    SELECT
                        *
                    FROM
                        `{$service_name}`
                    WHERE
                        parent_idx IS NULL
                        {$view_status_sql}
                )
            AS cmp
            
            {$parent_idx_sql}
            
            {$order_sql}

		", TRUE);
	}
	public static function getListType($sort_list = null)
    {// 목록 타입 조회 (2021.05.04 / By.Chungwon)
		$service_name = self::$service_name;
        $order_sql 			= empty($sort_list) 	? "" : "ORDER BY " . stripslashes($sort_list);		

		return DB::Execute("
            SELECT SQL_CALC_FOUND_ROWS
                *
            FROM
				`{$service_name}_type`
            {$order_sql}
        ");
	}
	public static function getListInfo($type_idx, $sort_list = null)
    {// 목록 정보 조회 (2021.05.07 / By.Chungwon)
		$service_name = self::$service_name;
        $order_sql 			= empty($sort_list) 	? "" : "ORDER BY " . stripslashes($sort_list);		

		return DB::Execute("
            SELECT SQL_CALC_FOUND_ROWS
                *
            FROM
				`{$service_name}_info`
			WHERE
				type_idx = {$type_idx}

            {$order_sql}
		");
	}
}