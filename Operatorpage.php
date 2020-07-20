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
 # calulate the timestamp range
 $date_range = date("m/d/Y-H:i:s", strtotime('-2 week'));
 $sql_command = "SELECT BATCH_NUM, TIMESTAMP, GRIND_OP, MIXING_OP FROM blend WHERE TIMESTAMP >= '$date_range' ORDER BY ID DESC";
 $sql_arr = $sql_task_manager->pdo_sql_rows_fetch($sql_command, array('BATCH_NUM', 'TIMESTAMP', 'GRIND_OP', 'MIXING_OP'));
?>

<script type="text/javascript">
var sql_arr = <?php echo json_encode($sql_arr) ?>;
document.open();
document.write("<h1 style='text-align:center'>Operator name display table</h1>");
document.write("<h2 style='text-align:center'>This table will display the Timestamp, Batch ID, Mixer Operator Name, Jet Mill Operator in recent two weeks</h2>");
document.write("<table style='width:100%'>")
document.write("<tr> <th>Timestamp</th> <th>Batch ID</th> <th>Mixer Operator Name</th> <th> Jet Mill Operator </th> </tr>");
for (i=0; i<sql_arr.length; i++) {
  var line_str = "<tr>";
  for (j=0; j<sql_arr[i].length; j++) {
    line_str+="<td>";
    line_str+=sql_arr[i][j];
    line_str+="</td>";
  }
  line_str += "</tr>";
  document.write(line_str);
}
document.write("</table>");
document.close();
</script>
</body>
</html>
