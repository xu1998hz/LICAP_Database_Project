<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Baghouse ENTRY PAGE</title>
</head>
 <body>
   <?php
    require_once('sql_task_manager.php');
    $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
    # pull out the ID based on last non-null value of baghouse column in table blend
    $sql_cmd = "SELECT ID, BAGHOUSE_WEIGHT FROM blend WHERE BAGHOUSE_WEIGHT IS NOT NULL ORDER BY ID DESC LIMIT 1";
    $row_vals = $sql_task_manager->pdo_sql_row_fetch($sql_cmd);
    $ID_Val = $row_vals['ID']; $Last_Weight = $row_vals['BAGHOUSE_WEIGHT'];
    $sql_batch = "SELECT BATCH_NUM FROM blend WHERE ID = '$ID_Val' + 1";
    echo "<H1 style='text-align:center'>BagHouse Page</H1>";
    echo "<h2 style='text-align:center'>"."Current Batch Number: ".$sql_task_manager->pdo_sql_row_fetch($sql_batch)['BATCH_NUM']."</h2>";
    $Cur_Date = date("m/d/Y");
    $sql_real_time = "SELECT BATCH_NUM, MIXING_OP, BAGHOUSE_WEIGHT FROM blend WHERE DATE = '$Cur_Date' ORDER BY ID DESC";
    $sql_results = $sql_task_manager->pdo_sql_rows_fetch($sql_real_time, array('BATCH_NUM', 'MIXING_OP', 'BAGHOUSE_WEIGHT'));
   ?>

   <form action="Baghouse.php" method="post">
     <p style="text-align:center">
       <label for="BAGHOUSE_WEIGHT">BagHouse Weight</label>
       <input type="text" name="BAGHOUSE_WEIGHT" value="<?php echo htmlentities($Last_Weight); ?>"/>
       <div style="text-align:center">
       <input type="submit" value="Submit">
       </div>
     </p>
     <hr>
   </form>

   <script type="text/javascript">
     var sql_arr = <?php echo json_encode($sql_results) ?>;
     document.open();
     document.write("<h1 style='text-align:center'>Baghouse records display Table</h1>");
     document.write("<h2 style='text-align:center'>This table will display the daily baghouse information</h2>");
     document.write("<table style='width:100%'>");
     document.write("<tr> <th>Batch Number</th> <th>Mix Operator</th> <th>Baghouse Weight</th> </tr>");
     for (i=0; i<sql_arr.length; i++) {
       var line_str = "<tr>";
       for (j=0; j<sql_arr[i].length; j++) {
         line_str+="<td style='text-align:center'>";
         line_str+=sql_arr[i][j];
         line_str+="</td>";
       }
       line_str += "</tr>";
       document.write(line_str);
     }
     document.write("</table>");
     document.close();
   </script>

   <?php
    if (count($_REQUEST)!==0) {
      $sql_update = "UPDATE blend SET DATE = ?, TIMESTAMP = ?, BAGHOUSE_WEIGHT = ? WHERE ID = ? + 1";
      $result_arr = $sql_task_manager->pdo_sql_vali_execute($sql_update, array(date("m/d/Y"), date("m/d/Y-H:i:s"), $_REQUEST['BAGHOUSE_WEIGHT'], $ID_Val));
      if ($result_arr[1]) {
        echo "<h3 style='text-align:center'>"."Records updated successfully!"."</h3>";
        header("refresh: 1");
      } elseif (!$result_arr[0]) {
        echo "<h3 style='text-align:center'>"."Internal Error! Contact IT Department for further helps"."</h3>";
      } else {
        echo "<h3 style='text-align:center'>"."All bag weights in the database have been updated!"."</h3>";
        header("refresh: 1");
      }
    }
   ?>
 </body>
</html>
