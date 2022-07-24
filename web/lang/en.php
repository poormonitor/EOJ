<?php
$MSG_TRUE_FALSE = array(true => 'Yes', false => 'No');
$MSG_TRUE = "Yes";
$MSG_FALSE = "No";
$MSG_GROUP = "Group";
$MSG_FROM = "From";
$MSG_TO = "To";
$MSG_COPY = "Copy";
$MSG_ENABLE = "Enable";
$MSG_DISABLE = "Disable";
$MSG_ENABLED = "Enabled";
$MSG_DISABLED = "Disabled";
$MSG_EMPTY = "Empty";
$MSG_UPLOAD = "Upload";
$MSG_NOT_EXISTED = "The resource you request does not exist.";
$MSG_SUBMISSIONS = " Submission(s)";
$MSG_NO_SUBMISSION = "No Submission";
$MSG_PRIVILEGE_WARNING = "You are not authorized to view this page.";
$MSG_ERROR = "Error";
$MSG_SUCCESS = "Success";
$MSG_UP_WRONG = "Username or password is wrong";
$MSG_PARAMS_ERROR = "Some of items is wrong";
$MSG_PARAMS_TOO_FEW = "You have filled in too few items";
$MSG_NICK_TOO_LONG = "Nick is too long";
$MSG_SCHOOL_TOO_LONG = "School is too long";
$MSG_DOWNLOAD_ALL_AC = "Download all AC code";

$MSG_CLIPBOARD = "Clipboard";
$MSG_NOT_LOGINED = "You are not logged in.";
$MSG_DELETE = "Delete";
//index.php
$MSG_RECENT_SUBMISSION = "Recent Submission";
$MSG_TOTAL = "All";
$MSG_ACCEPTED = "Accepted";
//oj-header.php
$MSG_FAQ = "FAQ";
$MSG_BBS = "Web Board";
$MSG_HOME = "Home";
$MSG_PROBLEMS = "ProblemSet";
$MSG_STATUS = "Status";
$MSG_RANKLIST = "Ranklist";
$MSG_CONTEST = "Homework";
$MSG_RECENT_CONTEST = "Recent";
$MSG_LOGOUT = "Logout";
$MSG_LOGIN = "Login";
$MSG_LOST_PASSWORD = "Lost Password";
$MSG_REGISTER = "Register";
$MSG_ADMIN = "Admin";
$MSG_SYSTEM = "System";
$MSG_STANDING = "Standing";
$MSG_STATISTICS = "Statistics";
$MSG_USERINFO = "User Information";
$MSG_MAIL = "Mail";
$MSG_POLICY = "EULA & Privacy Policy";
$MSG_AGREE_POLICY = "By logging in, you agree to the <a href='policy.html' id='footer'> $MSG_POLICY </a>";
$MSG_PASSWORD_RESET = "Password Reset";
//status.php
$MSG_Pending = "Pending";
$MSG_Pending_Rejudging = "Pending Rejudging";
$MSG_Compiling = "Compiling";
$MSG_Running_Judging = "Running & Judging";
$MSG_Accepted = "Accepted";
$MSG_Presentation_Error = "Presentation Error";
$MSG_Wrong_Answer = "Wrong Answer";
$MSG_Time_Limit_Exceed = "Time Limit Exceed";
$MSG_Memory_Limit_Exceed = "Memory Limit Exceed";
$MSG_Output_Limit_Exceed = "Output Limit Exceed";
$MSG_Runtime_Error = "Runtime Error";
$MSG_Compile_Error = "Compile Error";
$MSG_Runtime_Click = "Runtime Error(Click)";
$MSG_Compile_Click = "Compile Error(Click)";
$MSG_Compile_OK = "Compile OK";
$MSG_Click_Detail = "Click To View Detail";
$MSG_Manual = "Manual Judge";
$MSG_OK = "OK";
$MSG_Explain = "Type reason or explaination";

//fool's day
if (date('m') == 4 && date('d') == 1 && rand(0, 100) < 10) {
    $MSG_Accepted = "Happy Fool's Day!";
    $MSG_Presentation_Error = "Happy Fool's Day!";
    $MSG_Wrong_Answer = "Happy Fool's Day!";
    $MSG_Time_Limit_Exceed = "Happy Fool's Day!";
    $MSG_Memory_Limit_Exceed = "Happy Fool's Day!";
    $MSG_Output_Limit_Exceed = "Happy Fool's Day!";
    $MSG_Runtime_Error = "Happy Fool's Day!";
    $MSG_Compile_Error = "Happy Fool's Day!";
    $MSG_Compile_OK = "Happy Fool's Day!";
}

