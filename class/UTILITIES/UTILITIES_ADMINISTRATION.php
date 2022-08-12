<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_ADMINISTRATION1
 *
 * @author rene
 */
class UTILITIES_ADMINISTRATION {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->SQL = $UTILITIES->SQL;
        $this->RASPBERRY = $UTILITIES->RASPBERRY;
        $this->raspberry_emulated = $UTILITIES->raspberry_emulated;
    }
     
    function IsSetAdministratorPassword() {
        if(!$this->raspberry_emulated) {
            $hashedAdministratorPassword = $this->SQL->mySQLiQuery(
                    "SELECT value FROM settings WHERE BINARY "
                    . "setting=".json_encode("hashedAdministratorPassword")." "
                    . "AND session_id IS NULL");
            if(!$hashedAdministratorPassword) {
                return false;
            } else {
                return true;
            }
        }
    }
    
    function IsSetAdministratorPasswordAsString() {      
        if(!$this->raspberry_emulated) {
            if($this->IsSetAdministratorPassword()){
                return "enabled";
            } else {
                return "disabled";
            }
        } else {
            return "Disabled in demo";
        }
    }
    
    function IsThisPasswordValid($password){
        if (preg_match("/^[a-z_]([a-z0-9_-]{0,31}|[a-z0-9_-]{0,30}\\$)$/", $password)) {
            return true;
        }
        else {
            return false;
        }
    }
    
    function SetAdministratorPassword($password) {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("INSERT INTO settings (setting,value) VALUES ("
                    .json_encode("hashedAdministratorPassword").","
                    .json_encode(password_hash($password, PASSWORD_DEFAULT)).")");
        }
    }
    
    function ChangeAdministratorPassword($password) {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("UPDATE settings SET "
                    . "value=".json_encode(password_hash($password, PASSWORD_DEFAULT))
                    ." WHERE BINARY setting=".json_encode("hashedAdministratorPassword"));
        }
    }
    
    function IsThisAdministratorPasswordWorking($password) {
        if(!$this->raspberry_emulated) {
            $hashedAdministratorPassword = $this->SQL->mySQLiQuery("SELECT value FROM settings WHERE BINARY "
                    . "setting='hashedAdministratorPassword'")[0]['value'];
            if(password_verify($password, $hashedAdministratorPassword)) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    function DisableAdministratorPassword() {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("DELETE FROM settings WHERE BINARY "
                    . "setting='hashedAdministratorPassword'");
        }
    }
    
    
    
}
