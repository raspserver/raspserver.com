<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_LANGUAGES1
 *
 * @author rene
 */
class UTILITIES_LANGUAGES {
    
    function __construct (
            $UTILITIES
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->SQL = $UTILITIES->SQL;
        $this->raspberry_emulated = $UTILITIES->raspberry_emulated;
        $this->UTILITIES_LOGIN = $UTILITIES->UTILITIES_LOGIN; 
    }
    
    function GetLanguagesAvailable() {
        $languages_available = $this->SQL->mySQLiQuery("SELECT * FROM languages ORDER BY token");
        return $languages_available;
    }
    
    function GetLanguagesSet() {
        if(!$this->raspberry_emulated) {
            $languages_set = $this->SQL->mySQLiQuery("SELECT token,token_upper_case,language FROM languages "
                . "INNER JOIN settings ON token=setting WHERE BINARY "
                . "value='1' AND "
                . "session_id IS NULL "
                . "ORDER BY token");
        } elseif(!$this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn()) {
            $languages_set = $this->GetLanguagesAvailable();
        } else {
            $languages_set = $this->SQL->mySQLiQuery("SELECT token,token_upper_case,language FROM languages "
                . "INNER JOIN settings ON token=setting WHERE BINARY "
                . "value='1' AND "
                . "session_id=".json_encode(session_id())." "
                . "ORDER BY token");
        }
        return $languages_set;
    }
    
    function IsThisLanguageSet($token) {
        $result = false;
        $languages_set = $this->GetLanguagesSet();
        foreach($languages_set as $language) {
            if($language['token'] === $token) {
                $result = true;
            }
        }
        return $result;
    }
    
    function SetLanguage($lang) {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("UPDATE settings SET value=".json_encode($lang)." WHERE BINARY "
                . "setting='language' AND "
                . "session_id IS NULL");
        } elseif($this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn()) {
            $this->SQL->mySQLiQuery("UPDATE settings SET value=".json_encode($lang)." WHERE BINARY "
                . "setting='language' AND "
                . "session_id=".json_encode(session_id()));
        }  
    }
    
    function SetSetLanguage($lang) {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("UPDATE settings SET value='1' WHERE BINARY "
                . "setting=".json_encode($lang)." AND "
                . "session_id IS NULL");
        } else {
            $this->SQL->mySQLiQuery("UPDATE settings SET value='1' WHERE BINARY "
                . "setting=".json_encode($lang)." AND "
                . "session_id=".json_encode(session_id()));
        }
    }
    
    function UnsetSetLanguage($lang) {
        if(!$this->raspberry_emulated) {
            $this->SQL->mySQLiQuery("UPDATE settings SET value='0' WHERE BINARY "
                . "setting=".json_encode($lang)." AND "
                . "session_id IS NULL");
        } else {
            $this->SQL->mySQLiQuery("UPDATE settings SET value='0' WHERE BINARY "
                . "setting=".json_encode($lang)." AND "
                . "session_id=".json_encode(session_id()));
        }
    }
    
}