$MSG_RUNID = "RunID";
$MSG_USER = "User";
$MSG_PROBLEM = "Problem";
$MSG_RESULT = "Result";
$MSG_MEMORY = "Memory";
$MSG_TIME = "Time";
$MSG_LANG = "Language";
$MSG_CODE_LENGTH = "Code Length";
$MSG_SUBMIT_TIME = "Submit Time";

//problemstatistics.php
$MSG_PD = "PD";
$MSG_PR = "PR";
$MSG_CI = "CI";
$MSG_RJ = "RJ";
$MSG_AC = "AC";
$MSG_PE = "PE";
$MSG_WA = "WA";
$MSG_TLE = "TLE";
$MSG_MLE = "MLE";
$MSG_OLE = "OLE";
$MSG_RE = "RE";
$MSG_CE = "CE";
$MSG_CO = "CO";
$MSG_TR = "Test";
$MSG_RESET = "Reset";

//problemset.php
$MSG_SEARCH = "Search";
$MSG_PROBLEM_ID = "Problem ID";
$MSG_TITLE = "Title";
$MSG_SOURCE = "Source/Category";
$MSG_SUBMIT_NUM = "Submit Num";
$MSG_SUBMIT = "Submit";

//submit.php
$MSG_NO_SUCH_CONTEST = 'The homework you request does not exist. It\'s problably beacuse it does not exist itself or you are not invited.';
$MSG_TEST_RUN = "Test Run";
$MSG_VCODE_WRONG = "Verification Code Wrong!";
$MSG_LINK_ERROR = "Where do find this link? No such problem.";
$MSG_PROBLEM_RESERVED = "Problem disabled.";
$MSG_NOT_IN_CONTEST = "You Can't Submit Now Because Your are not invited by the homework or the homework is not running!!";
$MSG_NOT_INVITED = "You are not invited!";
$MSG_NO_PROBLEM = "No Such Problem!";
$MSG_NO_PLS = "Using unknown programing language!";
$MSG_TOO_SHORT = "Code too short!";
$MSG_TOO_LONG = "Code too long!";
$MSG_FORMAT_ERROR = "Your code doesn't fit the format!";
$MSG_BREAK_TIME = "You should not submit more than twice in 1 seconds.";
$MSG_COPY_CURRENT = "Copy Current Code";
$MSG_WRITE_DIRECTLY = "Code Directly";

//ranklist.php
$MSG_Number = "No.";
$MSG_NICK = "Name";
$MSG_SOVLED = "Solved";
$MSG_RATIO = "Ratio";
$MSG_DAY = "Day-Rank";
$MSG_WEEK = "Week-Rank";
$MSG_MONTH = "Month-Rank";
$MSG_YEAR = "Year-Rank";
$MSG_RANK_LOCKED = "The rank has been locked.";
//registerpage.php
$MSG_USER_ID = "User ID";
$MSG_PASSWORD = "Password";
$MSG_REPEAT_PASSWORD = "Repeat Password";
$MSG_SCHOOL = "School";
$MSG_EMAIL = "Email";
$MSG_REG_INFO = "Register Information";
$MSG_VCODE = "Verify Code";

//problem.php
$MSG_NO_SUCH_PROBLEM = "Problem is not Available!";
$MSG_Description = "Description";
$MSG_Input = "Input";
$MSG_Output = "Output";
$MSG_Sample_Input = "Sample Input";
$MSG_Sample_Output = "Sample Output";
$MSG_Test_Input = "Test Input";
$MSG_Test_Output = "Test Output";
$MSG_SPJ = "Special Judge";
$MSG_HINT = "Hint";
$MSG_Source = "Source";
$MSG_ALLOW_KEYWORD = "Required Keyword";
$MSG_BLOCK_KEYWORD = "Disabled Keyword";
$MSG_AB_KEYWORD = "Required/Disabled Keyword";
$MSG_Time_Limit = "Time Limit";
$MSG_Memory_Limit = "Memory Limit";
$MSG_EDIT = "Edit";
$MSG_TESTDATA = "TestData";

