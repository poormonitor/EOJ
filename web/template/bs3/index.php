<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="author" content="">
   <link rel="icon" href="../../favicon.ico">

   <title>
      <?php echo $OJ_NAME ?>
   </title>
   <?php include("template/$OJ_TEMPLATE/css.php"); ?>

</head>

<body>

   <div class="container">
      <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
      <div class="jumbotron">
         <p>
            <center> Recent submission :
               <?php echo $speed ?> .
               <div id="container" style="height: 400px; margin: 0 auto"></div>
            </center>
         </p>
         <?php echo $view_news ?>
         <br>
      </div>

   </div>
   <?php include("template/$OJ_TEMPLATE/js.php"); ?>
   <script language="javascript" type="text/javascript" src="https://cdn.jsdelivr.net/npm/highcharts@9.1.2/highcharts.js"></script>
   <script language="JavaScript">
      $(document).ready(function() {
         var chart = {
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
               text: ''
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
            data: <?php echo json_encode($chart_data_all) ?>
         }, {
            name: '<?php echo $MSG_SOVLED ?>',
            data: <?php echo json_encode($chart_data_ac) ?>
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
         $('#container').highcharts(json);

      });
   </script>
</body>

</html>