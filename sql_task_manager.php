<?php
  class sql_task_manager{
      private $username;
      private $hostname;
      private $password;
      private $db_name;
      private $link;

      public function __construct($username, $hostname, $password, $db_name) {
        $this->username = $username;
        $this->hostname = $hostname;
        $this->password = $password;
        $this->db_name = $db_name;
      }

      public function connect_sql_row_fetch($sql) {
        $this->link = mysqli_connect($this->username, $this->hostname, $this->password, $this->db_name);
        // Check connection
        if($this->link->connect_errno){
            die('ERROR: Could not connect. ' . $this->link->connect_error);
        }
        $sql_result = mysqli_query($this->link,$sql);
        $row_values = mysqli_fetch_assoc($sql_result);
        return $row_values;
      }

      public function date_compare($Batch_Digit, $THICKNESS) {
        $DATE_TEST = date("mdY");
        if ($Batch_Digit[2] === $DATE_TEST) {
          	$INC_DIGIT = $Batch_Digit[3] + 1;
          	$FILM_ID = "F-". $THICKNESS . "-" . $Batch_Digit[2] . "-" . $INC_DIGIT;
        } else {
          	$FILM_ID = "F-" . $THICKNESS . "-" . $DATE_TEST . "-1";
        }
        echo "<h1>" . "Current Roll:" . $FILM_ID . "</h1>";
      }

      public function __destructor() {
        mysqli_close($this->link);
      }
  }
?>
