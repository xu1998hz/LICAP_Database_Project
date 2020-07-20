<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SLITTER DATA ENTRY PAGE</title>
</head>
  <body>
    <form action="Slitter.php" method="post">

    <?php
      require_once('sql_task_manager.php');
      $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
      $sql_command = "SELECT SLIT_OP FROM SLITTER ORDER BY ID DESC LIMIT 1";
      $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);
      if (count($_REQUEST)!==0) {
        $state = $sql_task_manager->batch_opt_db_vali(array('ELECTRODE_SERIAL_1', 'ELECTRODE_SERIAL_2', 'ELECTRODE_SERIAL_3'),
        array("Electrode Serial 1", "Electrode Serial 2", "Electrode Serial 3"), 'ELECTRODE_SERIAL', 'LAMINATOR', 0);
      }
    ?>
    <p>
      <label for="SLIT_OP">Slitter Operator:</label>
      <input id="SLIT_OP" name="SLIT_OP" type="text" value="<?php echo htmlentities($row['SLIT_OP']); ?>" />
    </p>
    <HR>
    <p style="<?php echo $sql_task_manager->color_ls_read("ELECTRODE_SERIAL_1") ?>">
        <label for="ELECTRODE_SERIAL_1">Electrode Serial 1</label>
        <input id="ELECTRODE_SERIAL_1" name="ELECTRODE_SERIAL_1" type="text" value = "<?php echo isset($_POST['ELECTRODE_SERIAL_1'])&&(!$state) ? $_POST['ELECTRODE_SERIAL_1'] : '' ?>">
    </p>
    <p>
        <label for="ELECTRODE_LENGTH_1">Electrode Length 1</label>
        <input id="ELECTRODE_LENGTH_1" name="ELECTRODE_LENGTH_1" type="text" value = "<?php echo isset($_POST['ELECTRODE_LENGTH_1'])&&(!$state) ? $_POST['ELECTRODE_LENGTH_1'] : '' ?>">
        <label for="ELECTRODE_LENGTH_1">ft</label>
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("ELECTRODE_SERIAL_2") ?>">
        <label for="ELECTRODE_SERIAL_2">Electrode Serial 2</label>
        <input id="ELECTRODE_SERIAL_2" name="ELECTRODE_SERIAL_2" type="text" value = "<?php echo isset($_POST['ELECTRODE_SERIAL_2'])&&(!$state) ? $_POST['ELECTRODE_SERIAL_2'] : '' ?>">
    </p>
    <p>
        <label for="ELECTRODE_LENGTH_2">Electrode Length 2</label>
        <input id="ELECTRODE_LENGTH_2" name="ELECTRODE_LENGTH_2" type="text" value = "<?php echo isset($_POST['ELECTRODE_LENGTH_2'])&&(!$state) ? $_POST['ELECTRODE_LENGTH_2'] : '' ?>">
        <label for="ELECTRODE_LENGTH_2">ft</label>
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("ELECTRODE_SERIAL_3") ?>">
        <label for="ELECTRODE_SERIAL_3">Electrode Serial 3</label>
        <input id="ELECTRODE_SERIAL_3" name="ELECTRODE_SERIAL_3" type="text" value = "<?php echo isset($_POST['ELECTRODE_SERIAL_3'])&&(!$state) ? $_POST['ELECTRODE_SERIAL_3'] : '' ?>">
    </p>
    <p>
        <label for="ELECTRODE_LENGTH_3">Electrode Length 3</label>
        <input id="ELECTRODE_LENGTH" name="ELECTRODE_LENGTH_3" type="text" value = "<?php echo isset($_POST['ELECTRODE_LENGTH_3'])&&(!$state) ? $_POST['ELECTRODE_LENGTH_3'] : '' ?>">
        <label for="ELECTRODE_LENGTH_3">ft</label>
    </p>
    <p>
        <label for="PERFORATED">Was the electrode Perforated?</label>
        <select name="PERFORATED">
    		    <option value="0">No</option>
  		      <option value="1">Yes</option>
        </select>
    <hr>
    <p>
        <label for="NUM_HOLE">Number of Hole Defects</label>
        <input id="NUM_HOLE" name="NUM_HOLE" type="text" value = "<?php echo isset($_POST['NUM_HOLE'])&&(!$state) ? $_POST['NUM_HOLE'] : '' ?>">
        &nbsp;&nbsp;&nbsp;
        <label for="NUM_DELAM">Number of Delaminations</label>
        <input id="NUM_DELAM" name="NUM_DELAM" type="text" value = "<?php echo isset($_POST['NUM_DELAM'])&&(!$state) ? $_POST['NUM_DELAM'] : '' ?>">
        &nbsp;&nbsp;&nbsp;
        <label for="NUM_SPLICE">Number of Splices</label>
        <input id="NUM_SPLICE" name="NUM_SPLICE" type="text" value = "<?php echo isset($_POST['NUM_SPLICE'])&&(!$state) ? $_POST['NUM_SPLICE'] : '' ?>">
    </p>
    <hr>
        <label for="NOTES">Notes:</label>
        <input type="text" name="NOTES" id="NOTES" value = "<?php echo isset($_POST['NOTES'])&&(!$state) ? $_POST['NOTES'] : '' ?>">
    </p>
    <input type="submit" value="Submit">
    <hr>
    </form>

    <?php
      // without user inputs, this part of user codes are not executing
      if (count($_REQUEST)===0){
        return;
      }
      if (!($state)) {
        $sql_task_manager->error_msg_print();
        return;
      }
      $_REQUEST['DATE'] = date("m/d/Y");
      $_REQUEST['TIMESTAMP'] = date("m/d/Y-H:i:s");
      $_REQUEST['STRIP_LENGTH_FEET'] = $_REQUEST['ELECTRODE_LENGTH_1'] + $_REQUEST['ELECTRODE_LENGTH_2'] + $_REQUEST['ELECTRODE_LENGTH_3'];
      $_REQUEST['STRIP_LENGTH_METERS'] = round($_REQUEST['STRIP_LENGTH_FEET'] / 3.28084);
      # update 3 yield percentage at Laminator based on serial 1, 2, 3
      for ($i=1; $i<4; $i++) {
        $cur_sql = "UPDATE LAMINATOR SET YIELD_PERCENTAGE = ? / ELECTRODE_LENGTH * 100 WHERE ELECTRODE_SERIAL = ?";
        $result_arr = $sql_task_manager->pdo_sql_vali_execute($cur_sql, array($_REQUEST['STRIP_LENGTH_METERS'], $_REQUEST['ELECTRODE_SERIAL_'.$i]));
        if ($result_arr[1]) {
          echo "<h3>"."Electrode Serial ".$i." updated Laminator yield percentage successfully!"."</h3>";
        } elseif (!$result_arr[0]) {
          echo "<h3>"."Internal Error! Contact IT Department for further helps"."</h3>";
        } else {
          echo "<h3>"."Electrode Serial ".$i." Input has already updated yield percentage in laminator!"."</h3>";
        }
      }
      $_REQUEST['ELECTRODE_AREA'] = $_REQUEST['STRIP_LENGTH_METERS'] / 4;
      $_REQUEST['NUM_DEFECT'] = $_REQUEST['NUM_HOLE'] + $_REQUEST['NUM_DELAM'] + $_REQUEST['NUM_SPLICE'];
      // intermediate procees of ELECTRODE serial numbers
      $electrode_arr_vals = array($_REQUEST['ELECTRODE_SERIAL_1'], $_REQUEST['ELECTRODE_SERIAL_2'], $_REQUEST['ELECTRODE_SERIAL_3']);
      // number of processes within pipeline, compute serial number and remove the intermediate results
     for ($i=0; $i<3; $i++) {
       if ($_REQUEST['PERFORATED'] === '1') $electrode_arr_vals[$i] = $electrode_arr_vals[$i].'-PF';
       unset($_REQUEST['ELECTRODE_SERIAL_'.($i+1)]);
       unset($_REQUEST['ELECTRODE_LENGTH_'.($i+1)]);
     }
     $_REQUEST['COMBINED_SERIAL']= implode("/", $electrode_arr_vals);

     if ($sql_task_manager->sql_insert_gen($_REQUEST, 'SLITTER')) {
       echo "<h3>"."Records added successfully!"."</h3>";
     } else {
       echo "<h3>"."Unsuccessful insertion! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
     }
    ?>
  </body>
</html>
