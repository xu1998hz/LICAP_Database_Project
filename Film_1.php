 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>FILM DATA ENTRY PAGE</title>
</head>
  <body>
    <form action="Film.php" method="post">

    <p>
      <H1>FILM MILL 1 Log</H1>

    <?php
      require_once('sql_task_manager.php');
      $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
      $sql_command = "SELECT FILM_1_OP, FILM_2_OP, MIX_BATCH_NUM, MIX_BATCH_NUM_2, THICKNESS, MILL_TEMP, CAL_1_TEMP, CAL_2_TEMP, LINE_SPEED FROM FILM WHERE FILM_MILL=1 ORDER BY ID DESC LIMIT 1";
      $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);

      //Pull last Film Lot Number
      $sql_command = "SELECT FILM_ID FROM FILM ORDER BY ID DESC LIMIT 1";
      $row_2 = $sql_task_manager->pdo_sql_row_fetch($sql_command);

      //Compare last batch date with current date, because
      $FILM_ID = $sql_task_manager->ID_computation($row_2, $row['THICKNESS'],'');
      echo "<h1>" . "Current Roll:" . $FILM_ID . "</h1>";
    ?>


    <input id="FILM_MILL" name="FILM_MILL" type="hidden" value="1"/>
    <p>
      <label for="LENGTH">Length:</label>
      <input type="text" name="LENGTH" id="LENGTH">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <label for="THICKNESS">Thickness Spec</label>
      <input type="text" name="THICKNESS" value="<?php echo htmlentities($row['THICKNESS']); ?>" />
    </p>

    <p>
      <label for="END_OP">Ending Operator Thickness:</label>
      <input id="END_OP" name="END_OP" type="text" onchange="changeBackground4(this);" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <label for="END_CENTER">End Center Thickness:</label>
      <input id="END_CENTER" name="END_CENTER" type="text" onchange="changeBackground6(this);" />

      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <label for="END_MACHINE">Ending Machine Side Thickness:</label>
      <input id="END_MACHINE" name="END_MACHINE" type="text" onchange="changeBackground6(this);" />
    </p>

    <hr>

    <p>
      <label for="MIX_BATCH_NUM">Powder Batch 1</label>
      <input type="text" name="MIX_BATCH_NUM" value="<?php echo htmlentities($row['MIX_BATCH_NUM']); ?>" />
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <label for="MIX_BATCH_NUM_2">Powder Batch 2</label>
      <input type="text" name="MIX_BATCH_NUM_2" value="<?php echo htmlentities($row['MIX_BATCH_NUM_2']); ?>" />
    </p>

    <hr>

    <p>
      <label for="DEFECT_NUM">Number of Defects:</label>
      <input type="text" name="DEFECT_NUM" id="DEFECT_NUM">
    </p>

    <p>
      <label for="FILM_WEIGHT">Weight of 8-Layer Punch:</label>
      <input type="text" name="FILM_WEIGHT" id="FILM_WEIGHT">
      <label for="FILM_WEIGHT">g</label>
    </p>

    <p>
      <label for="FILM_NOTE">Note:</label>
      <input type="text" name="FILM_NOTE" id="FILM_NOTE">
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

  </body>

</html>
