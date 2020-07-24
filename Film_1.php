<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>FILM DATA ENTRY PAGE</title>
</head>
 <body>
   <?php
     require_once('sql_task_manager.php');
     $langs_trans = array("END_OP" => "Ending Operator Thickness", "END_CENTER" => "End Center Thickness", "END_MACHINE" => "Ending Machine Side Thickness");
     $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
     $sql_command = "SELECT FILM_1_OP, FILM_2_OP, MIX_BATCH_NUM, MIX_BATCH_NUM_2, THICKNESS, MILL_TEMP, CAL_1_TEMP, CAL_2_TEMP, LINE_SPEED FROM FILM WHERE FILM_MILL=1 ORDER BY ID DESC LIMIT 1";
     $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);
     //Pull last Film Lot Number
     $sql_command = "SELECT FILM_ID FROM FILM ORDER BY ID DESC LIMIT 1";
     $row_2 = $sql_task_manager->pdo_sql_row_fetch($sql_command);
     //Compare last batch date with current date
     $FILM_ID = $sql_task_manager->ID_computation($row_2['FILM_ID'], $row['THICKNESS'], 1, 'F-', 2);
     echo "<H1>FILM MILL 1 Log</H1>";
     echo "<h1>" . "Current Roll:" . $FILM_ID . "</h1>";
     // check if this is before user inputs, error handlings on the existing user inputs
     if (count($_REQUEST)!==0) {
       # error handling on userinput for END_OP, END_CENTER and END_MACHINE
       $batch_state = $sql_task_manager->batch_opt_db_vali(array('MIX_BATCH_NUM'), array("Powder Batch"), 'BATCH_NUM', 'blend', 1, '1');
       $spec_state = $sql_task_manager->user_Input_spec_vali($_REQUEST, $langs_trans, 156, 94, 2);
     }
   ?>

   <form action="Film_1.php" method="post">

   <input id="FILM_MILL" name="FILM_MILL" type="hidden" value="1"/>
   <p>
     <label for="LENGTH">Length:</label>
     <input type="text" name="LENGTH" id="LENGTH", value="<?php echo isset($_POST['LENGTH'])&&(!$batch_state) ? $_POST['LENGTH'] : '' ?>">
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <label for="THICKNESS">Thickness Spec</label>
     <input type="text" name="THICKNESS" value="<?php echo htmlentities($row['THICKNESS']); ?>" />
   </p>

   <p style="<?php echo $sql_task_manager->color_ls_read("END_OP") ?>">
     <label for="END_OP">Ending Operator Thickness:</label>
     <input id="END_OP" name="END_OP" value="<?php echo isset($_POST['END_OP'])&&(!$batch_state) ? $_POST['END_OP'] : '' ?>" type="text" onchange="changeBackground4(this);" />
   </p>
   <p style="<?php echo $sql_task_manager->color_ls_read("END_CENTER") ?>">
     <label for="END_CENTER">End Center Thickness:</label>
     <input id="END_CENTER" name="END_CENTER" value="<?php echo isset($_POST['END_CENTER'])&&(!$batch_state) ? $_POST['END_CENTER'] : '' ?>" type="text" onchange="changeBackground6(this);" />
   </p>
   <p style="<?php echo $sql_task_manager->color_ls_read("END_MACHINE") ?>">
     <label for="END_MACHINE">Ending Machine Side Thickness:</label>
     <input id="END_MACHINE" name="END_MACHINE" value="<?php echo isset($_POST['END_MACHINE'])&&(!$batch_state) ? $_POST['END_MACHINE'] : '' ?>" type="text" onchange="changeBackground6(this);" />
   </p>

   <hr>

   <p style="<?php echo $sql_task_manager->color_ls_read("MIX_BATCH_NUM") ?>">
     <label for="MIX_BATCH_NUM">Powder Batch 1</label>
     <input type="text" name="MIX_BATCH_NUM" value="<?php echo htmlentities($row['MIX_BATCH_NUM']); ?>" />
   </p>
   <p style="<?php echo $sql_task_manager->color_ls_read("MIX_BATCH_NUM_2") ?>">
     <label for="MIX_BATCH_NUM_2">Powder Batch 2</label>
     <input type="text" name="MIX_BATCH_NUM_2" value="<?php echo htmlentities($row['MIX_BATCH_NUM_2']); ?>" />
   </p>

   <hr>

   <p>
     <label for="DEFECT_NUM">Number of Defects:</label>
     <input type="text" name="DEFECT_NUM" id="DEFECT_NUM" value="<?php echo isset($_POST['DEFECT_NUM'])&&(!$batch_state) ? $_POST['DEFECT_NUM'] : '' ?>">
   </p>

   <p>
     <label for="FILM_WEIGHT">Weight of 8-Layer Punch:</label>
     <input type="text" name="FILM_WEIGHT" id="FILM_WEIGHT" value="<?php echo isset($_POST['FILM_WEIGHT'])&&(!$batch_state) ? $_POST['FILM_WEIGHT'] : '' ?>">
     <label for="FILM_WEIGHT">g</label>
   </p>

   <p>
     <label for="FILM_NOTE">Note:</label>
     <input type="text" name="FILM_NOTE" id="FILM_NOTE" value="<?php echo isset($_POST['FILM_NOTE'])&&(!$batch_state) ? $_POST['FILM_NOTE'] : '' ?>">
   </p>

   <hr>

   <p>
     <label for="MILL_TEMP">Mill Temperature:</label>
     <input type="text" name="MILL_TEMP" value="<?php echo htmlentities($row['MILL_TEMP']); ?>" />
     &nbsp;&nbsp;&nbsp;&nbsp;
     <label for="CAL_1_TEMP">Calender 1 Temperature:</label>
     <input type="text" name="CAL_1_TEMP" value="<?php echo htmlentities($row['CAL_1_TEMP']); ?>" />
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <label for="CAL_2_TEMP">Calender 2 Temperature:</label>
     <input type="text" name="CAL_2_TEMP" value="<?php echo htmlentities($row['CAL_2_TEMP']); ?>" />
   </p>

   <p>
     <label for="LINE_SPEED">Line Speed:</label>
     <input type="text" name="LINE_SPEED" value="<?php echo htmlentities($row['LINE_SPEED']); ?>" />
     <label for="LINE_SPEED">meters/minute</label>
   </p>

   <p>
     <label for="FILM_1_OP">Operator 1:</label>
     <input type="text" name="FILM_1_OP" value="<?php echo htmlentities($row['FILM_1_OP']); ?>" />
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <label for="FILM_2_OP">Operator 2:</label>
     <input type="text" name="FILM_2_OP" value="<?php echo htmlentities($row['FILM_2_OP']); ?>" />
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
       # compute values from above features
       $_REQUEST['DATE'] = date("m/d/Y");
       $_REQUEST['TIMESTAMP'] = date("m/d/Y-H:i:s");
       //Compute the film ID
       $sql_command = "SELECT FILM_ID FROM FILM ORDER BY ID DESC LIMIT 1";
       $row_3 = $sql_task_manager->pdo_sql_row_fetch($sql_command);
       $_REQUEST['FILM_ID'] = $sql_task_manager->ID_computation($row_3['FILM_ID'], $_REQUEST['THICKNESS'], $_REQUEST['FILM_MILL'], 'F-', 2);
       $_REQUEST['AVG_THICKNESS'] = ($_REQUEST['END_OP'] + $_REQUEST['END_CENTER'] + $_REQUEST['END_MACHINE'])/3;
       $NORMALIZE_WEIGHT = $_REQUEST['FILM_WEIGHT']/8;
       $_REQUEST['FILM_DENSITY'] = $NORMALIZE_WEIGHT/(5.064506 * $_REQUEST['AVG_THICKNESS']/10000);

       if ($sql_task_manager->sql_insert_gen($_REQUEST, 'FILM')) {
         echo "<h3>"."Records added successfully!"."</h3>";
       } else {
         echo "<h3>"."Unsuccessful insertion! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
       }
       //Creates new label
       /* Get the port for the service. */
       $port = "9100";

       /* Get the IP address for the target host. */
       $host = "10.1.10.192";

       /* construct the label */
       $label = "﻿CT~~CD,~CC^~CT~
       ^XA~TA000~JSN^LT0^MNW^MTD^PON^PMN^LH0,0^JMA^PR5,5~SD15^JUS^LRN^CI0^XZ
       ^XA
       ^MMT
       ^PW609
       ^LL0203
       ^LS0
       ^FO256,128^GFA,02304,02304,00024,:Z64:
       eJzt0rFtxDAMBVAaKlQyG3iNdF4sgLTBraRNottAhzQqBDGfupwty84AB5iFITwQBE2S6Ior3jP4pxLZQi4SP0Q2f+Bts5HSPA7OSFVPqyc451kkqOfOPT4OJdTL4IIS6nVz52m+wzMn4t6DepgL3MjmS6AlCNkKn3pHjfBFRp1G/0Rqc786Whf/AVJ3g+PlendoW57jcrfNjWy+dG7h9sRZPU919Fm9HB29J1OmcvDEcHNwFzhN1eSDe3V76iT/ejo4qXMa59Dc3SINc1t9mHOrv3xHeu1l74Fee+z+C0MK2KstnS+xuZ/+7qSf29Ofd9XPGUtAM7q4E293u98XltkSAu/3jgtsCdTfibqpmlB2voaLdMUVbx2/69wvHw==:09F5
       ^FT596,109^A0I,23,24^FH\^FDLOT: " . $_REQUEST['FILM_ID']. "^FS
       ^BY2,3,32^FT597,68^BCI,,N,N
       ^FD>:" . $_REQUEST['FILM_ID'] . "^FS
       ^FT597,38^A0I,28,28^FH\^FDLENGTH: " . $_REQUEST['LENGTH'] . " METERS^FS
       ^FT597,7^A0I,28,28^FH\^FDMS: ". $_REQUEST['END_MACHINE'] ."             C:". $_REQUEST['END_CENTER'] ."                  OS:". $_REQUEST['END_OP'] ." ^FS
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
     }
   ?>
 </body>

</html>
