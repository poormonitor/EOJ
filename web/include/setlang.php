<?php
if (isset($OJ_LANG)) {
	require_once(dirname(__FILE__) . "/../lang/$OJ_LANG.php");
	if (file_exists(dirname(__FILE__) . "/../template/faqs.$OJ_LANG.php")) {
		$OJ_FAQ_LINK = dirname(__FILE__) . "/../template/faqs.$OJ_LANG.php";
	}
} else {
	require_once(dirname(__FILE__) . "/../lang/en.php");
}
