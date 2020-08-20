<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Date Range Battery Chart</title>

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
      $THICKNESS = $_REQUEST['TYPE']; $Label_State = $_REQUEST['LABEL']; $min_limit = 0;
      # set the min and max average thickness to show better visualization
      if ($_REQUEST['VALUE_TYPE'] === "RESISTIVITY") {
        $max_limit = 2;
        # besides specific date range condition and thickness specification
        $sql_resistance = "SELECT AVG_RESISTIVITY, LOT FROM RESISTANCE WHERE LOT_DATE >= ? AND LOT_DATE <= ?";
        $sql_task_manager->pdo_sql_vali_execute($sql_resistance, array($T1, $T2));
        $sql_arr = $sql_task_manager->rows_fetch(array('AVG_RESISTIVITY', 'LOT'));
        $title = $_REQUEST['MODE'] == "CUSTOMER" ? "Date Range Composite Resistivity Trend Under Customer Spec" : "Date Range Composite Resistivity Trend Under Internal Spec";
      } else {
        if ($THICKNESS === '150') {
          $max_limit = $_REQUEST['MODE'] == "CUSTOMER" ? 0.1 : 0.05;
        } else {
          $max_limit = $_REQUEST['MODE'] == "CUSTOMER" ? 0.04 : 0.02;
        }
        $title =  $_REQUEST['MODE'] == "CUSTOMER" ? "Date Range Interface Resistance Trend Under Customer Spec" : "Date Range Interface Resistance Trend Under Internal Spec";
        $sql_resistance = "SELECT AVG_RESISTANCE, LOT FROM RESISTANCE WHERE LOT_DATE >= ? AND LOT_DATE <= ?";
        $sql_task_manager->pdo_sql_vali_execute($sql_resistance, array($T1, $T2));
        $sql_arr = $sql_task_manager->rows_fetch(array('AVG_RESISTANCE', 'LOT'));
      }
    ?>
    <script>
      function getcol(matrix, col, parse_indicator){
        var column = [];
        for(var i=0; i<matrix.length; i++){
          if (parse_indicator === 1) column.push(parseInt(matrix[i][col]));
          else column.push(matrix[i][col]);
        }
        return column;
      }
    </script>
    <script>
        var sql_arr = <?php echo json_encode($sql_arr) ?>;
        var state = <?php echo $Label_State ?>;
        var max_limit = <?php echo $max_limit ?>;
        var data_ls = getcol(sql_arr, 0, 0);
        var batch_ls = getcol(sql_arr, 1, 0);
        var title = <?php echo json_encode($title) ?>;
        var options = {
          series: [{
            name: "Battery Spec Chart",
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
            y: 0,
            y2: max_limit,
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
          text: title,
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
