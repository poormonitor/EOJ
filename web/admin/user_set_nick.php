<?php require_once("admin-header.php");
?>
<center><h3><?php echo $MSG_USER."-".$MSG_NICK?></h3></center>

<div class='container'>

<?php
if(isset($_POST['do'])){
  //echo $_POST['user_id'];
  require_once("../include/check_post_key.php");
  //echo $_POST['passwd'];
  require_once("../include/my_func.inc.php");

  $pieces = explode("\n", trim($_POST['ulist']));
  $ulist = "";
  if(count($pieces)>0 && strlen($pieces[0])>0){
    for($i=0; $i<count($pieces); $i++){
      $id_pw = explode(" ", trim($pieces[$i]));
      if(count($id_pw) != 2){
        echo "&nbsp;&nbsp;&nbsp;&nbsp;".$id_pw[0]." ... 错误：格式错误！<br />";
        for($j=0; $j<count($id_pw); $j++)
        {
          $ulist = $ulist.$id_pw[$j]." ";
        }
        $ulist = trim($ulist)."\n";
      } else {
        $sql = "SELECT `user_id` FROM `users` WHERE `users`.`user_id` = ?";
        $result = pdo_query($sql, $id_pw[0]);
        $rows_cnt = count($result);

        if($rows_cnt != 1){
          echo "&nbsp;&nbsp;&nbsp;&nbsp;".$id_pw[0]." ... 错误：用户不存在！<br />";
          $ulist = $ulist.$id_pw[0]." ".$id_pw[1]."\n";
        } else {
          $nick = $id_pw[1];
          $sql = "UPDATE `users` set `nick`=? WHERE `user_id`=?;";
          pdo_query($sql, $nick, $id_pw[0]);
          $sql = "UPDATE `solution` set `nick`=? WHERE `user_id`=?;";
          pdo_query($sql, $nick, $id_pw[0]);
          echo $id_pw[0]." 已更新！<br />";
        }
      }
    }
    echo "<br />剩余行有错误！<hr>";
  }
}
?>

<form action=user_set_nick.php method=post class="form-horizontal">
  <div>
    <label class="col-sm"><?php echo $MSG_USER_ID?> <?php echo $MSG_NICK?></label>
  </div>
  <div>
    <table width="100%">
      <tr>
        <td height="*">
          <p align=left>
            <textarea class='form-control' name='ulist' rows='10' style='width:100%;' placeholder='userid1 nick1<?php echo "\n"?>userid2 nick2<?php echo "\n"?>userid3 nick3<?php echo "\n"?>'><?php if(isset($ulist)){ echo $ulist;}?></textarea>
          </p>
        </td>
      </tr>
    </table>
  </div>
  <br />
  <div class="form-group">
    <?php require_once("../include/set_post_key.php");?>
    <div class="col-sm-offset-4 col-sm-2">
      <button name="do" type="hidden" value="do" class="btn btn-default btn-block" ><?php echo $MSG_SAVE?></button>
    </div>
    <div class="col-sm-2">
      <button name="submit" type="reset" class="btn btn-default btn-block"><?php echo $MSG_RESET?></button>
    </div>
  </div>

</form>

</div>

<?php
require_once("admin-footer.php");
?>
