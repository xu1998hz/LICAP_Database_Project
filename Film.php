<?php
  require_once('sql_task_manager.php');
  $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
  # error handling on userinput for END_OP, END_CENTER and END_MACHINE
  if ($sql_task_manager->user_Input_spec_vali($_REQUEST, array('END_OP', 'END_CENTER', 'END_MACHINE'), 156, 94, 2)) {
    $str_1 = $_REQUEST['MIX_BATCH_NUM'];
    print($str_1);
    $sql_cmd = "SELECT BATCH_NUM FROM BLEND WHERE BATCH_NUM=:str_1";
    $stmt = $sql_task_manager->pdo->prepare($sql_cmd);
    $stmt->execute(array(':str_1'=>$str_1));
    $row_temp = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "right before print line";
    if ($row_temp) {
      echo "value here confirmed";
    }
    # Those are the column names which require further computation
    $computed_names = array('FILM_ID', 'TIMESTAMP', 'DATE', 'AVG_THICKNESS', 'FILM_DENSITY');
    # compute values from above features
    $DATE = date("m/d/Y");
    $TIMESTAMP = date("m/d/Y-H:i:s");
    //Compute the film ID
    $sql_command = "SELECT FILM_ID FROM FILM ORDER BY ID DESC LIMIT 1";
    $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);
    $FILM_ID = $sql_task_manager->ID_computation($row, $_REQUEST['THICKNESS'], $_REQUEST['FILM_MILL']);
    //Currently optional, only default to 2.o, $AVG_THICKNESS = ($END_OP + $END_CENTER + $END_MACHINE)/3
    $AVG_THICKNESS = 2.0;
    $NORMALIZE_WEIGHT = $_REQUEST['FILM_WEIGHT']/8;
    $FILM_DENSITY = $NORMALIZE_WEIGHT/(5.064506 * $AVG_THICKNESS/10000);

    # computed values from above features
    $computed_vals_arr = array($FILM_ID, $TIMESTAMP, $DATE, $AVG_THICKNESS, $FILM_DENSITY);
    if ($sql_task_manager->pdo_sql_insert($_REQUEST, $computed_names, $computed_vals_arr)) {
      echo "Records added successfully.";
    } else {
      echo "bad insertion";
    }
  }
?>
