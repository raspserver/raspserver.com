<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UNIXUSERS
 *
 * @author rene
 */
class UNIXUSERS {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_UNIXUSERS = new UTILITIES_UNIXUSERS($UTILITIES, $BUTTONS);
        $this->unixusers = $UTILITIES->RASPBERRY->GetUnixUsers();
        $this->availableuids = $this->UTILITIES_UNIXUSERS->GetAvailableUids($this->unixusers);
        $this->availablegids = $this->UTILITIES_UNIXUSERS->GetAvailableGids($this->unixusers);
    }
    
    function DisplayUnixUsers($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("Raspberry Pi Users");
        $header = array("(uid/gid) ".$this->UTILITIES->Translate("Raspberry Pi User"));
        foreach($this->unixusers as $unixuser){
            $table[] = array("(".$unixuser['uid']."/".$unixuser['gid'].") ".$unixuser['name']);
        }
        $footer = $this->ButtonToUnixUserConfigurationTable();
        $TABLEOFUNIXUSERS = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFUNIXUSERS->DisplayTableOnCard($w3_responsive_class, "n");
        return $string;
    }
    
    function ButtonToUnixUserConfigurationTable() {
        $string = $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToUsersConfiguration",                                                                               // $button_id
            $this->UTILITIES->toroot."/pages/RASPBERRY_Users.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,  // $button_href    
            "DisableButtonsAndSpinThisButton(".json_encode("IdButtonLinkedToUsersConfiguration").")",                           // $button_jsfunction_onclick
            $this->UTILITIES->Translate("Configure")                                                                          // $button_text
        );
        return $string;
    }
    
    function ConfigureUnixUsers() {
        $string = "<div id='ConfigureUnixUsersTableParentNode'>".$this->ConfigureUnixUsersChildNode()."</div>";
        return $string;
    }
    
    function ConfigureUnixUsersChildNode() {
        $name =   "<a id='ConfigureUnixUsersTableName'>"
                    . $this->UTILITIES->Translate("Raspberry Pi Users")
                    . " (".count($this->unixusers)."/".$this->UTILITIES_UNIXUSERS->maxusers.")"
                . "</a>";
        $header = array("(uid/gid) ".$this->UTILITIES->Translate("Raspberry Pi User") , "");
        $table = $this->UnixUserConfigurationTable();
        $footer = $this->ButtonToDashboard();
        if(count($this->UTILITIES->RASPBERRY->GetUnixUsers()) < $this->UTILITIES_UNIXUSERS->maxusers) {
            $footer .= " ".$this->AddUserButton().$this->HiddenConsolePanels();
        } else {
            $footer .= "<br><br>".$this->UTILITIES->Translate("max users reached").$this->HiddenConsolePanels();
        }
        $CONFIGUREUNIXUSERTABLE = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureUnixUsersTableChildNode'>".$CONFIGUREUNIXUSERTABLE->DisplayTableOnCard("w3-row", "w3-right-align")."</div>";
        return $string;
    }
    
    function ButtonToDashboard() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToDashboard",
            $this->toroot."/pages/RASPBERRY_Dashboard.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToDashboard").")",
            "<i class='fa fa-arrow-left' aria-hidden='true'></i>"                                                                 // $button_text
        );
        return $string;
    }
    
    function UnixUserConfigurationTable() {
        foreach($this->unixusers as $unixuser){
            switch($unixuser['uid']) {
                case 0:
                    $table[] = array($this->UserEntry($unixuser), 
                             $this->ChangePasswordButton($unixuser)); break;
                case 1000:
                    $table[] = array($this->UserEntry($unixuser), 
                             "(".$this->UTILITIES->Translate("Default user").") ".$this->ChangePasswordButton($unixuser)); break;
                case 1001:
                    $table[] = array($this->UserEntry($unixuser), 
                             "(".$this->UTILITIES->Translate("PBX software's username").") ".$this->ChangePasswordButton($unixuser)); break;
                default:
                    $table[] = array($this->UserEntry($unixuser), 
                             $this->ChangeUidGidButton($unixuser)." ". $this->DeleteUserButton($unixuser)." "
                           . $this->ChangePasswordButton($unixuser));
            }
        }
        return $table;
    }
    
    function UserEntry($unixuser) {
        $string = "<a id='UnixUser:".$unixuser['uid'].":".$unixuser['gid'].":".$unixuser['name']."'>"
                      ."(".$unixuser['uid']."/".$unixuser['gid'].") ".$unixuser['name']
                  ."</a>";
        return $string;
    }
    
    function AddUserButton() {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonAddUnixUser", "", "", "", "", "AddUnixUser("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/UNIXUSERS_LoadConfigureUnixusersTable.php").", "         // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "
                . json_encode("IdButtonAddUnixUser").", "                                                               // idbuttonadduser
                . json_encode("IdConsolePanelAddUser").", "                                                             // idpanel
                . json_encode("IdEntryNewUsersName").", "                                                               // identry1
                . json_encode("IdEntryNewUsersPassword").", ".json_encode("IdEntryNewUsersPasswordConfirmation").", "   // identry2, identry3                                                           
                . json_encode("IdButtonCancelAddUnixUser").", "                                                         // idcancelbutton
                . json_encode("IdButtonAddUnixUserNow").", "                                                            // idconfirmbutton
                . json_encode("IdButtonClearConsoleAddUser").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputAddUser").", "                                                            // idconsole
                . json_encode($this->UTILITIES_UNIXUSERS->GetNewUsersUidGid($this->unixusers)['uid']).", "              // new_uid
                . json_encode($this->UTILITIES_UNIXUSERS->GetNewUsersUidGid($this->unixusers)['gid']).", "              // new_uid
                . json_encode($this->UTILITIES->Translate("User exists")).", "                                          // 
                . json_encode($this->UTILITIES->Translate("Invalid user name")).", "                                    // 
                . json_encode($this->UTILITIES->Translate("Invalid password")).", "                                      //
                . json_encode($this->UTILITIES->Translate("No match")).", "
                . json_encode("ConfigureUnixUsersTableParentNode").", "
                . json_encode("ConfigureUnixUsersTableChildNode").")",                                         // 
            $this->UTILITIES->Translate("Add user"));                                                               // $button_text
        return $string;
    }
    
    function ChangeUidGidButton($unixuser) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonChangeUidGid:".$unixuser['uid'].":".$unixuser['gid'].":".$unixuser['name'], "inline", "", "", "", "ChangeUidGid("  // $button_id, ...display, ...jsfunction_onclick               
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                     // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/UNIXUSERS_LoadConfigureUnixusersTable.php").", "                 // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                       // authorization_key, user, host  
                . json_encode("IdButtonLinkedToDashboard").", "
                . json_encode("IdButtonAddUnixUser").", "                                                               // idbuttonadduser
                . json_encode("IdConsolePanelChangeUidGid").", "                                                                // idpanel 
                . json_encode("IdEntryUid").", "                                                                                // identry1
                . json_encode("IdEntryGid").", "                                                                                // identry2
                . json_encode("IdButtonCancelChangeUidGid").", "                                                                // idcancelbutto
                . json_encode("IdButtonChangeUidGidNow").", "                                                                   // idconfirmbutton
                . json_encode("IdButtonClearConsoleChangeUidGid").", "                                                          // idconsolebutton
                . json_encode("IdConsoleOutputChangeUidGid").", "                                                               // idconsole
                . json_encode("UnixUser:".$unixuser['uid'].":".$unixuser['gid'].":".$unixuser['name']).", "                     // userid
                . json_encode($unixuser['name']).", "                                                                           // username
                . json_encode($unixuser['uid']).", "                                                                            // useruid
                . json_encode($unixuser['gid']).", "
                . json_encode($this->UTILITIES->Translate("select")).", "
                . json_encode("ConfigureUnixUsersTableParentNode").", "
                . json_encode("ConfigureUnixUsersTableChildNode").")",                                                                        // usergid             
            $this->UTILITIES->Translate("Change (uid/gid)"));                                                               // $button_text
        return $string;
    }
    
    function DeleteUserButton($unixuser) {       
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonDeleteUser:".$unixuser['uid'].":".$unixuser['gid'].":".$unixuser['name'], "inline", "", "", "", "DeleteUser("  // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/UNIXUSERS_GetCommandsForUserRemoval.php").", "  
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/UNIXUSERS_LoadConfigureUnixusersTable.php").", "             // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                    // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "
                . json_encode("IdButtonAddUnixUser").", "                                                               // idbuttonadduser
                . json_encode("IdConsolePanelDeleteUser").", "                                                              // idpanel
                . json_encode("IdButtonCancelDeleteUser").", "                                                              // idcancelbutton
                . json_encode("IdButtonDeleteUserNow").", "                                                                 // idconfirmbutton
                . json_encode("IdButtonClearConsoleDeleteUser").", "                                                        // idconsolebutton       
                . json_encode("IdConsoleOutputDeleteUser").", "                                                             // idconsole 
                . json_encode("UnixUser:".$unixuser['uid'].":".$unixuser['gid'].":".$unixuser['name']).", "                 // userid
                . json_encode($unixuser['name']).", "
                . json_encode("ConfigureUnixUsersTableParentNode").", "
                . json_encode("ConfigureUnixUsersTableChildNode").")",                                                                     // username
            $this->UTILITIES->Translate("Remove user"));                                                                // $button_text
        return $string;
    }
    
    function ChangePasswordButton($unixuser) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonChangePassword:".$unixuser['uid'].":".$unixuser['gid'].":".$unixuser['name'], "inline", "", "", "", "ChangePassword("  // $button_id, ...display, ...jsfunction_onclick           
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                         // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/UNIXUSERS_LoadConfigureUnixusersTable.php").", "                     // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                            // authorization_key, user, host 
                . json_encode("IdButtonLinkedToDashboard").", "
                . json_encode("IdButtonAddUnixUser").", "                                                               // idbuttonadduser
                . json_encode("IdConsolePanelChangePassword").", "                                                                  // idpanel
                . json_encode("IdEntryNewPassword").", "                                                                            // identry1
                . json_encode("IdEntryNewPasswordConfirmation").", "                                                                // identry2
                . json_encode("IdButtonCancelChangePassword").", "                                                                  // idcancelbutton          
                . json_encode("IdButtonChangePasswordNow").", "                                                                     // idconfirmbutton
                . json_encode("IdButtonClearConsoleChangePassword").", "                                                            // idconsolebutton
                . json_encode("IdConsoleOutputChangePassword").", "                                                                 // idconsole 
                . json_encode("UnixUser:".$unixuser['uid'].":".$unixuser['gid'].":".$unixuser['name']).", "                         // userid
                . json_encode($unixuser['name']).", "                                                                               // username
                . json_encode($this->UTILITIES->Translate("No match")).", "                                                         // 
                . json_encode($this->UTILITIES->Translate("Invalid password")).", "
                . json_encode("ConfigureUnixUsersTableParentNode").", "
                . json_encode("ConfigureUnixUsersTableChildNode").")",                                                 //                                          
            $this->UTILITIES->Translate("Change password"));                                                                    // $button_text
        return $string;
    }
    
    function HiddenConsolePanels() {
        if(count($this->UTILITIES->RASPBERRY->GetUnixUsers()) < $this->UTILITIES_UNIXUSERS->maxusers) {
            $string = $this->UTILITIES_UNIXUSERS->HiddenConsolePanelAddUser();
        }
        $string .= $this->UTILITIES_UNIXUSERS->HiddenConsolePanelChangeUidGid();
        $string .= $this->UTILITIES_UNIXUSERS->HiddenConsolePanelDeleteUser();
        $string .= $this->UTILITIES_UNIXUSERS->HiddenConsolePanelChangePassword();
        return $string;
    }
    
}
