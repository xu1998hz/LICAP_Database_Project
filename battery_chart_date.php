<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Laminator Chart Date selection</title>
</head>
<body>
  <form action="battery_chart.php" method="post">
    <h1 style='text-align:center'>Battery Chart Display Page</h1>
    <p style='text-align:center'>
    <label for='TYPE'>Battery Thickness Type:</label>
      <select id="TYPE" name="TYPE">
        <option value="">-- Please Select Thickness Type--</option>
        <option value="150">Thickness 150</option>
        <option value="90" selected>Thickness 90</option>
      </select>
    </p>
    <p style='text-align:center'>
    <label for='DEPARTMENT'>Department Type:</label>
      <select id="DEPARTMENT" name="DEPARTMENT">
        <option value="">-- Please Select Your Department--</option>
        <option value="RD">R&D</option>
        <option value="PROD">Production</option>
        <option value="QA" selected>QA</option>
      </select>
    </p>
    <p style='text-align:center'>
    <label for='MODE'>Spec Mode Selection:</label>
      <select id="MODE" name="MODE">
        <option value="">-- Please Select Mode Type--</option>
        <option value="CUSTOMER">Customer Mode</option>
        <option value="INTERNAL" selected>Internal Mode</option>
      </select>
    </p>
    <p style='text-align:center'>
    <label for='VALUE_TYPE'>Value Type Selection:</label>
      <select id="VALUE_TYPE" name="VALUE_TYPE">
        <option value="">-- Please Select Value Type--</option>
        <option value="RESISTIVITY">Average Composite Resistivity</option>
        <option value="RESISTANCE" selected>Average Interface Resistance</option>
      </select>
    </p>
    <p style='text-align:center'>
    <label for='LOWER_DATE'>Date range begin:</label>
    <input id="LOWER_DATE" name="LOWER_DATE" type="date">
    </p>
    <p style='text-align:center'>
    <label for='UPPER_DATE'>Date range end:</label>
    <input id="UPPER_DATE" name="UPPER_DATE" type="date">
    </p>
    <p style='text-align:center'>Enable Label:<br/>
      <input type="radio" name="LABEL" value="true">Yes<br>
      <input type="radio" name="LABEL" value="false" checked>No
    </p>
    </p>
    <p style="color:red; text-align:center">
      <label>Hint: Date format is Month-Day-Year, Ex: 08/06/2020</label>
    </p>
    <div style='text-align:center'>
    <input type="submit" id="Submit" value="Submit">
    </div>
  </form>

</body>
</html>
