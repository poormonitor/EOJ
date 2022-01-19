<?php
require("admin-header.php");
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}
if (isset($OJ_LANG)) {
  require_once("../lang/$OJ_LANG.php");
}

$py_bin = "/home/judge/py3/bin/pip3";

if (isset($_GET['do'])) {
  $module = trim($_GET["module"]);
  //require_once("../include/check_get_key.php");
  if ($_GET["do"] == "install") {
    $command = $py_bin . " install $module";
  }
  if ($_GET["do"] == "uninstall") {
    $command = $py_bin . " uninstall $module -y";
  }
  if ($_GET["do"] == "upgrade") {
    $command = $py_bin . " upgrade $module";
  }
  $install = shell_exec($command);
}
?>
<title>模块安装</title>
<hr>
<center>
  <h3>已安装模块</h3>
</center>

<div class='container'>

  <?php
  if (isset($install)) {
    echo "<pre id=code>";
    echo str_replace("\n", "<br />", htmlentities($install, ENT_QUOTES, "UTF-8"));
  }
  echo "</pre>";
  ?>

  <br />
  <center>
    <table width=100% border=1 style="text-align:center;">
      <tr>
        <td>模块</td>
        <td>版本</td>
        <td>更新</td>
        <td>卸载</td>
      </tr>
      <?php
      require_once("../include/set_get_key.php");
      $getkey = $_SESSION[$OJ_NAME . '_' . 'getkey'];

      $src = shell_exec($py_bin . " list --format=json");
      $json = json_decode($src);
      foreach ($json as $i) {
        $name = $i->name;
        $version = $i->version;
        echo "<tr>";
        echo "<td>$name</td>";
        echo "<td>$version</td>";
        echo "<td><a class='green' href='pip.php?do=upgrade&module=$name&getkey=$getkey'>更新</td>";
        echo "<td><a class='red' href='pip.php?do=uninstall&module=$name&getkey=$getkey'>卸载</td>";
        echo "</tr>";
      }
      ?>
    </table>
  </center>
</div>
<br /><br />
<center>
  <form action=pip.php class="form-search form-inline">
    <input type="text" name="module" class="form-control search-query" placeholder="模块名">
    <input type=hidden name="getkey" value="<?php echo $getkey; ?>">
    <button name="do" value="install" type="submit" class="form-control">安装</button>
  </form>
</center>