<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

$user_id = $_SESSION[$OJ_NAME . '_' . 'user_id'];

$folder = "../upload/files/";
if (!file_exists($folder)) {
	mkdir($folder, 0755);
}

$file = "../upload/files/msg.txt";

if (isset($_POST['enable'])) {
    require_once("../include/check_post_key.php");

    $enable = $_POST["enable"];
    $src = $_POST["src"];
    $href = $_POST["href"];
    $floating = $_POST["floating"];

    $content = "$enable\n$src\n$href\n$floating";

    $fp = fopen($file, "w");
    fputs($fp, $content);
    fclose($fp);
}

if (!file_exists($file)) {
    touch($file);
    chmod($file, 0755);
    $content = array("", "", "");
} else {
    $content = file_get_contents($file);
    $content = explode("\n", $content);
}
require_once("admin-header.php");
?>
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
                <input type=radio name="enable" value='1' <?php if ($content[0] == 1) echo "checked" ?>>
                <?php echo "/ " . $MSG_TRUE_FALSE[false] ?>
                <input type=radio name="enable" value='0' <?php if ($content[0] == 0) echo "checked" ?>>
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
                <input id="src" name="src" value="<?php echo $content[1] ?>" class="form-control" placeholder="<?php echo $MSG_FLOATING_URL ?>" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $MSG_FLOATING_HREF ?></label>
            <div class="col-sm-4">
                <input name="href" value="<?php echo $content[2] ?>" class="form-control" placeholder="<?php echo $MSG_FLOATING_HREF ?>" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $MSG_FLOATING_STATIC ?></label>
            <div class="col-sm-4">
                <?php echo $MSG_TRUE_FALSE[true] ?>
                <input type=radio name="floating" value='0' <?php if ($content[3] == 0) echo "checked" ?>>
                <?php echo "/ " . $MSG_TRUE_FALSE[false] ?>
                <input type=radio name="floating" value='1' <?php if ($content[3] == 1 || !$content[2]) echo "checked" ?>>
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
<?php
require_once("admin-footer.php");
?>