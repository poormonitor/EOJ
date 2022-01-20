<?php
if (stripos($_SERVER['REQUEST_URI'], "template") !== false) exit();
$url = basename($_SERVER['REQUEST_URI']);
if (isset($OJ_NEED_LOGIN) && $OJ_NEED_LOGIN && ($url != 'loginpage.php' &&
  $url != 'lostpassword.php' &&
  $url != 'lostpassword2.php' &&
  $url != 'registerpage.php') && !isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {

  header("location:" . "loginpage.php");
  exit();
}
$_SESSION[$OJ_NAME . '_' . 'profile_csrf'] = rand();
if ($OJ_ONLINE) {
  require_once('include/online.php');
  $on = new online();
}
?>
<!-- Static navbar -->
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo $OJ_HOME ?>"><i class="icon-home"></i><?php echo $OJ_NAME ?></a>
      <?php if (file_exists("moodle")) { ?>
        <a class="navbar-brand" href="moodle"><i class="icon-home"></i>Moodle</a>
      <?php } ?>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <?php $ACTIVE = "class='active'" ?>

        <?php if (!isset($OJ_ON_SITE_CONTEST_ID)) { ?>
          <li <?php if ($url == "faqs.php") echo " $ACTIVE"; ?>>
            <a href="faqs.php">
              <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> <?php echo $MSG_FAQ ?>
            </a>
          </li>
        <?php } else { ?>
        <?php } ?>

        <?php if (isset($OJ_PRINTER) && $OJ_PRINTER) { ?>
          <li <?php if ($url == "printer.php") echo " $ACTIVE"; ?>>
            <a href="printer.php">
              <span class="glyphicon glyphicon-print" aria-hidden="true"></span> <?php echo $MSG_PRINTER ?>
            </a>
          </li>
        <?php } ?>

        <?php if (!isset($OJ_ON_SITE_CONTEST_ID)) { ?>
          <li <?php if ($url == "problemset.php") echo " $ACTIVE"; ?>>
            <a href="problemset.php"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> <?php echo $MSG_PROBLEMS ?></a>
          </li>
          <li <?php if ($url == "category.php") echo " $ACTIVE"; ?>>
            <a href="category.php"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> <?php echo $MSG_SOURCE ?></a>
          </li>
          <li <?php if ($url == "status.php") echo " $ACTIVE"; ?>>
            <a href="status.php"><span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span> <?php echo $MSG_STATUS ?></a>
          </li>
          <li <?php if ($url == "ranklist.php") echo " $ACTIVE"; ?>>
            <a href="ranklist.php"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> <?php echo $MSG_RANKLIST ?></a>
          </li>
          <li <?php if ($url == "contest.php") echo " $ACTIVE"; ?>>
            <a href="contest.php"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> <?php echo $MSG_CONTEST ?></a>
          </li>
          <li <?php if ($url == "quiz.php") echo " $ACTIVE"; ?>>
            <a href="quiz.php"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> <?php echo $MSG_QUIZ ?></a>
          </li>
        <?php } else { ?>
          <li <?php if ($url == "contest.php") echo " $ACTIVE"; ?>>
            <a href="contest.php"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> <?php echo $MSG_CONTEST ?></a>
          </li>
        <?php } ?>

        <?php if (isset($_GET['cid'])) {
          $cid = intval($_GET['cid']);
        } ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span id="profile">Login</span><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <script src="<?php echo "template/$OJ_TEMPLATE/profile.php?profile_csrf=" . $_SESSION[$OJ_NAME . '_' . 'profile_csrf']; ?>"></script>
            <!--<li><a href="../navbar-fixed-top/">Fixed top</a></li>-->
          </ul>
        </li>
      </ul>
    </div>
    <!--/.nav-collapse -->
  </div>
  <!--/.container-fluid -->
</nav>