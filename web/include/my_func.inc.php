<?php
function send_udp_message($host, $port, $message)
{
  $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
  @socket_connect($socket, $host, $port);

  $num = 0;
  $length = strlen($message);
  do {
    $buffer = substr($message, $num);
    $ret = @socket_write($socket, $buffer);
    $num += $ret;
  } while ($num < $length);

  socket_close($socket);

  // UDP ............, ............
  return true;
}
function crypto_rand_secure($min, $max)
{
  $range = $max - $min;
  if ($range < 0) return $min; // not so random...
  $log = log($range, 2);
  $bytes = (int) ($log / 8) + 1; // length in bytes
  $bits = (int) $log + 1; // length in bits
  $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
  do {
    if (function_exists("openssl_random_pseudo_bytes")) {
      $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
    } else {
      $rnd = hexdec(bin2hex(rand() . "_" . rand()));
    }
    $rnd = $rnd & $filter; // discard irrelevant bits
  } while ($rnd >= $range);
  return $min + $rnd;
}

function getToken($length = 32)
{
  $token = "";
  $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
  $codeAlphabet .= "0123456789";
  for ($i = 0; $i < $length; $i++) {
    $token .= $codeAlphabet[crypto_rand_secure(0, strlen($codeAlphabet))];
  }
  return $token;
}

function pwGen($password, $md5ed = False)
{
  if (!$md5ed) $password = md5($password);
  $salt = sha1(rand());
  $salt = substr($salt, 0, 4);
  $hash = base64_encode(sha1($password . $salt, true) . $salt);
  return $hash;
}

function pwCheck($password, $saved)
{
  if (isOldPW($saved)) {
    if (!isOldPW($password)) $mpw = md5($password);
    else $mpw = $password;
    if ($mpw == $saved) return True;
    else return False;
  }
  $svd = base64_decode($saved);
  $salt = substr($svd, 20);
  if (!isOldPW($password)) $password = md5($password);
  $hash = base64_encode(sha1(($password) . $salt, true) . $salt);
  if (strcmp($hash, $saved) == 0) return True;
  else return False;
}

function isOldPW($password)
{
  if (strlen($password) != 32) return false;
  for ($i = strlen($password) - 1; $i >= 0; $i--) {
    $c = $password[$i];
    if ('0' <= $c && $c <= '9') continue;
    if ('a' <= $c && $c <= 'f') continue;
    if ('A' <= $c && $c <= 'F') continue;
    return False;
  }
  return True;
}

function is_valid_user_name($user_name)
{
  $len = strlen($user_name);
  for ($i = 0; $i < $len; $i++) {
    if (
      ($user_name[$i] >= 'a' && $user_name[$i] <= 'z') ||
      ($user_name[$i] >= 'A' && $user_name[$i] <= 'Z') ||
      ($user_name[$i] >= '0' && $user_name[$i] <= '9') ||
      $user_name[$i] == '_' ||
      ($i == 0 && $user_name[$i] == '*')
    );
    else return false;
  }
  return true;
}

function sec2str($sec)
{
  return sprintf("%02d:%02d:%02d", $sec / 3600, $sec % 3600 / 60, $sec % 60);
}
function is_running($cid)
{
  $now = strftime("%Y-%m-%d %H:%M", time());
  $sql = "SELECT count(*) FROM `contest` WHERE `contest_id`=? AND `end_time`>?";
  $result = pdo_query($sql, $cid, $now);
  $row = $result[0];
  $cnt = intval($row[0]);
  return $cnt > 0;
}
function check_ac($cid, $pid, $noip)
{
  //require_once("./include/db_info.inc.php");
  global $OJ_NAME;
  if ($noip) {
    $sql = "SELECT count(*) FROM `solution` WHERE `contest_id`=? AND `num`=? and `problem_id`!=0  AND `user_id`=?";
    $result = pdo_query($sql, $cid, $pid, $_SESSION[$OJ_NAME . '_' . 'user_id']);
    $row = $result[0];
    $sub = intval($row[0]);
    if ($sub > 0) return "<div class='label label-default'>?</div>";
    else return "";
  }
  $sql = "SELECT count(*) FROM `solution` WHERE `contest_id`=? AND `num`=? AND `result`='4' AND `user_id`=?";
  $result = pdo_query($sql, $cid, $pid, $_SESSION[$OJ_NAME . '_' . 'user_id']);
  $row = $result[0];
  $ac = intval($row[0]);
  if ($ac > 0) return "<div class='label label-success'>Y</div>";

  $sql = "SELECT count(*) FROM `solution` WHERE `contest_id`=? AND `num`=? AND `result`!=4 and `problem_id`!=0  AND `user_id`=?";
  $result = pdo_query($sql, $cid, $pid, $_SESSION[$OJ_NAME . '_' . 'user_id']);
  $row = $result[0];
  $sub = intval($row[0]);

  if ($sub > 0) return "<div class='label label-danger'>N</div>";
  else return "";
}



