<?php
  class sql_task_manager{
      private $username;
      private $hostname;
      private $password;
      private $db_name;
      private $pdo;
      private $link; # optional variable will be deleted in the future

      public function __construct($hostname, $username, $password, $db_name) {
        $this->username = $username;
        $this->hostname = $hostname;
        $this->password = $password;
        $this->db_name = $db_name;
        try {
          $this->pdo = new PDO("mysql:host=$this->hostname; port=3306; dbname=$this->db_name", $this->username, $this->password);
        } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
        }
      }

      # set up the conncection of local host on the web server, execute sql comamnds
      public function connect_sql_row_fetch($sql) {
        $this->link = mysqli_connect($this->hostname, $this->username, $this->password, $this->db_name);
        // Check connection
        if($this->link->connect_errno){
            die('ERROR: Could not connect. ' . $this->link->connect_error);
        }
        $sql_result = mysqli_query($this->link,$sql);
        $row_values = mysqli_fetch_assoc($sql_result);
        return $row_values;
      }

      # it provides the same functionalities as connect_sql_row_fetch, except of using pdo object
      # later code will move to use PDO object
      public function pdo_sql_row_fetch($sql) {
        $sql_result = $this->pdo->query($sql);
        $row_values = $sql_result->fetch(PDO::FETCH_ASSOC);
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
        // connection will be closed when pdo object no longer exists
        $this->pdo = NULL;
      }
  }
?>
