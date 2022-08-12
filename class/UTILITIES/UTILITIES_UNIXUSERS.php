<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_UNIXUSERS1
 *
 * @author rene
 */
class UTILITIES_UNIXUSERS {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->SQL = $UTILITIES->SQL;
        $this->mysqli_credentials = $UTILITIES->mysqli_credentials;
        $this->RASPBERRY = $UTILITIES->RASPBERRY;
        $this->raspberry_emulated = $UTILITIES->raspberry_emulated;
        $this->unixusers = $UTILITIES->RASPBERRY->GetUnixUsers();
        $this->maxusers = 101;
        $this->availableuids = $this->GetAvailableUids($this->unixusers);
        $this->availablegids = $this->GetAvailableGids($this->unixusers);
    } 
    
    function GetMaxUsers() {
        if(!$this->raspberry_emulated) {
            $maxusers = $this->SQL->mySQLiQuery("SELECT value FROM settings WHERE BINARY "
                . "setting='maxusers' AND "
                . "session_id IS NULL",
                $this->mysqli_credentials)[0]['value'];
        } else {
            $maxusers = $this->SQL->mySQLiQuery("SELECT value FROM settings WHERE BINARY "
                . "setting='maxusers' AND "
                . "session_id=".json_encode(session_id()),
                $this->mysqli_credentials)[0]['value'];
        }
        return $maxusers;
    }
    
    function IsThisUserNameValid($username){
        if (preg_match("/^[a-z_]([a-z0-9_-]{0,31}|[a-z0-9_-]{0,30}\\$)$/", $username)) {
            return true;
        }
        else {
            return false;
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
    
    function DoesThisUnixUserExist($user, $unixusers) {
        $result = false;
        foreach($unixusers as $unixuser) {
            if($unixuser['name'] === $user) {
                return true;
            }
        }
        return $result;
    }
    
    function GetUidOfUser($user, $unixusers) {
        foreach($unixusers as $unixuser) {
            if($unixuser['name'] === $user) {
                $uid = $unixuser['uid'];
            }
        }
        return $uid;
    }
    
    function GetGidOfUser($user, $unixusers) {
        foreach($unixusers as $unixuser) {
            if($unixuser['name'] === $user) {
                $gid = $unixuser['gid'];
            }
        }
        return $gid;
    }
    
    function GetAvailableUids($unixusers) {
        foreach($unixusers as $unixuser) {
            $uids[] = $unixuser['uid'];
        }
        for($i=0; $i<($this->maxusers - 2); $i++) {
            if(!in_array(1002 + $i, $uids)) {
                $availableuids[] = 1002 + $i;
            }
        }
        return $availableuids;
    }
    
    function GetAvailableGids($unixusers) {
        foreach($unixusers as $unixuser) {
            $gids[] = $unixuser['gid'];
        }
        for($i=0; $i<($this->maxusers - 2); $i++) {
            if(!in_array(1002 + $i, $gids)) {
                $availablegids[] = 1002 + $i;
            }
        }
        return $availablegids;
    }
    
    function GetNewUsersUidGid($unixusers) {
        $availableuids = $this->GetAvailableUids($unixusers);
        $uid = min($availableuids);
        $availablegids = $this->GetAvailableGids($unixusers);
        if(in_array($uid, $availablegids)) {
            $gid = $uid;
        } else {
            foreach($availablegids as $availablegid) {
                if(!in_array($availablegid, $availableuids)) {
                    $newavailablegids[] = $availablegid;
                }
            }
            $gid = min($newavailablegids);
        }
        $uidgid = array("uid" => $uid, "gid" => $gid);
        return $uidgid;
    }
    
    function HiddenConsolePanelAddUser() {
        $AddUserPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelAddUser", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                         // $name_display, $name  
            $this->BUTTONS->Input(                                                                              // $field_left 
                "IdEntryNewUsersName",                  "text",     $this->UTILITIES->Translate("New user"))        // $entry1_id, $entry1_type, $entry1_label
          . $this->BUTTONS->Input(                                                                              // $field_left continuation  
                "IdEntryNewUsersPassword",              "password", $this->UTILITIES->Translate("Password"))        // $entry1_id, $entry1_type, $entry1_label
          . $this->BUTTONS->Input(                                                                              // $field_left continuation
                "IdEntryNewUsersPasswordConfirmation",  "password", $this->UTILITIES->Translate("Confirmation"))    // $entry1_id, $entry1_type, $entry1_label
          . $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelAddUnixUser", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonAddUnixUserNow",    "", "", "", "", "", $this->UTILITIES->Translate("Add user")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputAddUser",                                                                           // $idconsole
            "IdButtonClearConsoleAddUser",                                                                      // $idclearconsolebutton  
            "");                                                                                                // $JSfunctionclearconsolebutton                                                                     
        $string = $AddUserPanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelChangeUidGid() {
        $ChangeUidGidPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                       // $UTILITIES
            "IdConsolePanelChangeUidGid", "none",                                                                   // $console_panel_id, $console_panel_display
            "none", "",                                                                                             // $name_display, $name
            $this->BUTTONS->Input_Dropdown(                                                                         // $field_left
                "IdEntryUid", $this->availableuids,                                                                     // $entry_id, $entry_values
                $this->UTILITIES->Translate("uid"), $this->UTILITIES->Translate("select"),                              // $entry_label
                "EnableDropdownConfirmationButton(".json_encode("IdButtonChangeUidGidNow").")")                                 // JsFunctionOnChange
          . $this->BUTTONS->Input_Dropdown(                                                                         // $field_left continuation
                "IdEntryGid", $this->availablegids,                                                                     // $entry_id, $entry_values
                $this->UTILITIES->Translate("gid"), $this->UTILITIES->Translate("select"),                              // $entry_label
                "EnableDropdownConfirmationButton(".json_encode("IdButtonChangeUidGidNow").")")                                 // JsFunctionOnChange
          . $this->BUTTONS->TwinButtons(                                                                            // $field_left continuation
                "IdButtonCancelChangeUidGid", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonChangeUidGidNow",    "", "none", "", "w3-opacity", "", $this->UTILITIES->Translate("Change (uid/gid)")), // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text
            "root", "raspbx",                                                                                       // $user, $host
            "IdConsoleOutputChangeUidGid",                                                                          // $idconsole
            "IdButtonClearConsoleChangeUidGid", "");                                                                // $idclearconsolebutton, $JSfunctionclearconsolebutton
        $string = $ChangeUidGidPanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelDeleteUser() {
        $DeleteUserPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                   // $UTILITIES
            "IdConsolePanelDeleteUser", "none",                                                 // $console_panel_id, $console_panel_display
            "none", "",                                                                         // $name_display, $name                                                                                                                    // 
            $this->BUTTONS->TwinButtons(                                                        // $field_left 
                "IdButtonCancelDeleteUser", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),          // $button_cancel_id, ...display, ...jsfunction_onclick, ...text                                                                             // 
                "IdButtonDeleteUserNow",    "", "", "", "", "", $this->UTILITIES->Translate("Remove user")),    // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text                                                                           //                          
            "root",                                                                             // $user
            "raspbx",                                                                           // $host
            "IdConsoleOutputDeleteUser",                                                        // idconsole                              
            "IdButtonClearConsoleDeleteUser",                                                   // $idclearconsolebutton  
            "");                                                                                // $JSfunctionclearconsolebutton                                             
        $string = $DeleteUserPanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelChangePassword() {
        $ChangePasswordPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                               // $UTILITIES
            "IdConsolePanelChangePassword", "none",                                                         // $console_panel_id, $console_panel_display
            "none", "",                                                                                     // $name_display, $name 
            $this->BUTTONS->Input(                                                                          // $field_left 
                "IdEntryNewPassword",             "password", $this->UTILITIES->Translate("New password"))      // $entry1_id, $entry1_type, $entry1_label 
          . $this->BUTTONS->Input(                                                                          // $field_left continuation
                "IdEntryNewPasswordConfirmation", "password", $this->UTILITIES->Translate("Confirmation"))      // $entry2_id, $entry2_type, $entry2_label  
          . $this->BUTTONS->TwinButtons(                                                                    // $field_left continuation
                "IdButtonCancelChangePassword", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                  // $button_cancel_id, ...display, ...jsfunction_onclick, ...text                                                                                                       // $button_cancel_display           
                "IdButtonChangePasswordNow",    "", "", "", "", "", $this->UTILITIES->Translate("Change password")),        // $button_confirmation_id                 
            "root",                                                                                         // $user
            "raspbx",                                                                                       // $host
            "IdConsoleOutputChangePassword",                                                                // $idconsole                      
            "IdButtonClearConsoleChangePassword",                                                           // $idclearconsolebutton  
            "");                                                                                            // $JSfunctionclearconsolebutton                                                                       
        $string = $ChangePasswordPanel->ConsolePanel();
        return $string;
    }
    
    
    
}
