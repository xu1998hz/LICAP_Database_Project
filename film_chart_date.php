<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Film Chart Date selection</title>
</head>
<body>
  <form action="film_chart.php" method="post">
    <h1 style='text-align:center'>Film Chart Display Page</h1>
    <p style='text-align:center'>
    <label for='THICKNESS'>Film Thickness Type:</label>
      <select id="THICKNESS" name="THICKNESS">
        <option value="">-- Please Select Thickness Type--</option>
        <option value="150">Thickness 150</option>
        <option value="90" selected>Thickness 90</option>
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
