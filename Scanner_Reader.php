<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>LAMINATOR 2</title>
</head>
<body>
<?php
    require_once('sql_task_manager.php');
    $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture_test");
    $sql_command = "SELECT SHIPPING_DATE FROM SHIPPING ORDER BY ID DESC LIMIT 1";
    $ship_date = $sql_task_manager->pdo_sql_row_fetch($sql_command)['SHIPPING_DATE'];
?>

<form action="Shipping_QC.php" method="post">
  <h1>Shipping Database Updating System</h1>
  <p>
  <label for='SHIPPING_DATE'>Current Shipping Date:</label>
  <input id="SHIPPING_DATE" name="SHIPPING_DATE" type="text" value ="<?php echo $ship_date ?>">
  </p>
  <p>
  <label for='ELECTRODE_BATCH_NUM'>Current Bar Code:</label>
  <input id="ELECTRODE_BATCH_NUM" name="ELECTRODE_BATCH_NUM" onmouseover="this.focus();" type="text">
  </p>
  <p style="color:red;">
    <label>Hint: Move the mouse around Bar Code input box and Scan the Bar Code</label>
  </p>
  <input type="submit" id="Submit" value="Submit">
</form>
<script type="text/javascript">
  window.onload = function(){
    document.getElementById('ELECTRODE_BATCH_NUM').click();
  }
  // do not show submit button
  document.getElementById("Submit").style.display = "none";
</script>

<?php
  if (count($_REQUEST)===0) return;
  else header("Location:http://localhost/LICAP_Database_Project/Shipping_QC.php");
?>

</body>
</html>
