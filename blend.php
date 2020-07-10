 <!DOCTYPE html>

    <html lang="en">

    <head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>POWDER MIXTURE DATA ENTRY PAGE</title>
	
    </head>

    <body>
	
    <form action="blend_1.php" method="post">

        <p>
<?php
$link = mysqli_connect("localhost", "operator", "Licap123!", "Manufacture");
// Check connection
if($link->connect_errno){
    die('ERROR: Could not connect. ' . $link->connect_error);

}
$sql = "SELECT ACETONE_WEIGHT, STRIP_RECYCLE, BAGHOUSE_RECYCLE,MIX_TIME, OUTSIDE_RECYCLE,PTFE_WEIGHT,BATCH_NUM,INJECTOR_PRESSURE,PERIPHERAL_PRESSURE,MIXER_RPM,AC_WEIGHT,ACETONE_WEIGHT,ACETONE_WEIGHT, MIXING_OP, AC_LOT_NUM_2, ACETONE_LOT, PTFE_LOT, GRIND_OP FROM blend ORDER BY ID DESC LIMIT 1";
        $result = mysqli_query($link, $sql);
    	$row = mysqli_fetch_assoc($result); 
	$BAG_WEIGHT_RESULT = $row['BAG_WEIGHT'];
	$AC_WEIGHT_RESULT = $row['AC_WEIGHT'];
  	$BATCH_NUM_LAST = $row['BATCH_NUM'];
	$MIXING_OP_RESULT = $row['MIXING_OP'];
	$AC_LOT_NUM_2 = $row['AC_LOT_NUM_2'];
	$ACETONE_LOT_RESULT = $row['ACETONE_LOT'];
	$ACETONE_WEIGHT_RESULT = $row['ACETONE_WEIGHT']; 
	$PTFE_LOT_RESULT = $row['PTFE_LOT'];
	$GRIND_OP_RESULT = $row['GRIND_OP'];
	$INJECTOR_PRESSURE_RESULT = $row['INJECTOR_PRESSURE'];
	$PERIPHERAL_PRESSURE_RESULT = $row['PERIPHERAL_PRESSURE'];
	$PTFE_WEIGHT_RESULT = $row['PTFE_WEIGHT'];
	$ACETONE_WEIGHT_RESULT= $row['ACETONE_WEIGHT'];
	$STRIP_RECYCLE_RESULT = $row['STRIP_RECYCLE'];
	$OUTSIDE_RECYCLE_RESULT = $row['OUTSIDE_RECYCLE'];
	$BAGHOUSE_RECYCLE_RESULT = $row['BAGHOUSE_RECYCLE'];
	$MIX_TIME_RESULT = $row['MIX_TIME'];
	$MIXER_RPM_RESULT = $row['MIXER_RPM'];

$EXPLODE_AC_LOT_NUM_2 = explode("-",$AC_LOT_NUM_2);
$AC_LOT_NUM_RESULT_2 = $EXPLODE_AC_LOT_NUM_2[0] . "-" . $EXPLODE_AC_LOT_NUM_2[1];

//Compare last batch date with current date
$Batch_Digit = explode("-",$BATCH_NUM_LAST);
$DATE_TEST = date("mdY");

if($Batch_Digit[1] === $DATE_TEST){
	$INC_DIGIT = $Batch_Digit[2] + 1;	
	$NEW_BATCH_NUM = "M-" . $Batch_Digit[1] . "-" . $INC_DIGIT;
		}
else{
	$NEW_BATCH_NUM = "M-" . $DATE_TEST . "-1";
		}
echo "<h1>" . "Current Batch:" . $NEW_BATCH_NUM . "</h1>";

mysqli_close($link);

?>
<p>
<label> Mixer Operator:</label>	
<input type="text" name="MIX_OP" value="<?php echo htmlentities($MIXING_OP_RESULT); ?>" />
</p>
<hr>
<p>         
<label for="AC_LOT_1">AC Lot Number 1:</label>
<input type="text" name="AC_LOT_1"value="<?php echo htmlentities($AC_LOT_NUM_RESULT_2); ?>"/>
<label for="AC_NUM_1">AC Bag Number 1:</label>
<input type="text" name="AC_NUM_1"/>

