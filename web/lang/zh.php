<?php
$MSG_TRUE_FALSE = array(true => '是', false => '否');
$MSG_TRUE = "是";
$MSG_FALSE = "否";
$MSG_GROUP = "组";
$MSG_FROM = "从";
$MSG_TO = "到";
$MSG_COPY = "复制";
$MSG_ENABLE = "启用";
$MSG_DISABLE = "禁用";
$MSG_ENABLED = "可用";
$MSG_DISABLED = "禁用";
$MSG_EMPTY = "无";
$MSG_UPLOAD = "上传";
$MSG_NOT_EXISTED = "您请求的项目不存在";
$MSG_SUBMISSIONS = "次提交";
$MSG_NO_SUBMISSION = "无提交";
$MSG_PRIVILEGE_WARNING = "您没有权限访问此页面";
$MSG_ERROR = "错误";
$MSG_SUCCESS = "成功";
$MSG_UP_WRONG = "用户名或密码错误";
$MSG_PARAMS_ERROR = "您填写的某些项目不正确";
$MSG_PARAMS_TOO_FEW = "您填写的项目太少";
$MSG_NICK_TOO_LONG = "用户名太长";
$MSG_SCHOOL_TOO_LONG = "学校名太长";
$MSG_DOWNLOAD_ALL_AC = "下载所有通过代码";

$MSG_CLIPBOARD = "剪贴板";
$MSG_NOT_LOGINED = "您还没有登录";
$MSG_DELETE = "删除";
//index.php
$MSG_RECENT_SUBMISSION = "最近提交";
$MSG_TOTAL = "全部提交";
$MSG_ACCEPTED = "正确提交";
//oj-header.php
$MSG_FAQ = "常见问答";
$MSG_BBS = "讨论版";
$MSG_HOME = "主页";
$MSG_PROBLEMS = "问题";
$MSG_STATUS = "状态";
$MSG_RANKLIST = "排名";
$MSG_CONTEST = "作业";
$MSG_RECENT_CONTEST = "名校联赛";
$MSG_LOGOUT = "注销";
$MSG_LOGIN = "登录";
$MSG_LOST_PASSWORD = "忘记密码";
$MSG_REGISTER = "注册";
$MSG_ADMIN = "管理";
$MSG_SYSTEM = "系统";
$MSG_STANDING = "名次";
$MSG_STATISTICS = "统计";
$MSG_USERINFO = "用户信息";
$MSG_IP_LOCATION = "IP属地";
$MSG_MAIL = "短消息";
$MSG_POLICY = "使用许可 & 隐私协议";
$MSG_AGREE_POLICY = "登录即代表您同意<a href='policy.html' id='footer'> $MSG_POLICY </a>";
$MSG_PASSWORD_RESET = "密码重置";
//status.php
$MSG_Pending = "等待";
$MSG_Pending_Rejudging = "等待重判";
$MSG_Compiling = "编译中";
$MSG_Running_Judging = "运行并评判";
$MSG_Accepted = "正确";
$MSG_Presentation_Error = "格式错误";
$MSG_Wrong_Answer = "答案错误";
$MSG_Time_Limit_Exceed = "时间超限";
$MSG_Memory_Limit_Exceed = "内存超限";
$MSG_Output_Limit_Exceed = "输出超限";
$MSG_Runtime_Error = "运行错误";
$MSG_Compile_Error = "编译错误";
$MSG_Runtime_Click = "运行错误(点击看详细)";
$MSG_Compile_Click = "编译错误(点击看详细)";
$MSG_Compile_OK = "编译成功";
$MSG_Click_Detail = "点击看详细";
$MSG_Manual = "人工判题";
$MSG_OK = "确定";
$MSG_Explain = "输入判定原因与提示";

//fool's day
if (date('m') == 4 && date('d') == 1 && rand(0, 100) < 10) {
	$MSG_Accepted = "人品问题-愚人节快乐";
	$MSG_Presentation_Error = "人品问题-愚人节快乐";
	$MSG_Wrong_Answer = "人品问题-愚人节快乐";
	$MSG_Time_Limit_Exceed = "人品问题-愚人节快乐";
	$MSG_Memory_Limit_Exceed = "人品问题-愚人节快乐";
	$MSG_Output_Limit_Exceed = "人品问题-愚人节快乐";
	$MSG_Runtime_Error = "人品问题-愚人节快乐";
	$MSG_Compile_Error = "人品问题-愚人节快乐";
	$MSG_Compile_OK = "人品问题-愚人节快乐";
}

