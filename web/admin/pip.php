<?php
require("admin-header.php");
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (isset($_GET['do'])) {
  $module = trim($_GET["module"]);
  //require_once("../include/check_get_key.php");
  if ($_GET["do"] == "install") {
    $command = $OJ_PY_BIN . " -m pip install $module";
  }
  if ($_GET["do"] == "uninstall") {
    $command = $OJ_PY_BIN . " -m pip uninstall $module -y";
  }
  if ($_GET["do"] == "upgrade") {
    $command = $OJ_PY_BIN . " -m pip upgrade $module";
  }
  $install = shell_exec($command);
}
?>
<h3 class='center'><?php echo $MSG_MODULE_INSTALLED ?></h3>

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
    <table width=100% class='center table table-bordered table-condensed'>
      <thead>
        <tr>
          <th class='center'><?php echo $MSG_MODULE ?></th>
          <th class='center'><?php echo $MSG_VERSION ?></th>
          <th class='center'><?php echo $MSG_UPGRADE ?></th>
          <th class='center'><?php echo $MSG_UNINSTALL ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once("../include/set_get_key.php");
        $getkey = $_SESSION[$OJ_NAME . '_' . 'getkey'];

        $src = shell_exec($OJ_PY_BIN . " -m pip list --format=json");
        echo $src;
        $json = json_decode($src);
        foreach ($json as $i) {
          $name = $i->name;
          $version = $i->version;
          echo "<tr>";
          echo "<td>$name</td>";
          echo "<td>$version</td>";
          echo "<td><a class='green' href='pip.php?do=upgrade&module=$name&getkey=$getkey'>$MSG_UPGRADE</td>";
          echo "<td><a class='red' href='pip.php?do=uninstall&module=$name&getkey=$getkey'>$MSG_UNINSTALL</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </center>
</div>
<br /><br />
<center>
  <form action=pip.php class="form-search form-inline">
    <input type="text" name="module" class="form-control search-query" placeholder="<?php echo $MSG_MODULE ?>">
    <input type=hidden name="getkey" value="<?php echo $getkey; ?>">
    <button name="do" value="install" type="submit" class="form-control"><?php echo $MSG_MODULE_INSTALL ?></button>
  </form>
</center>
<?php
require_once("admin-footer.php");
?>