//quiz.php
$MSG_QUIZ = "Quiz";
$MSG_QUIZ_ID = "Quiz ID";
$MSG_QUIZ_TITLE = "Title";
$MSG_QUIZ_NAME = "Quiz Name";
$MSG_QUIZ_STATUS = "Quiz Status";
$MSG_QUIZ_OPEN = "Open";
$MSG_QUIZ_CREATOR = "Creator";
$MSG_QUIZ_ANS = "Answer";
$MSG_QUIZ_SCORE = "Score";
$MSG_QUIZ_PROBLEM = "Problems";
$MSG_QUIZ_PROBLEM_INFORMATION = "Problem Information";
$MSG_QUIZ_ANSWER = "Answer";
$MSG_YOUR_ANSWER = "Your Answer";
$MSG_CORRECT_ANSWER = "Reference Answer";
$MSG_SCORE = "Point";
$MSG_SCORE_SUM = "Total Point";
$MSG_PLEASE_ANSWER_ALL_QUESTIONS = "Please check if all the problems have benn answered";
$MSG_NO_SUCH_QUIZ = "The quiz you request does not exist. It\'s problably beacuse it does not exist itself or you are not invited.";
$MSG_ALREADY_SUBMIT = "You have answered and cannot answer again.";
$MSG_TYPE = "Type";
$MSG_QUIZ_TYPE = array("Single Choice", "Multiple Choice", "Blank Filling", "Short Anser");
$MSG_INPUT_NUMBER = "Please input the number of problems";
$MSG_INQUERY_NUMBER = "the Number of Problem";
$MSG_NOT_FINISHED = "The user hasn't finished yet.";
$MSG_NO_NEED_DESCRIPTION = "No Need for Description";
$MSG_NEED_DESCRIPTION = "Need Description";
$MSG_IS_JUDGED = "Is Judged";
$MSG_NOT_JUDGED = "Unjudged";
$MSG_JUDGE_OVER = "Judged";
$MSG_JUDGE_LEFT = "Left to judge";
$MSG_HUMAN_JUDGE = "Human Judge Part";
$MSG_MACHINE_JUDGE = "Auto Judge Part";
$MSG_QUIZ_JUDGE_TIME = "Judge Time";

$MSG_QUIZ_JUDGE = "Judge";
$MSG_ANSWER_ID = "Answer ID";
// quiz analysis
$MSG_ANALYSIS = "Analysis";
$MSG_ANSWERED_NUMBER = "Answered";
$MSG_SCORE_ANALYSIS = "Score Analysis";
$MSG_CHOICE_ANALYSIS = "Choice Analysis";
$MSG_AVERAGE_SCORE = "Average";
$MSG_CORRECT_RATE = "Correct Rate";
$MSG_SCORE_RATE = "Score Rate";
$MSG_MAX_SCORE = "Highest";
$MSG_MIN_SCORE = "Lowest";
$MSG_SCORE_DIFF = "Distinction";
$MSG_SUBMITTED = "Submitted";
$MSG_NOT_SUBMITTED = "Not Submitted";

$MSG_PARENT_SEARCH = "Parent Check";
$MSG_ID_OR_NICK = "ID/Nick";
$MSG_NOT_FOUND = "The user is not found!";
$MSG_MULTIPLE_USER_CHOICE = "There are multiple matches. Please choose one!";
$MSG_STUDENT_ID = "ID";
$MSG_STUDENT_NAME = "Name";
$MSG_STUDENT_ADMINISTRATIVE_CLASS = "Administrative Class";
$MSG_STUDENT_TEACHING_CLASS = "Teaching Class";
$MSG_IS_FINISHED = "Is Finished";
$MSG_FINISHED = "Finished";
$MSG_NOT_PASS = "Not Pass";
$MSG_UNFINISHED = "Unfinished";
$MSG_IS_FINISHED_IN_TIME = "Is in time";
$MSG_IS_SIM_CHECKED = "Is Plagiarized";
$MSG_SIM_YES = "Plagiarized";
$MSG_SIM_NO = "Not Plagiarized";
$MSG_PARENT_EXPLAIN = "<div class='status_explain'><p>State Explanation:<li>Unfinished, means the user has not submitted any code in the system.</li><li>Not Pass, means that the user has submitted code but failed to pass the judging completely.</li><li>Finished, means that the user's code passed the judging.</li><li>Plagiarized, means that the submitted code has similarities in the submitted code, and cannot be used as a basis for copying.</li></p></div><p>The data provided on this page is only a display of the user's usage in this system, and does not constitute any meaning. </p>";

