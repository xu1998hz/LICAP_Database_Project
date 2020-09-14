<form action="Shipping_CSV.php" method="post">
  <h1 style='text-align:center'>Real Time Shpping Page</h1>
  <p style='text-align:center'>
    <label for='SHIPPING_DATE'>Shipping Date:</label>
    <input id="SHIPPING_DATE" name="SHIPPING_DATE" type="date">
  </p>
  <div style="text-align:center">
  <input type="submit" value="Download CSV" name="Submit_Date">
  </div>
  <p style='text-align:center'>
    <label for='Num_Rec'>Number of records:</label>
    <input id="Num_Rec" name="Num_Rec" type="text">
  </p>
  <div style="text-align:center">
  <input type="submit" value="Display Records" name="Submit_Num">
  </div>
  <br/><br/>
</form>

<?php
  require_once('sql_task_manager.php');
  $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
  if (isset($_REQUEST['SHIPPING_DATE'])) {
    $query_date = date('Y-m-d', strtotime($_REQUEST['SHIPPING_DATE']));
  }
  if(isset($_REQUEST["Submit_Date"])) {
    $sql_command = "SELECT PALLET_BOX_NUM, ELECTRODE_BATCH_NUM, TYPE, ELECTRODE_LENGTH, ELECTRODE_AREA, END_CENTER, ROLL_DIAMETER FROM SHIPPING WHERE SHIPPING_DATE = '$query_date'";
    $sql_results = $sql_task_manager->pdo_sql_rows_fetch($sql_command, array('PALLET_BOX_NUM', 'ELECTRODE_BATCH_NUM', 'TYPE', 'ELECTRODE_LENGTH', 'ELECTRODE_AREA', 'END_CENTER', "ROLL_DIAMETER"));
    $file_name = 'Shipping_Record_'.date("m_d_Y_H_i_s").'.csv';
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="'.$file_name.'";');
    // clean output buffer
    ob_end_clean();
    $file = fopen('php://output', 'w');
    fputcsv($file, array('Pallet# -Box #', 'Electrode #', 'P/N', 'Electrode length (M)', 'Electrode area (M2)', 'Electrode thickness center (um)', 'Roll Diameter (mm)'));
    foreach ($sql_results as $row) { fputcsv($file, $row); }
    fclose($file);
    // flush buffer
    ob_flush();
    // use exit to get rid of unexpected output afterward
    exit();
    echo "<h2 style='text-align:center; color:red'>"."CSV is successfully downloaded!"."</h2>";
  }
  if (isset($_REQUEST["Submit_Num"])) {
    $Num_Rec = $_REQUEST['Num_Rec'];
    $sql_command = "SELECT PALLET_BOX_NUM, ELECTRODE_BATCH_NUM, TYPE, ELECTRODE_LENGTH, ELECTRODE_AREA, END_CENTER, ROLL_DIAMETER FROM SHIPPING WHERE SHIPPING_DATE = '$query_date' ORDER BY ID DESC LIMIT ".$Num_Rec;
    $sql_results = $sql_task_manager->pdo_sql_rows_fetch($sql_command, array('PALLET_BOX_NUM', 'ELECTRODE_BATCH_NUM', 'TYPE', 'ELECTRODE_LENGTH', 'ELECTRODE_AREA', 'END_CENTER', "ROLL_DIAMETER"));
  }
?>

<script type="text/javascript">
  var sql_arr = <?php echo json_encode($sql_results) ?>;
  document.open();
  document.write("<h1 style='text-align:center'>Shipping records display Table</h1>");
  document.write("<h2 style='text-align:center'>This table will display the most recent records specified by your inputs</h2>");
  document.write("<table style='width:100%'>");
  document.write("<tr> <th>Pallet# -Box #</th> <th>Electrode #</th> <th>P/N</th> <th>Electrode length (M)</th> <th>Electrode area (M2)</th> <th>Electrode thickness center (um)</th> <th>Roll Diameter (mm)</th></tr>");
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
