<?php
if ($ok == true) {
    $brush = strtolower($language_toolkit[$slanguage]);
    ob_start();
    echo "\n'''\n";
    echo "=== Submission Info ===\n";
    echo "\tProblem: $sproblem_id\n\tUser: $suser_id\n";
    echo "\tLanguage: " . $language_name[$slanguage] . "\n\tResult: " . $judge_result[$sresult] . "\n";
    if ($sresult == 4) {
        echo "\tTime:" . $stime . " ms\n";
        echo "\tMemory:" . $smemory . " kb\n";
    }
    echo "'''";
    $auth = ob_get_contents();
    ob_end_clean();
    echo htmlentities(str_replace("\n\r", "\n", $view_source), ENT_QUOTES, "utf-8") . "\n" . $auth . "</pre>";
} else {
    echo $MSG_WARNING_ACCESS_DENIED;
}
