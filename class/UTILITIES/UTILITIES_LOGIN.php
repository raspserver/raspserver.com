<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_LOGIN
 *
 * @author rene
 */
class UTILITIES_LOGIN {
    
    function __construct (
            $SQL,
            $raspberry_emulated
    ) {
        $this->SQL = $SQL;
        $this->raspberry_emulated = $raspberry_emulated;
    }
    
    function DeleteCurrentSessionId() {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("DELETE FROM sessions WHERE BINARY "
                    . "session_id=".json_encode(session_id())." AND "
                    . "raspberry=true");
        } else {
            $this->SQL->mySQLiQuery("DELETE FROM sessions WHERE BINARY "
                . "session_id=".json_encode(session_id())." AND "
                . "raspberry=false");
        }
    }
    
    function SaveCurrentSessionID() {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("DELETE FROM sessions WHERE "
                    ."raspberry=true");
            $this->SQL->mySQLiQuery("INSERT INTO sessions (session_id, raspberry, ipaddress) VALUES ("
                    . json_encode(session_id()).", "
                    . "true, "
                    . json_encode(htmlspecialchars($_SERVER["REMOTE_ADDR"])).")");
        } else {
            $this->SQL->mySQLiQuery("INSERT INTO sessions (session_id, raspberry, ipaddress) VALUES ("
                    . json_encode(session_id()).", "
                    . "false, "
                    . json_encode(htmlspecialchars($_SERVER["REMOTE_ADDR"])).")");
        }
    }  

    function IsThisRASPBERRYUserSignedIn() {
        $session_id = $this->SQL->mySQLiQuery("SELECT session_id FROM sessions WHERE BINARY "
                . "session_id=".json_encode(session_id())." AND "
                . "raspberry=true")[0]['session_id'];
        if($session_id === session_id()) {
            return true;
        } else {
            return false;
        }
    }
    
    function IsThisRASPserverUserSignedIn() {
        $session_id = $this->SQL->mySQLiQuery("SELECT session_id FROM sessions WHERE BINARY "
                . "session_id=".json_encode(session_id())." AND "
                . "raspberry=false")[0]['session_id'];
        if($session_id === session_id()) {
            return true;
        } else {
            return false;
        }
    }
    

}