//admin menu
$MSG_SEE = "See";
$MSG_SEEOJ = "See OJ";
$MSG_ADD = "Add";
$MSG_LIST = "List";
$MSG_MENU = "menu";
$MSG_EXPLANATION = "explanation";
$MSG_NEWS = "News";
$MSG_CONTENTS = "Contents";
$MSG_SAVE = "Save";

$MSG_LOG = "Log";
$MSG_MODULE = "Module";
$MSG_VERSION = "Version";
$MSG_UPGRADE = "Upgrade";
$MSG_UNINSTALL = "Uninstall";
$MSG_LAST_UPDATE = "Last Upgrading";
$MSG_MODULE_INSTALL = "Install Module";
$MSG_EDIT_TIME = "Editing Time";
$MSG_ADD_TO_CONTEST = "Add to Homework";
$MSG_SET_TO = "Set to";
$MSG_MODULE_INSTALLED = "Installed Module";
$MSG_TEAMGENERATOR = "Team Generator";
$MSG_PROBLEM_2 = "2nd Problem List";
$MSG_PREFIX = "Prefix";
$MSG_TEAM_NUMBER = "Team Number";
$MSG_USER_LIST = "User List";
$MSG_SETMESSAGE = "Float Message";
$MSG_SETPASSWORD = "Change Password";
$MSG_REJUDGE = "Rejudge";
$MSG_PRIVILEGE = "Privilege";
$MSG_GIVESOURCE = "Give Source";
$MSG_IMPORT = "Import";
$MSG_EXPORT = "Export";
$MSG_UPDATE_DATABASE = "Update Database";
$MSG_ONLINE = "Online";
$MSG_CURRENT_ONLINE = "Current Online";
$MSG_BACKGROUND = "Background Image";
$MSG_SET_LOGIN_IP = "Set Login IP";
$MSG_PRIVILEGE_TYPE = "Privilege Type";
$MSG_IP_MNGT = "IP White List";

//contest.php
$MSG_PRIVATE_WARNING = "Homework has not started or you don't have privilege of it.";
$MSG_PRIVATE_USERS_ADD = "*Enter userIDs with newline, or you can copy and paste from a spreadsheet.";
$MSG_PLS_ADD = "*Please select all languages that can be submitted with Ctrl + click.";
$MSG_TIME_WARNING = "Before Homework Start";
$MSG_WATCH_RANK = "Click HERE to watch homework rank.";
$MSG_NOIP_WARNING = $OJ_NOIP_KEYWORD . " Homework does not show result, until it's over.";

$MSG_SERVER_TIME = "Server Time";
$MSG_START_TIME = "Start Time";
$MSG_END_TIME = "End Time";
$MSG_CONTEST_ID = "Homework ID";
$MSG_VIEW_ALL_CONTESTS = "Show All Homework";
$MSG_CONTEST_NAME = "Homework Name";
$MSG_CONTEST_STATUS = "Status";
$MSG_CONTEST_OPEN = "Open";
$MSG_CONTEST_CREATOR = "Creator";
$MSG_CONTEST_PENALTY = "Time Penalty";
$MSG_IP_VERIFICATION = "IP Verification";
$MSG_SUSPECT = "Suspect";
$MSG_CONTEST_SUSPECT1 = "IP addresses with multiple IDs. If multiple IDs are accessed at the same computer during the homework/exam, it logged.";
$MSG_CONTEST_SUSPECT2 = "IDs with multiple IP addresses. If switch to another computer during the homework/exam, it logged.";
$MSG_DOWNLOAD_TABLE = "Download Table";
$MSG_ROLLING = "Rolling";
$MSG_REPLAY = "Replay";
$MSG_SYNC_HISTORY = "Sync History";
$MSG_LEAVE_CONTEST = "Leave Homework";

$MSG_INPUT_MANAULLY = "Input Manually";
$MSG_BLANK_FILLING = "Blank Filling";
$MSG_BLANK_TEMPLATE = "Blank Template";
$MSG_TEMPLATE_EXPLAIN = "Use %*% to represent single line blank and *%* to represent multiline blank";
$MSG_EDIT_SUCCESS = "Edited Successfully!";