</p>
<p>         
<label for="AC_LOT_2">AC Lot Number 2:</label>
<input type="text" name="AC_LOT_2"value="<?php echo htmlentities($AC_LOT_NUM_RESULT_2); ?>"/>
<label for="AC_NUM_2">AC Bag Number 2:</label>
<input type="text" name="AC_NUM_2"/>

</p>
<!---<p>         
<label for="AC_LOT_3">AC Lot Number 3:</label>
<input type="text" name="AC_LOT_3"value="<?php echo htmlentities($AC_LOT_NUM_RESULT_3); ?>"/>
<label for="AC_NUM_3">AC Bag Number 3:</label>
<input type="text" name="AC_NUM_3"/>
</p>-->
<hr>
<p>
<label for="AC_WEIGHT">Active Carbon Weight:</label>
<input type="text" name="AC_WEIGHT" value="<?php echo htmlentities($AC_WEIGHT_RESULT); ?>"/>
</p>

<p>
<label for="ACETONE_LOT">Acetone Lot Number:</label>
<input type="text" name="ACETONE_LOT" value="<?php echo htmlentities($ACETONE_LOT_RESULT); ?>" />
</p>

<p>
<label for="ACETONE_WEIGHT">Acetone Weight:</label>
<input type="text" name="ACETONE_WEIGHT" value="<?php echo htmlentities($ACETONE_WEIGHT_RESULT); ?>" />

</p>
<p>
<label for="STIRP_RECYCLE">Strip Recycle Weight:</label>
<input type="text" name="STRIP_RECYCLE" id="STRIP_RECYCLE" value="<?php echo htmlentities($STRIP_RECYCLE_RESULT); ?>" />
<label for="BAGHOUSE_RECYCLE">*Must enter a zero if no recycle is used!</label>
</p>
<p>
<label for="BAGHOUSE_RECYCLE">Bag House Recycle Weight:</label>
<input type="text" name="BAGHOUSE_RECYCLE" id="BAGHOUSE_RECYCLE" value="<?php echo htmlentities($BAGHOUSE_RECYCLE_RESULT); ?>" />

</p>
<p>
<label for="OUTSIDE_RECYCLE">Outside Recycle Weight:</label>
<input type"text" name="OUTSIDE_RECYCLE" id="OUTSIDE_RECYCLE" value="<?php echo htmlentities($OUTSIDE_RECYCLE_RESULT); ?>" />
</p>

<p>
<label for="PTFE_LOT">PTFE Lot:</label>
<input type="text" name="PTFE_LOT" value="<?php echo htmlentities($PTFE_LOT_RESULT); ?>" />
</p>

<p>
<label for="PTFE_WEIGHT">PTFE Weight:</label>
<input type="text" name="PTFE_WEIGHT" id="PTFE_WEIGHT" value="<?php echo htmlentities($PTFE_WEIGHT_RESULT); ?>" />
</p>
<p>
<label for="MIX_TIME">Blend Time:</label>
<input type="text" name="MIX_TIME" id="MIX_TIME" value="<?php echo htmlentities($MIX_TIME_RESULT); ?>" />

<p>
<label for="INJECTOR_PRESSURE">Injection Pressure:</label>
<input type="text" name="INJECTOR_PRESSURE" value ="<?php echo htmlentities($INJECTOR_PRESSURE_RESULT); ?>" />

</p>
<p>
<label for="PERIPHERAL_PRESSURE">Peripheral Wall Pressure:</label>
<input type="text" name="PERIPHERAL_PRESSURE" value="<?php echo htmlentities($PERIPHERAL_PRESSURE_RESULT); ?>" />

</p>
<p>
<label for="GRIND_OP">Jet Mill Operator:</label>
<input type="text" name="GRIND_OP" value="<?php echo htmlentities($GRIND_OP_RESULT); ?>" />
</p>
<p>
<label for="MIXER_RPM">K-Tron RPM:</label>
<input type="text" name="MIXER_RPM" value="<?php echo htmlentities($MIXER_RPM_RESULT); ?>" />

</p>
<input type="submit" value="Submit">

    </form>

    </body>

    </html>

