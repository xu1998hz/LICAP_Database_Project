<?php
  require_once('sql_task_manager.php');
  $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
  # Those are the column names which require further computation
  $computed_names = array('FILM_ID', 'TIMESTAMP', 'DATE', 'AVG_THICKNESS', 'FILM_DENSITY');
  # computed values from above features
  $DATE = date("m/d/Y");
  $TIMESTAMP = date("m/d/Y-H:i:s");
  //Pull last Film Lot Number from Film database
  $sql_command = "SELECT FILM_ID FROM FILM ORDER BY ID DESC LIMIT 1";
  $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);
  $FILM_ID = $sql_task_manager->ID_computation($row, $_REQUEST['THICKNESS'], $_REQUEST['FILM_MILL']);

  //AVG Thickness and Film Density Calc
  $AVG_THICKNESS = 2.0;
  //$AVG_THICKNESS = ($END_OP + $END_CENTER + $END_MACHINE)/3;
  $NORMALIZE_WEIGHT = $_REQUEST['FILM_WEIGHT']/8;
  $FILM_DENSITY = $NORMALIZE_WEIGHT/(5.064506 * $AVG_THICKNESS/10000);
  /*
  if ($AVG_THICKNESS > 145 && $AVG_THICKNESS < "155") {
  $MESSAGE = "IN-SPEC Proceed to Laminate";
  } else if ($AVG_THICKNESS > '95' && $AVG_THICKNESS < '105') {
  $MESSAGE = "IN-SPEC Proceed to Laminate";
  } else if ($AVG_THICKNESS > '85' && $AVG_THICKNESS < '95') {
  $MESSAGE = "IN-SPEC Proceed to Laminate";
  else { $MESSAGE = "OUT OF SPEC HOLD FOR EVALUATION";}
  */
  $vals_array = array_values($_REQUEST);
  array_pop($vals_array);
  array_push($vals_array, $FILM_ID, $TIMESTAMP, $DATE, $AVG_THICKNESS, $FILM_DENSITY);
  if ($sql_task_manager->pdo_sql_insert($_REQUEST, $computed_names, $vals_array))
  {
    echo "Records added successfully.";
  } else{
    echo "bad insertion";
  }
?>
