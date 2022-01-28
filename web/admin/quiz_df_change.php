<?php require_once("admin-header.php");
require_once("../include/check_get_key.php");
$qid = intval($_GET['qid']);
if (!(isset($_SESSION[$OJ_NAME . '_' . "mq$qid"]) || isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) exit();
$sql = "select `defunct` FROM `quiz` WHERE `quiz_id`=?";
$result = pdo_query($sql, $qid);
$num = count($result);
if ($num < 1) {

	echo "No Such Quiz!";
	require_once("../oj-footer.php");
	exit(0);
}
$row = $result[0];
if ($row[0] == 'N')
	$sql = "UPDATE `quiz` SET `defunct`='Y' WHERE `quiz_id`=?";
else
	$sql = "UPDATE `quiz` SET `defunct`='N' WHERE `quiz_id`=?";
pdo_query($sql, $qid);
?>
<script language=javascript>
	history.go(-1);
</script>
<?php
require_once("admin-footer.php");
?>