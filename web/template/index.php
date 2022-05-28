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

   <div class="container">
      <?php include("template/nav.php"); ?>
      <div class="jumbotron">
         <p>
            <center> <?php echo $MSG_RECENT_SUBMISSION ?> :
               <?php echo $speed ?> .
               <div id="container" style="height:300px;margin:2em auto 3em;"></div>
            </center>
         </p>
         <?php echo $view_news ?>
         <br>
      </div>

   </div>
   <?php include("template/js.php"); ?>
   <script src="<?php echo $OJ_CDN_URL . "template/" ?>echarts.min.js"></script>
   <script>
      var statusChart = echarts.init(document.getElementById('container'));
      var option = {
         title: {
            text: "<?php echo $MSG_RECENT_SUBMISSION ?>",
         },
         legend: [{
            data: ['<?php echo $MSG_TOTAL ?>', '<?php echo $MSG_ACCEPTED ?>']
         }],
         grid: {
            left: '1%',
            right: '1%',
            bottom: '1%',
            containLabel: true
         },
         tooltip: {
            trigger: 'axis',
            formatter: function(params) {
               var text = '--'
               if (params && params.length) {
                  text = params[0].data[0]
                  params.forEach(item => {
                     var dotHtml = item.marker
                     text += `<div style='text-align:left'>${dotHtml}${item.seriesName} : ${item.data[1] ? item.data[1] : '-'}</div>`
                  })
               }
               return text
            }
         },
         xAxis: {
            type: 'time',
         },
         yAxis: {
            type: 'value'
         },
         textStyle: {
            fontFamily: "SourceHanSansCN-Medium"
         },
         series: [{
            data: <?php echo json_encode($chart_data_all) ?>,
            type: 'line',
            name: '<?php echo $MSG_TOTAL ?>',
            color: '#4B4B4B',
            smooth: true
         }, {
            data: <?php echo json_encode($chart_data_ac) ?>,
            type: 'line',
            name: '<?php echo $MSG_ACCEPTED ?>',
            color: '#22D35E',
            smooth: true
         }]
      };
      statusChart.setOption(option);
      window.onresize = function() {
         statusChart.resize();
      };
   </script>
</body>

</html>