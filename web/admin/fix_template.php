<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter']))) {
    $view_swal_params = "{title:'$MSG_NOT_LOGINED',icon:'error'}";
    $error_location = "../loginpage.php";
    require("../template/error.php");
    exit(0);
}

$problem_id = intval($_GET["id"]);

$sql = "SELECT * FROM problem WHERE problem_id = ?";
$result = pdo_query($sql, $problem_id);

if ($result) {
    $row = $result[0];
} else {
    echo "Not existed!";
    exit(0);
}

$template = $row["blank"];
$filling = str_replace("*%*", "%*%", $template);

$sql = "SELECT * FROM source_code JOIN solution ON solution.solution_id = source_code.solution_id
JOIN problem ON problem.problem_id = solution.problem_id
WHERE problem.problem_id = ?";
$result = pdo_query($sql, $problem_id);

foreach ($result as $row) {
    $res = getBlankCode($blank, $row["source"]);
    print_r($res);
    foreach ($res as $code) {
        $new_source = str_replace_limit("%*%", $code, 1);
    }
    $sql = "UPDATE source_code SET source = ? WHERE solution_id = ?";
    pdo_query($sql, $new_code, $row["solution_id"]);
}

echo "success";