<div class="panel panel-default panel-lg pb-5">
    <div class="panel-heading">
        <?php
        if (isset($cid)) {
            $problem_id = $problem_info['problem_id'];
            echo "<center><h3>$MSG_PROBLEM " . $PID[$pid] . ": " . $problem_info['title'] . "</h3></center>";
        } else {
            echo "<center><h3>$id: " . $problem_info['title'] . "</h3></center>";
        }
        echo "<center>";
        echo "<span class=green>$MSG_Time_Limit : </span><span><span fd='time_limit' pid='" . $problem_info['problem_id'] . "'  >" . $problem_info['time_limit'] . "</span></span> sec&nbsp;&nbsp;";
        echo "<span class=green>$MSG_Memory_Limit : </span>" . $problem_info['memory_limit'] . " MB";
        if ($problem_info['spj']) echo "&nbsp;&nbsp;<span class=red>Special Judge</span>";
        echo "<br><br>";
        echo "</center>";
        # end of head
        echo "</div>";
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
                            <div class='describe'><?php echo $problem_info['description'] ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        if ($problem_info['input']) { ?>
                            <div class="col-md-6">
                                <div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        <h4>
                                            <?php echo $MSG_Input ?>
                                        </h4>
                                    </div>
                                    <div class='panel-body content'>
                                        <?php echo $problem_info['input'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        if ($problem_info['output']) { ?>
                            <div class="col-md-6">
                                <div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        <h4>
                                            <?php echo $MSG_Output ?>
                                        </h4>
                                    </div>
                                    <div class='panel-body content'>
                                        <?php echo $problem_info['output'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <?php
                        $sinput = str_replace("<", "<", $problem_info['sample_input']);
                        $sinput = str_replace(">", ">", $sinput);
                        $soutput = str_replace("<", "<", $problem_info['sample_output']);
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
                    if ($problem_info['hint']) { ?>
                        <div class='panel panel-default'>
                            <div class='panel-heading'>
                                <h4>
                                    <?php echo $MSG_HINT ?>
                                </h4>
                            </div>
                            <div class='panel-body content'>
                                <?php echo $problem_info['hint'] ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($problem_info['blank']) { ?>
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
                            <?php if ($problem_info['allow'] || $problem_info['block']) { ?>
                                <?php if ($problem_info['block']) { ?>
                                    <div style='margin-top:10px;'><?php echo $MSG_BLOCK_KEYWORD ?>: <span class='label label-danger'><?php echo $block; ?></span></div>
                                <?php }
                                if ($problem_info['allow']) { ?>
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

                        <div fd="source" style='word-wrap:break-word;' pid=<?php echo $problem_info['problem_id'] ?> class='panel-body content'>
                            <?php if ($problem_info['source']) { ?>
                                <?php
                                $cats = explode(" ", $problem_info['source']);
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