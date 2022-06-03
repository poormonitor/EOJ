<?php
$MSG_TRUE_FALSE = array(true => 'Yes', false => 'No');
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
$MSG_CONTEST = "Contest";
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
$MSG_NOT_IN_CONTEST = "You Can't Submit Now Because Your are not invited by the contest or the contest is not running!!";
$MSG_NOT_INVITED = "You are not invited!";
$MSG_NO_PROBLEM = "No Such Problem!";
$MSG_NO_PLS = "Using unknown programing language!";
$MSG_TOO_SHORT = "Code too short!";
$MSG_TOO_LONG = "Code too long!";
$MSG_BREAK_TIME = "You should not submit more than twice in 1 seconds.....";

//ranklist.php
$MSG_Number = "No.";
$MSG_NICK = "Nick Name";
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
$MSG_ADD_TO_CONTEST = "Add to Contest";
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
$MSG_SET_LOGIN_IP = "SetLoginIP";
$MSG_PRIVILEGE_TYPE = "Privilege Type";
$MSG_IP_MNGT = "IP White List";

//contest.php
$MSG_PRIVATE_WARNING = "Contest has not started or you don't have privilege of it.";
$MSG_PRIVATE_USERS_ADD = "*Enter userIDs with newline, or you can copy and paste from a spreadsheet.";
$MSG_PLS_ADD = "*Please select all languages that can be submitted with Ctrl + click.";
$MSG_TIME_WARNING = "Before Contest Start";
$MSG_WATCH_RANK = "Click HERE to watch contest rank.";
$MSG_NOIP_WARNING = $OJ_NOIP_KEYWORD . " Contest does not show result, until it's over.";

$MSG_SERVER_TIME = "Server Time";
$MSG_START_TIME = "Start Time";
$MSG_END_TIME = "End Time";
$MSG_CONTEST_ID = "Contest ID";
$MSG_VIEW_ALL_CONTESTS = "Show All Contests";
$MSG_CONTEST_NAME = "Contest Name";
$MSG_CONTEST_STATUS = "Status";
$MSG_CONTEST_OPEN = "Open";
$MSG_CONTEST_CREATOR = "Creator";
$MSG_CONTEST_PENALTY = "Time Penalty";
$MSG_IP_VERIFICATION = "IP Verification";
$MSG_SUSPECT = "Suspect";
$MSG_CONTEST_SUSPECT1 = "IP addresses with multiple IDs. If multiple IDs are accessed at the same computer during the contest/exam, it logged.";
$MSG_CONTEST_SUSPECT2 = "IDs with multiple IP addresses. If switch to another computer during the contest/exam, it logged.";

$MSG_INPUT_MANAULLY ="Input Manually";
$MSG_BLANK_FILLING = "Code Blank Filling";
$MSG_BLANK_TEMPLATE = "Blank Template";
$MSG_TEMPLATE_EXPLAIN = "Use %*% to represent single line blank and *%* to represent multiline blank";

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
$MSG_MODIFY_NOT_ALLOWED_FOR_EXAM = "You can't change password during examing or contest on site.";
$MSG_MAIL_NOT_ALLOWED_FOR_EXAM = "You can't use mail during examing.";
$MSG_LOAD_TEMPLATE_CONFIRM = "Do you want to reload template?\\n You may lost all code that you've typed here!";
$MSG_NO_MAIL_HERE = "This OJ doesn't support Mail.";

