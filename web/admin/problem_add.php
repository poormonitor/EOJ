<?php
require_once("admin-header.php");
require_once("../include/check_post_key.php");

require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
require_once("../include/problem.php");

// contest_id
$title = $_POST['title'];
$title = str_replace(",", "&#44;", $title);
$time_limit = $_POST['time_limit'];
$memory_limit = $_POST['memory_limit'];

$description = $_POST['description'];
$description = str_replace("<p>", "", $description);
$description = str_replace("</p>", "<br>", $description);
$description = str_replace(",", "&#44;", $description);

$input = $_POST['input'];
$input = str_replace("<p>", "", $input);
$input = str_replace("</p>", "<br>", $input);
$input = str_replace(",", "&#44;", $input);

$output = $_POST['output'];
$output = str_replace("<p>", "", $output);
$output = str_replace("</p>", "<br>", $output);
$output = str_replace(",", "&#44;", $output);

$sample_input = $_POST['sample_input'];
$sample_output = $_POST['sample_output'];
$test_input = $_POST['test_input'];
$test_output = $_POST['test_output'];
/* don't do this , we will left them empty for not generating invalid test data files 
if ($sample_input=="") $sample_input="\n";
if ($sample_output=="") $sample_output="\n";
if ($test_input=="") $test_input="\n";
if ($test_output=="") $test_output="\n";
*/
$hint = $_POST['hint'];
$hint = str_replace("<p>", "", $hint);
$hint = str_replace("</p>", "<br>", $hint);
$hint = str_replace(",", "&#44;", $hint);
$source = join(" ", explode(",", trim($_POST['source'])));
$allow = join(" ", explode(",", trim($_POST['allow'])));
$block = join(" ", explode(",", trim($_POST['block'])));

$spj = $_POST['spj'];

//echo "->".$OJ_DATA."<-"; 
$pid = addproblem($title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $spj, $OJ_DATA);
$basedir = "$OJ_DATA/$pid";
mkdir($basedir);
if (strlen($sample_output) && !strlen($sample_input)) $sample_input = "0";
if (strlen($sample_input)) mkdata($pid, "sample.in", $sample_input, $OJ_DATA);
if (strlen($sample_output)) mkdata($pid, "sample.out", $sample_output, $OJ_DATA);
if (strlen($test_output) && !strlen($test_input)) $test_input = "0";
if (strlen($test_input)) mkdata($pid, "test.in", $test_input, $OJ_DATA);
if (strlen($test_output)) mkdata($pid, "test.out", $test_output, $OJ_DATA);

$sql = "INSERT INTO `privilege` (`user_id`,`rightstr`) VALUES(?,?)";
pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'], "p$pid");
$_SESSION[$OJ_NAME . '_' . "p$pid"] = true;
if ($_POST['blank'] == '1') {
  $blank_code = $_POST['blank_code'];
  $sql = 'update `problem` set `blank`=? where `problem_id`=?';
  pdo_query($sql, $blank_code, $pid);
}
if ($allow != '') {
  $sql = 'update `problem` set `allow`=? where `problem_id`=?';
  pdo_query($sql, $allow, $id);
}
if ($block != '') {
  $sql = 'update `problem` set `block`=? where `problem_id`=?';
  pdo_query($sql, $block, $id);
}
echo "&nbsp;&nbsp;- <a href='javascript:phpfm($pid);'>添加更多的测试数据</a>";
/*  */
?>

<?php
require_once("admin-footer.php");
?>
<script>
  function phpfm(pid) {
    //alert(pid);
    $.post("phpfm.php", {
      'frame': 3,
      'pid': pid,
      'pass': ''
    }, function(data, status) {
      if (status == "success") {
        document.location.href = "phpfm.php?frame=3&pid=" + pid;
      }
    });
  }
</script>
