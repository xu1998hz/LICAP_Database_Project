<!DOCTYPE html>
<html>
<head>
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 5px;
}
</style>
</head>
<body>
<?php
  require_once('sql_task_manager.php');
  $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
  $DATE = date("m/d/Y");
  $TIMESTAMP = date("m/d/Y-H:i:s");
  echo "Current Time: " . $TIMESTAMP . "<br>"."<br>";
  //Pulls data for query
  $sql_command = "SELECT FILM_ID, LENGTH, DEFECT_NUM, AVG_THICKNESS, TIMESTAMP FROM FILM WHERE DATE = '$DATE' ORDER BY ID DESC";
  $sql_arr = $sql_task_manager->pdo_sql_rows_fetch($sql_command, array('TIMESTAMP', 'FILM_ID', 'LENGTH', 'AVG_THICKNESS', 'DEFECT_NUM'));
  $sql_command = "SELECT COUNT(FILM_ID) FROM FILM WHERE DATE = '$DATE'";
  $AVG = $sql_task_manager->pdo_sql_row_fetch($sql_command)['COUNT(FILM_ID)'];
?>
  <script type="text/javascript">
  var sql_arr = <?php echo json_encode($sql_arr) ?>;
  var avg = <?php echo $AVG ?>;
  var total_length = 0;
  document.open();
  document.write("<table style='width:100%'>");
  document.write("<tr> <th>Timestamp</th> <th>Film ID</th> <th>Length</th><th>Avg Thickness</th><th>Number of Defects</th></tr>");
  for (i=0; i<sql_arr.length; i++) {
    var line_str = "<tr>";
    for (j=0; j<sql_arr[i].length; j++) {
      line_str+="<td>";
      line_str+=sql_arr[i][j];
      line_str+="</td>";
    }
    total_length += sql_arr[i][2];
    line_str += "</tr>";
    document.write(line_str);
  }
  document.write("</table>");
  var avg_length = total_length/avg;
  document.write("<h4> Total length: "+total_length+"</h4>");
  document.write("<h4> Average length: "+avg_length+"</h4>");
  document.close();
  </script>

<form action="csv_export.php" method="post">
  <p>
    <label for="T1">Begin Time:</label>
    <input type="text" name="T1" id="T1">
    <label for="T1">Month/Day/Year Ex: 05/04/2020</label>
  </p>
  <p>
    <label for="T2">End Time:</label>
    <input type="text" name="T2" id="T2">
    <label for="T2">Month/Day/Year</label>
  </p>
  <input type="submit" value="Download CSV">
</form>

</body>
</html>
