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
      $sql_update = "UPDATE blend SET DATE = ?, TIMESTAMP = ?, BAGHOUSE_WEIGHT = ? WHERE ID = ? + 1";
      $result_arr = $sql_task_manager->pdo_sql_vali_execute($sql_update, array(date("m/d/Y"), date("m/d/Y-H:i:s"), $_REQUEST['BAGHOUSE_WEIGHT'], $ID_Val));
      if ($result_arr[1]) {
        echo "<h3>"."Records updated successfully!"."</h3>";
        header("refresh: 1");
      } elseif (!$result_arr[0]) {
        echo "<h3>"."Internal Error! Contact IT Department for further helps"."</h3>";
      } else {
        echo "<h3>"."All bag weights in the database have been updated!"."</h3>";
        header("refresh: 1"); 
      }
    }
   ?>
 </body>
</html>
