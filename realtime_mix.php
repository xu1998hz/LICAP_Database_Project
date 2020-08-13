<!DOCTYPE html>
<html>
<body>
  <form action="realtime_mix.php" method="post">
    <h1 style='text-align:center'>Real Time Blend Page</h1>
    <p style='text-align:center'>
      <label for='LOWER_DATE'>Begin Query Date:</label>
      <input id="LOWER_DATE" name="LOWER_DATE" type="date">
    </p>
    <p style='text-align:center'>
      <label for='UPPER_DATE'>End Query Date:</label>
      <input id="UPPER_DATE" name="UPPER_DATE" type="date">
    </p>

    <div style="text-align:center">
    <input type="submit" value="Download CSV" name="Submit_CSV">
    </div>

    <br/>

    <div style="text-align:center">
    <input type="submit" value="Display Records" name="Submit_Display">
    </div>
    <br/><br/>
  </form>

<?php
  require_once('sql_task_manager.php');
  $sql_task_manager = new sql_task_manager("localhost", "root", "PQch782tdk@@", "Manufacture");
  # convert html date format to sql date format
  $T1 = date('Y-m-d', strtotime($_REQUEST['LOWER_DATE'])); $T2 = date('Y-m-d', strtotime($_REQUEST['UPPER_DATE']));
  if (isset($_REQUEST['Submit_CSV'])) {
    $row_names = $sql_task_manager->column_name_table('blend');
    $sql_cmd = "SELECT * FROM blend WHERE DATE >= ? AND DATE <= ? ORDER BY ID DESC";
    $sql_task_manager->pdo_sql_vali_execute($sql_cmd, array($T1, $T2));
    $row_values = $sql_task_manager->rows_fetch($row_names);
    $file_name = 'blend_Record_'.date("m_d_Y_H_i_s").'.csv';
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$file_name.'";');
    // clean output buffer
    ob_end_clean();
    $file = fopen('php://output', 'w');
    fputcsv($file, $row_names);
    foreach ($row_values as $row) { fputcsv($file, $row); }
    fclose($file);
    // flush buffer
    ob_flush();
    // use exit to get rid of unexpected output afterward
    exit();
  }
  if (isset($_REQUEST['Submit_Display'])) {
    $blend_query = "SELECT AC_WEIGHT, ACETONE_WEIGHT, STRIP_RECYCLE, BAGHOUSE_RECYCLE, OUTSIDE_RECYCLE, PTFE_WEIGHT, BATCH_NUM, TIMESTAMP FROM blend WHERE DATE >= ? AND DATE <= ? ORDER BY ID DESC";
    $sql_task_manager->pdo_sql_vali_execute($blend_query, array($T1, $T2));
    $sql_results = $sql_task_manager->rows_fetch(array('TIMESTAMP', 'BATCH_NUM', 'AC_WEIGHT', 'ACETONE_WEIGHT', 'PTFE_WEIGHT', 'STRIP_RECYCLE', 'BAGHOUSE_RECYCLE', 'OUTSIDE_RECYCLE'));
  }
?>

<script type="text/javascript">
  var sql_arr = <?php echo json_encode($sql_results) ?>;
  var total_AC = 0;
  var total_Acetone = 0;
  var total_PTFE = 0;
  var total_Recycle = 0;
  document.open();
  document.write("<h1 style='text-align:center'>Blend records display Table</h1>");
  document.write("<h2 style='text-align:center'>This table will display the records specified by your input dates</h2>");
  document.write("<table style='width:100%'>");
  document.write("<tr> <th>Time Stamp</th> <th>Batch ID</th> <th>AC Used (KG)</th> <th>Acetone Used (KG)</th> <th>PTFE WEIGHT (KG)</th> <th>Strip Recycle</th> <th>Baghouse Recycle</th> <th>Outside Recycle (KG)</th></tr>");
  for (i=0; i<sql_arr.length; i++) {
    var line_str = "<tr>";
    for (j=0; j<sql_arr[i].length; j++) {
      if (j === 2) {
        total_AC += parseInt(sql_arr[i][j]);
      } else if (j === 3) {
        total_Acetone += parseInt(sql_arr[i][j]);
      } else if (j === 4) {
        total_PTFE += parseInt(sql_arr[i][j]);
      } else if (j === 5) {
        total_Recycle += parseInt(sql_arr[i][j]);
      }
      line_str+="<td style='text-align:center'>";
      line_str+=sql_arr[i][j];
      line_str+="</td>";
    }
    line_str += "</tr>";
    document.write(line_str);
  }
  document.write("</table>");
  document.write("<h3 style='text-align:center'> Total AC Used: " + total_AC + " kg" + "</h3>");
  document.write("<h3 style='text-align:center'> Total Acetone Used: " + total_Acetone + " kg" + "</h3>");
  document.write("<h3 style='text-align:center'> Total PTFE Used: " + total_PTFE + " kg" + "</h3>");
  document.write("<h3 style='text-align:center'> Total Recycle Used: " + total_Recycle + " kg" + "</h3>");
  document.close();
</script>
</body>
</html>
