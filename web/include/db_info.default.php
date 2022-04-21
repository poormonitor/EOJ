<?php
$startTime = microtime(true);

ini_set("display_errors", "Off");  //set this to "On" for debugging  ,especially when no reason blank shows up.
error_reporting(E_ALL);
ini_set('date.timezone','Asia/Shanghai');
date_default_timezone_set("Asia/Shanghai");
//header('X-Frame-Options:SAMEORIGIN');
//for people using hoj out of China , be careful of the last two line of this file !
@session_start();
// connect db 
static 	$DB_HOST = "localhost";  //数据库服务器ip或域名
static 	$DB_NAME = "jol";   //数据库名
static 	$DB_USER = "jol";  //数据库账户
static 	$DB_PASS = "";  //数据库密码

static 	$OJ_NAME = "HOJ";  //左上角显示的系统名称
static 	$OJ_HOME = "./";    //主页目录
static 	$OJ_ADMIN = "root@localhost";  //管理员email
static 	$OJ_DATA = "/home/judge/data";  //测试数据目录
static  $OJ_ONLINE = true;  //是否记录在线情况
static  $OJ_LANG = "cn";  //默认语言
static  $OJ_SIM = true;  //显示相似度
static  $OJ_DICT = false; //显示在线翻译
static  $OJ_LANGMASK = 2097087; //TIOBE index top 10, calculator : https://pigeon-developer.github.io/hustoj-langmask/
static  $OJ_ACE_EDITOR = true;
static  $OJ_AUTO_SHARE = false; //true: One can view all AC submit if he/she has ACed it onece.
static  $OJ_CSS = "white.css";
static  $OJ_VCODE = false;  //验证码
static  $OJ_APPENDCODE = false;  // 代码预定模板
static  $OJ_CE_PENALTY = false;  // 编译错误是否罚时
static  $OJ_PRINTER = false;  //启用打印服务
static  $OJ_MAIL = false; //内邮
static  $OJ_MARK = "mark"; // "mark" for right "percent" for WA
static  $OJ_MEMCACHE = false;  //使用内存缓存
static  $OJ_MEMSERVER = "127.0.0.1";
static  $OJ_MEMPORT = 11211;
static  $OJ_UDP = true;   //使用UDP通知
static  $OJ_UDPSERVER = "127.0.0.1";
static  $OJ_UDPPORT = 1536;
static  $OJ_REDIS = false;   //使用REDIS队列
static  $OJ_REDISSERVER = "127.0.0.1";
static  $OJ_REDISPORT = 6379;
static  $OJ_REDISQNAME = "hoj";
static  $OJ_CDN_URL = ""; // https://cdn.jsdelivr.net/gh/poormonitor/hoj/web/
static  $OJ_BLOCK_START_TIME = 0; //开始禁用系统
static  $OJ_BLOCK_END_TIME = 0; //启用系统
static  $OJ_LOGIN_MOD = "hoj";
static  $OJ_REGISTER = false; //允许注册新用户
static  $OJ_REG_NEED_CONFIRM = false; //新注册用户需要审核
static  $OJ_NEED_LOGIN = false; //需要登录才能访问
static  $OJ_LONG_LOGIN = false; //启用长时间登录信息保留
static  $OJ_KEEP_TIME = "30";  //登录Cookie有效时间(单位:天(day),仅在上一行为true时生效)
static  $OJ_RANK_LOCK_PERCENT = 0; //比赛封榜时间比例
static  $OJ_SHOW_DIFF = true; //是否显示WA的对比说明
static  $OJ_DOWNLOAD = false; //是否允许下载WA的测试数据
static  $OJ_TEST_RUN = true; //提交界面是否允许测试运行
static  $OJ_ENCODE_SUBMIT = false; //是否启用base64编码提交的功能，用来回避WAF防火墙误拦截。
static  $OJ_OI_1_SOLUTION_ONLY = false; //比赛是否采用noip中的仅保留最后一次提交的规则。true则在新提交发生时，将本场比赛该题老的提交删除。
static  $OJ_OI_MODE = false; //是否开启OI比赛模式，禁用排名、状态、统计、用户信息、内邮、论坛等。
static  $OJ_SHOW_METAL = true; //榜单上是否按比例显示奖牌
static  $OJ_RANK_LOCK_DELAY = 3600; //赛后封榜持续时间，单位秒。根据实际情况调整，在闭幕式颁奖结束后设为0即可立即解封。
static  $OJ_BENCHMARK_MODE = false; //此选项将影响代码提交，不再有提交间隔限制，提交后会返回solution id
static  $OJ_CONTEST_RANK_FIX_HEADER = false; //比赛排名水平滚动时固定名单
static  $OJ_NOIP_KEYWORD = "noip";  // 标题包含此关键词，激活noip模式，赛中不显示结果，仅保留最后一次提交。
static  $OJ_SPONSOR = '';
static  $OJ_SPONSOR_URL = '';
static  $OJ_BEIAN = '';  // 如果有备案号，填写备案号
static  $OJ_MPS_BEIAN = '';  // 如果有备案号，填写备案号
static  $OJ_MPS_BEIAN_URL = '';  // 如果有备案号，填写备案号
static  $OJ_PYTHON_VER = 'Python';
static 	$OJ_PY_BIN = "/usr/bin/python3";
static  $OJ_GOOGLE_ANALYTICS = ""; // G-??
static  $OJ_FLOAT_NOTICE = array("", "", true); // 0 -> 图片链接, 1 -> 点击链接, 2 -> (boolean) 是否浮动
//static  $OJ_EXAM_CONTEST_ID=1000; // 启用考试状态，填写考试比赛ID
//static  $OJ_ON_SITE_CONTEST_ID=1000; //启用现场赛状态，填写现场赛比赛ID

