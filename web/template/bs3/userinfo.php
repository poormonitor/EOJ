<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $OJ_NAME ?></title>
    <?php include("template/$OJ_TEMPLATE/css.php"); ?>



</head>

<body>

    <div class="container">
        <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
        <!-- Main component for a primary marketing message or call to action -->
        <div class="jumbotron" style='padding:20px;'>
            <div class='row'>
                <div class='col-md-4'>
                    <center>
                        <table class="table table-striped" id=statics width=70%>
                            <thead>
                                <tr>
                                    <th>
                                        <span style='font-size:140%;'>
                                            <?php echo htmlentities($nick, ENT_QUOTES, "UTF-8") ?>
                                        </span>
                                        <span>
                                            <?php echo $user; ?>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width=5%><?php echo $MSG_Number ?></td>
                                    <td width=30% align=center><?php echo $Rank ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $MSG_SOVLED ?>
                                    <td align=center><a href='status.php?user_id=<?php echo $user ?>&jresult=4'><?php echo $AC ?></td></a>

                                </tr>
                                <tr>
                                    <td><?php echo $MSG_SUBMIT ?></td>
                                    <td align=center><a href='status.php?user_id=<?php echo $user ?>'><?php echo $Submit ?></a></td>
                                </tr>
                                <?php
                                foreach ($view_userstat as $row) {
                                    //i++;
                                    echo "<tr ><td>" . $jresult[$row[0]] . "</td><td align=center><a href=status.php?user_id=$user&jresult=" . $row[0] . " >" . $row[1] . "</a></td></tr>";
                                }
                                //}
                                echo "<tr id=pie ><td>Statistics</td><td style='width:20%;height:150px;padding:0px;'><div id='container_pie' style='height:150px;width:100%;'></div></td></tr>";
                                ?>
                                <?php
                                if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
                                    echo "<tr ><td>$MSG_GROUP:<td align=center>$group_name</tr>";
                                }
                                ?>
                                <tr>
                                    <td>School:
                                    <td align=center><?php echo $school ?>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                </div>
                <div class='col-md-8'>
                    <center>
                        <table class="table table-striped" id='submission' width=70%>
                            <thead>
                                <tr>
                                    <th style='text-align:center; width:80%;'>已解决的问题</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td rowspan=14 align=center style='background-color:#fff;'>
                                        <script language='javascript'>
                                            function p(id, c) {
                                                document.write("<a href=problem.php?id=" + id + ">" + id + " </a>(<a href='status.php?user_id=<?php echo $user ?>&problem_id=" + id + "'>" + c + "</a>)");

                                            }
                                            <?php $sql = "SELECT `problem_id`,count(1) from solution where `user_id`=? and result=4 group by `problem_id` ORDER BY `problem_id` ASC";
                                            if ($result = pdo_query($sql, $user)) {
                                                foreach ($result as $row)
                                                    echo "p($row[0],$row[1]);";
                                            }

                                            ?>
                                        </script>
                                        <div id="container_status" style="height: 100%; margin: 0 auto"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                </div>
            </div>
            <center>
                <br>
                <?php
                if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
                ?><div class='table-responsive'>
                        <table class='table table-bordered table-condensed' style='width:auto;'>
                            <thead>
                                <tr class=toprow>
                                    <th style='text-align:center;'>&nbsp;UserID&nbsp;</th>
                                    <th style='text-align:center;'>&nbsp;Password&nbsp;</th>
                                    <th style='text-align:center;'>&nbsp;IP&nbsp;</th>
                                    <th style='text-align:center;'>&nbsp;Time&nbsp;</th>
                                    <th style='text-align:center;'>&nbsp;IP Info&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cnt = 0;
                                require_once("./include/iplocation.php");
                                $ip = new IpLocation();
                                foreach ($view_userinfo as $row) {
                                    if ($cnt)
                                        echo "<tr class='oddrow'>";
                                    else
                                        echo "<tr class='evenrow'>";
                                    for ($i = 0; $i < count($row) / 2; $i++) {
                                        echo "<td style='text-align:center;'>";
                                        echo "\t" . $row[$i];
                                        echo "</td>";
                                    }
                                    echo "<td style='text-align:center;'><a href='https://www.ipip.net/ip/$row[2].html'  target='view_window'>" . $ip->getlocation($row[2])["country"] . " " . $ip->getlocation($row[2])["area"] . "</a></td>";
                                    echo "</tr>";
                                    $cnt = 1 - $cnt;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
                ?>
            </center>
        </div>

    </div>
    <?php include("template/$OJ_TEMPLATE/js.php"); ?>
    </script>
    <script language="javascript" type="text/javascript" src="https://cdn.jsdelivr.net/npm/highcharts@9.1.2/highcharts.js"></script>
    <script language="JavaScript">
        $(document).ready(function() {
            var d1 = [];
            var d2 = [];
            <?php
            foreach ($chart_data_all as $k => $d) { ?>
                d1.push([<?php echo $k ?>, <?php echo $d ?>]);
            <?php } ?>
            <?php foreach ($chart_data_ac as $k => $d) { ?>
                d2.push([<?php echo $k ?>, <?php echo $d ?>]);
            <?php } ?>
            var chart = {
                backgroundColor: 'rgba(0,0,0,0)',
                type: 'spline'
            };
            var title = {
                text: 'Submission Information'
            };
            var subtitle = {
                text: 'Recent'
            };
            var xAxis = {
                type: 'datetime',
                dateTimeLabelFormats: { // don't display the dummy year
                    month: '%e. %b',
                    year: '%b'
                },
                title: {
                    text: 'Date'
                }
            };
            var yAxis = {
                title: {
                    text: 'Submit (Times)'
                },
                min: 0
            };
            var tooltip = {
                headerFormat: '<b>{series.name}</b><br>',
                pointFormat: '{point.x:%e. %b}: {point.y:.2f} times'
            };
            var plotOptions = {
                spline: {
                    marker: {
                        enabled: true
                    }
                }
            };

            var series = [{
                name: '<?php echo $MSG_SUBMIT ?>',
                data: d1
            }, {
                name: '<?php echo $MSG_SOVLED ?>',
                data: d2
            }];

            var json = {};
            json.chart = chart;
            json.title = title;
            json.subtitle = subtitle;
            json.tooltip = tooltip;
            json.xAxis = xAxis;
            json.yAxis = yAxis;
            json.series = series;
            json.plotOptions = plotOptions;
            $('#container_status').highcharts(json);

        });
    </script>


    <script language="JavaScript">
        $(document).ready(function() {
            var info = new Array();
            var dt = document.getElementById("statics");
            var data = dt.rows;
            var n;
            var m;
            for (var i = 4; dt.rows[i].id != "pie"; i++) {
                n = dt.rows[i].cells[0];
                n = n.innerText || n.textContent;
                m = dt.rows[i].cells[1].firstChild;
                m = m.innerText || m.textContent;
                m = parseInt(m);
                if (n == "<?php echo $MSG_AC ?>") {
                    info.push({
                        name: n,
                        y: m,
                        sliced: true,
                        selected: true,
                        color: "#5cb85c"
                    });
                } else {
                    info.push([n, m]);
                }
            }
            var chart = {
                backgroundColor: 'rgba(0,0,0,0)',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            };
            var title = {
                text: ''
            };
            var tooltip = {
                pointFormat: '<b>{point.percentage:.1f}%</b>'
            };
            var plotOptions = {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            };
            var series = [{
                type: 'pie',
                name: '',
                data: info
            }];

            var json = {};
            json.chart = chart;
            json.title = title;
            json.tooltip = tooltip;
            json.series = series;
            json.plotOptions = plotOptions;
            $('#container_pie').highcharts(json);
        });
    </script>
</body>

</html>