$MSG_RUNID = "提交编号";
$MSG_USER = "用户";
$MSG_PROBLEM = "问题";
$MSG_RESULT = "结果";
$MSG_MEMORY = "内存";
$MSG_TIME = "耗时";
$MSG_LANG = "语言";
$MSG_CODE_LENGTH = "代码长度";
$MSG_SUBMIT_TIME = "提交时间";

//problemstatistics.php
$MSG_PD = "等待";
$MSG_PR = "等待重判";
$MSG_CI = "编译中";
$MSG_RJ = "运行并评判";
$MSG_AC = "正确";
$MSG_PE = "格式错误";
$MSG_WA = "答案错误";
$MSG_TLE = "时间超限";
$MSG_MLE = "内存超限";
$MSG_OLE = "输出超限";
$MSG_RE = "运行错误";
$MSG_CE = "编译错误";
$MSG_CO = "编译成功";
$MSG_TR = "测试运行";
$MSG_RESET = "重置";

$MSG_SIM_POS = "抄袭者";
$MSG_SIM_PAS = "被抄袭者";
$MSG_SIMILARITY = "相似度";

//problemset.php
$MSG_SEARCH = "查找";
$MSG_PROBLEM_ID = "题目编号";
$MSG_TITLE = "标题";
$MSG_SOURCE = "来源/分类";
$MSG_SUBMIT_NUM = "提交量";
$MSG_SUBMIT = "提交";

//submit.php
$MSG_NO_SUCH_CONTEST = '您请求的作业不存在。这可能是因为作业本身不存在或您尚未被邀请';
$MSG_TEST_RUN = "测试运行";
$MSG_VCODE_WRONG = "验证码错误";
$MSG_LINK_ERROR = "在哪里可以找到此链接？ 没有这个问题。";
$MSG_PROBLEM_RESERVED = "问题已禁用。";
$MSG_NOT_IN_CONTEST = "您不能立即提交，因为您没有被作业邀请或作业没有进行！";
$MSG_NOT_INVITED = "您不被邀请！";
$MSG_NO_PROBLEM = "没有这样的问题！";
$MSG_NO_PLS = "使用未知的编程语言！";
$MSG_TOO_SHORT = "代码太短！";
$MSG_FORMAT_ERROR = "您的代码不符合格式！";
$MSG_TOO_LONG = "代码太长！";
$MSG_BREAK_TIME = "您不应在10秒钟内提交超过1次的申请";
$MSG_COPY_CURRENT = "复制当前";
$MSG_WRITE_DIRECTLY = "直接填写";

//ranklist.php
$MSG_Number = "名次";
$MSG_NICK = "姓名";
$MSG_SOVLED = "解决";
$MSG_RATIO = "比率";
$MSG_DAY = "日排行";
$MSG_WEEK = "周排行";
$MSG_MONTH = "月排行";
$MSG_YEAR = "年排行";
$MSG_RANK_LOCKED = "排名已锁定";
//registerpage.php
$MSG_USER_ID = "用户名";
$MSG_PASSWORD = "密码";
$MSG_REPEAT_PASSWORD = "重复密码";
$MSG_SCHOOL = "班级";
$MSG_EMAIL = "电子邮件";
$MSG_REG_INFO = "设置注册信息";
$MSG_VCODE = "验证码";

$MSG_PASSWORD_RESET = "密码重置激活";
$MSG_PASSWORD_RESET_HINT = "%s:\n您好！\n您在 %s 选择了找回密码服务,为了验证您的身份,请将下面字串输入口令重置页面以确认身份:\n%s\n请注意，这则密码将会在重置成功后成为您的临时密码。\n\n";

//problem.php
$MSG_NO_SUCH_PROBLEM = "当前题目不可用。它可能被用于未来的作业、过去的私有作业，或者管理员由于尚未测试通过等其他原因暂时停止了该题目用于练习。";
$MSG_Description = "题目描述";
$MSG_Input = "输入";
$MSG_Output = "输出";
$MSG_Sample_Input = "样例输入";
$MSG_Sample_Output = "样例输出";
$MSG_Test_Input = "测试输入";
$MSG_Test_Output = "测试输出";
$MSG_SPJ = "特殊裁判";
$MSG_HINT = "提示";
$MSG_Source = "来源";
$MSG_ALLOW_KEYWORD = "必须关键字";
$MSG_BLOCK_KEYWORD = "禁用关键字";
$MSG_AB_KEYWORD = "必须/禁用关键字";
$MSG_Time_Limit = "时间限制";
$MSG_Memory_Limit = "内存限制";
$MSG_EDIT = "编辑";
$MSG_TESTDATA = "测试数据";

