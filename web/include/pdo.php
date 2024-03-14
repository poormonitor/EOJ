<?php
function pdo_query($sql)
{
	$num_args = func_num_args();
	$args = func_get_args();       //获得传入的所有参数的数组
	$args = array_slice($args, 1, --$num_args);

	global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS, $dbh;
	try {
		if (!$dbh) {
			$dbh = new PDO("mysql:host=" . $DB_HOST . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASS, array(PDO::ATTR_PERSISTENT => true, PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));
		}
		$sth = $dbh->prepare($sql);
		$sth->execute($args);
		$result = array();
		if (stripos($sql, "select") === 0) {
			$result = $sth->fetchAll();
		} else if (stripos($sql, "insert") === 0) {
			$result = $dbh->lastInsertId();
		} else {
			$result = $sth->rowCount();
		}
		//print($sql);
		$sth->closeCursor();
		return $result;
	} catch (PDOException $e) {
		$message = "SQL error, check your sql and the error log! SQL 错误，检查语句和错误日志！";
		$view_errors_js = "swal('SQL Error', '$message', 'error').then(()=>{window.location.reload()})";
		$OJ_FAIL = true;
		error_log(strval($e));
		error_log("SQL: " . str_replace(array(PHP_EOL, "   "), "", $sql) . " ARGS: " . json_encode($args));
		require_once(dirname(__FILE__) . "/../template/error.php");
		exit(0);
	}
}
