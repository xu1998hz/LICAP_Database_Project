<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Scanner Reader</title>
</head>
<body>

<form action="Shipping_QC.php" method="post">
  <h1 style='text-align:center'>Shipping Database Updating System</h1>
  <p style='text-align:center'>
  <label for='SHIPPING_DATE'>Current Shipping Date:</label>
  <input id="SHIPPING_DATE" name="SHIPPING_DATE" type="date">
  </p>
  <p style='text-align:center'>
  <label for='ELECTRODE_BATCH_NUM'>Current Bar Code:</label>
  <input id="ELECTRODE_BATCH_NUM" name="ELECTRODE_BATCH_NUM" onmouseover="this.focus();" type="text">
  </p>
  <p style="color:red; text-align:center">
    <label>Hint1: Move the mouse around Bar Code input box and Scan the Bar Code</label><br/>
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
  else header("Location:http://10.1.10.190/Shipping_QC.php");
?>

</body>
</html>