$MSG_MY_SUBMISSIONS = "My Submissions";
$MSG_MY_CONTESTS = "My Contests";
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
$MSG_HELP_ADD_CONTEST = "schedule a new contest, problem list seperated with comma. You can set up private contest and restrict participants with passwords or lists.";
$MSG_HELP_CONTEST_LIST = "The existing list of contests, modification time and public / private, try not to adjust the list of items after the start.";
$MSG_HELP_SET_LOGIN_IP = "Record computer(login IP) changes during the exam.";
$MSG_HELP_TEAMGENERATOR = "Generate accounts and passwords in batches for competitors from different schools. Small system should not be used casually, may produce garbage accounts, which can not be easily deleted.";
$MSG_HELP_SETMESSAGE = "Set floating notification content";
$MSG_HELP_SETPASSWORD = "Reset the password for the specified user, for administrator account need to downgrade to ordinary users to modify.";
$MSG_HELP_REJUDGE = "To review a specified subject, submission, or contest.";
$MSG_HELP_QUIZ = "View or modify quizzes which support single choice, multiiple choice and short answer.";
$MSG_HELP_QUIZ_ADD = "Create quizzes which support single choice, multiiple choice and short answer.";
$MSG_HELP_ADD_PRIVILEGE = " adds permissions to designated users, including administrators, subjects, players, organizers, participants, code viewer, manual judge questions, remote questions and other permissions. ";
$MSG_HELP_ADD_CONTEST_USER = "Add User to private contest.";
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
$MSG_HELP_WA = " Correct solution not reached for the inputs. The inputs and outputs that we use to test the programs are not public (it is recomendable to get accustomed to a true contest dynamic ;-)";
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


// template/../reinfo.php
$MSG_A_NOT_ALLOWED_SYSTEM_CALL = "Use the operating system calls prohibited by the system to see if you have unauthorized access to resources such as files or processes. If you are a system administrator and confirm that there is no problem with the submitted answer and the test data has no problem, you can send'RE' to the WeChat official account onlinejudge, view the solution.";
$MSG_SEGMETATION_FAULT = "Check whether the array is out of bounds, the pointer is abnormal, and the memory area that should not be accessed is accessed.";
$MSG_FLOATING_POINT_EXCEPTION = "Floating point error, check for division by zero.";
$MSG_BUFFER_OVERFLOW_DETECTED = "Buffer overflow, check whether the string length exceeds the array.";
$MSG_PROCESS_KILLED = "The process is killed because of memory or time, check whether there is an infinite loop.";
$MSG_ALARM_CLOCK = "The process was killed due to time. Check whether there is an infinite loop. This error is equivalent to timeout TLE.";
$MSG_CALLID_20 = "The array may be out of bounds, check the relationship between the amount of data described in the title and the size of the applied array.";
$MSG_ARRAY_INDEX_OUT_OF_BOUNDS_EXCEPTION = "Check if the array is out of bounds.";
$MSG_STRING_INDEX_OUT_OF_BOUNDS_EXCEPTION = "The character subscript of the string is out of range, check the parameters of subString, charAt and other methods.";

