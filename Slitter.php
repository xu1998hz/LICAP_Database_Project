 <!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>SLITTER DATA ENTRY PAGE</title>
    </head>

    <body>
        <form action="Slitter_1.php" method="post">
            <?php
                $link = mysqli_connect("localhost", "root", "PQch782tdk@@", "Manufacture");
                // Check connection
                if($link->connect_errno){
                    die('ERROR: Could not connect. ' . $link->connect_error);

                }
                $sql = "SELECT SLIT_OP FROM SLITTER ORDER BY ID DESC LIMIT 1";
                $result = mysqli_query($link,$sql);
                // Returns an associative array of strings that corresponds to the fectched row, or false if no more rows
                // add while if multiple rows existed
                $row = mysqli_fetch_assoc($result);
            	  $SLIT_OP_RESULT = $row['SLIT_OP'];
                mysqli_close($link);
            ?>


            <p>
              <label for="SLIT_OP">Slitter Operator:</label>
              <input id="SLIT_OP" name="SLIT_OP" type="text" value="<?php echo htmlentities($SLIT_OP_RESULT); ?>" />
            </p>

            <HR>
            <p>
                <label for="ELECTRODE_SERIAL_1">Electrode Serial 1</label>
                <input id="ELECTRODE_SERIAL_1" name="ELECTRODE_SERIAL_1" type="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="ELECTRODE_LENGTH_1">Electrode Length 1</label>
                <input id="ELECTRODE_LENGTH_1" name="ELECTRODE_LENGTH_1" type="text">
                <label for="ELECTRODE_LENGTH_1">ft</label>
            </p>

            <p>
                <label for="ELECTRODE_SERIAL_2">Electrode Serial 2</label>
                <input id="ELECTRODE_SERIAL_2" name="ELECTRODE_SERIAL_2" type="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="ELECTRODE_LENGTH_2">Electrode Length 2</label>
                <input id="ELECTRODE_LENGTH_2" name="ELECTRODE_LENGTH_2" type="text">
                <label for="ELECTRODE_LENGTH_2">ft</label>
            </p>

            <p>
                <label for="ELECTRODE_SERIAL_3">Electrode Serial 3</label>
                <input id="ELECTRODE_SERIAL_3" name="ELECTRODE_SERIAL_3" type="text">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="ELECTRODE_LENGTH_3">Electrode Length 3</label>
                <input id="ELECTRODE_LENGTH" name="ELECTRODE_LENGTH_3" type="text">
                <label for="ELECTRODE_LENGTH_3">ft</label>
            </p>

            <p>
                <label for="PERFORATED">Was the electrode Perforated?</label>
                    <select name="PERFORATED">
                    		<option value="0">No</option>
                  		  <option value="1">Yes</option>
                  	</select>
                <hr>
                <p>
                    <label for="NUM_HOLES">Number of Hole Defects</label>
                    <input id="NUM_HOLES" name="NUM_HOLES" type="text">
                    &nbsp;&nbsp;&nbsp;
                    <label for="NUM_DELAM">Number of Delaminations</label>
                    <input id="NUM_DELAM" name="NUM_DELAM" type="text">
                    &nbsp;&nbsp;&nbsp;
                    <label for="NUM_SPLICE">Number of Splices</label>
                    <input id="NUM_SPLICE" name="NUM_SPLICE" type="text">
                </p>
                <hr>
                    <label for="NOTES">Notes:</label>
                    <input type="text" name="NOTES" id="NOTES">
            </p>

            <input type="submit" value="Submit">

        </form>

    </body>

</html>
