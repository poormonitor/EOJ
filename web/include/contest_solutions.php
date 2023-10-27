<?php
$private = pdo_query("SELECT private FROM contest WHERE contest_id=?", $cid)[0][0];
if ($private === 1 and basename($_SERVER['PHP_SELF']) != "contestrank2.php") {
  $sql = "SELECT users.user_id, users.nick, solution.result, solution.num, solution.in_date, solution.pass_rate
          FROM users LEFT JOIN (SELECT * from solution WHERE solution.contest_id=$cid
          AND solution.num>=0 AND solution.problem_id>0) solution
          ON solution.user_id = users.user_id
          WHERE users.user_id IN (SELECT user_id FROM solution WHERE contest_id=$cid) 
          OR users.gid IN (SELECT gid FROM privilege_group WHERE rightstr='c$cid')
          ORDER BY users.user_id, solution.solution_id";
} else {
  $sql = "SELECT user_id,nick,solution.result,solution.num,solution.in_date,solution.pass_rate 
          FROM solution where solution.contest_id=$cid and num>=0 and problem_id>0 
          ORDER BY user_id, solution_id";
}
$result = mysql_query_cache($sql);
if ($result) $rows_cnt = count($result);
else $rows_cnt = 0;
