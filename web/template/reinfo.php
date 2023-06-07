<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/css.php"); ?>

</head>

<body>

  <div class="container">
    <?php include("template/nav.php"); ?>
    <div class="jumbotron">
      <div class="lr-container">
        <div class="table-responsive">
          <table class="table mb-0 w-50">
            <thead>
              <tr>
                <th><?php echo $MSG_RUNID ?></th>
                <th><?php echo $MSG_PROBLEM ?></th>
                <th><?php echo $MSG_USER ?></th>
                <th><?php echo $MSG_NICK ?></th>
                <th><?php echo $MSG_LANG ?></th>
                <th><?php echo $MSG_RESULT ?></span></th>
                <?php if ($show_info["result"] == 4) { ?>
                  <th><?php echo $MSG_TIME ?></th>
                  <th><?php echo $MSG_MEMORY ?></th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $id ?></td>
                <td>
                  <a href="problem.php?id=<?php echo $show_info["problem_id"] ?>">
                    <?php echo $show_info["problem_id"] ?>
                  </a>
                </td>
                <td>
                  <a href="userinfo.php?user=<?php echo $show_info["user_id"] ?>">
                    <?php echo $show_info["user_id"] ?>
                  </a>
                </td>
                <td><?php echo $show_info["nick"] ?></td>
                <td>
                  <a href="showsource.php?id=<?php echo $id ?>">
                    <?php echo $language_name[$show_info["language"]] ?>
                  </a>
                </td>
                <td>
                  <span class="label label-<?php echo $judge_color[$show_info["result"]] ?>">
                    <?php echo $judge_result[$show_info["result"]] ?>
                  </span>
                </td>
                <?php if ($show_info["result"] == 4) { ?>
                  <td><?php echo $show_info["time"] ?> ms</td>
                  <td><?php echo $show_info["memory"] ?> KB</td>
                <?php } ?>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <pre id='code' class="alert alert-error"><?php echo $view_reinfo ?></pre>
    </div>
  </div>

  <?php include("template/js.php"); ?>
</body>

</html>