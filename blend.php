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
    // error handlings to check Operator input their names
    $state = true;
    if (count($_REQUEST)!==0) {
      if ($_REQUEST["MIXING_OP"]==='') {
        $sql_task_manager->color_ls_update("MIXING_OP");
        $sql_task_manager->error_msg_append("ERROR: Mixer Operator name can't be empty"."<br/>");
        $state = false;
      }
      if ($_REQUEST["GRIND_OP"]==='') {
        $sql_task_manager->color_ls_update("GRIND_OP");
        $sql_task_manager->error_msg_append("ERROR: Jet Mill Operator name can't be empty"."<br/>");
        $state = false;
      }
    }
  ?>
  <p style="<?php echo $sql_task_manager->color_ls_read("MIXING_OP") ?>">
    <label> Mixer Operator:</label>
    <input type="text" name="MIXING_OP"/>
  </p>
  <p style="<?php echo $sql_task_manager->color_ls_read("GRIND_OP") ?>">
    <label for="GRIND_OP">Jet Mill Operator:</label>
    <input type="text" name="GRIND_OP"/>
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
    <label for="CNT_SERIAL">CNT Lot Number:</label>
    <input type="text" name="CNT_SERIAL"/>
    <label for="CNT_AMOUNT">CNT Weight:</label>
    <input type="text" name="CNT_AMOUNT"/>
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
      document.getElementById("PTFE").innerHTML = (0.08*AC_WEIGHT/0.92)+((0.018/0.987)*(BAGHOUSE_RECYCLE+STRIP_RECYCLE+OUTSIDE_RECYCLE));
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
    if (!$state) {
      $sql_task_manager->error_msg_print();
      echo "<h3>"."Above ERRORS needed to be corrected before records can be added!"."</h3>";
      return;
    }
    $_REQUEST['BATCH_NUM'] = $Batch_num;
    $_REQUEST['AC_LOT_NUM'] = $_REQUEST['AC_LOT_1']."-".$_REQUEST['AC_NUM_1'];
    $_REQUEST['AC_LOT_NUM_2'] = $_REQUEST['AC_LOT_2']."-".$_REQUEST['AC_NUM_2'];
    $_REQUEST['AC_LOT_NUM_3'] = 0;
    unset($_REQUEST['AC_LOT_1']); unset($_REQUEST['AC_NUM_1']);
    unset($_REQUEST['AC_LOT_2']); unset($_REQUEST['AC_NUM_2']);
    $_REQUEST['DATE'] = date("m/d/Y");
    $_REQUEST['TIMESTAMP'] = date("m/d/Y-H:i:s");
    $_REQUEST['BATCH_WEIGHT'] = $_REQUEST['AC_WEIGHT'] + $_REQUEST['ACETONE_WEIGHT'] + $_REQUEST['STRIP_RECYCLE']
     + $_REQUEST['BAGHOUSE_RECYCLE'] + $_REQUEST['OUTSIDE_RECYCLE'] + $_REQUEST['PTFE_WEIGHT'];
    $_REQUEST['PTFE_RECYCLE'] = (0.0204 * ($_REQUEST['STRIP_RECYCLE'] + $_REQUEST['BAGHOUSE_RECYCLE'] + $_REQUEST['OUTSIDE_RECYCLE']));
    $_REQUEST['ACETONE_PERCENT'] = (($_REQUEST['ACETONE_WEIGHT']/($_REQUEST['BATCH_WEIGHT'] - $_REQUEST['PTFE_RECYCLE']))*100);
    $_REQUEST['PTFE_PERCENT'] = ((($_REQUEST['PTFE_WEIGHT'] - $_REQUEST['PTFE_RECYCLE'])/($_REQUEST['AC_WEIGHT'] + $_REQUEST['PTFE_WEIGHT'] - $_REQUEST['PTFE_RECYCLE']))*100);
    if ($sql_task_manager->sql_insert_gen($_REQUEST, 'blend')) {
      echo "<h3>"."Records added successfully!"."</h3>";
      //Creates new label
      error_reporting(E_ALL);

      /* Get the port for the service. */
      $port = "9100";

      /* Get the IP address for the target host. */
      $host = "10.1.10.196";

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
      ^FT593,94^A0I,23,24^FH\^FDBATCH NUMBER: " . $Batch_num . "^FS
      ^BY2,3,32^FT593,53^BCI,,N,N
      ^FD>:" . $Batch_num . "^FS
      ^FT593,11^A0I,28,28^FH\
      ^PQ1,0,1,Y^XZ";

      $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      if ($socket === false) {
          echo "socket_create() failed: reason: " . socket_strerror(socket_last_error    ()) . "\n";
      } else {
          echo "OK.\n";
      }

      echo "Attempting to connect to '$host' on port '$port'...";
      $result = socket_connect($socket, $host, $port);
      if ($result === false) {
          echo "socket_connect() failed.\nReason: ($result) " . socket_strerror    (socket_last_error($socket)) . "\n";
      } else {
          echo "OK.\n";
      }

      socket_write($socket, $label, strlen($label));
      socket_close($socket);
      header("refresh: 1");
    } else {
      echo "<h3>"."Unsuccessful insertion! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
    }
  ?>
  </body>
</html>
