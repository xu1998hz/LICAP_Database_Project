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
            echo "$spec_arr[$i] has the value out of bound\n";
            return false;
          }
        }
        return true;
      }

      public function pdo_sql_vali_execute($sql, $pdo_exec_arr) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $this->pdo->prepare($sql);
        $state_exec = $stmt->execute($pdo_exec_arr);
        return array($state_exec, $stmt);
      }

      public function user_Input_batch_vali($col_name, $table_name, $request_array, $target) {
        $sql_cmd = "SELECT ".$col_name." FROM ".$table_name." WHERE ".$col_name."=:str_1";
        $stmt = $this->pdo_sql_vali_execute($sql_cmd, array(':str_1'=>$request_array[$target]))[1];
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? true : false;
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

      # generate sql insert commands based on both userinput features and features require further computation
      public function sql_insert_gen_exec($request_array, $computed_vals,$computed_vals_arr) {
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
        $pdo_exec_arr = array_combine(explode(',', $out_total),  $vals_array);
        return $this->pdo_sql_vali_execute($str_cmd, $pdo_exec_arr)[0];
      }

      # release the PDO object and close connection
      public function __destructor() {
        $this->pdo = NULL;
      }
  }
?>