$MSG_SECONDS = "seconds";
$MSG_MINUTES = "minutes";
$MSG_HOURS = "hours";
$MSG_DAYS = "days";
$MSG_MONTHS = "months";
$MSG_YEARS = "years";

$MSG_Public = "Public";
$MSG_Private = "Private";
$MSG_Running = "Running";
$MSG_Start = "Start";
$MSG_End = "End";
$MSG_TotalTime = "Total";
$MSG_LeftTime = "Left";
$MSG_Ended = "Finished";
$MSG_Login = "Please Login";
$MSG_JUDGER = "Judger";

$MSG_SOURCE_NOT_ALLOWED_FOR_EXAM = "You can't browse early code during examing .";
$MSG_BBS_NOT_ALLOWED_FOR_EXAM = "You can't use bbs during examing.";
$MSG_MODIFY_NOT_ALLOWED_FOR_EXAM = "You can't change password during examing or homework on site.";
$MSG_MAIL_NOT_ALLOWED_FOR_EXAM = "You can't use mail during examing.";
$MSG_LOAD_TEMPLATE_CONFIRM = "Do you want to reload template?\\n You may lost all code that you've typed here!";
$MSG_NO_MAIL_HERE = "This OJ doesn't support Mail.";

$MSG_MY_SUBMISSIONS = "My Submissions";
$MSG_MY_CONTESTS = "My Homework";
$MSG_Creator = "Creator";
$MSG_IMPORTED = "Imported";
$MSG_PRINTER = "Printer";
$MSG_PRINT_DONE = "Printed Fine";
$MSG_PRINT_PENDING = "Pending for Print";
$MSG_PRINT_WAITING = "Please waiting for delivery, don't post duplicated print task";
$MSG_COLOR = "Color";
$MSG_BALLOON = "Balloon";
$MSG_BALLOON_DONE = "Balloon Sent";
$MSG_BALLOON_PENDING = "Balloon Pending";

$MSG_FLOATING_URL = "Picture URL";
$MSG_FLOATING_HREF = "Go to";
$MSG_FLOATING_STATIC = "Is Static";

$MSG_RECENT_LOGIN = "Recent Login";
$MSG_REG_TIME = "Regesiter Time";
$MSG_STUCK_IN_RUNNING = "Stuck in running";

$MSG_VIEW_ERROR = "Allow View Error";

