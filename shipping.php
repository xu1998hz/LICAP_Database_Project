<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SLITTER DATA ENTRY PAGE</title>
</head>
  <body>
  <?php
    require_once('sql_task_manager.php');
    $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
    $sql_command = "SELECT PACKAGE_OP, PALLET_NUM, BOX_NUM FROM SLITTER ORDER BY ID DESC LIMIT 1";
    $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);
    $row['BOX_NUM'] = $row['BOX_NUM'] < 16 ? $row['BOX_NUM']++ : 1;
    if (count($_REQUEST)!==0) {
      $state = $sql_task_manager->batch_opt_db_vali(array('COMBINED_SERIAL'),
      array("Electrical Serial"), 'ELECTRODE_SERIAL', 'LAMINATOR', 0, '');
    }
  ?>
  <form action="shipping.php" method="post">
  <p>
  <HR>
    <label for='PACKAGE_OP'>Shipping Operator Name:</label>
    <input id='PACKAGE_OP' name='PACKAGE_OP' type="text" value="<?php echo htmlentities($row['PACKAGE_OP']); ?>" />
  </p>

  <p style="<?php echo $sql_task_manager->color_ls_read("COMBINED_SERIAL") ?>">
    <label for="COMBINED_SERIAL">Electrode Serial</label>
    <input id="COMBINED_SERIAL" name="COMBINED_SERIAL" type="text" value = "<?php echo isset($_POST['COMBINED_SERIAL'])&&(!$state) ? $_POST['COMBINED_SERIAL'] : '' ?>">
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
    <input id="WEIGHT" name="WEIGHT" type="text" style="max-width: 100px;" value = "<?php echo isset($_POST['WEIGHT'])&&(!$state) ? $_POST['WEIGHT'] : '' ?>">
    &nbsp;&nbsp;&nbsp;
    <label for="ROLL_DIAMETER">Roll Diameter</label>
    <input id="ROLL_DIAMETER" name="ROLL_DIAMETER" type="text" style="max-width: 100px;" value = "<?php echo isset($_POST['ROLL_DIAMETER'])&&(!$state) ? $_POST['ROLL_DIAMETER'] : '' ?>">
  </p>

  <p>
  <hr>
    <label for="NOTES">Notes:</label>
    <input type="text" name="NOTES" id="NOTES" value = "<?php echo isset($_POST['NOTES'])&&(!$state) ? $_POST['NOTES'] : '' ?>">
  </p>
  <input type="submit" value="Submit">
  </form>

  <?php
  // without user inputs, this part of user codes are not executing
  if (count($_REQUEST)===0){
    return;
  }
  if (!($state)) {
    $sql_task_manager->error_msg_print();
    echo "<h3>"."Above ERRORS needed to be corrected before records can be added!"."</h3>";
    return;
  }
  if ($sql_task_manager->query_record_exists('COMBINED_SERIAL', 'SLITTER', $_REQUEST['COMBINED_SERIAL'])) {
    $sql = "UPDATE SLITTER SET WEIGHT = ?, ROLL_DIAMETER = ?, PALLET_NUM = ?, BOX_NUM = ?, NOTES = ?, PACKAGE_OP=? WHERE COMBINED_SERIAL = ?";
    $result_arr = $sql_task_manager->pdo_sql_vali_execute($sql, array($_REQUEST['WEIGHT'], $_REQUEST['ROLL_DIAMETER'], $_REQUEST['PALLET_NUM'], $_REQUEST['BOX_NUM'],
    $_REQUEST['NOTES'],  $_REQUEST['PACKAGE_OP'], $_REQUEST['COMBINED_SERIAL']));
    if ($result_arr[1]) {
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
        //Creates new label
        /* Get the port for the service. */
        $port = "9100";

        /* Get the IP address for the target host. */
        $host = "10.1.10.194";

        /* construct the label */
        $label = "﻿CT~~CD,~CC^~CT~
        ^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ
        ^XA
        ^MMC
        ^PW609
        ^LL0203
        ^LS0
        ^FO384,96^GFA,03584,03584,00028,:Z64:
        eJzt1LFuxCAMAFAiBkY+Aak/wqdxUoeO/SU69Teo8gOMDAjXxuGAJCd17IB1wyXvxBlsI8SKFStW/MNwOzyEkCA2CPj0A5BG80IoEBoKW+n2c5gFXICM1hlNF4EvA5sfLJBJfBnZwtNCtayAsqgWZzNZA2VRLXVz+DuTDL4EtjybTZYN1xjN4ho2WhAGHmS2nM0k3KR3NefBaLWoI50amRqsZhGU3/0rk+97dLEadKOdgd/UnuwLE2bPs0n6p2bFJDJ5mGtW6zLZBpPp0VTNsJ7WxTTbQ94YbS1saLhd/PqZB7NsXrF9XEw87Xs0V6hi4HW+sVQtHDatiSXHJzy2dM2FOhI/VNjLHvAY8QmLHl+bfWkmkdkdzK0FsnJnNb1nbSfT2XkhdeuJG6NeCpf9qYKl1V+tB9u5dDPxTXJfT2cmqSVsbj3fLB9GXdVmZapftT5jk210bRie21O/sOk6sOc+40k97oJTf7Idd0g3yeZowutQjPNwWOK8xGSmm6aVRxuj32MrVqxY8Yf4BfwKIv4=:5FAF
        ^FT593,54^A0I,14,14^FH\^FDLOT:" . $_REQUEST['COMBINED_SERIAL'] . "^FS
        ^FT593,11^A0I,28,28^FH\^FDLENGTH:" . $row_result['ELECTRODE_LENGTH'] . " METERS^FS
        ^FT175,204^BQN,2,5
        ^FH\^FDLA," . $_REQUEST['COMBINED_SERIAL'] . "^FS
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
        header("refresh: 1"); 
      } else {
        echo "<h3>"."Unsuccessful update! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
      }
    }
  }
  ?>
 </body>
</html>