//quiz.php
$MSG_QUIZ = "测试";
$MSG_QUIZ_ID = "测试编号";
$MSG_QUIZ_TITLE = "测试标题";
$MSG_QUIZ_NAME = "测试名称";
$MSG_QUIZ_STATUS = "测试状态";
$MSG_QUIZ_OPEN = "开放";
$MSG_QUIZ_CREATOR = "创建者";
$MSG_QUIZ_ANS = "答题";
$MSG_QUIZ_SCORE = "得分";
$MSG_QUIZ_PROBLEM = "题目";
$MSG_QUIZ_PROBLEM_INFORMATION = "题目信息";
$MSG_QUIZ_ANSWER = "答案";
$MSG_YOUR_ANSWER = "您的答案";
$MSG_CORRECT_ANSWER = "参考答案";
$MSG_SCORE = "分值";
$MSG_SCORE_SUM = "总分";
$MSG_PLEASE_ANSWER_ALL_QUESTIONS = "请检查所有问题是否都回答";
$MSG_NO_SUCH_QUIZ = "您请求的测试不存在。这可能是因为测试本身不存在或您尚未被邀请";
$MSG_ALREADY_SUBMIT = "您已经提交过了，不能再次提交。";
$MSG_TYPE = "类型";
$MSG_QUIZ_TYPE = array("单选", "多选", "填空（机器阅卷）", "简答（人工阅卷）");
$MSG_INPUT_NUMBER = "请输入试题数量";
$MSG_INQUERY_NUMBER = "试题数量";
$MSG_NOT_FINISHED = "该用户尚未完成";
$MSG_NO_NEED_DESCRIPTION = "不需要题干";
$MSG_NEED_DESCRIPTION = "需要题干";
$MSG_IS_JUDGED = "是否阅完";
$MSG_NOT_JUDGED = "未阅";
$MSG_JUDGE_OVER = "已阅完";
$MSG_JUDGE_LEFT = "剩余待阅";
$MSG_HUMAN_JUDGE = "人工阅卷部分";
$MSG_MACHINE_JUDGE = "机器阅卷部分";
$MSG_QUIZ_JUDGE_TIME = "阅卷时间";

$MSG_QUIZ_JUDGE = "批改";
$MSG_ANSWER_ID = "答案编号";
// quiz analysis
$MSG_ANALYSIS = "分析";
$MSG_ANSWERED_NUMBER = "已答人数";
$MSG_SCORE_ANALYSIS = "分数分析";
$MSG_CHOICE_ANALYSIS = "选项分析";
$MSG_AVERAGE_SCORE = "平均分";
$MSG_CORRECT_RATE = "正答率";
$MSG_SCORE_RATE = "得分率";
$MSG_MAX_SCORE = "最高分";
$MSG_MIN_SCORE = "最低分";
$MSG_SCORE_DIFF = "区分度";
$MSG_SUBMITTED = "已提交";
$MSG_NOT_SUBMITTED = "未提交";

$MSG_PARENT_SEARCH = "家长查询";
$MSG_ID_OR_NICK = "学号/姓名";
$MSG_NOT_FOUND = "用户未找到！";
$MSG_MULTIPLE_USER_CHOICE = "有多个用户匹配，请选择";
$MSG_STUDENT_ID = "学号";
$MSG_STUDENT_NAME = "姓名";
$MSG_STUDENT_ADMINISTRATIVE_CLASS = "行政班";
$MSG_STUDENT_TEACHING_CLASS = "教学班";
$MSG_IS_FINISHED = "是否完成";
$MSG_FINISHED = "已完成";
$MSG_NOT_PASS = "未通过";
$MSG_UNFINISHED = "未完成";
$MSG_IS_FINISHED_IN_TIME = "是否按时完成";
$MSG_IS_SIM_CHECKED = "是否被查重";
$MSG_SIM_YES = "被查重";
$MSG_SIM_NO = "未被查重";
$MSG_PARENT_EXPLAIN = "<div class='status_explain'><p>状态解释：<li>未提交，意为用户并未在系统中提交任何代码。</li><li>未通过，意为用户提交过代码，但没能完全通过测评。</li><li>已完成，意为用户的代码通过测评。</li><li>被查重，仅代表提交的代码在已提交中有相似者，并不能作为抄袭的依据。</li></p></div><p>本页面所提供之数据，仅为被查询用户在本系统中的使用情况之显示，并不构成任何意思表示。</p>";

