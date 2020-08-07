<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Date Range Laminator Chart</title>

    <link href="../../assets/styles.css" rel="stylesheet" />

    <style>

    #chart {
      max-width: 1300px;
      margin: 70px auto;
    }

    </style>

    <script>
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>'
        )
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>'
        )
      window.Promise ||
        document.write(
          '<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>'
        )
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  </head>

  <body>
     <div id="chart"></div>
    <?php
      require_once('sql_task_manager.php');
      $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
      $T1 = $_REQUEST['LOWER_DATE']; $T2 = $_REQUEST['UPPER_DATE']; $T1_year = explode('/', $T1)[2]; $T2_year = explode('/', $T2)[2];
      $THICKNESS = $_REQUEST['THICKNESS']; $Label_State = $_REQUEST['LABEL'];
      # set the min and max average thickness to show better visualization
      $Min_THICKNESS = $THICKNESS === "150" ?  295 : 190;
      $Max_THICKNESS = $THICKNESS === "150" ?  335 : 230;
      echo $Min_THICKNESS; echo $Max_THICKNESS;
      # besides specific date range condition and thickness specification
      $sql_daily_lam = "SELECT AVG_THICKNESS, ELECTRODE_SERIAL FROM LAMINATOR WHERE LAM_DATE >= ? AND LAM_DATE <= ? AND RIGHT(LAM_DATE, 4) >= ? AND RIGHT(LAM_DATE, 4) <= ?
      AND THICKNESS = ? AND AVG_THICKNESS >= ".$Min_THICKNESS." AND AVG_THICKNESS <= ".$Max_THICKNESS;
      $sql_task_manager->pdo_sql_vali_execute($sql_daily_lam, array($T1, $T2, $T1_year, $T2_year, $THICKNESS));
      $sql_arr = $sql_task_manager->rows_fetch(array('AVG_THICKNESS', 'ELECTRODE_SERIAL'));
    ?>
    <script>
      function getcol(matrix, col){
        var column = [];
        for(var i=0; i<matrix.length; i++){
          column.push(matrix[i][col]);
        }
        return column;
      }
    </script>
    <script>
        var sql_arr = <?php echo json_encode($sql_arr) ?>;
        var state = <?php echo $Label_State ?>;
        var data_ls = getcol(sql_arr, 0);
        var batch_ls = getcol(sql_arr, 1);
        var options = {
          series: [{
            name: "Laminator Avg Thickness",
            data: data_ls
        }],
          chart: {
          height: 700,
          type: 'line',
          zoom: {
            enabled: true
          }
        },
        annotations: {
          yaxis: [{
            y: 205,
            y2: 215,
            borderColor: '#000',
            fillColor: '#FEB019',
            opacity: 0.2,
            label: {
              borderColor: '#333',
              style: {
                fontSize: '10px',
                color: '#333',
                background: '#FEB019',
              },
              text: '',
            }
          }, {
            y: 310,
            y2: 320,
            borderColor: '#000',
            fillColor: '#FEB019',
            opacity: 0.2,
            label: {
              borderColor: '#333',
              style: {
                fontSize: '10px',
                color: '#333',
                background: '#FEB019',
              },
              text: '',
            }
          }]
        },
        dataLabels: {
          enabled: state
        },
        stroke: {
          curve: 'straight'
        },
        title: {
          text: 'Date Range Laminator Average Thickness Trend',
          align: 'center'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: batch_ls,
        }
      };

      var chart = new ApexCharts(document.querySelector("#chart"), options);

      chart.render();
    </script>
  </body>
</html>
