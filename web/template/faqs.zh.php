<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="<?php echo $OJ_NAME?>">
    <link rel="shortcut icon" href="/favicon.ico">

    <title>
        帮助 - <?php echo $OJ_NAME ?>
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
                <h2 class="ui center aligned header" style="font-size:36px!important;">帮助</h2>
                <br>
                <h2 class="ui header">评测</h2>
                <p>
                    Python 使用 <code><?php echo $OJ_PYTHON_VER ?></code> 编译和运行，<br>
                    编译命令为<code>python3 -m py_compile Main.py</code>，<br>
                    运行命令为<code>python3 Main.py</code>。<br>
                    这里给出的编译器版本仅供参考，请以实际编译器版本为准。
                </p>
                <p>
                    请使用<strong>标准输入输出</strong>读入和输出。<br>
                    或者，您可以从data.in文件中读入，并输出到user.out文件中。<br>
                    <strong>在Python中，input中的prompt字段会被输出并被认定为答案的一部分。</strong>
                </p>
                <br>
                <h2 class="ui header">查重</h2>
                <p>
                    我们使用 Dick Grune 的 <code>SIM</code> 软件进行代码相似度比较。该软件基于token比较，可有效鉴别修改变量名、增加注释等代码变动。
                </p>
                <p>
                    同一用户的重复提交并不会作为代码重复。
                    <br>200% 相似度意味着基于token的比较和文本比较同时返回100%，即两份代码一模一样。
                </p>
                <br>
                <?php if (file_exists("./policy.html")) { ?>
                <h2 class="ui header">个人资料</h2>
                <p>请您在使用服务前，仔细阅读<a href='/policy.html'>使用条款和用户隐私协议</a>，这规定了我们如何收集和使用您的信息。
                    <br>我们依据 GB/T 35273-2020 《信息安全技术 个人信息安全规范》存储、使用您的隐私数据。
                </p>
                <br>
                <?php } ?>
                <h2 class="ui header">返回结果说明</h2>
                <div class="ques-view">
                    <p>试题的解答提交后由评分系统评出即时得分，每一次提交会判决结果会及时通知；系统可能的反馈信息包括：</p>
                    <li>等待评测：评测系统还没有评测到这个提交，请稍候</li>
                    <li>正在评测：评测系统正在评测，稍候会有结果</li>
                    <li>编译错误：您提交的代码无法完成编译，点击“编译错误”可以看到编译器输出的错误信息</li>
                    <li>答案正确：恭喜！您通过了这道题</li>
                    <li>格式错误：您的程序输出的格式不符合要求（比如空格和换行与要求不一致）</li>
                    <li>答案错误：您的程序未能对评测系统的数据返回正确的结果</li>
                    <li>运行超时：您的程序未能在规定时间内运行结束</li>
                    <li>内存超限：您的程序使用了超过限制的内存</li>
                    <li>运行错误：您的程序在运行过程中崩溃了，发生了如段错误、浮点错误等</li>
                    <li>输出超限：您的程序输出了过多内容，一般可能是无限循环输出导致的结果</li>
                </div>
                <h3>感谢您选择 <?php echo $OJ_NAME ?>。</h3>
            </div>
        </div>
    </div>
    <?php include("template/js.php"); ?>

    </script>
</body>

</html>