//admin menu
$MSG_SEE = "查看";
$MSG_SEEOJ = "查看前台";
$MSG_ADD = "添加";
$MSG_MENU = "菜单";
$MSG_EXPLANATION = "内容描述";
$MSG_LIST = "列表";
$MSG_NEWS = "新闻";
$MSG_CONTENTS = "内容";
$MSG_SAVE = "保存";

$MSG_LOG = "日志";
$MSG_MODULE = "模块";
$MSG_VERSION = "版本";
$MSG_UPGRADE = "升级";
$MSG_UNINSTALL = "卸载";
$MSG_LAST_UPDATE = "最后更新";
$MSG_MODULE_INSTALL = "安装模块";
$MSG_EDIT_TIME = "编辑时间";
$MSG_ADD_TO_CONTEST = "加入作业";
$MSG_SET_TO = "设置为";
$MSG_MODULE_INSTALLED = "已安装模块";
$MSG_TEAMGENERATOR = "比赛队帐号生成器";
$MSG_PROBLEM_2 = "第二题库";
$MSG_PREFIX = "前缀";
$MSG_TEAM_NUMBER = "队伍数量";
$MSG_USER_LIST = "用户列表";
$MSG_SETMESSAGE = "悬浮公告";
$MSG_SETPASSWORD = "修改密码";
$MSG_REJUDGE = "重判题目";
$MSG_PRIVILEGE = "权限";
$MSG_GIVESOURCE = "转移源码";
$MSG_IMPORT = "导入";
$MSG_EXPORT = "导出";
$MSG_UPDATE_DATABASE = "更新数据库";
$MSG_ONLINE = "在线";
$MSG_CURRENT_ONLINE = "在线";
$MSG_BACKGROUND = "背景图片";
$MSG_SET_LOGIN_IP = "指定登录IP";
$MSG_PRIVILEGE_TYPE = "权限 类型";
$MSG_IP_MNGT = "IP白名单";

//contest.php
$MSG_PRIVATE_WARNING = "作业尚未开始或私有，不能查看信息。";
$MSG_PRIVATE_USERS_ADD = "*可以将学生学号从Excel整列复制过来，然后要求他们用学号做UserID注册,就能进入私有的作业。";
$MSG_PLS_ADD = "*可通过Ctrl +单击选择。";
$MSG_TIME_WARNING = "作业尚未开始。";
$MSG_WATCH_RANK = "点击这里查看作业排名。";
$MSG_NOIP_WARNING = $OJ_NOIP_KEYWORD . " 作业进行中，结束后才能查看结果。";

$MSG_SERVER_TIME = "服务器时间";
$MSG_START_TIME = "开始时间";
$MSG_END_TIME = "结束时间";
$MSG_CONTEST_ID = "作业编号";
$MSG_CONTEST_NAME = "作业名称";
$MSG_CONTEST_STATUS = "作业状态";
$MSG_CONTEST_OPEN = "开放";
$MSG_CONTEST_CREATOR = "创建人";
$MSG_CONTEST_PENALTY = "累计时间";
$MSG_IP_VERIFICATION = "IP验证";
$MSG_SUSPECT = "可疑记录";
$MSG_CONTEST_SUSPECT1 = "具有多个ID的IP地址。如果在作业/考试期间在同一台计算机上访问了多个ID，则会记录该ID。";
$MSG_CONTEST_SUSPECT2 = "具有多个IP地址的ID。 如果在作业/考试期间切换到另一台计算机，它将记录下来。";
$MSG_DOWNLOAD_TABLE = "下载表格";
$MSG_ROLLING = "滚榜";
$MSG_REPLAY = "重播";
$MSG_SYNC_HISTORY = "同步历史提交";
$MSG_LEAVE_CONTEST = "离开作业";

$MSG_INPUT_MANAULLY = "手动输入";
$MSG_BLANK_FILLING = "代码填空";
$MSG_BLANK_TEMPLATE = "填空模板";
$MSG_TEMPLATE_EXPLAIN = "单行填空请用%*%表示，多行填空用*%*表示";
$MSG_EDIT_SUCCESS = "编辑成功！";

