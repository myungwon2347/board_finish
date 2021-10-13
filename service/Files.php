<?php	
namespace service;

require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";

use util\DB;
use util\Log;
use util\File;

class Files
{
	static $service_name = "files";

	/************************************** INSERT **************************************/
	public static function insert($type, $ref_table, $ref_key, $ref_idx, $n_name, $o_name, $size, $ext, $order_num = "NULL", $value = "NULL")
	{	// 파일 추가 (2020.07.16 / By.Chungwon)
		$service_name = self::$service_name;

		$order_num 	= empty($order_num) ? "NULL" : "{$order_num}";
		$value 		= empty($value) 	? "NULL" : "'{$value}'";

		return DB::Execute("
			INSERT INTO `{$service_name}`
			(
				type
				,	ref_table
				,	ref_key
				,	ref_idx
				,	n_name
				,	o_name
				,	size
				,	ext
				,	order_num
				,	value
			)
			VALUES 
			(
				'{$type}'
				,	'{$ref_table}'
				,	'{$ref_key}'
				,	{$ref_idx}
				,	'{$n_name}'
				,	'{$o_name}'
				,	'{$size}'
				,	'{$ext}'
				,	{$order_num}
				,	{$value}
			)
		");
	}
	/************************************** UPDATE **************************************/
	public static function update($file_idx, $order_num, $type, $ref_table, $ref_key, $ref_idx, $n_name, $o_name, $value)
	{	// 파일 수정
		$service_name = self::$service_name;

		$update_list = array();

		array_push($update_list, empty($order_num) 	? "" : ",	 order_num = {$order_num}");
		array_push($update_list, empty($type) 	   	? "" : ",	 type = '{$type}'");
		array_push($update_list, empty($ref_table) 	? "" : ",	 ref_table = '{$ref_table}'");
		array_push($update_list, empty($ref_key) 	? "" : ",	 ref_key = '{$ref_key}'");
		array_push($update_list, empty($ref_idx) 	? "" : ",	 ref_idx = {$ref_idx}");
		array_push($update_list, empty($n_name) 	? "" : ",	 n_name = '{$n_name}'");
		array_push($update_list, empty($o_name) 	? "" : ",	 o_name = '{$o_name}'");
		array_push($update_list, empty($value) 		? "" : ",	 value = '{$value}'");
		
		$update_list = join("\n", $update_list);

		return DB::Execute("
			UPDATE `{$service_name}` SET
				update_date = NOW()
				{$update_list}
			WHERE
				idx = {$file_idx}
		");
	}
	public static function updateName($o_name, $order_num, $type, $ref_table, $ref_key, $ref_idx, $value)
	{	// 파일 수정
		$service_name = self::$service_name;

		$update_list = array();

		array_push($update_list, empty($order_num) 	? "" : ",	 order_num = {$order_num}");
		array_push($update_list, empty($value) 		? "" : ",	 value = '{$value}'");
		
		$update_list = join("\n", $update_list);

		return DB::Execute("
			UPDATE `{$service_name}` SET
				update_date = NOW()
				{$update_list}
			WHERE
				o_name = '{$o_name}'
				AND type = '{$type}'
				AND ref_table = '{$ref_table}'
				AND ref_key = '{$ref_key}'
				AND ref_idx = {$ref_idx}
		");
	}
	public static function updateHit($target_idx)
	{	// 수정 (2020.07.25 / By.Chungwon)
		$service_name = self::$service_name;

		return DB::Execute("
			UPDATE `{$service_name}` SET
				click_count = click_count + 1
			WHERE
				idx = {$target_idx}
		");
    }
	/************************************** DELETE **************************************/
	public static function delete($file_idx, $ref_idx, $is_admin = false)
	{	// 파일 삭제 (2020.05.26 / By.Chungwon)
		$service_name = self::$service_name;

		$file = Files::getFromIdx($file_idx);

		if($file)
		{
			if(File::delete($file['path']))
			{
				$ref_idx_sql = $is_admin ? "" : "AND ref_idx = {$ref_idx}";

				return DB::Execute("
					DELETE FROM `{$service_name}`
					WHERE
						idx = {$file_idx}
						{$ref_idx_sql}
				");
			}else{
				return true;
			}
		}
		return false;
	}
	public static function getFromIdx($file_idx)
	{	// 키 값으로 파일 가져오기
		$service_name = self::$service_name;

		$file =  DB::Execute("
			SELECT
				*
				,	CONCAT('/', type, '/', ref_table, '/', ref_key, '/', n_name) AS path
			FROM
				`{$service_name}`
			WHERE
				idx = {$file_idx}
		");

		return $file ? $file[0] : false;
	}
	/************************************** SELECT DETAIL **************************************/
	public static function getInfo($file_idx)
	{	// 파일 조회 (2020.05.26 / By.Chungwon)
		$service_name = self::$service_name;

		$file =  DB::Execute("
			SELECT
				*
				,	CONCAT('/', type, '/', ref_table, '/', ref_key, '/', n_name) AS path
			FROM
				`{$service_name}`
			WHERE
				idx = {$file_idx}
		");

		return $file ? $file[0] : false;
	}
	public static function getList($type = NULL, $ref_table = NULL, $ref_key = NULL, $ref_idx = NULL, $is_path = TRUE, $sort_list = NULL, $limit = 100, $offset = 0)
	{	// 파일 가져오기
		$service_name = self::$service_name;
        $order_sql 			= empty($sort_list) 	? "ORDER BY ISNULL(order_num) ASC,	order_num ASC" : "ORDER BY " . stripslashes($sort_list);		

		$type_sql = empty($type) ? "" : "AND type = '{$type}'";
		$ref_table_sql = empty($ref_table) ? "" : "AND ref_table = LOWER('{$ref_table}')";
		$ref_key_sql = empty($ref_key) ? "" : "AND ref_key = LOWER('{$ref_key}')";
		$ref_idx_sql = empty($ref_idx) ? "" : "AND ref_idx = {$ref_idx}";

		$path_sql = $is_path ? ",	CONCAT('/', type, '/', ref_table, '/', ref_key, '/', n_name) AS path" : "";

		return DB::Execute("
			SELECT SQL_CALC_FOUND_ROWS
				idx
				,	type
				,	ref_table
				,	ref_key
				,	size
				,	ref_idx
				,	o_name
				{$path_sql}
				,	IFNULL(order_num, 0) AS order_num
				,	IFNULL(value, '') AS value
				,	hit
			FROM
				`{$service_name}`
			WHERE
				1 = 1
				{$type_sql}
				{$ref_table_sql}
				{$ref_key_sql}
				{$ref_idx_sql}

				

			{$order_sql}

			LIMIT {$limit}
			OFFSET {$offset}

		", TRUE);
	}
}