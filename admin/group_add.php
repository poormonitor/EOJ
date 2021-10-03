<?php require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}
?>

<title>Add Group</title>
<hr>
<center><h3><?php echo $MSG_GROUP."-".$MSG_ADD?></h3></center>

<div class='container'>

<?php
if(isset($_POST['do']) and $_POST['do']!= "" and isset($_POST['gid']) and $_POST['gid']!= "" and $_POST['gid']!= "-1"){
  //echo $_POST['user_id'];
  require_once("../include/check_post_key.php");
  //echo $_POST['passwd'];
  require_once("../include/my_func.inc.php");
  
  $gid = $_POST['gid'];
  $pieces = explode("\n", trim($_POST['ulist']));
  $pieces = preg_replace("/[^\x20-\x7e]/", " ", $pieces);  //!!important
  $count = 0;
  foreach ($pieces as $i){
      if ($gid == "0"){
          pdo_query("UPDATE `users` SET `gid`=NULL WHERE `user_id`=?",$i);
      } else {
          pdo_query("UPDATE `users` SET `gid`=? WHERE `user_id`= ?;",$gid,$i);
      }
      $count+=1;
  }
  echo "<div class='alert alert-success' role='alert' style='margin-left: 10px;margin-right: 10px;margin-top: 10px;'>Successfully set $count user(s).</div>";

}
?>

<form action=group_add.php method=post class="form-horizontal">
  <div>
    <label class="col-sm"><?php echo $MSG_USER_ID?></label>
  </div>
  <div>
    <?php echo "( Add new user newline )"?>
    <br><br>
    <table width="100%">
      <tr>
        <td height="*">
          <p align=left>
            <textarea name='ulist' rows='10' style='width:100%;' placeholder="userid1&#10;userid2&#10;userid3"></textarea>
          </p>
        </td>
      </tr>
    </table>
  </div>

  <div class="form-group">
    <?php require_once("../include/set_post_key.php");?>
      <div class='col-md-4'></div>
      <div class='col-md-4'>
		<div style='text-align:center;'><?php echo $MSG_GROUP?></div>
    	<select class="form-control" size="1" name="gid">
    	    <option value='0'>删除组信息</option>
    		<?php
    		require_once("../include/my_func.inc.php");
    		$sql_all="SELECT * FROM `group`;";
            $result = pdo_query($sql_all);
            $all_group = $result;
    		if (isset($_GET['gid'])) {
    			$gid = intval($_GET['gid']);
    		}
    		foreach ($all_group as $i) {
    		    $show_id=$i["gid"];
    		    $show_name=$i["name"];    		
    		    if (isset($_GET['gid'])) {
    		        if ($_GET['gid'] == $show_id){
    		            echo "<option value=$show_id selected>$show_name</option>";
    		        } else {
    		            echo "<option value=$show_id >$show_name</option>";
    		        }

    		    } else {
    			echo "<option value=$show_id >$show_name</option>";
    		    }
    		}
    		?>
    	</select>&nbsp;&nbsp;
      <button name="do" type="hidden" value="do" class="btn btn-default btn-block" ><?php echo $MSG_SAVE?></button>
    </div>
    <div class='col-md-4'></div>
  </div>

</form>

</div>


