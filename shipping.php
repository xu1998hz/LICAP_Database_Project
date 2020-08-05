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
    $Proc_State = 'PROCESSED';
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
    $Proc_State = 'UNPROCESSED';
    if ($sql_task_manager->query_record_exists('ELECTRODE_SERIAL', 'LAMINATOR', $_REQUEST['COMBINED_SERIAL'])) {
      $FETCH = "SELECT ELECTRODE_LENGTH, NUM_DEFECT, NUM_HOLE, NUM_DELAM, NUM_SPLICE, FOIL_TYPE, THICKNESS FROM LAMINATOR WHERE ELECTRODE_SERIAL = :str_1 LIMIT 1";
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
        // print the small label on 193 printer; Get the IP address for the target host.
        $port = "9100";
        $host = "10.1.10.193";
        /* construct the label for small label */
        $label_small = "﻿CT~~CD,~CC^~CT~
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
        $sql_task_manager->socket_connect_label($host, $port, $label_small);
        // print the large label for shipping
        $host = "10.1.10.194";
        $P_N = implode('-',array($row_result['FOIL_TYPE'], $row_result['THICKNESS']));
        /* construct the label for big label */
        $label_big = "CT~~CD,~CC^~CT~
              ^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR6,6~SD15^JUS^LRN^CI0^XZ
              ~DG000.GRF,11520,024,
              ,::::::::::::::::::U07FgIF,T05FgJF,S07FgKF,S0gMF,R07FgLF,R0gNF,Q01FgMF,:Q03FgMF,Q07FgMF,:P01FgNF,P03FgNF,::P07FgNF,P0gPF,O01FgOF,::O03FgOF,O07FgOF,O0gQF,:N01FgPF,:::N03FgPF,N03FLF,N03FKFE,N0MF8,:N0MF0,N0LFE0,N0LFC0,:N0LF80,::::::::::::::::::::::::::::::::::::::::::,::::::N0gRF80,:::::::::::::::::::::::::,::::::gG0NF8,Y0QF80,X01FQFC,W03FSF,W0UFC0,V03FUF0,U07FVFE,U0YF,T03FXFC0,T0gGF8,S03FgFC,S07FgGF,S0gIF,R01FgHF80,R03FgHFC0,R07FgHFE0,R0PF8001FOFE0,R0OF80K07FMF0,Q03FMFC0L01FMF8,Q03FMFO01FLFC,Q07FLFQ01FKFE,Q0MFC0Q0MF,P01FLFC0Q07FKF,P03FLF80Q03FKF80,P0NFS01FKFC0,O01FLFC0J07FE0K07FJFE0,O01FLF80H0107FE0K03FKF0,O03FLFJ0F07FE0E0J0LF0,O07FKFC0H07F07FE0F80I0LF8,O0MF8001FF07FE0FF0I07FJF8,O0MFI07FF07FE0FFC0H03FJF8,O0LFE0H0IF07FE0FFE0H01FJF8,N01FKFC001FHF07FE0FHFJ0KFC,N01FKF8007FHF07FE0FHFC0H03FIFC,N03FJFE0H0JF07FE0FHFE0H03FIFE,N07FJFC001FIF07FE0FIFI03FIFE,N07FJF8003FIF07FE0FIF8003FJF,N07FJF8007FIF07FE0FIFC003FJF,N07FJFI0KF07FE0FIFE001FJF,N07FJFH01FJF07FE0FJFH01FJF80,N07FIFE001FJF07FE0FJFI0KF80,N07FIFE003FJF07FE0FJF800FJF80,N07FIFE007FJF07FE0FJFC00FJF80,:N07FIFE00FKF07FE0FJFE00FJFC0,:N07FIFE01FKF07FE0FKFH0KFC0,::N07FIFE03FKF07FE0FKF80FJFE0,:N07FIFE03FKF07FE0FKF807FIFE0,N07FIFE0N07FE0N07FIFE0,:::::::::N07FIFE03FKF07FE0FKF807FIFE0,:N07FIFE03FKF07FE0FKF80FJFC0,::N07FIFE01FKF07FE0FKFH0KFC0,N07FIFE01FKF07FE0FKF01FJF80,:N07FIFE00FKF07FE0FJFE01FJF80,:N07FIFE007FJF07FE0FJFC01FJF80,N07FIFE007FJF07FE0FJFC03FIFE,N07FIFE003FJF07FE0FJF803FIFE,N07FJFH01FJF07FE0FJFH03FIFE,N01FJFH01FJF07FE0FJFH07FIFC,N01FJF800FJF07FE0FIFE00FJFC,O0KF8007FIF07FE0FIFC00FJF8,O07FIF8003FIF07FE0FIF803FJF8,O07FIFC001FIF07FE0FIFH03FJF8,O07FIFC0H0JF07FE0FHFE007FJF8,O03FIFE0H07FHF07FE0FHFC00FKF0,O03FJF8003FHF07FE0FHFI0KFE0,O01FJFC0H0IF07FE0FFE001FJFE0,P0KFE0H07FF07FE0FFC003FJF80,P0LFI01FF07FE0FF0H07FJF80,P07FJFJ07F07FE0FC0H0LF,P03FJF80H01F07FE0F0I0KFE,P01FJFC0I0107FE0J01FJFC,Q0KFE0K07FE0J03FJF8,Q07FIFE0S03FJF8,Q07FIFE0S07FJF0,Q03FIFC0S07FJF0,Q03FIFC0S07FIFE0,Q01FIFC0S07FIFC0,R0JF80S03FIF80,R07FHFU01FIF80,R03FFE0U0IFE,R03FFC0U07FFC,S07F80U03FFC,T070W0FE0,gS07C0,,:::N03FC0,N03FFC,N03FIF0,N03FIFC,N03FJF80,N03FJFE0,N03FLF80,N03FLFE0,N03FMF0,N03FMFE,N03FNF,N03FNFE0,N03FOF8,N03FOFE,N03FPFC0,N03FRF,N03FRFC0,N03FSF0,N03FTF,N03FTFC0,N03FUF0,N03FUFC,N03FVF80,N03FWF0,N03FWFC,N03FXF80,N03FXFE0,N03FYF0,O0gFE,O03FYF,P07FYF0,Q07FXFC,Q01FXFE,R01FXFC0,S03FWFE0,T03FWFC,U01FWF,U01FWFC0,U01FXF8,U01FXFE,U01FYF80,U01FgF0,U01FgFC,U01FJFC3FTF,U01FJFC0FUF0,U01FJFC01FUF,U01FJFC0H07FSF80,U01FJFC0I07FRFC0,U01FJFC0J01FQFE0,:U01FJFC0L0QFE0,U01FJFC0L01FOFE0,U01FJFC0M07FNFE0,U01FJFC0M01FNFE0,U01FJFC0N03FMFE0,U01FJFC0O07FLFE0,::::::U01FJFC0O0NFE0,U01FJFC0M07FNFE0,U01FJFC0L03FOFE0,U01FJFC0K03FPFE0,U01FJFC0K0RFE0,U01FJFC0J07FQFE0,U01FJFC0I0TFE0,U01FJFC0H03FSFE0,U01FJFC0H07FSF80,U01FJFC001FTF,U01FJFC00FTF8,U01FJFC07FTF8,U01FJFC3FTF80,U01FgGF,U01FgFE,U01FgF8,U01FYF80,U01FXFC,U0YFE0,T0gFC0,S03FYF,R03FYF0,Q01FYF80,Q07FXFE,P0gGF0,O01FYFE0,O07FYF,N01FYF0,N01FXFC0,N01FWFC,N01FVFE0,N01FUFE,N01FTFE0,N01FTF,N01FSF8,N01FRF80,N01FRF,N01FQF8,N01FPF80,N01FOFE,N01FNFC0,N01FMFE,:N01FLFE0,N01FKFE,N01FKF8,N01FKF0,N01FJF,N01FIFC,N01FIF0,N01FHF80,N01FFC,N01FF8,,:::::::::N01FgPF80,:::::::::::::::::::N01FTF80N01FJF80,:::Y01FIF80N01FJF80,:::::::::::::::::::::::::Y01FJFC0M01FJF80,::Y01FJFC0L03FKF80,::g0MFK01FLF80,:g07FXF80,:g03FXF80,g03FXF,g01FXF,g01FWFE,gG0XFE,gG0XFC,gG0XF8,gG07FVF0,gG03FUFE0,gG03FUFC0,gG01FUF80,gH07FTF,gH03FTF,gI0TFE,gI07FRFC,gI07FQFE0,gI03FQF80,gI01FQF,gJ03FOF8,gJ01FNFE0,gK0OF,gK03FLF0,,::::::::::::::::::::::::::^XA
              ^MMC
              ^PW812
              ^LL1218
              ^LS0
              ^FT567,33^A0R,39,31^FH\^FDLot:".$_REQUEST['COMBINED_SERIAL']."^FS
              ^FT400,31^A0R,62,62^FH\^FDLength:".$row_result['ELECTRODE_LENGTH']." Meters^FS
              ^BY3,3,68^FT471,30^BCR,,N,N
              ^FD>:2E2F-150->508052020>6-18^FS
              ^FT143,534^A0R,135,134^FB327,1,0,C^FH\^FDP08-^FS
              ^FT146,854^A0R,135,134^FH\^FDB04^FS
              ^FT314,27^A0R,51,50^FH\^FDDiameter:".$_REQUEST['ROLL_DIAMETER']."mm^FS
              ^FT397,813^A0R,37,38^FH\^FDWEIGHT:".$_REQUEST['WEIGHT']."KG^FS
              ^FT314,818^A0R,39,38^FH\^FDP/N:".$P_N."^FS
              ^FT182,42^A0R,56,57^FH\^FD".$Proc_State."^FS
              ^FT608,832^XG000.GRF,1,1^FS
              ^FT36,467^A0R,51,50^FH\^FDMADE IN USA^FS
              ^PQ1,1,1,Y^XZ
              ^XA^ID000.GRF^FS^XZ";
        $sql_task_manager->socket_connect_label($host, $port, $label_big);
        echo "<script>setTimeout(\"location.href = 'http://10.1.10.190/shipping.php';\",2000);</script>";
      } else {
        echo "<h3>"."Unsuccessful update! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
      }
    }
  }
  ?>
 </body>
</html>
