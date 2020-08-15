<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>LAMINATOR 2</title>
</head>
  <body>
    <?php
      require_once('sql_task_manager.php');
      $langs_trans = array("END_OP" => "Ending Operator Side Thickness", "END_CENTER" => "End Center Thickness", "END_MACHINE" => "Ending Machine Side Thickness");
      $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
      $sql_command = "SELECT LAM_OP, FOIL_TYPE, CAF_BATCH_NUM, CAF_BATCH_NUM_2, LAM_TEMP_UPPER, LAM_TEMP_LOWER, LAM_SPEED, GAP_OP, GAP_MACHINE, THICKNESS FROM LAMINATOR WHERE LAM_ID=2 ORDER BY ID DESC LIMIT 1";
      $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);
      $CAF_BATCH_NUM_FINAL_RESULT = $row['CAF_BATCH_NUM_2'] ? $row['CAF_BATCH_NUM_2'] : $row['CAF_BATCH_NUM'];
      //Pull last Film Lot Number
      $sql_command = "SELECT ELECTRODE_SERIAL FROM LAMINATOR ORDER BY ID DESC LIMIT 1";
      $row_2 = $sql_task_manager->pdo_sql_row_fetch($sql_command);
      $ELECTRODE_SERIAL = $sql_task_manager->ID_computation($row_2['ELECTRODE_SERIAL'], $row['THICKNESS'], '', 'E-', 3);
      echo "<h1> LAMINATOR 2 </h1>";
      echo "<h1>" . "Current Roll:" . $ELECTRODE_SERIAL . "</h1>";
      // check if this is before user inputs, error handlings on existing user inputs
      if (count($_REQUEST)!==0) {
        $batch_state = $sql_task_manager->batch_opt_db_vali(array('UPPER_FILM_BATCH_NUM', 'LOWER_FILM_BATCH_NUM'), array("Upper Film Batch Number", "Lower Film Batch Number"), 'FILM_ID', 'FILM', 2, '1');
        $spec_state = $sql_task_manager->user_Input_spec_vali($_REQUEST, $langs_trans, 315, 210, 5);
      }
    ?>
    <form action="Laminator_2.php" method="post">
    <p>
      <input id="LAM_ID" name="LAM_ID" type="hidden" value="2"/>
      <label for="LAM_OP">Laminator Operator:</label>
      <input id="LAM_OP" name="LAM_OP" type="text" value="<?php echo htmlentities($row['LAM_OP']); ?>" />
      <label for="FOIL_TYPE">Foil Type:</label>
      <input id="FOIL_TYPE" name="FOIL_TYPE" type="text" value="<?php echo htmlentities($row['FOIL_TYPE']); ?>" />
      <label for="THICKNESS">Thickness:</label>
      <input id="THICKNESS" name="THICKNESS" type="text" value="<?php echo htmlentities($row['THICKNESS']); ?>" />
    </p>
    <p>
      <label for="CAF_BATCH_NUM">Foil Batch Number:</label>
      <input id="CAF_BATCH_NUM" name="CAF_BATCH_NUM" type="text" value="<?php echo htmlentities($CAF_BATCH_NUM_FINAL_RESULT); ?>" />
    </p>
    <p>
      <label for="CAF_BATCH_NUM_2">Foil Batch Number 2:</label>
      <input id="CAF_BATCH_NUM_2" name="CAF_BATCH_NUM_2" type="text" value="<?php echo isset($_POST["CAF_BATCH_NUM_2"])&&(!$batch_state) ? $_POST["CAF_BATCH_NUM_2"] : '' ?>"/>
    </p>
    <hr>
    <p style="<?php echo $sql_task_manager->color_ls_read("UPPER_FILM_BATCH_NUM") ?>">
      <label for="UPPER_FILM_BATCH_NUM">Upper Film Batch Number 1:</label>
      <input id="UPPER_FILM_BATCH_NUM" name="UPPER_FILM_BATCH_NUM" type="text" value="<?php echo isset($_POST['UPPER_FILM_BATCH_NUM'])&&(!$batch_state) ? $_POST['UPPER_FILM_BATCH_NUM'] : '' ?>"/>
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("LOWER_FILM_BATCH_NUM") ?>">
      <label for="LOWER_FILM_BATCH_NUM">Lower Film Batch Number 1:</label>
      <input id="LOWER_FILM_BATCH_NUM" name="LOWER_FILM_BATCH_NUM" type="text" value="<?php echo isset($_POST['LOWER_FILM_BATCH_NUM'])&&(!$batch_state) ? $_POST['LOWER_FILM_BATCH_NUM'] : '' ?>"/>
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("UPPER_FILM_BATCH_NUM_2") ?>">
      <label for="UPPER_FILM_BATCH_NUM_2">Upper Film Batch Number 2:</label>
      <input id="UPPER_FILM_BATCH_NUM_2" name="UPPER_FILM_BATCH_NUM_2" type="text" value="<?php echo isset($_POST['UPPER_FILM_BATCH_NUM_2'])&&(!$batch_state) ? $_POST['UPPER_FILM_BATCH_NUM_2'] : '' ?>"/>
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("LOWER_FILM_BATCH_NUM_2") ?>">
      <label for="LOWER_FILM_BATCH_NUM_2">Lower Film Batch Number 2:</label>
      <input id="LOWER_FILM_BATCH_NUM_2" name="LOWER_FILM_BATCH_NUM_2" type="text" value="<?php echo isset($_POST['LOWER_FILM_BATCH_NUM_2'])&&(!$batch_state) ? $_POST['LOWER_FILM_BATCH_NUM_2'] : '' ?>"/>
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("UPPER_FILM_BATCH_NUM_3") ?>">
      <label for="UPPER_FILM_BATCH_NUM_3">Upper Film Batch Number 3:</label>
      <input id="UPPER_FILM_BATCH_NUM_3" name="UPPER_FILM_BATCH_NUM_3" type="text" value="<?php echo isset($_POST['UPPER_FILM_BATCH_NUM_3'])&&(!$batch_state) ? $_POST['UPPER_FILM_BATCH_NUM_3'] : '' ?>"/>
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("LOWER_FILM_BATCH_NUM_3") ?>">
      <label for="LOWER_FILM_BATCH_NUM_3">Lower Film Batch Number 3:</label>
      <input id="LOWER_FILM_BATCH_NUM_3" name="LOWER_FILM_BATCH_NUM_3" type="text" value="<?php echo isset($_POST['LOWER_FILM_BATCH_NUM_3'])&&(!$batch_state) ? $_POST['LOWER_FILM_BATCH_NUM_3'] : '' ?>"/>
    </p>
    <hr>
    <p>
      <label for="ELECTRODE_LENGTH">Length:</label>
      <input type="text" name="ELECTRODE_LENGTH" value="<?php echo isset($_POST["ELECTRODE_LENGTH"])&&(!$batch_state) ? $_POST["ELECTRODE_LENGTH"] : '' ?>">
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("END_OP") ?>">
      <label for="END_OP">Ending Operator Side Thickness:</label>
      <input id="END_OP" name="END_OP" type="text" style="max-width: 100px;" value="<?php echo isset($_POST["END_OP"])&&(!$batch_state) ? $_POST["END_OP"] : '' ?>"/>
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("END_CENTER") ?>">
      <label for="END_CENTER">Ending Center Thickness:</label>
      <input id="END_CENTER" name="END_CENTER" type="text" style="max-width: 100px;" value="<?php echo isset($_POST["END_CENTER"])&&(!$batch_state) ? $_POST["END_CENTER"] : '' ?>"/>
    </p>
    <p style="<?php echo $sql_task_manager->color_ls_read("END_MACHINE") ?>">
      <label for="END_MACHINE">Ending Machine Side Thickness:</label>
      <input id="END_MACHINE" name="END_MACHINE" type="text" style="max-width: 100px;" value="<?php echo isset($_POST["END_MACHINE"])&&(!$batch_state) ? $_POST["END_MACHINE"] : '' ?>"/>
    </p>
    <hr>
    <p>
      <label for="ROLL_DIAMETER">Roll Diameter:</label>
      <input id="ROLL_DIAMETER" name="ROLL_DIAMETER" type="text" style="max-width: 100px;" value="<?php echo isset($_POST["ROLL_DIAMETER"])&&(!$batch_state) ? $_POST["ROLL_DIAMETER"] : '' ?>"/>
      &nbsp;&nbsp;
    <hr>
      <label for="NUM_HOLE">Number of Holes:</label>
      <input type="text" name="NUM_HOLE" id="NUM_HOLE" style="max-width: 100px;" value="<?php echo isset($_POST["NUM_HOLE"])&&(!$batch_state) ? $_POST["NUM_HOLE"] : '' ?>"/>
      <label for="NUM_DELAM">Number of Delaminations:</label>
      <input type="text" name="NUM_DELAM" id="NUM_DELAM" style="max-width: 100px;" value="<?php echo isset($_POST["NUM_DELAM"])&&(!$batch_state) ? $_POST["NUM_DELAM"] : '' ?>"/>
      <label for="NUM_SPLICE">Number of Splices:</label>
      <input type="text" name="NUM_SPLICE" id="NUM_SPLICE" style="max-width: 100px;" value="<?php echo isset($_POST["NUM_SPLICE"])&&(!$batch_state) ? $_POST["NUM_SPLICE"] : '' ?>"/>
    </p>
    <hr>
    <p>
      <label for="LAM_TEMP_UPPER">Upper Roll Temperature:</label>
      <input type="text" name="LAM_TEMP_UPPER" value="<?php echo htmlentities($row['LAM_TEMP_UPPER']); ?>"style="max-width: 100px;" />
      <label for="LAM_TEMP_LOWER">Lower Roll Temperature:</label>
      <input type="text" name="LAM_TEMP_LOWER" value="<?php echo htmlentities($row['LAM_TEMP_LOWER']); ?>"style="max-width: 100px;" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </p>
    <p>
      <label for="GAP_OP">Gap Operator Side:</label>
      <input type="text" name="GAP_OP" value="<?php echo htmlentities($row['GAP_OP']); ?>"style="max-width: 100px;" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <label for="GAP_MACHINE">Gap Side Machine:</label>
      <input type="text" name="GAP_MACHINE" value="<?php echo htmlentities($row['GAP_MACHINE']); ?>" style="max-width: 100px;"/>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <label for="LAM_SPEED">Line Speed:</label>
      <input type="text" name="LAM_SPEED" value="<?php echo htmlentities($row['LAM_SPEED']); ?>" style="max-width: 100px;"/>
      <label for="LAM_SPEED">meters/minute</label>
    </p>
    <p>
      <label for="NOTES">Note:</label>
      <input type="text" name="NOTES" id="NOTES">
    </p>
    <input type="submit" value="Submit">
    </form>

    <?php
      if (count($_REQUEST)!==0) {
        if (!$batch_state) {
          $sql_task_manager->error_msg_print();
          echo "<h3>"."Above ERRORS needed to be corrected before records can be added!"."</h3>";
          return;
        } elseif (!$spec_state) {
          $sql_task_manager->error_msg_print();
        }
        //Sets date and timestamp
        $_REQUEST['LAM_DATE'] = date("Y-m-d");
        $_REQUEST['TIMESTAMP'] = date("m/d/Y-H:i:s");
        $_REQUEST['TAPE_TEST'] = 0; $_REQUEST['BEGIN_OP'] = 0; $_REQUEST['BEGIN_CENTER'] = 0; $_REQUEST['BEGIN_MACHINE']=0;
        //Update Amount of CAF used based on serial number
        $inventory_manager = new sql_task_manager("localhost", "root", "PQch782tdk@@", "INVENTORY_TEST");
        $update_length = "UPDATE INVENTORY_TABLE SET AMOUNT_USED = AMOUNT_USED + ? WHERE SERIAL=?";
        $inventory_manager->pdo_sql_vali_execute($update_length, array($_REQUEST['ELECTRODE_LENGTH'], $_REQUEST['CAF_BATCH_NUM']));
        $inventory_manager->pdo_sql_vali_execute($update_length, array($_REQUEST['ELECTRODE_LENGTH'], $_REQUEST['CAF_BATCH_NUM_2']));
        # get the last record
        $row = $sql_task_manager->pdo_sql_row_fetch("SELECT ELECTRODE_SERIAL FROM LAMINATOR ORDER BY ID DESC LIMIT 1");
        # sprcific ELECTRODE seiral compuation in Laminator
        $_REQUEST['ELECTRODE_SERIAL'] = $sql_task_manager->ID_computation($row['ELECTRODE_SERIAL'], $_REQUEST['THICKNESS'], $_REQUEST['LAM_ID'], "E-".explode("-", $_REQUEST['UPPER_FILM_BATCH_NUM'])[0]."-", 3);
        $LAM_DATA_CHART = array("BATCH_NUM"=>$_REQUEST['ELECTRODE_SERIAL'], "END_CENTER"=>$_REQUEST['END_CENTER'], "DATE"=>date("Y-m-d"), "THICKNESS"=>$_REQUEST['THICKNESS']);
        if ($sql_task_manager->sql_insert_gen($LAM_DATA_CHART, 'LAM_CHART_DATA')) {
          echo "<h3>"."Chart Records added successfully!"."</h3>";
        } else {
          echo "<h3>"."Unsuccessful insertion! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
        }
        $_REQUEST['AVG_THICKNESS'] = ($_REQUEST['END_OP'] + $_REQUEST['END_CENTER'] + $_REQUEST['END_MACHINE'])/3;
        $_REQUEST['NUM_DEFECT'] = $_REQUEST['NUM_SPLICE'] + $_REQUEST['NUM_HOLE'] + $_REQUEST['NUM_DELAM'];
        if ($sql_task_manager->sql_insert_gen($_REQUEST, 'LAMINATOR')) {
          echo "<h3>"."Records added successfully!"."</h3>";
          //Creates new label
          /* Get the port for the service. */
          $port = "9100";

          /* Get the IP address for the target host. */
          $host = "10.1.10.191";

          /* construct the label */
          $label = "﻿CT~~CD,~CC^~CT~
          ^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ
          ^XA
          ^MMT
          ^PW609
          ^LL0203
          ^LS0
          ^FO192,96^GFA,03584,03584,00028,:Z64:
          eJzt1D1u7CAQB3CQC0qSE3AUzpQTQJRiy1zJuQlPuQDuKAiTGQZ7MbaeXvM6Rivvip/X5uMPQsyaNWvWfywJn4BfCrywQQiTLZSLiYUaySAc9mhmAFa21JkXQoNwAJEtD1YEUGO1ctgHPgptAWqsBmczWQE1svnd3tk0mWdbn4Z9tsmQrQb74Z7m2SwE/JCpfYBkkf+HjZFMDuaizvi7mqCGZo7eFFV89S5Vc4MFFbZoL2arya8N5dZW+diKZrNP45EJvYEuvVk4zGzw6G2B3XAKX9TJaBK9hn16l97sYYIf0xkNzdesXA2aqVLv++5MVluQ2f50tuym7yxLtnxjAU3CemOKgMykS1+wh2gCo5AuYzisze7JSjX3N4u7dfOpd3OYrGGNmtlEhmv7eWtrtT4T7X0m4bJTXsyNZbwsmDObL+PTP3hR4S207J6MIqEThX2cTzIcQsGhx3Ed8Bag6GBwwri2aIVjxXuzz0Q7ddqePmeJTbezYDAJdSfzGXLOJ5tsZ89hplniZ0Qx7gdsTpz/0JtsxhNBM9Dvza6Os3HWrFmz/qF+AdfROhU=:6460
          ^FT593,94^A0I,23,24^FH\^FDSerial: " . $_REQUEST['ELECTRODE_SERIAL'] . "^FS
          ^BY2,3,32^FT593,53^BCI,,N,N
          ^FD>:" . $_REQUEST['ELECTRODE_SERIAL'] . "^FS
          ^FT593,11^A0I,28,28^FH\
          ^FT593,9^A0I,23,24^FH\^FDLENGTH: " . $_REQUEST['ELECTRODE_LENGTH'] . "meters Diameter:" . $_REQUEST['ROLL_DIAMETER']."^FS
          ^PQ1,0,1,Y^XZ";
          $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
          if ($socket === false) {
              echo "socket_create() failed: reason: " . socket_strerror(socket_last_error    ()) . "<br/>";
          } else {
              echo "OK"."<br/>";
          }

          echo "Attempting to connect to '$host' on port '$port'...";
          $result = socket_connect($socket, $host, $port);
          if ($result === false) {
              echo "socket_connect() failed.\nReason: ($result) " . socket_strerror    (socket_last_error($socket)) . "<br/>";
          } else {
              echo "OK"."<br/>";
          }
          socket_write($socket, $label, strlen($label));
          socket_close($socket);
          echo "<script>setTimeout(\"location.href = 'http://10.1.10.190/Laminator_2.php';\",2000);</script>";
        } else {
          echo "<h3>"."Unsuccessful insertion! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
        }
      }
    ?>
  </body>
</html>
