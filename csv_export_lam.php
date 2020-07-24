<?php
/* vars for export */
// database record to be exported
$db_record = 'LAMINATOR';
// filename for export
$csv_filename = 'db_export_'.$db_record.'_'.date('Y-m-d').'.csv';
// database variables
$hostname = "localhost";
$user = "operator";
$password = "Licap123!";
$database = "Manufacture";
$conn = mysqli_connect($hostname, $user, $password, $database);
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
$T1 = mysqli_real_escape_string($conn, $_REQUEST['T1']);
$T2 = mysqli_real_escape_string($conn, $_REQUEST['T2']);
$T1_year = explode('/', $T1)[2];
$T2_year = explode('/', $T2)[2];
$where = "WHERE LAM_DATE >= '$T1' AND LAM_DATE <= '$T2' AND RIGHT(LAM_DATE, 4) >= '$T1_year' AND RIGHT(LAM_DATE, 4) >= '$T2_year'";
// create empty variable to be filled with export data
$csv_export = '';
// query to get data from database
$query = mysqli_query($conn, "SELECT * FROM ".$db_record." ".$where);
$field = mysqli_field_count($conn);
// create line with field names
for($i = 0; $i < $field; $i++) {
    $csv_export.= mysqli_fetch_field_direct($query, $i)->name.',';
}
// newline (seems to work both on Linux & Windows servers)
$csv_export.= '
';
// loop through database query and fill export variable
while($row = mysqli_fetch_array($query)) {
    // create line with field values
    for($i = 0; $i < $field; $i++) {
        $csv_export.= '"'.$row[mysqli_fetch_field_direct($query, $i)->name].'",';
    }
    $csv_export.= '
';
}
// Export the data and prompt a csv file for download
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=".$csv_filename."");
echo($csv_export);
