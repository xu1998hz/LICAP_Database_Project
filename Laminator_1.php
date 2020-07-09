 <!DOCTYPE html>

    <html lang="en">

    <head>

    <meta charset="UTF-8">

    <title>LAMINATOR 1</title>

    </head>

    <body>
	
    <form action="Laminator.php" method="post">

        <p>
<?php
$link = mysqli_connect("localhost", "operator", "Licap123!", "Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}
$sql = "SELECT LAM_OP, FOIL_TYPE, CAF_BATCH_NUM, CAF_BATCH_NUM_2, LAM_TEMP_UPPER, LAM_TEMP_LOWER, LAM_SPEED, GAP_OP, GAP_MACHINE, THICKNESS FROM LAMINATOR WHERE LAM_ID=1 ORDER BY ID DESC LIMIT 1";
$result = mysqli_query($link,$sql);
$row = mysqli_fetch_assoc($result); 
	
	$LAM_OP_RESULT = $row['LAM_OP'];
	$LAM_TEMP_RESULT_UPPER = $row['LAM_TEMP_UPPER'];
	$LAM_TEMP_RESULT_LOWER = $row['LAM_TEMP_LOWER'];
	$LAM_SPEED_RESULT = $row['LAM_SPEED'];
	$GAP_OP_RESULT = $row['GAP_OP'];
	$GAP_MACHINE_RESULT = $row['GAP_MACHINE'];
	$CAF_BATCH_NUM_RESULT = $row['CAF_BATCH_NUM'];
	$CAF_BATCH_NUM_RESULT_2 = $row['CAF_BATCH_NUM_2'];
	$FOIL_TYPE_RESULT = $row['FOIL_TYPE'];
	$THICKNESS_RESULT = $row['THICKNESS'];
if($CAF_BATCH_NUM_RESULT_2 == ""){
$CAF_BATCH_NUM_FINAL_RESULT = $CAF_BATCH_NUM_RESULT;
}
else { $CAF_BATCH_NUM_FINAL_RESULT = $CAF_BATCH_NUM_RESULT_2;
}
	
//Pull last Film Lot Number
$myquery = "SELECT ELECTRODE_SERIAL FROM LAMINATOR ORDER BY ID DESC LIMIT 1";
$result = mysqli_query($link, $myquery);
$row = mysqli_fetch_assoc($result);
$Last_Film_ID = $row['ELECTRODE_SERIAL'];


//Compare last batch date with current date
$Batch_Digit = explode("-",$Last_Film_ID);
$DATE_TEST = date("mdY");

if($Batch_Digit[3] === $DATE_TEST){
	$INC_DIGIT = $Batch_Digit[4] + 1;	
	$ELECTRODE_SERIAL = "E-". $THICKNESS_RESULT . "-" . $Batch_Digit[3] . "-" . $INC_DIGIT;
		}
else{
	$ELECTRODE_SERIAL = "E-" . $THICKNESS_RESULT . "-" . $DATE_TEST . "-1";
		}
echo "<h1> LAMINATOR 1 </h1>";
echo "<h1>" . "Current Roll:" . $ELECTRODE_SERIAL . "</h1>";

?>

<p>
<input id="LAM_ID" name="LAM_ID" type="hidden" value="1"/>
<label for="LAM_OP">Laminator Operator:</label>
<input id="LAM_OP" name="LAM_OP" type="text" value="<?php echo htmlentities($LAM_OP_RESULT); ?>" />
<label for="FOIL_TYPE">Foil Type:</label>
<input id="FOIL_TYPE" name="FOIL_TYPE" type="text" value="<?php echo htmlentities($FOIL_TYPE_RESULT); ?>" />
<label for="THICKNESS">Thickness:</label>
<input id="THICKNESS" name="THICKNESS" type="text" value="<?php echo htmlentities($THICKNESS_RESULT); ?>" />
</p>
<p>
<label for="CAF_BATCH_NUM">Foil Batch Number:</label>
<input id="CAF_BATCH_NUM" name="CAF_BATCH_NUM" type="text" value="<?php echo htmlentities($CAF_BATCH_NUM_FINAL_RESULT); ?>" />


</p>
<p>
<label for="CAF_BATCH_NUM_2">Foil Batch Number 2:</label>
<input id="CAF_BATCH_NUM_2" name="CAF_BATCH_NUM_2" type="text" />
<input id="LAM_ID" name="LAM_ID" value ="1" type="hidden"/>
</p>
<hr>
<p>
<label for="UPPER_FILM_BATCH_NUM">Upper Film Batch Number:</label>
<input id="UPPER_FILM_BATCH_NUM" name="UPPER_FILM_BATCH_NUM" type="text" />
&nbsp;&nbsp;
<label for="LOWER_FILM_BATCH_NUM">Lower Film Batch Number:</label>
<input id="LOWER_FILM_BATCH_NUM" name="LOWER_FILM_BATCH_NUM" type="text" />
</p>
<p>
<label for="UPPER_FILM_BATCH_NUM_2">Upper Film Batch Number 2:</label>
<input id="UPPER_FILM_BATCH_NUM_2" name="UPPER_FILM_BATCH_NUM_2" type="text" />
&nbsp;&nbsp;
<label for="LOWER_FILM_BATCH_NUM_2">Lower Film Batch Number 2:</label>
<input id="LOWER_FILM_BATCH_NUM_2" name="LOWER_FILM_BATCH_NUM_2" type="text" />
</p>
<label for="UPPER_FILM_BATCH_NUM_3">Upper Film Batch Number 3:</label>
<input id="UPPER_FILM_BATCH_NUM_3" name="UPPER_FILM_BATCH_NUM_3" type="text" />
&nbsp;&nbsp;
<label for="LOWER_FILM_BATCH_NUM_3">Lower Film Batch Number 3:</label>
<input id="LOWER_FILM_BATCH_NUM_3" name="LOWER_FILM_BATCH_NUM_3" type="text" />
</p>
<!--<p>
<label for="TAPE_TEST">Tape Test Result:</label>
<SELECT name="TAPE_TEST">
	<OPTION value="PASS">PASS</OPTION>
	<OPTION value="PASS-ADJUSTMENT">PASS-ADJUSTMENT</OPTION>
	<OPTION value="FAIL">FAIL</OPTION>
</SELECT>
</p>
&nbsp;&nbsp;
<label for="ELECTRODE_LENGTH_2">Length:</label>
<input type="text" name="ELECTRODE_LENGTH_2" >
<p>
<label for="BEGIN_OP">Beginning Operator Thickness:</label>
<input id="BEGIN_OP" name="BEGIN_OP" type="text" style="max-width: 100px;"/>
&nbsp;&nbsp;
<label for="BEGIN_CENTER">Beginning Center Thickness:</label>
<input id="BEGIN_CENTER" name="BEGIN_CENTER" type="text" style="max-width: 100px;"/>
&nbsp;&nbsp;
<label for="BEGIN_MACHINE">Beginning Machine Thickness:</label>
<input id="BEGIN_MACHINE" name="BEGIN_MACHINE" type="text"style="max-width: 100px;"/>
</p>-->
<hr>
<p>
<label for="ELECTRODE_LENGTH">Length:</label>
<input type="text" name="ELECTRODE_LENGTH" >
<p>
<label for="END_OP">Ending Operator Side Thickness:</label>
<input id="END_OP" name="END_OP" type="text" style="max-width: 100px;" />
&nbsp;&nbsp;
<label for="END_CENTER">Ending Center Thickness:</label>
<input id="END_CENTER" name="END_CENTER" type="text" style="max-width: 100px;"/>
&nbsp;&nbsp;
<label for="END_MACHINE">Ending Machine Side Thickness:</label>
<input id="END_MACHINE" name="END_MACHINE" type="text" style="max-width: 100px;"/>
</p>
<hr>
<p>

<label for="ROLL_DIAMETER">Roll Diameter:</label>
<input id="ROLL_DIAMETER" name="ROLL_DIAMETER" type="text" style="max-width: 100px;">
&nbsp;&nbsp;
<hr>
<label for="NUM_HOLE">Number of Holes:</label>
<input type="text" name="NUM_HOLE" id="NUM_HOLE" style="max-width: 100px;">
<label for="NUM_DELAM">Number of Delaminations:</label>
<input type="text" name="NUM_DELAM" id="NUM_DELAM" style="max-width: 100px;">
<label for="NUM_SPLICE">Number of Splices:</label>
<input type="text" name="NUM_SPLICE" id="NUM_SPLICE" style="max-width: 100px;">
</p>
<hr>
<p>
<label for="LAM_TEMP_UPPER">Upper Roll Temperature:</label>
<input type="text" name="LAM_TEMP_UPPER" value="<?php echo htmlentities($LAM_TEMP_RESULT_UPPER); ?>"style="max-width: 100px;" />
<label for="LAM_TEMP_LOWER">Lower Roll Temperature:</label>
<input type="text" name="LAM_TEMP_LOWER" value="<?php echo htmlentities($LAM_TEMP_RESULT_LOWER); ?>"style="max-width: 100px;" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<p>
<label for="GAP_OP">Gap Operator Side:</label>
<input type="text" name="GAP_OP" value="<?php echo htmlentities($GAP_OP_RESULT); ?>"style="max-width: 100px;" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label for="GAP_MACHINE">Gap Side Machine:</label>
<input type="text" name="GAP_MACHINE" value="<?php echo htmlentities($GAP_MACHINE_RESULT); ?>" style="max-width: 100px;"/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label for="LAM_SPEED">Line Speed:</label>
<input type="text" name="LAM_SPEED" value="<?php echo htmlentities($LAM_SPEED_RESULT); ?>" style="max-width: 100px;"/>
<label for="LAM_SPEED">meters/minute</label>
<p>
</p>
<label for="NOTES">Note:</label>
<input type="text" name="NOTES" id="NOTES">  
</p>
<input type="submit" value="Submit">

    </form>

    </body>

    </html>

