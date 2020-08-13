<!DOCTYPE html>
<html>
<body>
  <form action="realtime_laminator.php" method="post">
    <h1 style='text-align:center'>Real Time Laminator Page</h1>
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
    $row_names = $sql_task_manager->column_name_table('LAMINATOR');
    $sql_cmd = "SELECT * FROM LAMINATOR WHERE LAM_DATE >= ? AND LAM_DATE <= ? ORDER BY ID DESC";
    $sql_task_manager->pdo_sql_vali_execute($sql_cmd, array($T1, $T2));
    $row_values = $sql_task_manager->rows_fetch($row_names);
    $file_name = 'LAMINATOR_Record_'.date("m_d_Y_H_i_s").'.csv';
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
    $lam_query = "SELECT ELECTRODE_SERIAL, ELECTRODE_LENGTH, NUM_DEFECT, AVG_THICKNESS, TIMESTAMP FROM LAMINATOR WHERE LAM_DATE >= ? AND LAM_DATE <= ? ORDER BY ID DESC";
    $sql_task_manager->pdo_sql_vali_execute($lam_query, array($T1, $T2));
    $sql_results = $sql_task_manager->rows_fetch(array('TIMESTAMP', 'ELECTRODE_SERIAL', 'ELECTRODE_LENGTH', 'AVG_THICKNESS', 'NUM_DEFECT'));
  }
?>

<script type="text/javascript">
  var sql_arr = <?php echo json_encode($sql_results) ?>;
  var total_length = 0;
  document.open();
  document.write("<h1 style='text-align:center'>Laminator records display Table</h1>");
  document.write("<h2 style='text-align:center'>This table will display the records specified by your input dates</h2>");
  document.write("<table style='width:100%'>");
  document.write("<tr> <th>Time Stamp</th> <th>Electrode Serial</th> <th>Length</th> <th>Avg Thickness</th> <th>Number of Defects</th></tr>");
  for (i=0; i<sql_arr.length; i++) {
    var line_str = "<tr>";
    for (j=0; j<sql_arr[i].length; j++) {
      if (j === 2) {
        total_length += parseInt(sql_arr[i][j]);
      }
      line_str+="<td style='text-align:center'>";
      line_str+=sql_arr[i][j];
      line_str+="</td>";
    }
    line_str += "</tr>";
    document.write(line_str);
  }
  document.write("</table>");
  var avg_length = total_length/sql_arr.length;
  document.write("<h3 style='text-align:center'> Total Length: " + total_length + "</h3>");
  document.write("<h3 style='text-align:center'> Average Length: " + avg_length + "</h3>");
  document.close();
</script>

</body>
</html>
