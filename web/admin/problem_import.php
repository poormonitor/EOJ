<?php
require_once("../include/db_info.inc.php");
require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))) {
  $view_errors_js = "swal('$MSG_NOT_LOGINED','$MSG_Login','error').then((onConfirm)=>{window.location.href='loginpage.php'})";
  require("template/error.php");
  exit(0);
}

function writable($path)
{
  $ret = false;
  $fp = fopen($path . "/testifwritable.tst", "w");
  $ret = !($fp === false);

  fclose($fp);
  unlink($path . "/testifwritable.tst");
  return $ret;
}

$maxfile = min(ini_get("upload_max_filesize"), ini_get("post_max_size"));

echo "<center><h3>" . $MSG_PROBLEM . "-" . $MSG_IMPORT . "</h3></center>";

?>

<div class="container">
  <br><br>
  <?php
  $show_form = true;

  if (!writable($OJ_DATA)) {
    echo "- You need to add  $OJ_DATA into your open_basedir setting of php.ini,<br>
        or you need to execute:<br>
        <b>chmod 775 -R $OJ_DATA && chgrp -R www-data $OJ_DATA</b><br>
        you can't use import function at this time.<br>";

    if ($OJ_LANG == "cn")
      echo "权限异常，请先去执行sudo chmod 775 -R $OJ_DATA <br> 和 sudo chgrp -R www-data $OJ_DATA <br>";

    $show_form = false;
  }

  if (!file_exists("../upload"))
    mkdir("../upload");

  if (!writable("../upload")) {
    echo "../upload is not writable, <b>chmod 770</b> to it.<br>";
    $show_form = false;
  }
  ?>

  <?php if ($show_form) { ?>
    - Import Problem XML<br><br>
    <form class='form-inline' action='problem_import_xml.php' method=post enctype="multipart/form-data">
      <div class='form-group'>
        <input class='form-control' type=file name=fps>
      </div>
      <br><br>
      <br><br><br>
      <center>
        <div class='form-group'>
          <button class='btn btn-default btn-sm' type=submit>Upload to HOJ</button>
        </div>
      </center>
      <?php require_once("../include/set_post_key.php"); ?>
    </form>
  <?php } ?>

  <br><br>

  - Import FPS data, please make sure you file is smaller than [<?php echo $maxfile ?>] or set upload_max_filesize and post_max_size in <span style='color:blue'>php.ini</span><br>
  - If you fail on import big files[10M+],try enlarge your [memory_limit] setting in <span style='color:blue'>php.ini</span><br>
  - To find the php configuration file, use <span style='color:blue'> find /etc -name php.ini </span>

</div>

<?php
require_once("admin-footer.php");
?>