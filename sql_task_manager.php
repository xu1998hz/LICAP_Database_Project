<?php
  class sql_task_manager{
      private $pdo;
      private $color_ls;
      private $err_msgs;
      private $stmt;

      public function __construct($hostname, $username, $password, $db_name) {
        $this->err_msgs = array();
        # set up the PPO computatiion with database at localhost port 3306
        try {
          $this->pdo = new PDO("mysql:host=$hostname; port=3306; dbname=$db_name", $username, $password);
        } catch (PDOException $e) {
          echo 'Connection failed: ' . $e->getMessage();
        }
      }

      # validate general user-input based sql command and return the status of the command
      public function pdo_sql_vali_execute($sql, $pdo_exec_arr) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
          $this->stmt = $this->pdo->prepare($sql);
          $state_exec = $this->stmt->execute($pdo_exec_arr);
        } catch (Exception $ex) {
          print($ex->getMessage());
          return array(false, 0);
        }
        return array($state_exec, $this->stmt->rowCount());
      }

      # fetch the row from the last query command in which query needs to be validated
      public function row_fetch() {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
      }

      # check if record exists in the specific table column
      public function query_record_exists($col_name, $table_name, $content) {
        $sql_cmd = "SELECT ".$col_name." FROM ".$table_name." WHERE ".$col_name." = :str_1";
        $this->pdo_sql_vali_execute($sql_cmd, array(':str_1'=>$content));
        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? true : false;
      }

      # fetch only one row based on sql command
      public function pdo_sql_row_fetch($sql) {
        $sql_result = $this->pdo->query($sql);
        $row_values = $sql_result->fetch(PDO::FETCH_ASSOC);
        return $row_values;
      }

      # fetch multiple rows based on specific sql command
      public function pdo_sql_rows_fetch($sql, $name_ls) {
        $sql_result = $this->pdo->query($sql);
        $sql_arr = array();
        while ($row = $sql_result->fetch(PDO::FETCH_ASSOC)) {
          $ret_arr = array();
          for ($i=0; $i<count($name_ls); $i++) {
            array_push($ret_arr, $row[$name_ls[$i]]);
          }
          array_push($sql_arr, $ret_arr);
        }
        return $sql_arr;
      }

      # generate sql insert commands based on both userinput features and features require further computation
      public function sql_insert_gen($request_array, $table) {
        $str_cmd = "INSERT INTO ".$table." (";
        $key_array = array_keys($request_array);
        $vals_array = array_values($request_array);
        $out_vals_array = implode(',', array_map(function($val) { return ':'.$val; }, $key_array));
        $str_cmd .= implode(",", $key_array);
        $str_cmd .= ') VALUES ('.$out_vals_array.')';
        $pdo_exec_arr = array_combine(explode(',', $out_vals_array),  $vals_array);
        return $this->pdo_sql_vali_execute($str_cmd, $pdo_exec_arr)[0];
      }

      # show the current color of this text box
      public function color_ls_read($ele) {
        return $this->color_ls[$ele];
      }

      # update color of text box because there is an error or warning
      public function color_ls_update($ele) {
        $this->color_ls[$ele] = "color:red;";
      }

      # adds one specfic error message in the error history
      public function error_msg_append($str_msg) {
        array_push($this->err_msgs, $str_msg);
      }

      # print out all the error messages in this page history
      public function error_msg_print() {
        echo "<hr>";
        for ($i=0; $i <count($this->err_msgs); $i++) {
          echo $this->err_msgs[$i];
        }
      }

      # check if specifc spec values are out of bounds, leave val1 and val2 for two versions, val2 can be optional
      public function user_Input_spec_vali($request_array, $spec_arr, $val1, $val2, $uncertainty) {
        $state = true; $key_arr=array_keys($spec_arr); $val_arr=array_values($spec_arr);
        for($i=0; $i<count($spec_arr); $i++) {
          if ((!($request_array[$key_arr[$i]]>=($val1-$uncertainty) && $request_array[$key_arr[$i]]<=($val1+$uncertainty)))
          && (!($request_array[$key_arr[$i]]>=($val2-$uncertainty) && $request_array[$key_arr[$i]]<=($val2+$uncertainty)))) {
            $this->error_msg_append("WARNING: The value of ".$val_arr[$i]." is suggested in between ".($val1-$uncertainty)
            ." to ".($val1+$uncertainty)." or ".($val2-$uncertainty)." to ".($val2+$uncertainty)."<br/>");
            $this->color_ls_update($key_arr[$i]);
            $state = false;
          }
        }
        return $state ? true : false;
      }

      # batch validation function which can check both required boxes and optional boxes valeus existed in the specific databse column
      public function batch_opt_db_vali($var_names, $display_names, $col_name, $table_name, $opt_num) {
        $batch_state = true;
        for ($j=0; $j<count($var_names); $j++) {
          $cur_state = $this->query_record_exists($col_name, $table_name, $_REQUEST[$var_names[$j]]);
          $batch_state = $batch_state && $cur_state;
          if (!$cur_state) {
            $this->error_msg_append("ERROR: ".$display_names[$j]." ". ($j+1) . " is out of Spec!"."<br/>");
            $this->color_ls_update($var_names[$j]);
          }
          for ($i=2; $i<$opt_num+2; $i++) {
              $request_str =$var_names[$j].'_'.$i;
              $cur_state = $_REQUEST[$request_str] ? $this->query_record_exists($col_name, $table_name, $_REQUEST[$request_str]) : true;
              $batch_state = $batch_state && $cur_state;
              if (!$cur_state) {
                $this->error_msg_append("ERROR: ".$display_names[$j]." ".$i." is out of Spec!"."<br/>");
                $this->color_ls_update($request_str);
              }
          }
        }
        return $batch_state;
      }

      # compute general ID in the specific algorithm and input parameters
      public function ID_computation($row_value, $mid_var, $prefix, $letter, $indice) {
        $Batch_Digit = explode("-", $row_value);
        $DATE_TEST = date("mdY");
        if ($Batch_Digit[$indice] === $DATE_TEST) {
          	$INC_DIGIT = $Batch_Digit[$indice+1] + 1;
          	$FILM_ID = $prefix . $letter . $mid_var . "-" . $Batch_Digit[$indice] . "-" . $INC_DIGIT;
        } else {
          	$FILM_ID = $prefix . $letter . $mid_var . "-" . $DATE_TEST . "-1";
        }
        return $FILM_ID;
      }

      # release the PDO object and close connection
      public function __destructor() {
        $this->pdo = NULL;
      }
  }
?>
