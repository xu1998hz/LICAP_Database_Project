<?php
    if (count($_REQUEST) === 0){
      return;
    }
    require_once('sql_task_manager.php');
    $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture_test");
    $sql_command = "SELECT FOIL_TYPE, THICKNESS, END_CENTER FROM LAMINATOR WHERE ELECTRODE_SERIAL = ?";
    $sql_task_manager->pdo_sql_vali_execute($sql_command, array(explode('/', $_REQUEST['ELECTRODE_BATCH_NUM'])[0]));
    $row_result_LAM = $sql_task_manager->row_fetch();
    $sql_command = "SELECT STRIP_LENGTH_METERS, ELECTRODE_AREA, TIMESTAMP, PALLET_NUM, BOX_NUM, ROLL_DIAMETER FROM SLITTER WHERE COMBINED_SERIAL = ?";
    $sql_task_manager->pdo_sql_vali_execute($sql_command, array($_REQUEST['ELECTRODE_BATCH_NUM']));
    $row_result_SLITTER = $sql_task_manager->row_fetch();
    $_REQUEST['TYPE'] = implode('-', array($row_result_LAM["THICKNESS"], $row_result_LAM['FOIL_TYPE']));
    $_REQUEST['PALLET_BOX_NUM'] = implode('--', array($row_result_SLITTER['PALLET_NUM'], $row_result_SLITTER['BOX_NUM']));
    $_REQUEST['ELECTRODE_LENGTH'] = $row_result_SLITTER['STRIP_LENGTH_METERS']; $_REQUEST['ELECTRODE_AREA'] = $row_result_SLITTER['ELECTRODE_AREA'];
    $_REQUEST['PACK_DATE'] = $row_result_SLITTER['TIMESTAMP']; $_REQUEST['END_CENTER'] = $row_result_LAM['END_CENTER']; $_REQUEST['ROLL_DIAMETER'] = $row_result_SLITTER['ROLL_DIAMETER'];
?>

<form action="Shipping_QC.php" method="post">
  <h1> Data Information for Shipping </h1>
  <p>
  <label for='PALLET_BOX_NUM'>Pallet # - Box #:</label>
  <input id="PALLET_BOX_NUM" name="PALLET_BOX_NUM" type="text" value ="<?php echo $_REQUEST['PALLET_BOX_NUM'] ?>">
  </p>
  <p>
  <label for='ELECTRODE_BATCH_NUM'>Electrode #:</label>
  <input id="ELECTRODE_BATCH_NUM" name="ELECTRODE_BATCH_NUM" type="text" value ="<?php echo $_REQUEST['ELECTRODE_BATCH_NUM'] ?>">
  </p>
  <p>
  <label for='TYPE'>P/N:</label>
  <input id="TYPE" name="TYPE" type="text" value ="<?php echo $_REQUEST['TYPE'] ?>">
  </p>
  <p>
  <label for='ELECTRODE_LENGTH'>Electrode length (M):</label>
  <input id="ELECTRODE_LENGTH" name="ELECTRODE_LENGTH" type="text" value ="<?php echo $_REQUEST['ELECTRODE_LENGTH'] ?>">
  </p>
  <p>
  <label for='ELECTRODE_AREA'>Electrode area (M2):</label>
  <input id="ELECTRODE_AREA" name="ELECTRODE_AREA" type="text" value ="<?php echo $_REQUEST['ELECTRODE_AREA'] ?>">
  </p>
  <p>
  <label for='END_CENTER'>Electrode thickness center (um):</label>
  <input id="END_CENTER" name="END_CENTER" type="text" value ="<?php echo $_REQUEST['END_CENTER'] ?>">
  </p>
  <p>
  <label for='ROLL_DIAMETER'>Roll Diameter (um):</label>
  <input id="ROLL_DIAMETER" name="ROLL_DIAMETER" type="text" value ="<?php echo $_REQUEST['ROLL_DIAMETER'] ?>">
  </p>
  <p>
  <label for='PACK_DATE'>Packing Date:</label>
  <input id="PACK_DATE" name="PACK_DATE" type="text" value ="<?php echo $_REQUEST['PACK_DATE'] ?>">
  </p>
  <p>
  <label for='SHIPPING_DATE'>Current Shipping Date:</label>
  <input id="SHIPPING_DATE" name="SHIPPING_DATE" type="text" value ="<?php echo $_REQUEST['SHIPPING_DATE'] ?>">
  </p>

  <input type="submit" value="Submit" name="Submit_QC">
</form>

<?php
  if(isset($_REQUEST["Submit_QC"])) {
    unset($_REQUEST["Submit_QC"]);
    if ($sql_task_manager->sql_insert_gen($_REQUEST, 'SHIPPING')) {
      echo "<h3>"."Records added to SHIPPING Database successfully!"."</h3>";
      echo "<script>setTimeout(\"location.href = 'http://localhost/LICAP_Database_Project/Scanner_Reader.php';\",2000);</script>";
    } else {
      echo "<h3>"."Unsuccessful insertion! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
    }
  }
?>
