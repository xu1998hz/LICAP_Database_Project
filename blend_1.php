<?php

$check_ac_1 = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$AC_LOT_1'";
$check_ac_result_1 = mysqli_query($inventory_link, $check_ac_1);
$row = mysqli_fetch_assoc($check_ac_result_1);
$TEST_1 = 1;//$row['TEST'];

if(empty($AC_LOT_2) !== true){
$check_ac_2 = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$AC_LOT_2'";
$check_ac_result_2 = mysqli_query($inventory_link, $check_ac_2);
$row = mysqli_fetch_assoc($check_ac_result_2);
$TEST_2 = 1; //$row['TEST'];
}
else{ $TEST_2 = 1;}



$CHECK_ACETONE = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$ACETONE_LOT'";
$CHECK_ACETONE_RESULT = mysqli_query($inventory_link, $CHECK_ACETONE);
$row = mysqli_fetch_assoc($CHECK_ACETONE_RESULT);
$TEST_ACETONE = 1;//$row['TEST'];

$CHECK_PTFE = "SELECT 1 AS TEST FROM INVENTORY_TABLE WHERE SERIAL = '$PTFE_LOT'";
$CHECK_PTFE_RESULT = mysqli_query($inventory_link, $CHECK_PTFE);
$row = mysqli_fetch_assoc($CHECK_PTFE_RESULT);
$TEST_PTFE = 1;
//$row['TEST'];
/*
if ($TEST_1 > 0 && $TEST_2 > 0 && $TEST_3 > 0 && $TEST_PTFE > 0 && $TEST_ACETONE > 0 ) {
	$acetone_sql = "UPDATE INVENTORY_TABLE SET AMOUNT_USED = AMOUNT_USED + '$ACETONE_WEIGHT' WHERE SERIAL ='$ACETONE_LOT'";
	mysqli_query($inventory_link, $acetone_sql);

	$ptfe_sql = "UPDATE INVENTORY_TABLE SET AMOUNT_USED = AMOUNT_USED + '$PTFE_WEIGHT' WHERE SERIAL ='$PTFE_LOT'";
	mysqli_query($inventory_link, $ptfe_sql);
for ($i=1; $i<=3; $i++) {
	$ac_sql = "UPDATE INVENTORY_TABLE
		SET AMOUNT_USED = AMOUNT_USED + '10'
		WHERE SERIAL ='$AC_LOT[$i]'";
	mysqli_query($inventory_link, $ac_sql);
}

} else {

echo "ERROR: Serial Number is entered incorrectly or not in the database. Recheck your data entry. If error persists contact Process Engineer.\n";
mysqli_close($link);
}
*/
