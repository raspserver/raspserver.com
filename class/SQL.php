<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of SQL
 *
 * @author rene
 */
class SQL {
    
    function __construct (
            $mysqli_credentials
    ) {
        $this->mysqli_credentials = $mysqli_credentials;
    }
    
    function mySQLiQuery($mysqli_statement) {
        $mysqli = new mysqli(explode(":", $this->mysqli_credentials)[0], explode(":", $this->mysqli_credentials)[1], explode(":", $this->mysqli_credentials)[2], explode(":", $this->mysqli_credentials)[3]);
        if (!$mysqli->connect_errno) {
            $mysqli->set_charset("utf8");
            $result = $mysqli->query($mysqli_statement);
        }
        else {
            throw new Exception("mysqli (".$mysqli->connect_errno.") ".$mysqli->connect_error);
        }
        $mysqli->close();
        if($result !== true and $result !== false) {
            while($row = $result->fetch_assoc()) {
                $table[] = $row;
            }
            return $table;
        } else {
            return $result;
        }
    }
    
    
    
}
