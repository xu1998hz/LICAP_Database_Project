<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>LAMINATOR 2</title>
</head>
  <body>
    <form action="Laminator.php" method="post">

    <p>

    <?php
      require_once('sql_task_manager.php');
      $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
      $sql_command = "SELECT LAM_OP, FOIL_TYPE, CAF_BATCH_NUM, CAF_BATCH_NUM_2, LAM_TEMP_UPPER, LAM_TEMP_LOWER, LAM_SPEED, GAP_OP, GAP_MACHINE, THICKNESS FROM LAMINATOR WHERE LAM_ID=1 ORDER BY ID DESC LIMIT 1";
      $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);

      $CAF_BATCH_NUM_FINAL_RESULT = $row['CAF_BATCH_NUM_2'] ? $row['CAF_BATCH_NUM_2'] : $row['CAF_BATCH_NUM'];

      //Pull last Film Lot Number
      $sql_command = "SELECT ELECTRODE_SERIAL FROM LAMINATOR ORDER BY ID DESC LIMIT 1";
      $row_2 = $sql_task_manager->pdo_sql_row_fetch($sql_command);

      $ELECTRODE_SERIAL = $sql_task_manager->ID_computation($row_2['ELECTRODE_SERIAL'], $row['THICKNESS'], '', 'E-', 3);
      echo "<h1> LAMINATOR 1 </h1>";
      echo "<h1>" . "Current Roll:" . $ELECTRODE_SERIAL . "</h1>";
    ?>

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
      <input id="CAF_BATCH_NUM_2" name="CAF_BATCH_NUM_2" type="text" />
      <input id="LAM_ID" name="LAM_ID" value ="1" type="hidden"/>
    </p>
    <hr>
    <p>
      <label for="UPPER_FILM_BATCH_NUM">Upper Film Batch Number:</label>
      <input id="UPPER_FILM_BATCH_NUM" name="UPPER_FILM_BATCH_NUM" type="text" />
      &nbsp;&nbsp;
      <label for="LOWER_FILM_BATCH_NUM">Lower Film Batch Number:</label>
      <input id="LOWER_FILM_BATCH_NUM" name="LOWER_FILM_BATCH_NUM" type="text" />
    </p>
    <p>
      <label for="UPPER_FILM_BATCH_NUM_2">Upper Film Batch Number 2:</label>
      <input id="UPPER_FILM_BATCH_NUM_2" name="UPPER_FILM_BATCH_NUM_2" type="text" />
      &nbsp;&nbsp;
      <label for="LOWER_FILM_BATCH_NUM_2">Lower Film Batch Number 2:</label>
      <input id="LOWER_FILM_BATCH_NUM_2" name="LOWER_FILM_BATCH_NUM_2" type="text" />
    </p>
      <label for="UPPER_FILM_BATCH_NUM_3">Upper Film Batch Number 3:</label>
      <input id="UPPER_FILM_BATCH_NUM_3" name="UPPER_FILM_BATCH_NUM_3" type="text" />
      &nbsp;&nbsp;
      <label for="LOWER_FILM_BATCH_NUM_3">Lower Film Batch Number 3:</label>
      <input id="LOWER_FILM_BATCH_NUM_3" name="LOWER_FILM_BATCH_NUM_3" type="text" />
    </p>
    <hr>
    <p>
      <label for="ELECTRODE_LENGTH">Length:</label>
      <input type="text" name="ELECTRODE_LENGTH" >
    <p>
      <label for="END_OP">Ending Operator Side Thickness:</label>
      <input id="END_OP" name="END_OP" type="text" style="max-width: 100px;" />
      &nbsp;&nbsp;
      <label for="END_CENTER">Ending Center Thickness:</label>
      <input id="END_CENTER" name="END_CENTER" type="text" style="max-width: 100px;"/>
      &nbsp;&nbsp;
      <label for="END_MACHINE">Ending Machine Side Thickness:</label>
      <input id="END_MACHINE" name="END_MACHINE" type="text" style="max-width: 100px;"/>
    </p>
    <hr>
    <p>
      <label for="ROLL_DIAMETER">Roll Diameter:</label>
      <input id="ROLL_DIAMETER" name="ROLL_DIAMETER" type="text" style="max-width: 100px;">
      &nbsp;&nbsp;
    <hr>
      <label for="NUM_HOLE">Number of Holes:</label>
      <input type="text" name="NUM_HOLE" id="NUM_HOLE" style="max-width: 100px;">
      <label for="NUM_DELAM">Number of Delaminations:</label>
      <input type="text" name="NUM_DELAM" id="NUM_DELAM" style="max-width: 100px;">
      <label for="NUM_SPLICE">Number of Splices:</label>
      <input type="text" name="NUM_SPLICE" id="NUM_SPLICE" style="max-width: 100px;">
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
    <p>
    </p>
      <label for="NOTES">Note:</label>
      <input type="text" name="NOTES" id="NOTES">
    </p>
    <input type="submit" value="Submit">
    </form>
  </body>
</html>
