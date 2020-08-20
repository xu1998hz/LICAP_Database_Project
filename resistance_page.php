<!DOCTYPE html>
<html lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>ELECTRODE RESISTANCE DATA ENTRY PAGE</title>
  </head>
  <body>
  <form action="resistance_page.php" method="post">

  <?php
  require_once('sql_task_manager.php');
  $sql_task_manager = new sql_task_manager("localhost", "operator", "Licap123!", "Manufacture");
  ?>

  <h1>Resistance Data Input Page</h1>

  <p>
  <label for="DEPARTMENT">Department:</label>
    <select id="DEPARTMENT" name="DEPARTMENT">
      <option value="">-- Please Select Your Department--</option>
      <option value="RD">R&D</option>
      <option value="PROD">Production</option>
      <option value="QA" selected>QA</option>
    </select>
  </p>

  <p>
  <label for='TYPE'>Thickness Type:</label>
    <select id="TYPE" name="TYPE">
      <option value="">-- Please Select Thickness Type--</option>
      <option value="150">Thickness 150</option>
      <option value="90" selected>Thickness 90</option>
    </select>
  </p>

  <p>
    <label for="OP">Operator Name:</label>
    <input type="text" name="OP"/>
  </p>

  <p>
    <label for="LOT">Electrode Serial Number:</label>
    <input type="text" name="LOT"/>
  </p>

  <p>
    <label for="LOT_DATE">Electrode Lot Date:</label>
    <input type="date" name="LOT_DATE"/>
  </p>

  <p>
    <label for="TOTAL_THICKNESS">Total Thickness (um):</label>
    <input type="text" id="TOTAL_THICKNESS" name="TOTAL_THICKNESS" onkeyup="single_compute()"/>
  </p>

  <p style="color:red;">
    Real time Single Film Thickness (um): <span id="SINGLE_THICKNESS"></span>
  </p>
  <script>
    function single_compute() {
      var TOTAL_THICKNESS = document.getElementById("TOTAL_THICKNESS").value;
      document.getElementById("SINGLE_THICKNESS").innerHTML = (TOTAL_THICKNESS-20)/2;
    }
  </script>

  <p>
    <label for="RESISTIVITY_1">Composite resistivity [Ω cm]:</label>
    <input type="text" name="RESISTIVITY_1"/>
  </p>

  <p>
    <label for="RESISTANCE_1">Interface resistance [Ω cm^2]:</label>
    <input type="text" name="RESISTANCE_1"/>
  </p>

  <p>
    <label for="RESISTIVITY_2">Composite resistivity [Ω cm]:</label>
    <input type="text" name="RESISTIVITY_2"/>
  </p>

  <p>
    <label for="RESISTANCE_2">Interface resistance [Ω cm^2]:</label>
    <input type="text" name="RESISTANCE_2"/>
  </p>

  <p>
    <label for="RESISTIVITY_3">Composite resistivity [Ω cm]:</label>
    <input type="text" name="RESISTIVITY_3"/>
  </p>

  <p>
    <label for="RESISTANCE_3">Interface resistance [Ω cm^2]:</label>
    <input type="text" name="RESISTANCE_3"/>
  </p>

  <p>
    <label for="COMMENT">Comment:</label>
    <input type="text" name="COMMENT"/>
  </p>

  <input type="submit" value="Submit">
  </form>

  <?php
    // check whether there is user input
    if (count($_REQUEST)===0) {
      return;
    }
    $_REQUEST['SINGLE_THICKNESS'] = ($_REQUEST['TOTAL_THICKNESS']-20)/2;
    $_REQUEST['DATE'] = date("Y-m-d");
    $_REQUEST['AVG_RESISTIVITY'] = ($_REQUEST['RESISTIVITY_1'] + $_REQUEST['RESISTIVITY_2'] + $_REQUEST['RESISTIVITY_3']) / 3;
    $_REQUEST['AVG_RESISTANCE'] = ($_REQUEST['RESISTANCE_1'] + $_REQUEST['RESISTANCE_2'] + $_REQUEST['RESISTANCE_3']) / 3;
    if ($sql_task_manager->sql_insert_gen($_REQUEST, 'RESISTANCE')) {
      echo "<h3>"."Records added successfully!"."</h3>";
    } else {
      echo "<h3>"."Unsuccessful insertion! Check all the input values! Contact IT Department if you need further assitance"."</h3>";
    }

    echo "<script>setTimeout(\"location.href = 'http://10.1.10.190/resistance_page.php';\",2000);</script>";
  ?>
  </body>
</html>
