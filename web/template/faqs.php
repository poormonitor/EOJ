<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/favicon.ico">

    <title>
        <?php echo $OJ_NAME ?>
    </title>
    <?php include("template/css.php"); ?>



</head>

<body>
    <style>
        p {
            font-size: 16px !important;
        }

        h2 {
            font-size: 24px !important;
        }

        div.ques-view li {
            margin-left: 20px;
            font-size: 16px;
        }
    </style>
    <div class="container">
        <?php include("template/nav.php"); ?>
        <div class="jumbotron">
            <div class='main-container'>
            <h2 class="ui center aligned header" style="font-size:36px!important;">Help</h2>
                <br>
                <h2 class="ui header">Judging</h2>
                <p>
                    Python is compiled and run using <code><?php echo $OJ_PYTHON_VER ?></code>, <br>
                    The compilation command is <code>python3 -m py_compile Main.py</code>, <br>
                    The running command is <code>python3 Main.py</code>. <br>
                    The compiler version given here is for reference only, please refer to the actual compiler version.
                </p>
                <p>
                    Please use <strong>STDIN and STDOUT</strong> to read in and out. <br>
                    Alternatively, you can read in from the data.in file and output to the user.out file. <br>
                    <strong>In Python, the prompt field in the input is output and considered part of the answer. </strong>
                </p>
                <br>
                <h2 class="ui header">Plagiarize</h2>
                <p>
                    We use Dick Grune's <code>SIM</code> software for code similarity comparisons. Based on token comparison, the software can effectively identify code changes such as modifying variable names and adding comments.
                </p>
                <p>
                    Duplicate submissions by the same user are not duplicated as code.
                    <br>200% similarity means that both the token-based comparison and the text comparison return 100%, that is, the two codes are identical.
                </p>
                <br>
                <h2 class="ui header">Profile</h2>
                <p>Please read the <a href='/policy.html'>EULA and Privacy Policy</a> carefully before using the service, which stipulates how we collect and use your information.
                    <br>We store and use your private data in accordance with GB/T 35273-2020 "Information Security Technology Personal Information Security Specification".
                </p>
                <br>
                <h2 class="ui header">Return result description</h2>
                <div class="ques-view">
                    <p>After the answer to the test question is submitted, the scoring system will give an instant score, and the judgment result will be notified in time for each submission; the possible feedback information of the system includes:</p>
                    <li>Waiting for evaluation: The evaluation system has not yet evaluated this submission, please wait.</li>
                    <li>Evaluating: The evaluation system is evaluating, and there will be results later</li>
                    <li>Compilation error: The code you submitted cannot be compiled, click "Compile Error" to see the error message output by the compiler</li>
                    <li>Correct answer: Congratulations! You passed this question</li>
                    <li>Format error: the format of your program output does not meet the requirements (such as spaces and newlines are inconsistent with the requirements)</li>
                    <li>Incorrect answer: Your program failed to return correct results for the data from the profiling system</li>
                    <li>Run Timeout: Your program failed to finish running within the specified time</li>
                    <li>Memory limit exceeded: Your program is using more memory than the limit</li>
                    <li>Running error: Your program crashed during operation, such as segmentation fault, floating point error, etc.</li>
                    <li>Exceeded output limit: Your program outputs too much content, which may be the result of infinite loop output.</li>
                </div>
                <h3>Thank you for choosing <?php echo $OJ_NAME ?>. </h3>
            </div>
        </div>
    </div>
    <?php include("template/js.php"); ?>

    </script>
</body>

</html>