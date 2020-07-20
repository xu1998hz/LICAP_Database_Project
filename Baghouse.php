<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Baghouse ENTRY PAGE</title>
</head>
 <body>
   <?php
    require_once('sql_task_manager.php');
    $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
    $sql_check_col = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'SLITTER' AND COLUMN_NAME = 'BAGHOUSE_WEIGHT'";
    // check if the required column exists here
    if (!$sql_task_manager->pdo_sql_row_fetch($sql_check_col)) {
      $sql_task_manager->pdo_sql_row_fetch("ALTER TABLE SLITTER ADD BAGHOUSE_WEIGHT FLOAT(24)");
    }
    $sql_command = "SELECT * FROM SLITTER ORDER BY TIMESTAMP DESC LIMIT 1";
    $row = $sql_task_manager->pdo_sql_row_fetch($sql_command);
    array_pop($row);
   ?>

   <form action="Baghouse.php" method="post">
     <H1>BagHouse Page</H1>
     <p>
       <label for="BAGHOUSE_WEIGHT">BagHouse Weight</label>
       <input type="text" name="BAGHOUSE_WEIGHT"/>
       <input type="submit" value="Submit">
     </p>
     <hr>
   </form>

   <?php
    if (count($_REQUEST)!==0) {
      $row['ID']++;
      $row['DATE'] = date("m/d/Y");
      $row['TIMESTAMP'] = date("m/d/Y-H:i:s");
      $row['BAGHOUSE_WEIGHT'] = $_REQUEST['BAGHOUSE_WEIGHT'];
      if ($sql_task_manager->sql_insert_gen($row, 'SLITTER')) {
        echo "<h3>"."Records added successfully!"."</h3>";
      } else {
        echo "<h3>"."Unsuccessful insertion. Check the input value. Contact IT Department if you need further assitance"."</h3>";
      }
    }
   ?>
 </body>
</html>