function RemoveXSS($val)
{
  // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
  // this prevents some character re-spacing such as <java\0script>
  // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
  $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

  // straight replacements, the user should never need these since they're normal characters
  // this prevents like <IMG SRC=@avascript:alert('XSS')>
  $search = 'abcdefghijklmnopqrstuvwxyz';
  $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $search .= '1234567890!@#$%^&*()';
  $search .= '~`";:?+/={}[]-_|\'\\';
  for ($i = 0; $i < strlen($search); $i++) {
    // ;? matches the ;, which is optional
    // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

    // @ @ search for the hex values
    $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
    // @ @ 0{0,7} matches '0' zero to seven times
    $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
  }

  // now the only remaining whitespace attacks are \t, \n, and \r   //, 'style'
  $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'base');
  $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
  $ra = array_merge($ra1, $ra2);

  $found = true; // keep replacing as long as the previous round replaced something
  while ($found == true) {
    $val_before = $val;
    for ($i = 0; $i < sizeof($ra); $i++) {
      $pattern = '/';
      for ($j = 0; $j < strlen($ra[$i]); $j++) {
        if ($j > 0) {
          $pattern .= '(';
          $pattern .= '(&#[xX]0{0,8}([9ab]);)';
          $pattern .= '|';
          $pattern .= '|(&#0{0,8}([9|10|13]);)';
          $pattern .= ')*';
        }
        $pattern .= $ra[$i][$j];
      }
      $pattern .= '/i';
      $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
      $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
      if ($val_before == $val) {
        // no replacements were made, so exit the loop
        $found = false;
      }
    }
  }
  return $val;
}

function str_replace_limit($search, $replace, $subject, $limit = -1)
{
  if (is_array($search)) {
    foreach ($search as $k => $v) {
      $search[$k] = '`' . preg_quote($search[$k], '`') . '`';
    }
  } else {
    $search = '`' . preg_quote($search, '`') . '`';
  }
  return preg_replace($search, $replace, $subject, $limit);
}

function auto_judge_quiz(
  array $user_answer,
  array $correct_answer,
  array $score,
  array $type,
  array $origin = array()
): array {
  $result = array();
  for ($i = 0; $i < count($type); $i++) {
    $n_type = intval($type[$i]);
    $c_score = intval($score[$i]);
    $c_answer = $correct_answer[$i];
    $u_answer = $user_answer[$i];
    switch ($n_type) {
      case 2:
      case 0:
        if ($c_answer == $u_answer) {
          $result[$i] = $c_score;
        } else {
          $result[$i] = 0;
        }
        break;
      case 1:
        $flag = false;
        foreach (str_split($u_answer) as $k) {
          if (strpos($c_answer, $k) === false) {
            $flag = true;
          }
        }
        if ($flag || !strlen($u_answer)) {
          $result[$i] = 0;
        } elseif ($u_answer == $c_answer) {
          $result[$i] = $c_score;
        } else {
          $result[$i] = round($c_score / 2);
        }
        break;
      case 3:
        if (count($origin))
          $result[$i] = intval($origin[$i]);
        else
          $result[$i] = 0;
        break;
    }
  }
  return $result;
}

class Answer
{
  var array $choice;
  var array $score;
  var string $user;
  var string $nick;
  var int $total;
  function __construct(array $choice, array $score, string $user, string $nick, int $total)
  {
    $this->choice = $choice;
    $this->score = array_map("intval", $score);
    $this->user = $user;
    $this->nick = $nick;
    $this->total = $total;
  }
}

class Analysis
{
  var int $num;
  var array $type;
  var array $score;
  var array $f_score;
  var array $sum_score;
  var array $choices;
  var int $answered = 0;

  function __construct(array $type, array $score)
  {
    $this->num = count($type);
    $this->type = $type;
    $this->score = $score;
    $this->sum_score = array();
    for ($i = 0; $i < $this->num; $i++) {
      $this->f_score[$i] = array();
      $this->choices[$i] = array();
    }
  }
  function add_answer(Answer $answer)
  {
    for ($i = 0; $i < $this->num; $i++) {
      if (isset($this->choices[$i][$answer->choice[$i]]))
        $this->choices[$i][$answer->choice[$i]] += 1;
      else
        $this->choices[$i][$answer->choice[$i]] = 1;
      if (isset($this->f_score[$i][$answer->score[$i]])) {
        $this->f_score[$i][$answer->score[$i]] += 1;
      } else {
        $this->f_score[$i][$answer->score[$i]] = 1;
      }
    }
    $this->sum_score[] = $answer->total;
    $this->answered += 1;
  }
  function get_problem_average(int $problem_id): float
  {
    $sum = 0;
    $num = 0;
    foreach ($this->f_score[$problem_id] as $key => $value) {
      $sum += $key * $value;
      $num += $value;
    }
    return $sum / $num;
  }
  function get_average()
  {
    return array_sum($this->sum_score) / $this->answered;
  }
  function get_answered_num(): int
  {
    return $this->answered;
  }
  function get_choice_num(int $problem_id): array
  {
    return $this->choices[$problem_id];
  }
  function get_score_num(int $problem_id): array
  {
    return $this->f_score[$problem_id];
  }
  function get_choice_rate(int $problem_id, string $ans): float
  {
    $correct = $this->choices[$problem_id][$ans];
    return $correct / $this->answered;
  }
  function get_diff()
  {
    if ($this->answered * 0.27 < 1) {
      return 1;
    }
    rsort($this->sum_score);
    $high = 0;
    $high_num = 0;
    for ($i = 0; $i < $this->answered * 0.27; $i++) {
      $high += $this->sum_score[$i];
      $high_num += 1;
    }
    $high_average = $high / $high_num;
    $low = 0;
    $low_num = 0;
    for ($i = $this->answered - 1; $i > $this->answered * 0.73 - 1; $i--) {
      $low += $this->sum_score[$i];
      $low_num += 1;
    }
    $low_average = $low / $low_num;
    return ($high_average - $low_average) / array_sum($this->score);
  }
}

function isAllEmpty(array $haystack): bool
{
  foreach ($haystack as $i) {
    if (!empty($i))
      return false;
  }
  return true;
}
