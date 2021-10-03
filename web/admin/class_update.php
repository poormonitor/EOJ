<?php require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

if(isset($OJ_LANG)){
  require_once("../lang/$OJ_LANG.php");
}
?>

<title><?php echo $MSG_SCHOOL."-".$MSG_EDIT?></title>
<hr>
<center><h3><?php echo $MSG_SCHOOL."-".$MSG_EDIT?></h3></center>

<div class='container'>

<?php
if(isset($_GET['do']) and isset($_GET['prefix']) and $_GET['prefix']!= "" and isset($_GET['grade']) and $_GET['grade']!= ""){
  //echo $_POST['user_id'];
  require_once("../include/check_get_key.php");
  //echo $_POST['passwd'];
  require_once("../include/my_func.inc.php");
  
  $reg = $_GET['prefix'].".{5}";
  $pieces = pdo_query("SELECT * FROM users WHERE user_id REGEXP ?",$reg);
  $count = 0;
  foreach ($pieces as $i){
      $user = $i["user_id"];
      $class = trim($_GET['grade'])."（".strval(intval(substr($user,3,2)))."）班";
      pdo_query("UPDATE `users` SET `school`=? WHERE `user_id`=?",$class,$user);
      $count+=1;
  }
  echo "<div class='alert alert-success' role='alert' style='margin-left: 10px;margin-right: 10px;margin-top: 10px;'>Successfully set $count user(s).</div>";

}
?>

<form action="class_update.php" method="get" class="form-horizontal">
  <div class="form-group">
    <?php require_once("../include/set_get_key.php");?>
      <div class='col-md-4'></div>
      <div class='col-md-4'>
        <label>入学年份（学号前两位）</label>
        <input type="text" name=prefix class="form-control search-query" placeholder="20" style='margin-top:5px;margin-bottom:10px'>
        <label>年级</label>
        <input type="text" name=grade class="form-control search-query" placeholder="高二" style='margin-top:5px;margin-bottom:10px'>
        <input type=hidden name=getkey value="<?php echo $_SESSION[$OJ_NAME.'_'.'getkey']?>">
      <button name="do" type="hidden" value="do" class="btn btn-default btn-block" style='margin-top:10px;'><?php echo $MSG_SAVE?></button>
    </div>
    <div class='col-md-4'></div>
  </div>

</form>

</div>


