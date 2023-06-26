<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keyword" content="<?php echo str_replace(" ", "", $row['source']) ?>">
    <meta name="author" content="<?php echo $OJ_NAME ?>">
    <link rel="shortcut icon" href="/favicon.ico">

    <title>
        <?php echo $OJ_NAME ?>
    </title>

    <?php include("template/css.php"); ?>

    <link rel="stylesheet" href="<?php echo $OJ_CDN_URL . "katex/" ?>katex.min.css">
    <script defer src="<?php echo $OJ_CDN_URL . "katex/" ?>katex.min.js"></script>
    <script defer src="<?php echo $OJ_CDN_URL . "katex/" ?>contrib/auto-render.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            renderMathInElement(document.body, {
                // customised options
                // • auto-render specific keys, e.g.:
                delimiters: [{
                        left: '$$',
                        right: '$$',
                        display: true
                    },
                    {
                        left: '$',
                        right: '$',
                        display: false
                    },
                    {
                        left: '\\(',
                        right: '\\)',
                        display: false
                    },
                    {
                        left: '\\[',
                        right: '\\]',
                        display: true
                    }
                ],
                // • rendering keys, e.g.:
                throwOnError: false
            });
        });
    </script>
    <style>
        .jumbotron1 {
            font-size: 18px;
        }
    </style>
</head>


