<?php
$private = pdo_query("SELECT private FROM contest WHERE contest_id=?", $cid)[0][0];
if ($private === "1" and basename($_SERVER['PHP_SELF']) != "contestrank2.php") {
  $sql = "SELECT user_id,nick,solution.result,solution.num,solution.in_date,solution.pass_rate 
          FROM solution where solution.contest_id=$cid and num>=0 and problem_id>0
          ORDER BY user_id, solution_id  UNION
          SELECT users.user_id,users.nick,solution.result,solution.num,solution.in_date,solution.pass_rate 
          FROM privilege_group LEFT JOIN users ON users.gid=privilege_group.gid
          LEFT JOIN solution on solution.user_id = users.user_id 
          AND solution.contest_id=$cid and num>=0 and problem_id>0
          WHERE privilege_group.rightstr='c$cid' ORDER BY user_id, solution_id";
} else {
  $sql = "SELECT user_id,nick,solution.result,solution.num,solution.in_date,solution.pass_rate 
          FROM solution where solution.contest_id=$cid and num>=0 and problem_id>0 
          ORDER BY user_id, solution_id";
}
$result = mysql_query_cache($sql);
if ($result) $rows_cnt = count($result);
else $rows_cnt = 0;