// template/../ceinfo.php
$MSG_ERROR_EXPLAIN = "Explain";
$MSG_SYSTEM_OUT_PRINT = "The usage of System.out.print in Java is different from that of C language printf, please try System.out.format";
$MSG_NO_SUCH_FILE_OR_DIRECTORY = "The server is a Linux system and cannot use non-standard header files unique to Windows.";
$MSG_NOT_A_STATEMENT = "Check the matching of braces {}, eclipse sorts the code shortcut key Ctrl+Shift+F";
$MSG_EXPECTED_CLASS_INTERFACE_ENUM = "Please do not place java functions (methods) outside the class declaration, pay attention to the ending position of the braces }";
$MSG_SUBMIT_JAVA_AS_C_LANG = "Please do not submit java program as C language.";
$MSG_DOES_NOT_EXIST_PACKAGE = "Check spelling, such as: the system object System starts with a capital S";
$MSG_POSSIBLE_LOSS_OF_PRECISION = "Assignment will lose precision, check the data type, if it is correct, you can use coercive type conversion.";
$MSG_INCOMPATIBLE_TYPES = "Different types of data in Java cannot be assigned to each other, and integers cannot be used as Boolean values.";
$MSG_ILLEGAL_START_OF_EXPRESSION = "String should be surrounded by English double quotation marks (\\\")";
$MSG_CANNOT_FIND_SYMBOL = "Misspelling or missing objects required to call the function such as println() need to call System.out";
$MSG_EXPECTED_SEMICOLON = "The semicolon is missing.";
$MSG_DECLARED_JAVA_FILE_NAMED = "Java must use public class Main.";
$MSG_EXPECTED_WILDCARD_CHARACTER_AT_END_OF_INPUT = "The code is not over, missing matching brackets or semicolons, check whether all the codes are selected when copying.";
$MSG_INVALID_CONVERSION = "The implicit type conversion is invalid, try to use explicit coercion such as (int *)malloc(....)";
$MSG_NO_RETURN_TYPE_IN_MAIN = "In the C++ standard, the main function must have a return value.";
$MSG_NOT_DECLARED_IN_SCOPE = "The variable has not been declared, check for spelling errors!";
$MSG_MAIN_MUST_RETURN_INT = "In the standard C language, the return value type of the main function must be int, and the use of void in teaching materials and VC is a non-standard usage.";
$MSG_PRINTF_NOT_DECLARED_IN_SCOPE = "The printf function is called without a declaration, and check whether the <stdio.h> or <cstdio> header file is imported.";
$MSG_IGNOREING_RETURN_VALUE = "Warning: Ignore the return value of the function, it may be that the function is used incorrectly or the return value is not considered abnormal.";
$MSG_NOT_DECLARED_INT64 = "__int64 is not declared. The __int64 in Microsoft VC is not supported in standard C/C++, please use long long to declare 64-bit variables.";
$MSG_EXPECTED_SEMICOLON_BEFORE = "The semicolon is missing from the previous line.";
$MSG_UNDECLARED_NAME = "Variables must be declared before they are used, or they may be spelled incorrectly. Pay attention to case sensitivity.";
$MSG_SCANF_NOT_DECLARED_IN_SCOPE = "The scanf function is called without being declared, and check whether the <stdio.h> or <cstdio> header file is imported.";
$MSG_MEMSET_NOT_DECLARED_IN_SCOPE = "The memset function is called without being declared, and check whether the <stdlib.h> or <cstdlib> header file is imported.";
$MSG_MALLOC_NOT_DECLARED_IN_SCOPE = "The malloc function is called without being declared, and check whether the <stdlib.h> or <cstdlib> header file is imported.";
$MSG_PUTS_NOT_DECLARED_IN_SCOPE = "The puts function is called without being declared, and check whether the <stdio.h> or <cstdio> header file is imported.";
$MSG_GETS_NOT_DECLARED_IN_SCOPE = "The gets function is called without being declared, and check whether the <stdio.h> or <cstdio> header file is imported.";
$MSG_STRING_NOT_DECLARED_IN_SCOPE = "The string function is called without being declared, and check whether the <string.h> or <cstring> header file is imported.";
$MSG_NO_TYPE_IMPORT_IN_C_CPP = "Don't submit Java language programs as C/C++, please choose the language type before submitting.";
$MSG_ASM_UNDECLARED = "It is not allowed to embed assembly language code in C/C++.";
$MSG_REDEFINITION_OF = "The function or variable is repeatedly defined, and see if the code is pasted multiple times.";
$MSG_EXPECTED_DECLARATION_OR_STATEMENT = "The program does not seem to be finished. Check if the code is missing when copying and pasting.";
$MSG_UNUSED_VARIABLE = "Warning: Variables are not used after declaration. Check for spelling errors and misuse variables with similar names.";
$MSG_IMPLICIT_DECLARTION_OF_FUNCTION = "Function implicit declaration, check whether the correct header file is imported. Or the function with the specified name required by the title is missing.";
$MSG_ARGUMENTS_ERROR_IN_FUNCTION = "The number of parameters provided in the function call is incorrect. Check whether the wrong parameters are used.";
$MSG_EXPECTED_BEFORE_NAMESPACE = "Don't submit C++ language program as C, please choose the language type before submitting.";
$MSG_STRAY_PROGRAM = "Chinese spaces, punctuation, etc. cannot appear in the program other than comments and character strings. Please close the Chinese input method when writing a program. Please do not use the code copied from the Internet.";
$MSG_DIVISION_BY_ZERO = "Division by zero will cause a floating point overflow.";
$MSG_CANNOT_BE_USED_AS_A_FUNCTION = "Variables cannot be used as functions. Check for duplicates of variable and function names, or spelling errors.";
$MSG_CANNOT_FIND_TYPE = "The format description of scanf/printf is inconsistent with the following parameter list. Check whether there is more or less address character \\\"&\\\", or it may be a spelling error.";
$MSG_JAVA_CLASS_ERROR = "Java language submission can only have one public class, and the class name must be Main, please do not use public keywords for other classes.";
$MSG_EXPECTED_BRACKETS_TOKEN = "Missing closing brackets";
$MSG_NOT_FOUND_SYMBOL = "Use an undefined function or variable, check whether the spelling is wrong, do not use a non-existent function, Java call methods usually need to give the object name such as list1.add(...). Java method calls are sensitive to parameter types, such as: cannot pass an integer (int) to a method that accepts a string object (String).";
$MSG_NEED_CLASS_INTERFACE_ENUM = "Keyword is missing, it should be declared as class, interface or enum.";
$MSG_CLASS_SYMBOL_ERROR = "To use the examples on the textbook, you must submit the relevant code and remove the public keyword.";
$MSG_INVALID_METHOD_DECLARATION = "Only the method with the same class name is the constructor, and the return value type is not written. If you change the class name to Main, please also change the name of the constructor.";
$MSG_EXPECTED_AMPERSAND_TOKEN = "Don't submit C++ language program as C, please choose the language type before submitting.";
$MSG_DECLARED_FUNCTION_ORDER = "Please pay attention to the order of the declaration of functions and methods. The declaration of another method cannot appear in one method.";
$MSG_NEED_SEMICOLON = "The line marked above lacks a semicolon at the end.";
$MSG_EXTRA_TOKEN_AT_END_OF_INCLUDE = "The include statement must be on its own line and cannot be placed on the same line as the following statement";
$MSG_INT_HAS_NEXT = "hasNext() should be changed to nextInt()";
$MSG_UNTERMINATED_COMMENT = "The comment is not over, please check whether the ending character \\\"*/\\\" corresponding to \\\"/*\\\" is correct";
$MSG_EXPECTED_BRACES_TOKEN = "Function declaration lacks parentheses (), such as int main() written as int main";
$MSG_REACHED_END_OF_FILE_1 = "Check whether the submitted source code is not copied intact, or the closing brace is missing.";
$MSG_SUBSCRIPT_ERROR = "Cannot perform subscript access to non-array or pointer variables";
$MSG_EXPECTED_PERCENT_TOKEN = "The format part of scanf needs to be enclosed in double quotes";
$MSG_EXPECTED_EXPRESSION_TOKEN = "The parameter or expression is not finished";
$MSG_EXPECTED_BUT = "Wrong punctuation or symbols.";
$MSG_REDEFINITION_MAIN = "This question may be an additional code question. Please review the question again to see the meaning of the question. Do not submit the main function defined by the system, but submit a function in the specified format.";
$MSG_IOSTREAM_ERROR = "Please do not submit C++ programs as C.";
$MSG_EXPECTED_UNQUALIFIED_ID_TOKEN = "Pay attention to whether the semicolon is missing after the array declaration.";
$MSG_REACHED_END_OF_FILE_2 = "Missing braces at the end of the program.";
$MSG_INVALID_SYMBOL = "Check if Chinese punctuation or spaces are used.";
$MSG_DECLARED_FILE_NAMED = "The public class in OJ can only be Main.";
$MSG_EXPECTED_IDENTIFIER = "It may not have declare a variable name or missing parentheses when declaring a variable.";
$MSG_VARIABLY_MODIFIED = "Variables cannot be used for array size. Variables cannot be used as the dimension size of global arrays in C language, including const variables.";
$MSG_FUNCTION_GETS_REMOVIED = "The function std::gets is removed from C++14, use fgets to replace it. Or add #define gets(S) fgets(S,sizeof(S),stdin)";
$MSG_PROBLEM_USED_IN = "This problem is used in private contest";
$MSG_MAIL_CAN_ONLY_BETWEEN_TEACHER_AND_STUDENT = "Mails can only be sent between teachers and student, not between students.";
