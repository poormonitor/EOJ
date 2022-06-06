<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="<?php echo $OJ_NAME?>">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="template/scrollboard.css">
    <title><?php echo $OJ_NAME ?></title>
    <?php include("template/css.php"); ?>

</head>

<body>
    
    <?php include("template/js.php"); ?>
    <script type="text/javascript" src="<?php echo $OJ_CDN_URL ?>include/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="<?php echo $OJ_CDN_URL ?>template/scrollboard.min.js"></script>
    <script type="text/javascript">
        var board = new Board(<?php echo $problem_num ?>, new Array(<?php echo $gold_num ?>, <?php echo $silver_num ?>, <?php echo $bronze_num ?>), StringToDate("<?php echo  $start_time_str ?>"), StringToDate("<?php echo $lock_time_str ?>"), <?php echo $cid ?>);

        board.showInitBoard();
        $('html').click(function(e) {
            board.keydown();
        });
        $('html').keydown(function(e) {
            if (e.keyCode == 13)
                board.keydown();
        });
    </script>
</body>

</html>