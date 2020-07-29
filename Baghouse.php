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
    # pull out the ID based on last non-null value of baghouse column in table blend
    $sql_cmd = "SELECT ID FROM blend WHERE BAGHOUSE_WEIGHT IS NOT NULL ORDER BY ID DESC LIMIT 1";
    $ID_Val = $sql_task_manager->pdo_sql_row_fetch($sql_cmd)['ID'];
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
      $sql_update = "UPDATE blend SET DATE = ?, TIMESTAMP = ?, ";
      if ($sql_task_manager->sql_insert_gen($row, 'blend')) {
        echo "<h3>"."Records updated successfully!"."</h3>";
      } else {
        echo "<h3>"."Unsuccessful insertion. Check the input value. Contact IT Department if you need further assitance"."</h3>";
      }
    }
   ?>
 </body>
</html>
