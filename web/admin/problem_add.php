<?php
require_once("../include/check_post_key.php");
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
require_once("../include/problem.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
	|| isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])
)) {
	$view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
	$error_location = "../index.php";
	require("../template/error.php");
	exit(0);
}

require_once("admin-header.php"); ?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">
  <?php include("../template/css.php"); ?>
  <title><?php echo $OJ_NAME ?></title>
</head>

<body>
  <div class='container'>
    <?php include("../template/nav.php") ?>
    <div class='jumbotron'>
      <div class='row lg-container'>
        <?php require_once("sidebar.php") ?>
        <div class='col-md-9 col-lg-10 p-0'>
          <?php
          echo "<center><h3>" . $MSG_PROBLEM . "-" .  $MSG_EDIT . "</h3></center>";
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
            $sql = 'UPDATE `problem` set `blank`=? where `problem_id`=?';
            pdo_query($sql, $blank_code, $pid);
          }
          if ($allow != '') {
            $sql = 'UPDATE `problem` set `allow`=? where `problem_id`=?';
            pdo_query($sql, $allow, $pid);
          }
          if ($block != '') {
            $sql = 'UPDATE `problem` set `block`=? where `problem_id`=?';
            pdo_query($sql, $block, $pid);
          }
          ?>

          &nbsp;&nbsp;- <a href='phpfm.php?frame=3&pid=<?php echo $pid; ?>'><?php echo $MSG_TESTDATA ?></a>

          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
  <script>
    swal({
      title: "<?php echo $MSG_SUCCESS ?>",
      icon: "success",
      text: "<?php echo $MSG_EDIT_SUCCESS ?>",
      buttons: {
        roll: {
          text: "<?php echo $MSG_SEE ?>",
          value: "href"
        },
        test: {
          text: "<?php echo $MSG_TESTDATA ?>",
          value: "test"
        },
        confirm: true
      }
    }).then((value) => {
      if (value == "href") {
        window.location.href = "../problem.php?id=<?php echo $pid ?>"
      } else if (value == "test") {
        window.location.href = "phpfm.php?frame=3&pid=<?php echo $pid ?>";
      }
    })
  </script>
</body>

</html>