$MSG_SECONDS = "秒";
$MSG_MINUTES = "分";
$MSG_HOURS = "小时";
$MSG_DAYS = "天";
$MSG_MONTHS = "月份";
$MSG_YEARS = "年份";

$MSG_Public = "公开";
$MSG_Private = "私有";
$MSG_Running = "运行中";
$MSG_Start = "开始于";
$MSG_End = "结束于";
$MSG_TotalTime = "总赛时";
$MSG_LeftTime = "剩余";
$MSG_Ended = "已结束";
$MSG_Login = "请登录后继续操作";
$MSG_JUDGER = "判题机";

$MSG_SOURCE_NOT_ALLOWED_FOR_EXAM = "考试期间，不能查阅以前提交的代码。";
$MSG_BBS_NOT_ALLOWED_FOR_EXAM = "考试期间,讨论版禁用。";
$MSG_MODIFY_NOT_ALLOWED_FOR_EXAM = "考试期间,禁止修改帐号信息。";
$MSG_MAIL_NOT_ALLOWED_FOR_EXAM = "考试期间,内邮禁用。";
$MSG_LOAD_TEMPLATE_CONFIRM = "是否加载默认模板?\\n 如果选择是，当前代码将被覆盖!";
$MSG_NO_MAIL_HERE = "本OJ不支持内部邮件";

$MSG_MY_SUBMISSIONS = "我的提交";
$MSG_MY_CONTESTS = "我的$MSG_CONTEST";
$MSG_Creator = "命题人";
$MSG_IMPORTED = "外部导入";
$MSG_PRINTER = "打印";
$MSG_PRINT_DONE = "打印完成";
$MSG_PRINT_PENDING = "提交成功,待打印";
$MSG_PRINT_WAITING = "请耐心等候，不要重复提交相同的打印任务";
$MSG_COLOR = "颜色";
$MSG_BALLOON = "气球";
$MSG_BALLOON_DONE = "气球已发放";
$MSG_BALLOON_PENDING = "气球待发放";

$MSG_FLOATING_URL = "图片链接";
$MSG_FLOATING_HREF = "跳转链接";
$MSG_FLOATING_STATIC = "是否固定";

$MSG_RECENT_LOGIN = "最近登录";
$MSG_REG_TIME = "注册时间";
$MSG_STUCK_IN_RUNNING = "卡在运行中";

$MSG_VIEW_ERROR = "查看错误信息";