$MSG_HELP_MODULE = "Install Python Modules.";
$MSG_HELP_PROBLEM_2 = "Store some problems which is not showed and can be switched at any time";
$MSG_HELP_EDIT_SCHOOL = "Change users' school according to some rules";
$MSG_HELP_EDIT_NICK = "Change nick for users";
$MSG_HELP_LIST_GROUP = "View the existed gruops and add new groups";
$MSG_HELP_CHANGE_GROUP = "Add users to groups or delete users from a group.";
$MSG_HELP_SEEOJ = "watch the front pages";
$MSG_HELP_ADD_NEWS = "add news for the homepage";
$MSG_HELP_NEWS_LIST = "edit or shutdown published news";
$MSG_HELP_USER_LIST = "enable/disable user";
$MSG_HELP_USER_ADD = "add new user";
$MSG_HELP_ADD_PROBLEM = "add new problem,multi test cases can be added after problem added ,using TestData button on the Problem List";
$MSG_HELP_PROBLEM_LIST = "manage existing problems, test case files can be uploaded within a zip file and decompress later";
$MSG_HELP_ADD_CONTEST = "schedule a new homework, problem list seperated with comma. You can set up private homework and restrict participants with passwords or lists.";
$MSG_HELP_CONTEST_LIST = "The existing list of homework, modification time and public / private, try not to adjust the list of items after the start.";
$MSG_HELP_SET_LOGIN_IP = "Record computer(login IP) changes during the exam.";
$MSG_HELP_TEAMGENERATOR = "Generate accounts and passwords in batches for competitors from different schools. Small system should not be used casually, may produce garbage accounts, which can not be easily deleted.";
$MSG_HELP_SETMESSAGE = "Set floating notification content";
$MSG_HELP_SETPASSWORD = "Reset the password for the specified user, for administrator account need to downgrade to ordinary users to modify.";
$MSG_HELP_REJUDGE = "To review a specified subject, submission, or homework.";
$MSG_HELP_QUIZ = "View or modify quizzes which support single choice, multiiple choice and short answer.";
$MSG_HELP_QUIZ_ADD = "Create quizzes which support single choice, multiiple choice and short answer.";
$MSG_HELP_ADD_PRIVILEGE = " adds permissions to designated users, including administrators, subjects, players, organizers, participants, code viewer, manual judge questions, remote questions and other permissions. ";
$MSG_HELP_ADD_CONTEST_USER = "Add User to private homework.";
$MSG_HELP_ADD_SOLUTION_VIEW = "Add Problem Solution View for User.";
$MSG_HELP_PRIVILEGE_LIST = "looks at the existing list of special permissions and deletes them. ";
$MSG_HELP_GIVESOURCE = " transfers the label of the import system to the designated account number, which is used to assist the person who has not passed the training after the training. ";
$MSG_HELP_EXPORT_PROBLEM = "export the problems of the system in the form of fps.xml file. ";
$MSG_HELP_IMPORT_PROBLEM = " import fps.xml files downloaded from internet or tk.hustoj.com. ";
$MSG_HELP_UPDATE_DATABASE = "Update the database structure, after each upgrade (sudo update-hustoj), run it once. ";
$MSG_HELP_ONLINE = "View online users";
$MSG_HELP_KEYWORD = '"||" to represent "or" and ENTER represent "and"';
$MSG_HELP_AC = "Congratulations!";
$MSG_HELP_PE = "Your output format is not exactly the same as the judge's output, although your answer to the problem is correct. Check your output for spaces, blank lines,etc against the problem output specification";
$MSG_HELP_WA = " Correct solution not reached for the inputs. The inputs and outputs that we use to test the programs are not public (it is recomendable to get accustomed to a true homework dynamic ;-)";
$MSG_HELP_TLE = "Your program tried to run during too much time";
$MSG_HELP_MLE = "Your program tried to use more memory than the judge default settings";
$MSG_HELP_OLE = "Your program tried to write too much information. This usually occurs if it goes into a infinite loop. Currently the output limit is 1M bytes";
$MSG_HELP_RE = "All the other Error on the running phrase will get Runtime Error, such as 'segmentation fault','floating point exception','used forbidden functions', 'tried to access forbidden memories' and so on";
$MSG_HELP_CE = "The compiler could not compile your ANSI program. Of course, warning messages are not error messages. Click the link at the judge reply to see the actual error message";

$MSG_HELP_MORE_TESTDATA_LATER = "more testdata can be added later after this problem added.";
$MSG_HELP_ADD_FAQS = "Add a news which titled \"faqs.$OJ_LANG\", it apears as <a href=../faqs.php>$MSG_FAQ</a>";
$MSG_HELP_HOJ = "<sub><a target='_blank' href='https://github.com/zhblue/hustoj'><span class='glyphicon glyphicon-heart' aria-hidden='true'></span> Please give us a <span class='glyphicon glyphicon-star' aria-hidden='true'></span>Star @HUSTOJ Github!</a></sub>";
$MSG_HELP_SPJ = "Add 'pre' and 'spj' to test data to do special judge. It supports C/C++, Shell, Python。<br>'pre' is pre-script which is running before the user's program,
				<br>'spj' is the special judge program. The input parameters for running are the test input file name, the test output file name, and the user output file name.<br>'spj' returning 0 represents accepted and 1 represents wrong.";
$MSG_HELP_BALLOON_SCHOOL = "School Field of the Printer/Balloon privileged Accout, will be used as filter in task list.";

$MSG_WARNING_LOGIN_FROM_DIFF_IP = "Login from a diffrent ip ";
$MSG_WARNING_DURING_EXAM_NOT_ALLOWED = " during exam is not allowed ";
$MSG_WARNING_ACCESS_DENIED = "I am sorry, You could not view this message! Because it's not belong to you, or Administrator won't show you.";

$MSG_WARNING_USER_ID_SHORT = "User ID should be NO less than 3 chars !";
$MSG_WARNING_PASSWORD_SHORT = "Password should be NO less than 6 chars !";
$MSG_WARNING_REPEAT_PASSWORD_DIFF = "The second password is different with the first one !";


$MSG_LOSTPASSWORD_MAILBOX = "Input the Code sended to your email (Trash Box )";
$MSG_LOSTPASSWORD_WILLBENEW = "if it's correct, it will be the new password";
