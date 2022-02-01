<?php require("admin-header.php"); ?>
<div class="container">
  <br />
  <?php
  $csql = array();
  $csql[0] = "
  DELETE FROM solution WHERE result=13;
  DELETE FROM source_code WHERE solution_id NOT in (SELECT solution_id FROM solution);
  DELETE FROM runtimeinfo WHERE solution_id NOT IN (SELECT solution_id FROM solution);
  UPDATE solution SET solution.nick = (SELECT users.nick FROM users WHERE users.user_id = solution.user_id) WHERE solution.nick != (SELECT users.nick FROM users WHERE users.user_id = solution.user_id);
  DELETE FROM sim WHERE sim_s_id NOT IN (SELECT solution_id FROM solution);
  DELETE FROM sim WHERE s_id NOT IN (SELECT solution_id FROM solution);
  ";
  if (isset($_POST['do'])) {
    require_once("../include/check_post_key.php");
    for ($i = 0; isset($csql[$i]); $i++) {
      pdo_query($csql[$i]);
    }
    echo "<span class='alert alert-success'>成功！</span>";
  }
  ?>
  <br />
  <h3 class='center'><?php echo $MSG_UPDATE_DATABASE ?></h3>
  <p class='center'>
    <?php echo $MSG_HELP_UPDATE_DATABASE ?>
  </p>
  <br />
  <form action='update_db.php' method=post class='middle'>
    <?php require_once("../include/set_post_key.php"); ?>
    <input type='hidden' name='do' value='do'>
    <input type=submit class='btn btn-info' value='更新'>
  </form>
</div>
<?php
require_once("admin-footer.php");
?>