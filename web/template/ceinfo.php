<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">
  <title><?php echo $id . " - " .$OJ_NAME ?></title>
  <?php include("template/css.php"); ?>

  <link href='<?php echo $OJ_CDN_URL ?>template/prism.css' rel='stylesheet' type='text/css' />
</head>

<body>

  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="lr-container">
        <div class="table-responsive">
          <table class="table" style="margin-bottom:0;width:50%">
            <thead>
              <tr>
                <th><?php echo $MSG_RUNID ?></th>
                <th><?php echo $MSG_PROBLEM ?></th>
                <th><?php echo $MSG_USER ?></th>
                <th><?php echo $MSG_NICK ?></th>
                <th><?php echo $MSG_LANG ?></th>
                <th><?php echo $MSG_RESULT ?></span></th>
                <?php if ($sresult == 4) { ?>
                  <th><?php echo $MSG_TIME ?></th>
                  <th><?php echo $MSG_MEMORY ?></th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $id ?></td>
                <td>
                  <a href="problem.php?id=<?php echo $sproblem_id ?>">
                    <?php echo $sproblem_id ?>
                  </a>
                </td>
                <td>
                  <a href="userinfo.php?user=<?php echo $suser_id ?>">
                    <?php echo $suser_id ?>
                  </a>
                </td>
                <td><?php echo $snick ?></td>
                <td>
                  <a href="showsource.php?id=<?php echo $id ?>">
                    <?php echo $language_name[$slanguage] ?>
                  </a>
                </td>
                <td>
                  <span class="label label-<?php echo $judge_color[$sresult] ?>">
                    <?php echo $judge_result[$sresult] ?>
                  </span>
                </td>
                <?php if ($sresult == 4) { ?>
                  <td><?php echo $stime ?> ms</td>
                  <td><?php echo $smemory ?> KB</td>
                <?php } ?>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <pre id='code' class="alert alert-error"><?php echo $view_reinfo ?></pre>
      <?php
      if ($ok == true) {
        $brush = strtolower($language_name[$slanguage]);
        if ($brush == "python3") $brush = "py";
        if ($brush == 'freebasic') $brush = 'vb';
        echo "<pre id='code'><code class='language-$brush line-numbers'>";
        ob_start();
        $auth = ob_get_contents();
        ob_end_clean();
        echo htmlentities(str_replace("\n\r", "\n", $view_source), ENT_QUOTES, "utf-8") . $auth . "</code></pre>";
      } else {
        echo $MSG_WARNING_ACCESS_DENIED;
      }
      ?>
    </div>

  </div>
  <?php include("template/js.php"); ?>
  <script src='<?php echo $OJ_CDN_URL ?>template/prism.js' type='text/javascript'></script>
</body>

</html>