$MSG_HELP_MODULE = "安装Python模块。";
$MSG_HELP_PROBLEM_2 = "存放一些题目但并不在前台展示，可以随时转换到前台。";
$MSG_HELP_EDIT_SCHOOL = "根据规则批量修改用户的班级。";
$MSG_HELP_EDIT_NICK = "批量修改用户的昵称。";
$MSG_HELP_LIST_GROUP = "查看已有用户组，并可添加新的用户组。";
$MSG_HELP_CHANGE_GROUP = "将用户批量加入用户组，或批量删除用户的用户组信息。";
$MSG_HELP_SEEOJ = "跳转回到前台";
$MSG_HELP_ADD_NEWS = "添加首页显示的新闻";
$MSG_HELP_NEWS_LIST = "管理已经发布的新闻";
$MSG_HELP_USER_LIST = "对注册用户停用、启用帐号";
$MSG_HELP_USER_ADD = "添加用户";
$MSG_HELP_ADD_PROBLEM = "手动添加新的题目，多组测试数据在添加后从题目列表TestData按钮进入上传，新建题目<b>默认隐藏</b>，需在问题列表中点击红色<font color='red'>Reserved</font>切换为绿色<font color='green'>Available</font>启用。";
$MSG_HELP_PROBLEM_LIST = "管理已有的题目和数据，上传数据可以用zip压缩不含目录的数据。";
$MSG_HELP_ADD_CONTEST = "规划新的作业，用逗号分隔题号。可以设定私有作业，用密码或名单限制参与者。";
$MSG_HELP_CONTEST_LIST = "已有的作业列表，修改时间和公开/私有，尽量不要在开赛后调整题目列表。";
$MSG_HELP_SET_LOGIN_IP = "记录考试期间的计算机(登录IP)更改。";
$MSG_HELP_TEAMGENERATOR = "批量生成大量作业帐号、密码，用于来自不同学校的参赛者。小系统不要随便使用，可能产生垃圾帐号，无法删除。";
$MSG_HELP_SETMESSAGE = "设置悬浮公告内容";
$MSG_HELP_SETPASSWORD = "重设指定用户的密码，对于管理员帐号需要先降级为普通用户才能修改。";
$MSG_HELP_REJUDGE = "重判指定的题目、提交或作业。";
$MSG_HELP_QUIZ = "查看或修改支持单选多选和填空的测试。";
$MSG_HELP_QUIZ_ADD = "创建支持单选多选和填空的测试。";
$MSG_HELP_ADD_PRIVILEGE = "给指定用户增加权限，包括管理员、题目添加者、作业组织者、作业参加者、代码查看者、手动判题、远程判题、打印员、气球发放员等权限。";
$MSG_HELP_ADD_CONTEST_USER = "给用户添加单个作业权限。";
$MSG_HELP_ADD_SOLUTION_VIEW = "给用户添加单个题目的答案查看权限。";
$MSG_HELP_PRIVILEGE_LIST = "查看已有的特殊权限列表、进行删除操作。";
$MSG_HELP_GIVESOURCE = "将导入系统的标程赠与指定帐号，用于训练后辅助未通过的人学习参考。";
$MSG_HELP_EXPORT_PROBLEM = "将系统中的题目以fps.xml文件的形式导出。";
$MSG_HELP_IMPORT_PROBLEM = "导入fps.xml文件。";
$MSG_HELP_UPDATE_DATABASE = "更新数据库结构，在每次升级之后或者导入老系统数据库备份，应至少操作一次。";
$MSG_HELP_ONLINE = "查看在线用户";
$MSG_HELP_KEYWORD = '"||"分隔，关系为或；回车分隔，关系为与';
$MSG_HELP_AC = "答案正确，请再接再厉。";
$MSG_HELP_PE = "答案基本正确，但是格式不对。";
$MSG_HELP_WA = "答案不对，仅仅通过样例数据的测试并不一定是正确答案，一定还有你没想到的地方，点击查看系统可能提供的对比信息。";
$MSG_HELP_TLE = "运行超出时间限制，检查下是否有死循环，或者应该有更快的计算方法";
$MSG_HELP_MLE = "超出内存限制，数据可能需要压缩，检查内存是否有泄露";
$MSG_HELP_OLE = "输出超过限制，你的输出比正确答案长了两倍，一定是哪里弄错了";
$MSG_HELP_RE = "运行时错误，非法的内存访问，数组越界，指针漂移，调用禁用的系统函数。请点击后获得详细输出";
$MSG_HELP_CE = "编译错误，请点击后获得编译器的详细输出";

$MSG_HELP_MORE_TESTDATA_LATER = "更多组测试数据，请在题目添加完成后补充";
$MSG_HELP_ADD_FAQS = "管理员可以添加一条新闻，命名为\"faqs.$OJ_LANG\" 来取代本页内容 <a href=../faqs.php>$MSG_FAQ</a>。";
$MSG_HELP_EOJ = "感谢您选择" . $OJ_NAME . "。";
$MSG_HELP_SPJ = "在测试文件中添加 pre 和 spj 程序特判，支持C/C++, Shell, Python。<br>pre 为预处理程序，用户程序运行前运行，传入测试输入文件名。
				<br>spj 为特判程序，运行传入参数分别为测试输入文件名，测试输出文件名，用户输出文件名。<br>特判程序返回0为正确，1为错误。";
$MSG_HELP_BALLOON_SCHOOL = "打印，气球帐号的School字段用于过滤任务列表，例如填zjicm则只显示帐号为zjicm开头的任务";

$MSG_WARNING_LOGIN_FROM_DIFF_IP = "从不同的IP地址登录";
$MSG_WARNING_DURING_EXAM_NOT_ALLOWED = " 在考试期间不被允许 ";
$MSG_WARNING_ACCESS_DENIED = "抱歉，您无法查看此消息！因为它不属于您，或者管理员设定系统状态为不显示此类信息。";

$MSG_WARNING_USER_ID_SHORT = "用户名至少3位字符!";
$MSG_WARNING_PASSWORD_SHORT = "密码至少6位!";
$MSG_WARNING_REPEAT_PASSWORD_DIFF = "两次输入的密码不一致!";


$MSG_LOSTPASSWORD_MAILBOX = "请到您邮箱的垃圾邮件文件夹寻找验证码，并填写到这里";
$MSG_LOSTPASSWORD_WILLBENEW = "如果填写正确，通过下一步验证，这个验证码就自动成为您的新密码！";
