<?php
require_once("../include/db_info.inc.php");
require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
	|| isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])
)) {
	$view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
	$error_location = "../index.php";
	require("../template/error.php");
	exit(0);
}

?>
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
        <div class='col-md-10 p-0'>
          <?php
          echo "<center><h3>" . $MSG_PROBLEM . "-" . $MSG_EXPORT . "</h3></center>";
          ?>
          <div class="container">
            <br><br>
            - Export Problem XML<br><br>
            <form class="form-inline" action="problem_export_xml.php" method=post>
              <div class="form-group">
                <label>1) Continuous Problem IDs:</label>
                <input class="form-control" name="start" type="text" placeholder="1001">
              </div>
              <div class="form-group">
                <label> ~ </label>
                <input class="form-control" name="end" type="text" placeholder="1009">
              </div>
              <br><br>
              <div class="form-group">
                <label>2) Separate&nbsp;&nbsp;&nbsp;&nbsp; Problem IDs:</label>
                <input class="form-control" name="in" type="text" placeholder="1001,1003,1005, ... ">
              </div>
              <br><br>

              <center>
                <div class='form-group'>
                  <input type="hidden" name="do" value="do">
                  <!-- <input type="submit" name="submit" value="Export to XML Script"> -->
                  <button class='btn btn-default btn-sm' type=submit>Download to XML File</button>
                </div>
              </center>

              <?php require_once("../include/set_post_key.php"); ?>
            </form>

            <br><br>
            <!--
    * from-to will working if empty IN <br>
    * if using IN,from-to will not working.<br>
    * IN can go with "," seperated problem_ids like [1000,1020]
    -->
            - Continuous Problem IDs fields will be applied when Seperate Problem IDs fields was empty.<br>
            - Seperate Problem IDs fields will be applied when Continuous Problem IDs fields was empty.
          </div>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>