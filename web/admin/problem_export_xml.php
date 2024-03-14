<?php
require_once("../include/db_info.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
  || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])
)) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
if (!isset($OJ_LANG)) {
  $OJ_LANG = "en";
}

require_once("../lang/$OJ_LANG.php");
require_once("../include/const.inc.php");

function fixcdata($content)
{
  $content = str_replace("\x1a", "", $content);   // remove some strange \x1a [SUB] char from datafile
  return str_replace("]]>", "]]]]><![CDATA[>", $content);
}

function getTestFileIn($pid, $testfile, $OJ_DATA)
{
  if ($testfile != "")
    return file_get_contents("$OJ_DATA/$pid/" . $testfile . ".in");
  else
    return "";
}

function getTestFileOut($pid, $testfile, $OJ_DATA)
{
  if ($testfile != "")
    return file_get_contents("$OJ_DATA/$pid/" . $testfile . ".out");
  else
    return "";
}

function printTestCases($pid, $OJ_DATA)
{
  $ret = "";
  //$pdir = opendir("$OJ_DATA/$pid/");
  $files = scandir("$OJ_DATA/$pid/"); //sorting file names by ascending order with default scandir function

  //while ($file=readdir($pdir)) {
  foreach ($files as $file) {
    $pinfo = pathinfo($file);
    $fullpath = "$OJ_DATA/$pid/" . $file;
    if (is_file($fullpath) && $pinfo['basename'] != "sample.in") {
      if ($pinfo['extension'] == "in") {
        $ret = basename($pinfo['basename'], "." . $pinfo['extension']);

        $outfile = "$OJ_DATA/$pid/" . $ret . ".out";
        $infile = "$OJ_DATA/$pid/" . $ret . ".in";

        if (file_exists($infile)) {
          echo '<test_input name="' . $ret . '"><![CDATA[' . fixcdata(file_get_contents($infile)) . ']]></test_input>';
        }

        if (file_exists($outfile)) {
          echo '<test_output name="' . $ret . '"><![CDATA[' . fixcdata(file_get_contents($outfile)) . ']]></test_output>';
        }
        //break;
      } else if ($pinfo['extension'] != "in" && $pinfo['extension'] != "out") {
        echo '<test_file name="' . $pinfo["basename"] . '"><![CDATA[' . fixcdata(base64_encode(file_get_contents($fullpath))) . ']]></test_file>';
      }
    }
  }

  //closedir($pdir);
  return $ret;
}


class Solution
{
  var $language = "";
  var $source_code = "";
}

function getSolution($pid, $lang)
{
  $ret = new Solution();

  $language_name = $GLOBALS['language_name'];

  $sql = "SELECT `solution_id`,`language` FROM solution WHERE problem_id=? AND result=4 AND language=? LIMIT 1";
  //echo $sql;

  $result = pdo_query($sql, $pid, $lang);

  if ($result && $row = $result[0]) {
    $solution_id = $row[0];
    $ret->language = $language_name[$row[1]];
    $sql = "SELECT source FROM source_code WHERE solution_id=?";
    $result = pdo_query($sql, $solution_id);

    if ($row = $result[0]) {
      $ret->source_code = $row['source'];
    }
  }

  return $ret;
}

function fixurl($img_url)
{
  $img_url = html_entity_decode($img_url, ENT_QUOTES, "UTF-8");

  if (substr($img_url, 0, 4) != "http") {
    if (substr($img_url, 0, 1) == "/") {
      $ret = 'https://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER["SERVER_PORT"] . $img_url;
    } else {
      $path = dirname($_SERVER['PHP_SELF']);
      $ret = 'http://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER["SERVER_PORT"] . $path . "/../" . $img_url;
    }
  } else {
    $ret = $img_url;
  }

  return  $ret;
}

function image_base64_encode($img_url)
{
  $img_url = fixurl($img_url);

  if (substr($img_url, 0, 4) != "http")
    return false;

  $handle = @fopen($img_url, "rb");

  if ($handle) {
    $contents = stream_get_contents($handle);
    $encoded_img = base64_encode($contents);
    fclose($handle);
    return $encoded_img;
  } else
    return false;
}

function getImages($content)
{
  preg_match_all("<[iI][mM][gG][^<>]+[sS][rR][cC]=\"?([^ \"\>]+)/?>", $content, $images);
  return $images;
}

function fixImageURL(&$html, &$did)
{
  $images = getImages($html);
  $imgs = array_unique($images[1]);

  foreach ($imgs as $img) {
    $html = str_replace($img, fixurl($img), $html);
    //print_r($did);

    if (!in_array($img, $did)) {
      $base64 = image_base64_encode($img);
      if ($base64) {
        echo "<img><src><![CDATA[";
        echo fixurl($img);
        echo "]]></src><base64><![CDATA[";
        echo $base64;
        echo "]]></base64></img>";
      }
      array_push($did, $img);
    }
  }
}


if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']))) {
  $view_errors_js = "swal('$MSG_NOT_LOGINED','$MSG_Login','error').then((onConfirm)=>{window.location.href='loginpage.php'})";
  require("template/error.php");
  exit(0);
}


