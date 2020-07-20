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

      public function color_ls_read($ele) {
        return $this->color_ls[$ele];
      }

      public function color_ls_update($ele) {
        $this->color_ls[$ele] = "color:red;";
      }

      public function error_msg_append($str_msg) {
        array_push($this->err_msgs, $str_msg);
      }

      public function error_msg_print() {
        echo "<hr>";
        echo "<h3>Error Messages:</h3>";
        for ($i=0; $i <count($this->err_msgs); $i++) {
          echo $this->err_msgs[$i];
        }
        echo "Above user inputs are not in standards. Records are not added!"."<br/>";
      }

      # set up the conncection of local host on the web server, execute sql comamnds
      public function pdo_sql_row_fetch($sql) {
        $sql_result = $this->pdo->query($sql);
        $row_values = $sql_result->fetch(PDO::FETCH_ASSOC);
        return $row_values;
      }

      # check if specifc spec values are out of bounds, leave val1 and val2 for two versions, val2 can be optional
      public function user_Input_spec_vali($request_array, $spec_arr, $val1, $val2, $uncertainty) {
        $state = true; $key_arr=array_keys($spec_arr); $val_arr=array_values($spec_arr);
        for($i=0; $i<count($spec_arr); $i++) {
          if ((!($request_array[$key_arr[$i]]>=($val1-$uncertainty) && $request_array[$key_arr[$i]]<=($val1+$uncertainty)))
          && (!($request_array[$key_arr[$i]]>=($val2-$uncertainty) && $request_array[$key_arr[$i]]<=($val2+$uncertainty)))) {
            $this->error_msg_append("The value of ".$val_arr[$i]." must be in between ".($val1-$uncertainty)
            ." to ".($val1+$uncertainty)." or ".($val2-$uncertainty)." to ".($val2+$uncertainty)."<br/>");
            $this->color_ls_update($key_arr[$i]);
            $state = false;
          }
        }
        return $state ? true : false;
      }

      public function pdo_sql_vali_execute($sql, $pdo_exec_arr) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
          $this->stmt = $this->pdo->prepare($sql);
          $state_exec = $this->stmt->execute($pdo_exec_arr);
        } catch (Exception $ex) {
          print($ex->getMessage());
          return array(false);
        }
        return array($state_exec, $this->stmt->rowCount());
      }

      public function user_Input_batch_vali($col_name, $table_name, $request_array, $target) {
        $sql_cmd = "SELECT ".$col_name." FROM ".$table_name." WHERE ".$col_name."=:str_1";
        $this->pdo_sql_vali_execute($sql_cmd, array(':str_1'=>$request_array[$target]));
        $row = $this->stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? true : false;
      }

      public function row_fetch() {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
      }

      public function query_record_exists($col_name, $table_name, $content) {
        $sql_cmd = "SELECT ".$col_name." FROM ".$table_name." WHERE ".$col_name." = :str_1";
        $stmt = $this->pdo->prepare($sql_cmd);
        $state_exec = $stmt->execute(array(':str_1'=>$content));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? true : false;
      }

      public function batch_opt_db_vali($batch_str, $size, $col_name, $table_name, $opt_num) {
        $batch_state = true;
        for ($j=0; $j<count($batch_str); $j++) {
          $cur_state = $this->user_Input_batch_vali($col_name, $table_name, $_REQUEST, $batch_str[$j]);
          $batch_state = $batch_state && $cur_state;
          if (!$cur_state) {
            $this->error_msg_append($size[$j]." is out of Spec!"."<br/>");
            $this->color_ls_update($batch_str[$j]);
          }
          for ($i=2; $i<$opt_num+2; $i++) {
              $request_str =$batch_str[$j].'_'.$i;
              $cur_state = $_REQUEST[$request_str] ? $this->user_Input_batch_vali($col_name, $table_name, $_REQUEST, $request_str) : true;
              $batch_state = $batch_state && $cur_state;
              if (!$cur_state) {
                $this->error_msg_append($size[$j]." ".$i." is out of Spec!"."<br/>");
                $this->color_ls_update($request_str);
              }
          }
        }
        return $batch_state;
      }

      # compute general ID in the specific algorithm and input parameters
      public function ID_computation($row_value, $THICKNESS, $prefix, $letter, $indice) {
        $Batch_Digit = explode("-", $row_value);
        $DATE_TEST = date("mdY");
        if ($Batch_Digit[$indice] === $DATE_TEST) {
          	$INC_DIGIT = $Batch_Digit[$indice+1] + 1;
          	$FILM_ID = $prefix . $letter . $THICKNESS . "-" . $Batch_Digit[$indice] . "-" . $INC_DIGIT;
        } else {
          	$FILM_ID = $prefix . $letter . $THICKNESS . "-" . $DATE_TEST . "-1";
        }
        return $FILM_ID;
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

      # release the PDO object and close connection
      public function __destructor() {
        $this->pdo = NULL;
      }
  }
?>