<body style="margin: 0;">
    <div class="container">
        <!-- Main component for a primary marketing message or call to action -->
        <!-- <div class="jumbotron"></div> -->
        <div class="panel panel-default panel-lg">
            <div class="panel-heading">
                <?php
                if ($pr_flag) {
                    echo "<title>$MSG_PROBLEM" . $row['problem_id'] . "--" . $row['title'] . "</title>";
                    echo "<center><h3>$id: " . $row['title'] . "</h3></center>";
                    echo "<div align=right><sub>[$MSG_Creator : <span id='creator'><a href='userinfo.php?user=" . htmlentities($creator, ENT_QUOTES, 'utf-8') . "'>" . htmlentities($creator, ENT_QUOTES, 'utf-8') . "</a></span>]</sub></div>";
                } else {
                    //$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $id = $row['problem_id'];
                    echo "<title>$MSG_PROBLEM " . $PID[$pid] . ": " . $row['title'] . " </title>";
                    echo "<center><h3>$MSG_PROBLEM " . $PID[$pid] . ": " . $row['title'] . "</h3><center>";
                    echo "<div align=right><sub>[$MSG_Creator : <span id='creator'><a href='userinfo.php?user=" . htmlentities($creator, ENT_QUOTES, 'utf-8') . "'>" . htmlentities($creator, ENT_QUOTES, 'utf-8') . "</a></span>]</sub></div>";
                }
                echo "<center>";
                echo "<span class=green>$MSG_Time_Limit : </span><span><span fd='time_limit' pid='" . $row['problem_id'] . "'  >" . $row['time_limit'] . "</span></span> sec&nbsp;&nbsp;";
                echo "<span class=green>$MSG_Memory_Limit : </span>" . $row['memory_limit'] . " MB";
                if ($row['spj']) echo "&nbsp;&nbsp;<span class=red>Special Judge</span>";
                echo "<br><br>";
                echo "</center>";
                # end of head
                echo "</div>";
                echo "<!--StartMarkForVirtualJudge-->";
                ?>
                <div class="panel-body with-footer">
                    <div class="row">
                        <div class="col-md-9">
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    <h4>
                                        <?php echo $MSG_Description ?>&nbsp;
                                        <a class='btn btn-xs btn-info' href="javascript:CopyToClipboard($('.describe').text())"><?php echo $MSG_COPY ?></a>
                                    </h4>
                                </div>
                                <div class='panel-body content'>
                                    <div class='describe'><?php echo $row['description'] ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                if ($row['input']) { ?>
                                    <div class="col-md-6">
                                        <div class='panel panel-default'>
                                            <div class='panel-heading'>
                                                <h4>
                                                    <?php echo $MSG_Input ?>
                                                </h4>
                                            </div>
                                            <div class='panel-body content'>
                                                <?php echo $row['input'] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                if ($row['output']) { ?>
                                    <div class="col-md-6">
                                        <div class='panel panel-default'>
                                            <div class='panel-heading'>
                                                <h4>
                                                    <?php echo $MSG_Output ?>
                                                </h4>
                                            </div>
                                            <div class='panel-body content'>
                                                <?php echo $row['output'] ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="row">
                                <?php
                                $sinput = str_replace("<", "<", $row['sample_input']);
                                $sinput = str_replace(">", ">", $sinput);
                                $soutput = str_replace("<", "<", $row['sample_output']);
                                $soutput = str_replace(">", ">", $soutput);
                                if (strlen($sinput)) { ?>
                                    <div class="col-md-6">
                                        <div class='panel panel-default'>
                                            <div class='panel-heading'>
                                                <h4>
                                                    <?php echo $MSG_Sample_Input ?>&nbsp;
                                                    <a class='btn btn-xs btn-info' href="javascript:CopyToClipboard($('#sampleinput').text())"><?php echo $MSG_COPY ?></a>
                                                </h4>
                                            </div>
                                            <div class='panel-body'>
                                                <pre class=content><span id="sampleinput" class=sampledata><?php echo $sinput ?></span></pre>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                if (strlen($soutput)) { ?>
                                    <div class="col-md-6">
                                        <div class='panel panel-default'>
                                            <div class='panel-heading'>
                                                <h4>
                                                    <?php echo $MSG_Sample_Output ?>&nbsp;
                                                    <a class='btn btn-xs btn-info' href="javascript:CopyToClipboard($('#sampleoutput').text())"><?php echo $MSG_COPY ?></a>
                                                </h4>
                                            </div>
                                            <div class='panel-body'>
                                                <pre class=content><span id='sampleoutput' class=sampledata><?php echo $soutput ?></span></pre>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } ?>
                            </div>
                            <?php
                            if ($row['hint']) { ?>
                                <div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        <h4>
                                            <?php echo $MSG_HINT ?>
                                        </h4>
                                    </div>
                                    <div class='panel-body content'>
                                        <?php echo $row['hint'] ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ($row['blank']) { ?>
                                <div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        <h4>
                                            <?php echo $MSG_BLANK_FILLING ?>
                                            <a class='btn btn-xs btn-info' href="javascript:CopyToClipboard($('.blank-code').text())"><?php echo $MSG_COPY; ?></a>
                                        </h4>
                                    </div>
                                    <div class='panel-body content' style='padding:10px;'>
                                        <pre id='code' class="blank-code" style='padding:15px!important;'><?php echo $blank; ?></pre>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">

                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    <h4>
                                        <?php echo $MSG_AB_KEYWORD ?>
                                    </h4>
                                </div>
                                <div class='panel-body content'>
                                    <?php if ($row['allow'] || $row['block']) { ?>
                                        <?php if ($row['block']) { ?>
                                            <div style='margin-top:10px;'><?php echo $MSG_BLOCK_KEYWORD ?>: <span class='label label-danger'><?php echo $block; ?></span></div>
                                        <?php }
                                        if ($row['allow']) { ?>
                                            <div style='margin-top:10px;'><?php echo $MSG_ALLOW_KEYWORD ?>: <span class='label label-success'><?php echo $allow; ?></span></div>
                                        <?php } ?>
                                    <?php } else {
                                        echo $MSG_EMPTY;
                                    } ?>
                                </div>
                            </div>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    <h4>
                                        <?php echo $MSG_SOURCE ?>
                                    </h4>
                                </div>

                                <div fd="source" style='word-wrap:break-word;' pid=<?php echo $row['problem_id'] ?> class='panel-body content'>
                                    <?php if ($row['source']) { ?>
                                        <?php
                                        $cats = explode(" ", $row['source']);
                                        foreach ($cats as $cat) {
                                            if ($cat == "") continue;
                                            $hash_num = hexdec(substr(md5($cat), 0, 7));
                                            $label_theme = $color_theme[$hash_num % count($color_theme)];
                                            if ($label_theme == "") $label_theme = "default";
                                            echo "<a class='label label-$label_theme' style='display: inline-block;' href='problemset.php?search=" . urlencode(htmlentities($cat, ENT_QUOTES, 'utf-8')) . "'>" . htmlentities($cat, ENT_QUOTES, 'utf-8') . "</a>&nbsp;";
                                        } ?>
                                    <?php } else {
                                        echo $MSG_EMPTY;
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var OJ_CDN = "<?php echo $OJ_CDN_URL ?>";
        var OJ_LANG = '<?php echo $OJ_LANG ?>';
    </script>
    <script src="<?php echo $OJ_CDN_URL . "template/" ?>jquery.min.js"></script>
    <script src="<?php echo $OJ_CDN_URL . "template/" ?>bootstrap.min.js"></script>
    <script src="<?php echo $OJ_CDN_URL . "template/" ?>index.min.js?v=1.32"></script>
    <script src="<?php echo $OJ_CDN_URL . "include/" ?>sweetalert.min.js"></script>
    <?php
    $endTime = microtime(true);
    $runTime = ($endTime - $startTime) * 1000 . ' ms';
    $prefix = isset($prefix) ? $prefix : "";
    ?>
    <script>
        $(document).ready(function() {
            $("#csrf").load("<?php echo $prefix; ?>csrf.php");
        });
        console.log("Loading used <?php echo $runTime; ?>.")
        console.log("Thanks for choosing <?php echo $OJ_NAME; ?>.");
    </script>
    <script src="<?php echo $OJ_CDN_URL . "include/" ?>simpleLightbox.min.js"></script>
    <script>
        $(".content").find("img").each(function(index, elem) {
            var atag = $("<a class='image'></a>")
            atag.attr("href", $(elem).attr("src"))
            $(elem).wrap(atag)
        })
        $(".image").simpleLightbox();
    </script>
    <?php if ($row["background"]) { ?>
        <style>
            @media (prefers-color-scheme: light) {
                body {
                    opacity: 0.9;
                    background: url("<?php echo $row["background"] ?>") no-repeat 50% 50% / cover;
                    background-attachment: fixed;
                }

                .footer-container {
                    background: rgba(255, 255, 255, 0.9);
                    margin: 18px 2rem 18px 2rem;
                    padding: 1em;
                    border-radius: 20px;
                }

                .panel {
                    background: rgba(255, 255, 255, 0);
                }

                .panel-body {
                    background: rgba(255, 255, 255, 1);
                }
            }
        </style>
    <?php } ?>
</body>

</html>