if (isset($_POST['do']) || isset($_GET['cid'])) {
  if (isset($_POST['in']) && strlen($_POST['in']) > 0) {
    require_once("../include/check_post_key.php");
    $in = $_POST['in'];
    $ins = explode(",", $in);
    $in = "";

    foreach ($ins as $pid) {
      $pid = intval($pid);

      if ($in)
        $in .= ",";

      $in .= $pid;
    }

    $sql = "SELECT * FROM problem WHERE problem_id IN($in)";
    $result = pdo_query($sql);

    $filename = "-$in";
  } else if (isset($_GET['cid'])) {
    require_once("../include/check_get_key.php");

    $cid = intval($_GET['cid']);
    $sql = "SELECT title FROM contest WHERE contest_id=?";
    $result = pdo_query($sql, $cid);

    $row = $result[0];
    $filename = '-' . $row['title'];

    $sql = "SELECT * FROM problem WHERE problem_id IN(SELECT problem_id FROM contest_problem WHERE contest_id=?)";
    $result = pdo_query($sql, $cid);
  } else {
    require_once("../include/check_post_key.php");

    $start = intval($_POST['start']);
    $end = intval($_POST['end']);

    $sql = "SELECT * FROM problem WHERE problem_id>=? AND problem_id<=? ORDER BY problem_id ";
    $result = pdo_query($sql, $start, $end);

    $filename = "-$start-$end";
  }

  //echo $sql;

  if (isset($_POST['submit']) && $_POST['submit'] == "Export")
    header('Content-Type:text/xml');
  else {
    header("content-type:application/file");
    header("content-disposition:attachment;filename=\"fps-" . $_SESSION[$OJ_NAME . '_' . 'user_id'] . $filename . ".xml\"");
  }
?>

  <!DOCTYPE fps PUBLIC "-//freeproblemset//An opensource XML standard for Algorithm Contest Problem Set//EN" "http://hustoj.com/fps.current.dtd">
  <fps version="1.3" url="https://github.com/zhblue/freeproblemset/">
    <generator name="EOJ" url="https://github.com/poormonitor/eoj/" />
    <?php foreach ($result as  $row) { ?>
      <item>
        <?php
        echo "<title><![CDATA[" . $row['title'] . "]]></title>";
        echo "<time_limit unit=\"s\"><![CDATA[" . $row['time_limit'] . "]]></time_limit>";
        echo "<memory_limit unit=\"mb\"><![CDATA[" . $row['memory_limit'] . "]]></memory_limit>";

        $did = array();
        fixImageURL($row['description'], $did);
        fixImageURL($row['input'], $did);
        fixImageURL($row['output'], $did);
        fixImageURL($row['hint'], $did);
        echo "<description><![CDATA[" . $row['description'] . "]]></description>";
        echo "<input><![CDATA[" . $row['input'] . "]]></input>";
        echo "<output><![CDATA[" . $row['output'] . "]]></output>";
        echo "<hint><![CDATA[" . $row['hint'] . "]]></hint>";

        echo "<sample_input><![CDATA[" . $row['sample_input'] . "]]></sample_input>";
        echo "<sample_output><![CDATA[" . $row['sample_output'] . "]]></sample_output>";
        printTestCases($row['problem_id'], $OJ_DATA);

        echo "<source><![CDATA[" . fixcdata($row['source']) . "]]></source>";
        echo "<allow><![CDATA[" . fixcdata($row['allow']) . "]]></allow>";
        echo "<block><![CDATA[" . fixcdata($row['block']) . "]]></block>";
        echo "<blank><![CDATA[" . fixcdata($row['blank']) . "]]></blank>";

        $pid = $row['problem_id'];
        for ($lang = 0; $lang < count($language_ext); $lang++) {
          $solution = getSolution($pid, $lang);

          if ($solution->language) {
            echo "<solution language=\"" . $solution->language . "\">" . htmlspecialchars($solution->source_code) . "</solution>";
          }

          $pta = array("prepend", "template", "append");

          foreach ($pta as $pta_file) {
            $append_file = "$OJ_DATA/$pid/$pta_file." . $language_ext[$lang];
            //echo "<filename value=\"$lang  $append_file $language_ext[$lang]\"/>";

            if (file_exists($append_file)) {
              echo "<" . $pta_file . " language=\"" . $language_name[$lang] . "\"><![CDATA[" . fixcdata(file_get_contents($append_file)) . "]]></" . $pta_file . ">";
            }
          }
        }
        ?>

        <?php
        if ($row['spj'] != 0) {
          $filec = "$OJ_DATA/" . $row['problem_id'] . "/spj.c";
          $filecc = "$OJ_DATA/" . $row['problem_id'] . "/spj.cc";
          $filesh = "$OJ_DATA/" . $row['problem_id'] . "/spj.sh";
          $filepy = "$OJ_DATA/" . $row['problem_id'] . "/spj.py";

          if (file_exists($filec)) {
            echo '<spj language="C"><![CDATA[' . fixcdata(file_get_contents($filec)) . ']]></spj>';
          } else if (file_exists($filecc)) {
            echo '<spj language="C++"><![CDATA[' . fixcdata(file_get_contents($filecc)) . ']]></spj>';
          } else if (file_exists($filesh)) {
            echo '<spj language="Shell"><![CDATA[' . fixcdata(file_get_contents($filesh)) . ']]></spj>';
          } else if (file_exists($filepy)) {
            echo '<spj language="Python"><![CDATA[' . fixcdata(file_get_contents($filepy)) . ']]></spj>';
          }
        }
        ?>
      </item>

    <?php } ?>
  </fps>
<?php } ?>