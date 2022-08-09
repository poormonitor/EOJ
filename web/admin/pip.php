<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");
require("admin-header.php");

if (isset($_POST['do'])) {
  require_once("../include/check_post_key.php");
  $module = $_POST["module"];
  $temp = tempnam(sys_get_temp_dir(), "pip_module");
  file_put_contents($temp, $module);
  if ($_POST["do"] == "install") {
    $command = $OJ_PY_BIN . " -m pip install -r $temp";
    $install = shell_exec($command);
    unlink($temp);
  }
  if ($_POST["do"] == "uninstall") {
    $command = $OJ_PY_BIN . " -m pip uninstall -y -r $temp";
    $install = shell_exec($command);
    echo $install;
    unlink($temp);
    exit(0);
  }
  if ($_POST["do"] == "upgrade") {
    $command = $OJ_PY_BIN . " -m pip install --upgrade -r $temp";
    $install = shell_exec($command);
    echo $install;
    unlink($temp);
    exit(0);
  }
}

$src = shell_exec($OJ_PY_BIN . " -m pip list --format=json");
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
          <h3 class='center'><?php echo $MSG_MODULE_INSTALLED ?></h3>

          <div class='container'>

            <?php
            if (isset($install)) {
              echo "<pre id=code>";
              echo str_replace("\n", "<br>", htmlentities($install, ENT_QUOTES, "UTF-8"));
              echo "</pre>";
            }
            ?>

            <br>
            <center>
              <form action=pip.php method="POST" class="form-search form-inline">
                <input type="text" name="module" class="form-control search-query" placeholder="<?php echo $MSG_MODULE ?>">
                <?php require_once("../include/set_post_key.php"); ?>
                <button name="do" value="install" type="submit" class="form-control"><?php echo $MSG_MODULE_INSTALL ?></button>
              </form>
            </center>
            <center>
              <table width=100% class='center table table-condensed'>
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

                  $json = json_decode($src);
                  foreach ($json as $i) {
                    $name = $i->name;
                    $version = $i->version;
                    echo "<tr>";
                    echo "<td>$name</td>";
                    echo "<td>$version</td>";
                    echo "<td><a class='green' href='javascript:upgradeModule(\"$name\", this)'>$MSG_UPGRADE</td>";
                    echo "<td><a class='red' href='javascript:uninstallModule(\"$name\", this)'>$MSG_UNINSTALL</td>";
                    echo "</tr>";
                  }
                  ?>
                </tbody>
              </table>
            </center>
          </div>
          <br>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
  <script>
    function uninstallModule(mod) {
      $.post("pip.php", {
        do: "uninstall",
        module: mod,
        postkey: "<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>"
      }, function(data, status) {
        swal({
          text: data,
          icon: "info"
        }).then(() => {
          window.location.reload()
        })
      })
    }

    function upgradeModule(mod) {
      $.post("pip.php", {
        do: "upgrade",
        module: mod,
        postkey: "<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>"
      }, function(data, status) {
        swal({
          text: data,
          icon: "info"
        }).then(() => {
          window.location.reload()
        })
      })
    }
  </script>
</body>

</html>