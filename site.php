<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

      <p> Guessing game ... </p>
      <form method="post">
        <p><label for="guess">Input Guess</label>
        <input type="text " name="guess" size="40" id="guess"/></p>
      </form>

      <!-- Various of different forms to be documented -->
      <form method="post">
        <!-- Text box, username -->
        <p><label for="inp01">Account:</label>
        <input type="text" name="account" id="inp01" size="40">
        </p>
        <!-- Text box, password -->
        <p><label for="inp02">Password:</label>
        <input type="password" name="pw" id="inp02" size=40>
        </p>
        <!-- Radio button -->
        <p>Preferred Time:<br/>
          <input type="radio" name="when" value="am">AM<br>
          <input type="radio" name="when" value="pm" checked>PM
        </p>
        <!-- Check boxes -->
        <p>Classes taken:<br/>
          <input type="checkbox" name="class1" value="si502" checked>
              SI502 - Network Tech<br>
          <input type="checkbox" name="class2" value="si539">
              SI539 - App Engine<br>
          <input type="checkbox" name="class3">
              SI543 - Java<br>
        </p>

        <!-- Select / Drop-Down -->
        <p><label for="inp06">Which soda:
          <select name="soda" id="inp06">
            <option value="">-- Please Select --</option>
            <option value="chips">Chips</option>
            <option value="peanuts" selected>Peanuts</option>
            <option value="cookie">Cookie</option>
          </select>
        </p>

        <p><label for="inp08">Tell us about yourself:<br/>
          <textarea rows="10" cols="40" id="inp08" name="about">
          </textarea>
        </p>

        <input type="submit"/>
        </form>
        <pre>
        $_POST:
        <?php
          print_r($_POST);
        ?>
        </pre>

  </body>
</html>
