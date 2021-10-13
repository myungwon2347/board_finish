<?php	
namespace service;

use util\DB;
use util\Log;

require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

class Etc
{
	static $service_name = "etc";

	/************************************** SELECT DETAIL **************************************/
	public static function get($category = NULL, $type = NULL, $key = NULL, $status = NULL)
	{	// ETC 조회 (2020.06.16 / By.Chungwon)
		$category_sql = empty($category)	? "" : "AND category = '{$category}'";
		$type_sql = 	empty($type) 		? "" : "AND `type` = '{$type}'";
		$key_sql = 	empty($key) 		? "" : "AND `key` = '{$key}'";
		$status_sql = 	empty($status) 		? "AND status = 0" : "AND status = {$status}";

		$result = DB::Execute("
			SELECT
				*
			FROM
				etc
			WHERE
				1 = 1
				{$category_sql}
				{$type_sql}
				{$key_sql}
				{$status_sql}
		");

		return $result ? $result[0] : false;
	}
	/************************************** SELECT LIST **************************************/
	public static function getList($category = NULL, $type = NULL, $status = NULL)
	{	// ETC 목록 조회 (2020.06.16 / By.Chungwon)
		$category_sql = empty($category) ? "" : "AND category = '{$category}'";
		$type_sql = empty($type) ? "" : "AND `type` = '{$type}'";
		$status_sql = empty($status) ? "" : "AND status = {$status}";

		return DB::Execute("
			SELECT
				*
			FROM
				etc
			WHERE
				1 = 1
				{$category_sql}
				{$type_sql}
				{$status_sql}
		");
	}
	public static function getTypeList($category = NULL, $type = NULL)
	{	// ETC 타입 목록 조회 (2020.06.16 / By.Chungwon)
		$category_sql = empty($category) ? "" : "AND category = '{$category}'";

		return DB::Execute("
			SELECT DISTINCT
				`type`
			FROM
				etc
			WHERE
				`type` IS NOT NULL
				{$category_sql}
		");
	}
	/************************************** UPDATE **************************************/
	public static function update($category, $type, $value, $key, $name, $status, $height, $description, $placeholder)
	{	// 수정 (2020.07.10 / By.Chungwon)
		$service_name = self::$service_name;
		$update_list = array();

		// 접근 권한 설정 (작성자만 접근가능, 관리자는 무조건 실행)
		$auth_sql = "";
		// $auth_sql = $is_admin ? "" : "AND reg_user_idx = {$user_common_idx}";

		// 수정 파라미터 매핑
		array_push($update_list, is_null($target_idx) ? 		 "" : ",	 `target_idx` 		 = {$target_idx}	" );
		array_push($update_list, is_null($status) ? 			 "" : ",	 `status` 			 = {$status}		" );
		array_push($update_list, is_null($category) ? 			 "" : ",	 `category` 			 = '{$category}'	" );
		array_push($update_list, is_null($type) ? 				 "" : ",	 `type` 				 = '{$type}'		" );
		array_push($update_list, is_null($key) ? 			     "" : ",	 `key` 			 	 = '{$key}'		" );
		array_push($update_list, is_null($name) ? 				 "" : ",	 `name` 				 = '{$name}'		" );
		array_push($update_list, is_null($value) ? 				 "" : ",	 `value` 				 = '{$value}'		" );
		array_push($update_list, is_null($height) ? 			 "" : ",	 `height` 			 = {$height}		" );
		array_push($update_list, is_null($description) ? 		 "" : ",	 `description` 		 = '{$description}'	" );
		array_push($update_list, is_null($placeholder) ? 		 "" : ",	 `placeholder` 		 = '{$placeholder}'	" );
		$update_list = join("\n", $update_list);

		return DB::Execute("
			UPDATE {$service_name} SET
				update_date = NOW()
				{$update_list}
			WHERE
				`category` = '{$category}'
				AND `type` = '{$type}'
				AND `key` = '{$key}'
				{$auth_sql}
		");
	}
}