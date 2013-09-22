<?php

/*
  Name: Khwaja Anas Nasarullah
  Description: class for DB
  Date: 30 Nov 2008
 */

class DBConnect {

    var $server;
    var $user;
    var $password;
    var $dbname;

    function DBConnect($s, $db, $u, $p) {
        $this->server = $s;
        $this->user = $u;
        $this->password = $p;
        $this->dbname = $db;
    }

    function setDb() {
        $con = mysql_connect($this->server, $this->user, $this->password);
        if (!$con) {
            die('Could not connect: ' . mysql_error());
        }
        
        $this->makeQuery("CREATE DATABASE IF NOT EXISTS {$this->dbname};");
        
        mysql_select_db($this->dbname, $con);
        return $con;
    }

    function makeQuery($query) {
        $result = mysql_query($query) or die(mysql_error());#die('Error in query execution');
        return $result;
    }

    function fetchItem($result) {
        return mysql_fetch_array($result);
    }

    function closeConnection($conn) {
        mysql_close($conn);
    }

    function insert($tablename, $parameter_order, $values) {
        $query = "insert into $tablename (";
        foreach ($parameter_order as $po) {
            $query .= $po . ', ';
        }
        $query = substr($query, 0, strlen($query) - 2) . ') values (';
        foreach ($values as $v) {
            $query .= "'$v', ";
        }
        $query = substr($query, 0, strlen($query) - 2) . ');';
        return $this->makeQuery($query);
    }

    function createTable($tablename, $fields, $types, $overwrite = FALSE) {
        //we wish to provide powerful functionalities to this method, so we leave it for laters...
        //CREATE TABLE `TableName` (`FieldName1` DATE, `FieldName2` VARCHAR (50), `FieldName3` TEXT)
        $params = "";
        if (count($fields) == count($types)) {
            $params .= "(";
            $i = 0;
            foreach ($fields as $field) {
                $params .= $field . " " . $types[$i];
                if ($i < (count($types) - 1)) {
                    $params .= ",";
                }
                $i++;
            }
            $params .= ");";
        }
        if ($overwrite == TRUE) {
            $query = "drop table if exists $tablename;";
            $this->makeQuery($query);
        }

        $query = "CREATE TABLE $tablename " . $params;
        //echo $query.'<br/>';
        return $this->makeQuery($query);
    }

    function find($tablename, $field, $value, $flag = 0) {
        if ($flag == 0) {
            $query = "select * from $tablename where $field like '%$value%';";
        } else {
            $query = "select * from $tablename where $field = '$value';";
        }
        //echo $query;
        return $this->makeQuery($query);
    }

    //later we will make the find parameters as array so that we can make complex select for editing (see remove)
    //we can also make $tablename an array where we can join multiple tables to find value
    function edit($tablename, $edit_field, $edit_value, $find_field = "", $find_value = "") {
        if (($find_field == "") && ($find_value == "")) {
            $query = "update $tablename set $edit_field = '$edit_value';";
            return $this->makeQuery($query);
        } else if (($find_field != "") && ($find_value != "")) {
            $query = "update $tablename set $edit_field = '$edit_value' where $find_field = '$find_value';";
            //echo $query;
            return $this->makeQuery($query);
        }
    }

    /*
      function remove($tablename, $find_field, $find_value)
      {
      $query = "delete from $tablename where $find_field = '$find_value';";
      }
     */

    function remove($tablename, $find_field, $find_value, $operator = 'and') {
        //required: $operator should be either and & or

        if ((count($find_field) != count($find_value)) || (count($find_field) == 0)) {
            return "Error in query...";
        }
        $query = "delete from $tablename where ";

        if ((count($find_field) >= 1) && (count($find_value) >= 1)) {
            for ($i = 0; $i < count($find_field); $i++) {
                $query .= "$find_field[$i] = '$find_value[$i]'";
                if ($i < count($find_value) - 1) {
                    $query .= ' ' . $operator . ' ';
                } else {
                    $query .= ';';
                }
            }
        }

        return $this->makeQuery($query);
    }

}

?>
