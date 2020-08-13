<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Date Range Film Chart</title>

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
      $T1 = date('Y-m-d', strtotime($_REQUEST['LOWER_DATE'])); $T2 = date('Y-m-d', strtotime($_REQUEST['UPPER_DATE']));
      $THICKNESS = $_REQUEST['THICKNESS']; $Label_State = $_REQUEST['LABEL'];
      # besides specific date range condition and thickness specification, +/-6 for the spec to determine the displayed range
      $sql_daily_film = "SELECT AVG_THICKNESS, FILM_ID FROM FILM WHERE DATE >= ? AND DATE <= ?  AND THICKNESS = ? AND AVG_THICKNESS >= ?-4  AND AVG_THICKNESS <= ?+12";
      $sql_task_manager->pdo_sql_vali_execute($sql_daily_film, array($T1, $T2, $THICKNESS, $THICKNESS, $THICKNESS));
      $sql_arr = $sql_task_manager->rows_fetch(array('AVG_THICKNESS', 'FILM_ID'));
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
            name: "Film Avg Thickness",
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
            y: 92,
            y2: 96,
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
            y: 152,
            y2: 156,
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
          text: 'Date Range Film Average Thickness Trend',
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
