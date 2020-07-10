<?php
  class sql_task_manager{
      private $username;
      private $hostname;
      private $password;
      private $db_name;
      public $pdo;

      public function __construct($hostname, $username, $password, $db_name) {
        $this->username = $username;
        $this->hostname = $hostname;
        $this->password = $password;
        $this->db_name = $db_name;
        # set up the PPO computatiion with database at localhost port 3306
        try {
          $this->pdo = new PDO("mysql:host=$this->hostname; port=3306; dbname=$this->db_name", $this->username, $this->password);
        } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
        }
      }

      # set up the conncection of local host on the web server, execute sql comamnds
      public function pdo_sql_row_fetch($sql) {
        $sql_result = $this->pdo->query($sql);
        $row_values = $sql_result->fetch(PDO::FETCH_ASSOC);
        return $row_values;
      }

      # check if specifc spec values are out of bounds, leave val1 and val2 for two versions, val2 can be optional
      public function user_Input_spec_vali($request_array, $spec_arr, $val1, $val2, $uncertainty) {
        for($i=0; $i<count($spec_arr); $i++) {
          if ((!($request_array[$spec_arr[$i]]>=($val1-$uncertainty) && $request_array[$spec_arr[$i]]<=($val1+$uncertainty)))
          && (!($request_array[$spec_arr[$i]]>=($val2-$uncertainty) && $request_array[$spec_arr[$i]]<=($val2+$uncertainty)))) {
            echo "$spec_arr[$i] has the value out of bound";
            return false;
          }
        }
        return true;
      }

      # generate sql insert commands based on both userinput features and features require further computation
      private function sql_insert_generator($request_array, $computed_vals,$computed_vals_arr) {
        $str_cmd = "INSERT INTO FILM (";
        $temp = array_keys($request_array);
        array_pop($temp);
        $vals_array = array_values($request_array);
        array_pop($vals_array);
        $str_cmd .= implode(",", $temp).',';
        $out_temp = array_map(function($val) { return ':'.$val; }, $temp);
        $out_computed_vals = array_map(function($val) { return ':'.$val; }, $computed_vals);
        $str_cmd .= implode(",", $computed_vals).') VALUES (';
        $out_total = implode(",", $out_temp).','.implode(",", $out_computed_vals);
        $str_cmd .= $out_total;
        $str_cmd .= ')';
        for ($i=0; $i<count($computed_vals_arr); $i++) {
          array_push($vals_array, $computed_vals_arr[$i]);
        }
        return array($str_cmd, explode(',', $out_total), $vals_array);
      }

      # error handling for sql injection and executes sql insertion commands
      public function pdo_sql_insert($request_array, $computed_names, $computed_vals_arr) {
        # error detection setup in both codes for sql and php
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $output = $this->sql_insert_generator($request_array, $computed_names, $computed_vals_arr);
        $sql = $output[0]; $key_array = $output[1]; $vals_array = $output[2];
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_combine($key_array,  $vals_array));
      }

      # compute general ID in the specific algorithm and input parameters
      public function ID_computation($row_values, $THICKNESS, $prefix) {
        $Batch_Digit = explode("-", $row_values['FILM_ID']);
        $DATE_TEST = date("mdY");
        if ($Batch_Digit[2] === $DATE_TEST) {
          	$INC_DIGIT = $Batch_Digit[3] + 1;
          	$FILM_ID = $prefix . "F-". $THICKNESS . "-" . $Batch_Digit[2] . "-" . $INC_DIGIT;
        } else {
          	$FILM_ID = $prefix . "F-" . $THICKNESS . "-" . $DATE_TEST . "-1";
        }
        return $FILM_ID;
      }

      # release the PDO object and close connection
      public function __destructor() {
        $this->pdo = NULL;
      }
  }
?>
