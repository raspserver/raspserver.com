<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_PING
 *
 * @author rene
 */
class UTILITIES_PING {
    
    function __construct (
            $UTILITIES
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->SQL = $UTILITIES->SQL;
        $this->raspberry_emulated = $UTILITIES->raspberry_emulated;
        $this->UTILITIES_LOGIN = $UTILITIES->UTILITIES_LOGIN; 
    }
    
    function GetDomains() {
        if(!$this->raspberry_emulated) {
            $domains = $this->SQL->mySQLiQuery("SELECT value FROM settings WHERE BINARY "
                    . "setting=".json_encode("ping")." AND "
                    . "session_id IS NULL");
        } else {
            $domains = $this->SQL->mySQLiQuery("SELECT value FROM settings WHERE BINARY "
                    . "setting=".json_encode("ping")." AND "
                    . "session_id=".json_encode(session_id()));
        }
        return $domains;
    }
    
    function IsThisDomainOnTheList($newdomain) {
        $result = false;
        $domains = $this->GetDomains();
        foreach($domains as $domain) {
        if($domain['value'] === $newdomain) {
                return true;
            }
        }
        return $result;
    }
    
    function AddDomainToTheList($newdomain) {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("INSERT INTO settings (setting,value) VALUES ("
                . json_encode("ping").","
                . json_encode($newdomain).")");
        } else {
            $this->SQL->mySQLiQuery("INSERT INTO settings VALUES ("
                . json_encode(session_id()).","
                . json_encode("ping").","
                . json_encode($newdomain).")");
        }
    }
    
    function RemoveDomainFromTheList($domain) {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("DELETE FROM settings WHERE BINARY "
                . "setting=".json_encode("ping")." AND BINARY "
                . "value=".json_encode($domain)." AND "
                . "session_id IS NULL");
        } else {
            $this->SQL->mySQLiQuery("DELETE FROM settings WHERE BINARY "
                . "setting=".json_encode("ping")." AND BINARY "
                . "value=".json_encode($domain)." AND "
                . "session_id=".json_encode(session_id()));
        }
    }
    
    
    //put your code here
}