/* share code */
static  $OJ_SHARE_CODE = false; // 代码分享功能
/* recent contest */
static  $OJ_RECENT_CONTEST = true; // "http://algcontest.rainng.com/contests.json" ; // 名校联赛

//$OJ_ON_SITE_TEAM_TOTAL用于根据比例的计算奖牌的队伍总数
//CCPC比赛的一种做法是比赛结束后导出终榜看AC至少1题的不打星的队伍数，现场修改此值即可正确计算奖牌
//0表示根据榜单上的出现的队伍总数计算(包含了AC0题的队伍和打星队伍)
static $OJ_ON_SITE_TEAM_TOTAL = 0;

$time = date("H", time());
if (($OJ_BLOCK_START_TIME < $OJ_BLOCK_END_TIME && $time >= $OJ_BLOCK_START_TIME && $time < $OJ_BLOCK_END_TIME - 1) ||
	($OJ_BLOCK_START_TIME > $OJ_BLOCK_END_TIME && ($time >= $OJ_BLOCK_START_TIME || $time < $OJ_BLOCK_END_TIME - 1))
) {
	header("Cache-Control: no-cache, must-revalidate");
	require(dirname(__FILE__) . "/../index.html");
	exit(0);
}

require_once(dirname(__FILE__) . "/pdo.php");
require_once(dirname(__FILE__) . "/memcache.php");

// use db
//pdo_query("set names utf8");	

static  $smtpserver = ""; //SMTP服务器
static  $smtpserverport = 465; //SMTP服务器端口
static  $smtpusermail = ""; //SMTP服务器的用户邮箱
static  $smtpuser = ""; //SMTP服务器的用户帐号
static  $smtppass = ""; //SMTP服务器的用户密码

//sychronize php and mysql server with timezone settings, dafault setting for China
//if you are not from China, comment out these two lines or modify them.
pdo_query("SET time_zone ='+8:00'");

/* log */
$OJ_LOG_FILE = "/var/log/hoj/{$OJ_NAME}.log";
static  $OJ_LOG_ENABLED = false;
static  $OJ_LOG_DATETIME_FORMAT = "Y-m-d H:i:s";
static  $OJ_LOG_PID_ENABLED = false;
static  $OJ_LOG_USER_ENABLED = false;
static  $OJ_LOG_URL_ENABLED = false;
static  $OJ_LOG_URL_HOST_ENABLED = false;
static  $OJ_LOG_URL_PARAM_ENABLED = false;
static  $OJ_LOG_TRACE_ENABLED = false;

require_once(dirname(__FILE__) . "/logger.php");
if (isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	$logger = new Logger(
		$_SESSION[$OJ_NAME . '_' . 'user_id'],
		$OJ_LOG_FILE,
		$OJ_LOG_DATETIME_FORMAT,
		$OJ_LOG_ENABLED,
		$OJ_LOG_PID_ENABLED,
		$OJ_LOG_USER_ENABLED,
		$OJ_LOG_URL_ENABLED,
		$OJ_LOG_URL_HOST_ENABLED,
		$OJ_LOG_URL_PARAM_ENABLED,
		$OJ_LOG_TRACE_ENABLED
	);
} else {
	$logger = new Logger(
		"guest",
		$OJ_LOG_FILE,
		$OJ_LOG_DATETIME_FORMAT,
		$OJ_LOG_ENABLED,
		$OJ_LOG_PID_ENABLED,
		$OJ_LOG_USER_ENABLED,
		$OJ_LOG_URL_ENABLED,
		$OJ_LOG_URL_HOST_ENABLED,
		$OJ_LOG_URL_PARAM_ENABLED,
		$OJ_LOG_TRACE_ENABLED
	);
}

$logger->info();
