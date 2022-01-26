<?php
require_once("admin-header.php");
require_once("../include/my_func.inc.php");
function machine_rejudge($type, $score, $c_answer, $answers)
{
	foreach ($answers as $i) {
		$user_answer = explode("/", $i['answer']);
		$origin_score = array_map("intval", explode("/", $i['score']));
		$auto = auto_judge_quiz($user_answer, $c_answer, $score, $type, $origin_score);
		$total = array_sum($auto);
		$auto = join("/", $auto);
		$sql = "UPDATE `answer` SET `score`=?, `total`=? WHERE `aid`=?";
		pdo_query($sql, $auto, $total, $i['aid']);
	}
}
function human_rejudge($type, $answers)
{
	foreach ($answers as $i) {
		if ($i['judged'] = 1) {
			$n_score = array_map("intval", explode("/", $i['score']));
			for ($j = 0; $j < count($type); $j++) {
				if ($type[$j] == 3) {
					$n_score[$j] = 0;
				}
			}
			$total = array_sum($n_score);
			$n_score = join("/", $n_score);
			$sql = "UPDATE `answer` SET `score`=?, `total`=?, `judged`=0,
		 			`judgetime` = NULL WHERE `aid`=?";
			pdo_query($sql, $n_score, $total, $i['aid']);
		}
	}
}
if (isset($_POST['do']) && isset($_POST['qid'])) {
	$qid = intval($_POST['qid']);
	$sql = "SELECT * FROM `quiz` WHERE `quiz_id`=?";
	$quiz = pdo_query($sql, $qid)[0];
	$type = array_map("intval", explode("/", $quiz['type']));
	$score = array_map("intval", explode("/", $quiz['score']));
	$c_answer = explode("/", $quiz['correct_answer']);
	$sql = "SELECT * FROM `answer` WHERE `quiz_id` = ?";
	$answers = pdo_query($sql, $qid);
	if (isset($_POST['auto'])) {
		machine_rejudge($type, $score, $c_answer, $answers);
	} elseif (isset($_POST['human'])) {
		human_rejudge($type, $answers);
	} elseif (isset($_POST['all'])) {
		machine_rejudge($type, $score, $c_answer, $answers);
		human_rejudge($type, $answers);
	}
	header("Location:quiz_list.php");
	exit(0);
}
?>
<?php require_once("../include/set_post_key.php"); ?>
<hr>
<div class="container">
	<h3 class='center'><b><?php echo $MSG_QUIZ . "-" . $MSG_REJUDGE ?></b></h3>
	<ol>
		<br />
		<div class='center form-group'>
			<form action='quiz_rejudge.php' method=post>
				<label class='control-label'>
					<li><?php echo $MSG_TOTAL ?></li>
				</label>
				<div class='form-inline'>
					<input type=input class='form-control' name='qid' style='%' placeholder="1001" value='<?php if (isset($_GET['qid'])) echo $_GET['qid'] ?>'>
					<input type='hidden' name='do' value='do'>
					<input type='hidden' name='all' value='all'>
					<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
					<input type=submit class='form-control btn btn-default' value='<?php echo $MSG_SUBMIT; ?>'>
				</div>
			</form>
			<form action='quiz_rejudge.php' method=post>
				<label class='control-label'>
					<li><?php echo $MSG_HUMAN_JUDGE ?></li>
				</label>
				<div class='form-inline'>
					<input type=input class='form-control' name='qid' style='%' placeholder="1001" value='<?php if (isset($_GET['qid'])) echo $_GET['qid'] ?>'>
					<input type='hidden' name='do' value='do'>
					<input type='hidden' name='human' value='human'>
					<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
					<input type=submit class='form-control btn btn-default' value='<?php echo $MSG_SUBMIT; ?>'>
				</div>
			</form>
			<form action='quiz_rejudge.php' method=post>
				<label class='control-label'>
					<li><?php echo $MSG_MACHINE_JUDGE ?></li>
				</label>
				<div class='form-inline'>
					<input type=input class='form-control' name='qid' style='%' placeholder="1001" value='<?php if (isset($_GET['qid'])) echo $_GET['qid'] ?>'>
					<input type='hidden' name='do' value='do'>
					<input type='hidden' name='auto' value='auto'>
					<input type=hidden name="postkey" value="<?php echo $_SESSION[$OJ_NAME . '_' . 'postkey'] ?>">
					<input type=submit class='form-control btn btn-default' value='<?php echo $MSG_SUBMIT; ?>'>
				</div>
			</form>
		</div>
</div>