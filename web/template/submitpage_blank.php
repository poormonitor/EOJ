<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="<?php echo $OJ_NAME ?>">
    <link rel="shortcut icon" href="/favicon.ico">

    <title><?php echo $OJ_NAME ?></title>
    <?php include("template/css.php"); ?>

</head>

<body>
    <div class="modal micromodal-slide" id="modal-1" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <main class="modal__content" id="modal-1-content">
                    <?php if (isset($id)) { ?>
                        <iframe class="modal-iframe" src="problem.php?simple&id=<?php echo $id ?>"></iframe>
                    <?php } else { ?>
                        <iframe class="modal-iframe" src="problem.php?simple&cid=<?php echo $cid ?>&pid=<?php echo $pid ?>"></iframe>
                    <?php } ?>
                </main>
            </div>
        </div>
    </div>
    <div class="container">
        <?php include("template/nav.php"); ?>
        <!-- Main component for a primary marketing message or call to action -->
        <div class="jumbotron">
            <center>
                <form id=frmSolution action="submit.php" method="post">
                    <?php if (isset($id)) { ?>
                        <div class="fs-2">
                            <div>
                                <?php echo $MSG_PROBLEM_ID ?> :
                                <a href="javascript:MicroModal.show('modal-1');" class=blue>
                                    <?php echo $id ?>
                                </a>
                            </div>
                        </div>
                        <br>

                        <input id=problem_id type='hidden' value='<?php echo $id ?>' name="id">
                    <?php } else {
                        //$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                        //if ($pid>25) $pid=25;
                    ?>
                        <div style="font-size: 1.8rem">
                            <div>
                                <?php echo $MSG_PROBLEM_ID ?> :
                                <a href="javascript:MicroModal.show('modal-1');" class=blue>
                                    <?php echo chr($pid + ord('A')) ?>
                                </a>
                            </div>
                            <div>
                                <?php echo $MSG_CONTEST_ID ?> : <a href="contest.php?cid=<?php echo $cid ?>" class=blue> <?php echo $cid ?> </a>
                            </div>
                        </div>
                        <br>

                        <input id="cid" type='hidden' value='<?php echo $cid ?>' name="cid">
                        <input id="pid" type='hidden' value='<?php echo $pid ?>' name="pid">
                    <?php } ?>

                    <span class='form-inline' id="language_span"><?php echo $MSG_LANG ?>&nbsp;:
                        <select class='form-control' id="language" name="language" onChange="reloadtemplate($(this).val());">
                            <?php
                            $lang_count = count($language_ext);

                            if (isset($_GET['langmask']))
                                $langmask = $_GET['langmask'];
                            else
                                $langmask = $OJ_LANGMASK;

                            $lang = (~((int)$langmask)) & ((1 << ($lang_count)) - 1);

                            if (isset($_COOKIE['lastlang'])) $lastlang = $_COOKIE['lastlang'];
                            else $lastlang = 6;

                            for ($i = 0; $i < $lang_count; $i++) {
                                if ($lang & (1 << $i))
                                    echo "<option value=$i " . ($lastlang == $i ? "selected" : "") . ">" . $language_name[$i] . "</option>";
                            }
                            ?>
                        </select>
                    </span>

                    <br></br>
                    <pre id='copy' style='display:none;'><?php echo $copy; ?></pre>
                    <pre id='copy-blank' style='display:none;'><?php echo htmlentities($blank, ENT_QUOTES, "UTF-8"); ?></pre>
                    <div class='btn-group'>
                        <a class='btn btn-sm btn-info' href='javascript:CopyToClipboard($("#copy").text())'><?php echo $MSG_COPY ?></a>
                        <a class='btn btn-sm btn-info' href='javascript:CopyToClipboard(get_full_code())'><?php echo $MSG_COPY_CURRENT ?></a>
                        <a class='btn btn-sm btn-info' href='<?php echo $_SERVER['REQUEST_URI']; ?>&blank=false'><?php echo $MSG_WRITE_DIRECTLY ?></a>
                    </div>

                    <div id='container_status'>
                        <pre id='code' class='alert alert-error form-inline' style='text-align:left;'><?php echo $code; ?></pre>
                    </div>

                    <?php if (isset($OJ_TEST_RUN) && $OJ_TEST_RUN) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="test_run_checkbox">
                                <span><?php echo $MSG_TEST_RUN ?></span>
                            </label>
                        </div>
                        <div id='test_run' class='form-group' style='margin-bottom:10px;width:80%;'>
                            <div class='row'>
                                <div class='col-sm-4'>
                                    <textarea id="input_text" class='form-control' style='width:100%;resize:none;' rows=5 name="input_text"><?php echo $view_sample_input ?></textarea>
                                </div>
                                <div class='col-sm-4'>
                                    <textarea id="s_out" name="out" class='form-control' style='width:100%;resize:none;' rows=5 disabled="true"><?php echo $view_sample_output ?></textarea>
                                </div>
                                <div class='col-sm-4'>
                                    <textarea id="out" name="out" class='form-control' style='width:100%;resize:none;' rows=5 disabled="true"></textarea>
                                </div>
                            </div>
                            <div class='row' style='margin-top:10px;'>
                                <div class='col-sm-4 col-sm-offset-4'>
                                    <input id="TestRun" class="btn btn-info btn-sm" type=button value="<?php echo $MSG_TR ?>" onclick=do_test_run();>
                                    <span class="label label-info m-3" id=result><?php echo $MSG_STATUS ?></span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <button id="Submit" type="button" class="btn btn-info btn-sm m-2" onclick="doBlankSubmit()"><?php echo $MSG_SUBMIT; ?></button>
                </form>
                <br>
            </center>
        </div>
    </div>
    <?php include("template/js.php"); ?>

    <script>
        <?php if (isset($OJ_TEST_RUN) && $OJ_TEST_RUN) { ?>
            $('#TestRun').hide()
            $('#result').hide()
            $('#test_run').hide()
            $('#test_run_checkbox').prop("checked", false)
            $('#vcode_input').val("")

            $("#test_run_checkbox").click(function() {
                $('#TestRun').fadeToggle()
                $('#result').fadeToggle()
                $('#test_run').fadeToggle()
                $('#test_run_btn').toggleClass("height");
                $('#test_run_btn').toggleClass("margin");
            });
        <?php } ?>

        var mark = "<?php echo isset($id) ? 'problem_id' : 'cid'; ?>";

        function resume() {
            count--;
            var s = $("#Submit")[0];
            var t = $("#TestRun")[0];
            if (count < 0) {
                s.disabled = false;
                if (t != null) t.disabled = false;
                s.value = "<?php echo $MSG_SUBMIT ?>";
                if (t != null) t.value = "<?php echo $MSG_TR ?>";
                if (handler_interval) window.clearInterval(handler_interval);
                if ($("#vcode") != null) $("#vcode").click();
            } else {
                s.value = "<?php echo $MSG_SUBMIT ?>(" + count + ")";
                if (t != null) t.value = "<?php echo $MSG_TR ?>(" + count + ")";
                window.setTimeout("resume();", 1000);
            }
        }

        function reloadtemplate(lang) {
            console.log("lang=" + lang);
            document.cookie = "lastlang=" + lang;
            //swal(document.cookie);
            var url = window.location.href;
            var i = url.indexOf("sid=");
            if (i != -1) url = url.substring(0, i - 1);

            <?php if (isset($OJ_APPENDCODE) && $OJ_APPENDCODE) { ?>
                if (confirm("<?php echo  $MSG_LOAD_TEMPLATE_CONFIRM ?>"))
                    document.location.href = url;
            <?php } ?>
            switchLangs(lang);
        }

        function doBlankSubmit() {
            var codeData = $("#frmSolution").serializeArray();
            for (var i = 0; i < codeData.length; i++) {
                if (/((code|multiline)[0-9]?)|(source)/.test(codeData[i].name)) {
                    codeData[i].value = encode64(utf16to8(codeData[i].value))
                }
            }
            $.post("submit.php?ajax", $.param(codeData), function(data, textStatus, request) {
                if (/status\.php\?.*/.test(data)) {
                    window.location.href = data;
                } else {
                    swal("<?php echo $MSG_ERROR ?>", data, "error")
                }
            }, );
        }

        var sid = 0;
        var i = 0;
        var judge_result = [<?php
                            foreach ($judge_result as $result) {
                                echo "'$result',";
                            } ?> ''];
    </script>

    <script language="javascript" type="text/javascript" src="<?php echo $OJ_CDN_URL ?>include/base64.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $OJ_CDN_URL ?>template/micromodal.min.js"></script>
    <script>
        MicroModal.init();
    </script>
    <script src="<?php echo $OJ_CDN_URL . "monaco/min/vs/" ?>loader.js"></script>
    <script>
        require.config({
            paths: {
                vs: 'monaco/min/vs'
            }
        });

        window.editors = [];

        var CodeContent = [
            <?php if (isset($multi_line_matches)) {
                foreach ($multi_line_matches as $line) { ?> `<?php echo $line[0] ?>`,
            <?php }
            } ?>
        ];

        require(['vs/editor/editor.main'], function() {
            $("div[id^='source']").each(function(index, elem) {
                window.editors[index] = monaco.editor.create(document.getElementById("source" + index), {
                    value: CodeContent[index],
                    language: 'plain',
                    fontSize: "18px",
                    lineNumbers: 'off',
                    glyphMargin: false,
                    folding: false,
                    lineDecorationsWidth: 0,
                    lineNumbersMinChars: 0,
                    scrollbar: {
                        alwaysConsumeMouseWheel: false
                    }
                });

                window.editors[index].getModel().onDidChangeContent((event) => {
                    $("textarea[name=multiline" + index + "]").val(window.editors[index].getValue())
                });
            })

            switchLangs(<?php echo isset($lastlang) ? $lastlang : 6;  ?>);
        });

        window.onresize = function() {
            for (var i = 0; i < window.editors.length; i++) {
                window.editors[i].layout();
            }
        }
    </script>

    <?php if ($OJ_VCODE) { ?>
        <script>
            $(document).ready(function() {
                $("#vcode").attr("src", "vcode.php?small=true&" + Math.random());
            })
        </script>
    <?php } ?>
    <?php if (isset($background_url)) { ?>
        <style>
            @media (prefers-color-scheme: light) {
                body {
                    opacity: 0.95;
                    background: url("<?php echo $background_url ?>") no-repeat 50% 50% / cover;
                    background-attachment: fixed;
                }

                .footer-container {
                    background: rgba(255, 255, 255, 0.9);
                    margin: 18px 2rem 18px 2rem;
                    padding: 1em;
                    border-radius: 20px;
                }

                .container .jumbotron {
                    background-color: rgba(255, 255, 255, 0.9);
                }
            }
        </style>
    <?php } ?>
</body>

</html>