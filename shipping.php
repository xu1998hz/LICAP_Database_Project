<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SLITTER DATA ENTRY PAGE</title>
</head>
  <body>
    <form action="shipping.php" method="post">
  <?php
    require_once('sql_task_manager.php');
    $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
    $sql_command = "SELECT PACKAGE_OP, PALLET_NUM, BOX_NUM FROM SLITTER ORDER BY ID DESC LIMIT 1";
    $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);
    $row['BOX_NUM'] = $row['BOX_NUM'] < 16 ? $row['BOX_NUM']++ : 1;
    if (count($_REQUEST)!==0) {
      $state = $sql_task_manager->batch_opt_db_vali(array('COMBINED_SERIAL'),
      array("Electrical Serial"), 'ELECTRODE_SERIAL', 'LAMINATOR', 0);
    }
  ?>
  <p>
    <label for="PACKAGE_OP">Shipping Operator Name:</label>
    <input id="PACKAGE_OP" name="PACKAGE_OP" type="text" value="<?php echo htmlentities($row['PACKAGE_OP']); ?>" />
  </p>
  <HR>
  <p style="<?php echo $sql_task_manager->color_ls_read("COMBINED_SERIAL") ?>">
    <label for="COMBINED_SERIAL">Electrode Serial</label>
    <input id="COMBINED_SERIAL" name="COMBINED_SERIAL" type="text">
  </p>
  <p>
    <label for="PALLET_NUM">Pallet_Number:</label>
    <input id="PALLET_NUM" name="PALLET_NUM" type="text" value="<?php echo htmlentities($row['PALLET_NUM']); ?>" />
    &nbsp;&nbsp;&nbsp;
    <label for="BOX_NUM">Box_Number:</label>
    <input id="BOX_NUM" name="BOX_NUM" type="text" value="<?php echo htmlentities($row['BOX_NUM']); ?>" />
  </p>
  <p>
    <label for="WEIGHT">Shipping Weight</label>
    <input id="WEIGHT" name="WEIGHT" type="text" style="max-width: 100px;">
    &nbsp;&nbsp;&nbsp;
    <label for="ROLL_DIAMETER">Roll Diameter</label>
    <input id="ROLL_DIAMETER" name="ROLL_DIAMETER" type="text" style="max-width: 100px;">
  </p>
  <p>
  <hr>
    <label for="NOTES">Notes:</label>
    <input type="text" name="NOTES" id="NOTES">
  </p>
  <input type="submit" value="Submit">
  </form>

  <?php
  // without user inputs, this part of user codes are not executing
  if (count($_REQUEST)===0){
    return;
  }
  if (!($state)) {
    echo "<hr>";
    echo "<h3>Error Messages:</h3>";
    $sql_task_manager->error_msg_print();
    echo "Above user inputs are not in standards. Records are not added!"."<br/>";
    return;
  }
  //print_r($sql_task_manager->pdo_sql_vali_execute($sql_cmd, array(':str_1' => $_REQUEST['COMBINED_SERIAL']))[0]);
  if ($sql_task_manager->query_record_exists('COMBINED_SERIAL', 'SLITTER', $_REQUEST['COMBINED_SERIAL'])) {
    $sql = "UPDATE SLITTER SET WEIGHT = ".$_REQUEST['WEIGHT'].", ROLL_DIAMETER = ".$_REQUEST['ROLL_DIAMETER'].", PALLET_NUM = "
    .$_REQUEST['PALLET_NUM'].", BOX_NUM = ".$_REQUEST['BOX_NUM'].", NOTES = ".$_REQUEST['NOTES']." WHERE COMBINED_SERIAL = :str_2";
    $result_arr = $sql_task_manager->pdo_sql_vali_execute($sql, array(':str_2' => $_REQUEST['COMBINED_SERIAL']));
    if ($result_arr[1]) {
      //print_r($_REQUEST['COMBINED_SERIAL']);
      echo "<h3>"."Records updated successfully!"."</h3>";
    } elseif (!$result_arr[0]) {
      echo "<h3>"."Internal Error! Contact IT Department for further helps"."</h3>";
    } else {
      echo "<h3>"."Inputs are the same in the current record!"."</h3>";
    }
  } else {
    if ($sql_task_manager->query_record_exists('ELECTRODE_SERIAL', 'LAMINATOR', $_REQUEST['COMBINED_SERIAL'])) {
      $FETCH = "SELECT ELECTRODE_LENGTH, NUM_DEFECT, NUM_HOLE, NUM_DELAM, NUM_SPLICE FROM LAMINATOR WHERE ELECTRODE_SERIAL = :str_1 LIMIT 1";
      $sql_task_manager->pdo_sql_vali_execute($FETCH, array('str_1'=>$_REQUEST['COMBINED_SERIAL']));
      $row_result = $sql_task_manager->row_fetch();
      $_REQUEST['NUM_DEFECT'] = $row_result['NUM_DEFECT'];
      $_REQUEST['NUM_HOLE'] = $row_result['NUM_HOLE'];
      $_REQUEST['NUM_DELAM'] = $row_result['NUM_DELAM'];
      $_REQUEST['NUM_SPLICE'] = $row_result['NUM_SPLICE'];
      $_REQUEST['STRIP_LENGTH_METERS'] = $row_result['ELECTRODE_LENGTH'];
      $_REQUEST['STRIP_LENGTH_FEET'] = $row_result['ELECTRODE_LENGTH']*3.28;
      $_REQUEST['SLIT_OP'] = "NO SLITTER";
      $_REQUEST['PERFORATED'] = "0";
      $_REQUEST['ELECTRODE_AREA'] = $row_result['ELECTRODE_LENGTH']/4;
      $_REQUEST['TIMESTAMP'] = date("m/d/Y-H:i:s");
      $_REQUEST['DATE'] = date("m/d/Y");
      if ($sql_task_manager->sql_insert_gen($_REQUEST, 'SLITTER')) {
        echo "<h3>"."Records created successfully!"."</h3>";
      } else {
        echo "<h3>"."Unsuccessful update! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
      }
    }
  }
  ?>
 </body>
</html>
