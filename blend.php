<!DOCTYPE html>
<html lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>POWDER MIXTURE DATA ENTRY PAGE</title>
  </head>
  <body>
  <form action="blend.php" method="post">

  <?php
    require_once('sql_task_manager.php');
    $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
    $sql_command = "SELECT * FROM blend ORDER BY ID DESC LIMIT 1";
    $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);
    $AC_LOT_NUM_RESULT_2 = explode("-", $row['AC_LOT_NUM_2'])[0] . "-" . explode("-", $row['AC_LOT_NUM_2'])[1];
    $Batch_num = $sql_task_manager->ID_computation($row['BATCH_NUM'], '', '', 'M', 1);
    echo "<h1>" . "Current Batch:" . $Batch_num . "</h1>";
    // error handlings to check certain values existing at database
  ?>
  <p>
    <label> Mixer Operator:</label>
    <input type="text" name="MIXING_OP" value="<?php echo htmlentities($row['MIXING_OP']); ?>" />
  </p>
  <hr>
  <p>
    <label for="AC_LOT_1">AC Lot Number 1:</label>
    <input type="text" name="AC_LOT_1"value="<?php echo htmlentities($AC_LOT_NUM_RESULT_2); ?>"/>
    <label for="AC_NUM_1">AC Bag Number 1:</label>
    <input type="text" name="AC_NUM_1"/>
  </p>
  <p>
    <label for="AC_LOT_2">AC Lot Number 2:</label>
    <input type="text" name="AC_LOT_2"value="<?php echo htmlentities($AC_LOT_NUM_RESULT_2); ?>"/>
    <label for="AC_NUM_2">AC Bag Number 2:</label>
    <input type="text" name="AC_NUM_2"/>
  </p>
  <hr>
  <p>
    <label for="AC_WEIGHT">Active Carbon Weight:</label>
    <input type="text" id="AC_WEIGHT" name="AC_WEIGHT" value="<?php echo htmlentities($row['AC_WEIGHT']); ?>" onkeyup="PTFE_compute()"/>
  </p>
  <p>
    <label for="BAGHOUSE_RECYCLE">Bag House Recycle Weight:</label>
    <input type="text" id="BAGHOUSE_RECYCLE" name="BAGHOUSE_RECYCLE" value="<?php echo htmlentities($row['BAGHOUSE_RECYCLE']); ?>" onkeyup="PTFE_compute()"/>
  </p>
  <p>
    <label for="STRIP_RECYCLE">Strip Recycle Weight:</label>
    <input type="text" id="STRIP_RECYCLE" name="STRIP_RECYCLE" value="<?php echo htmlentities($row['STRIP_RECYCLE']); ?>" onkeyup="PTFE_compute()"/>
    <label for="BAGHOUSE_RECYCLE">*Must enter a zero if no recycle is used!</label>
  </p>
  <p>
    <label for="OUTSIDE_RECYCLE">Outside Recycle Weight:</label>
    <input type"text" id="OUTSIDE_RECYCLE" name="OUTSIDE_RECYCLE" value="<?php echo htmlentities($row['OUTSIDE_RECYCLE']); ?>" onkeyup="PTFE_compute()"/>
  </p>
  <p style="color:red;">
    Real time PTFE value: <span id="PTFE"></span>
  </p>
  <script>
    function PTFE_compute() {
      var AC_WEIGHT = document.getElementById("AC_WEIGHT").value;
      var BAGHOUSE_RECYCLE = document.getElementById("BAGHOUSE_RECYCLE").value;
      var STRIP_RECYCLE = document.getElementById("STRIP_RECYCLE").value;
      var OUTSIDE_RECYCLE = document.getElementById("OUTSIDE_RECYCLE").value;
      document.getElementById("PTFE").innerHTML = (0.08*AC_WEIGHT/0.92)+(0.018/0.987)*(BAGHOUSE_RECYCLE+STRIP_RECYCLE+OUTSIDE_RECYCLE);
    }
  </script>
  <p>
    <label for="ACETONE_LOT">Acetone Lot Number:</label>
    <input type="text" name="ACETONE_LOT" value="<?php echo htmlentities($row['ACETONE_LOT']); ?>" />
  </p>
  <p>
    <label for="ACETONE_WEIGHT">Acetone Weight:</label>
    <input type="text" name="ACETONE_WEIGHT" value="<?php echo htmlentities($row['ACETONE_WEIGHT']); ?>" />
  </p>
  <p>
    <label for="PTFE_LOT">PTFE Lot:</label>
    <input type="text" name="PTFE_LOT" value="<?php echo htmlentities($row['PTFE_LOT']); ?>" />
  </p>
  <p>
    <label for="PTFE_WEIGHT">PTFE Weight:</label>
    <input type="text" name="PTFE_WEIGHT" id="PTFE_WEIGHT" value="<?php echo htmlentities($row['PTFE_WEIGHT']); ?>" />
  </p>
  <p>
    <label for="MIX_TIME">Blend Time:</label>
    <input type="text" name="MIX_TIME" id="MIX_TIME" value="<?php echo htmlentities($row['MIX_TIME']); ?>" />
  </p>
  <p>
    <label for="INJECTOR_PRESSURE">Injection Pressure:</label>
    <input type="text" name="INJECTOR_PRESSURE" value ="<?php echo htmlentities($row['INJECTOR_PRESSURE']); ?>" />
  </p>
  <p>
    <label for="PERIPHERAL_PRESSURE">Peripheral Wall Pressure:</label>
    <input type="text" name="PERIPHERAL_PRESSURE" value="<?php echo htmlentities($row['PERIPHERAL_PRESSURE']); ?>" />
  </p>
  <p>
    <label for="GRIND_OP">Jet Mill Operator:</label>
    <input type="text" name="GRIND_OP" value="<?php echo htmlentities($row['GRIND_OP']); ?>" />
  </p>
  <p>
    <label for="MIXER_RPM">K-Tron RPM:</label>
    <input type="text" name="MIXER_RPM" value="<?php echo htmlentities($row['MIXER_RPM']); ?>" />
  </p>
  <input type="submit" value="Submit">
  </form>

  <?php
    // check whether there is user input
    if (count($_REQUEST)===0) {
      return;
    }
    $_REQUEST['BATCH_NUM'] = $Batch_num;
    $_REQUEST['AC_LOT_NUM'] = $_REQUEST['AC_LOT_1']."-".$_REQUEST['AC_NUM_1'];
    $_REQUEST['AC_LOT_NUM_2'] = $_REQUEST['AC_LOT_2']."-".$_REQUEST['AC_NUM_2'];
    unset($_REQUEST['AC_LOT_1']); unset($_REQUEST['AC_NUM_1']);
    unset($_REQUEST['AC_LOT_2']); unset($_REQUEST['AC_NUM_2']);
    $_REQUEST['DATE'] = date("m/d/Y");
    $_REQUEST['TIMESTAMP'] = date("m/d/Y-H:i:s");
    $_REQUEST['BATCH_WEIGHT'] = $_REQUEST['AC_WEIGHT'] + $_REQUEST['ACETONE_WEIGHT'] + $_REQUEST['STRIP_RECYCLE']
     + $_REQUEST['BAGHOUSE_RECYCLE'] + $_REQUEST['OUTSIDE_RECYCLE'] + $_REQUEST['PTFE_WEIGHT'];
    $_REQUEST['PTFE_RECYCLE'] = (0.0204 * ($_REQUEST['STRIP_RECYCLE'] + $_REQUEST['BAGHOUSE_RECYCLE'] + $_REQUEST['OUTSIDE_RECYCLE']));
    $_REQUEST['ACETONE_PERCENT'] = (($_REQUEST['ACETONE_WEIGHT']/($_REQUEST['BATCH_WEIGHT'] - $_REQUEST['PTFE_RECYCLE']))*100);
    $_REQUEST['PTFE_PERCENT'] = ((($_REQUEST['PTFE_WEIGHT'] - $_REQUEST['PTFE_RECYCLE'])/($_REQUEST['AC_WEIGHT'] + $_REQUEST['PTFE_WEIGHT'] - $_REQUEST['PTFE_RECYCLE']))*100);
    if ($sql_task_manager->sql_insert_gen($_REQUEST, 'BLEND')) {
      echo "<h3>"."Records added successfully!"."</h3>";
    } else {
      echo "<h3>"."Unsuccessful insertion! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
    }
  ?>
  </body>
</html>
