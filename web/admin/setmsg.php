<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']))) {
    $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
    $error_location = "../index.php";
    require("../template/error.php");
    exit(0);
}

$user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];

$sql = "SELECT * FROM news WHERE news_id = -1";
$result = pdo_query($sql);

if (!$result) {
    $content = array("float" => "false", "image" => "", "href" => "");
    $content = json_encode($content);

    $sql = "INSERT INTO news (news_id, user_id, title, content, defunct) 
            VALUES (-1, ?, 'msg', ?, 'Y')";
    pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'], $content);

    $sql = "SELECT * FROM news WHERE news_id = -1";
    $result = pdo_query($sql);
}

$content = $result[0]["content"];
$content = json_decode($content, true);
$disable = $result[0]["defunct"];

if (isset($_POST['disable'])) {
    require_once("../include/check_post_key.php");

    $disable = $_POST["disable"];
    $src = $_POST["src"];
    $href = $_POST["href"];
    $floating = $_POST["floating"];

    $content = array("float" => $floating, "image" => $src, "href" => $href);
    $json = json_encode($content);

    $sql = "UPDATE news SET content = ?, defunct = ? WHERE news_id = -1";
    pdo_query($sql, $json, $disable);

    $ip = getRealIP();
    $sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
    pdo_query($sql, "msg", $_SESSION[$OJ_NAME . '_' . 'user_id'], "set msg", $ip);
}

require_once("admin-header.php");
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
                <div class='col-md-9 col-lg-10 p-0'>
                    <div class="container">
                        <center>
                            <h3><?php echo  $MSG_NEWS . "-" . $MSG_SETMESSAGE ?></h3>
                        </center>
                        <br>
                        <form method=POST action="setmsg.php" role="form" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo $MSG_ENABLE ?></label>
                                <div class="col-sm-4">
                                    <?php echo $MSG_TRUE_FALSE[true] ?>
                                    <input type=radio name="disable" value='N' <?php if ($disable === "N") echo "checked" ?>>
                                    <?php echo "/ " . $MSG_TRUE_FALSE[false] ?>
                                    <input type=radio name="disable" value='Y' <?php if ($disable === "Y") echo "checked" ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo $MSG_UPLOAD ?></label>
                                <div class="col-sm-4">
                                    <input id="file" type="file">
                                </div>
                                <div class="col-sm-4">
                                    <a href="javascript:uploadFile()" class="btn btn-sm btn-primary"><?php echo $MSG_UPLOAD ?></a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo $MSG_FLOATING_URL ?></label>
                                <div class="col-sm-4">
                                    <input id="src" name="src" value="<?php echo $content["image"] ?>" class="form-control" placeholder="<?php echo $MSG_FLOATING_URL ?>" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo $MSG_FLOATING_HREF ?></label>
                                <div class="col-sm-4">
                                    <input name="href" value="<?php echo $content["href"] ?>" class="form-control" placeholder="<?php echo $MSG_FLOATING_HREF ?>" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo $MSG_FLOATING_STATIC ?></label>
                                <div class="col-sm-4">
                                    <?php echo $MSG_TRUE_FALSE[true] ?>
                                    <input type=radio name="floating" value='false' <?php if ($content["float"] === "false") echo "checked" ?>>
                                    <?php echo "/ " . $MSG_TRUE_FALSE[false] ?>
                                    <input type=radio name="floating" value='true' <?php if ($content["float"] === "true") echo "checked" ?>>
                                </div>
                            </div>
                            <div class='col-sm-4 col-sm-offset-4'>
                                <?php require_once("../include/set_post_key.php"); ?>
                                <button name="submit" type="submit" class="btn btn-default btn-block"><?php echo $MSG_SAVE ?></button>
                            </div>
                        </form>
                    </div><br><br>

                    <?php $_SESSION[$OJ_NAME . '_' . 'uploadkey'] = strtoupper(substr(MD5($_SESSION[$OJ_NAME . '_' . 'user_id'] . rand(0, 9999999)), 0, 10)); ?>
                    <script>
                        var uploadkey = '<?php echo $_SESSION[$OJ_NAME . '_' . 'uploadkey'] ?>';

                        function uploadFile() {
                            var fd = new FormData();
                            fd.append("uploadkey", uploadkey);
                            fd.append("file", $("input#file").get(0).files[0]);
                            $.ajax({
                                url: "../tinymce/upimg.php",
                                type: "POST",
                                processData: false,
                                contentType: false,
                                data: fd,
                                success: function(data, status) {
                                    $("input#src").val(JSON.parse(data).location);
                                }
                            });
                        }
                    </script>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <?php require_once("../template/js.php"); ?>
</body>

</html>