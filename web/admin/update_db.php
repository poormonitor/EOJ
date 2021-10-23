<?php require("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
} ?>
<div class="container">
  <br />
  <?php
  $csql = array();
  $csql[0] = "
  DELETE FROM solution WHERE result=13;
  DELETE FROM source_code WHERE solution_id NOT in (SELECT solution_id FROM solution);
  DELETE FROM source_code_user WHERE solution_id NOT in (SELECT solution_id FROM solution);
  DELETE FROM runtimeinfo WHERE solution_id NOT IN (SELECT solution_id FROM solution);
  UPDATE solution SET solution.nick = (SELECT users.nick FROM users WHERE users.user_id = solution.user_id) WHERE solution.nick != (SELECT users.nick FROM users WHERE users.user_id = solution.user_id);
  
  ";
  if (isset($_POST['do'])) {
    require_once("../include/check_post_key.php");
    for ($i = 0; isset($csql[$i]); $i++) {
      pdo_query($csql[$i]);
    }
    echo "<span class='alert alert-success'>成功！</span>";
  }
  ?>
  <br /><br />
  <b>更新数据库</b>
  用于确认数据库格式正确。
  <form action='update_db.php' method=post>
    <?php require_once("../include/set_post_key.php"); ?>
    <input type='hidden' name='do' value='do'>
    <input type=submit class='btn btn-info' value='更新'>
  </form>

  <?php if (file_exists("update_pw.php")) {  ?>
    <b>更新密码</b>
    用于保证用户数据安全。<br>
    <a class='btn btn-info' href="update_pw.php">更新</a>
    * 请只执行一次！
  <?php } ?>
</div>