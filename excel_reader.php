<html>
<head>
<style type="text/css">
  td,th {
    border: 1px solid rgb(190, 190, 190);
    padding: 10px;
  }

  td {
    text-align: center;
  }

  tr:nth-child(even) {
    background-color: #eee;
  }

  table {
    border-collapse: collapse;
    border: 2px solid rgb(200, 200, 200);
    letter-spacing: 1px;
    font-family: sans-serif;
    font-size: .8rem;
  }
</style>
</head>
<body>

<?php

  include "SimpleXLSX.php";

  echo '<h1>Data Shipping Table 2020</h1><pre>';

  if ($xlsx = SimpleXLSX::parse('data_shipping.xlsx') ) {
    //print_r($xlsx->rows());
    echo '<table><tbody>';
    $i = 0;

    foreach ($xlsx->rows() as $elt) {
      if ($i == 0) {
        echo "<tr><th>" . $elt[0] . "</th><th>" . $elt[1] . "</th><th>" . $elt[2] . "</th><th>" . $elt[3] . "</th><th>" . $elt[4] . "</th></tr>";
      } else {
        echo "<tr><td>" . $elt[0] . "</td><td>" . $elt[1] . "</td><td>" . $elt[2] . "</td><td>" . $elt[3] . "</td><td>" . $elt[4] . "</td></tr>";
      }
      $i++;
    }

    echo "</tbody></table>";

  } else {
    echo SimpleXLSX::parseError();
  }

?>

</body>
</html>
