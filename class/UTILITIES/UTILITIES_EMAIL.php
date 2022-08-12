<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_EMAIL
 *
 * @author rene
 */
class UTILITIES_EMAIL {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
    }
    
    function LoadEmailTable() {
        $string = "<script>"
                    . "ReloadElement("
                        . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_LoadEmailTable.php").", " // AjaxFileExecut
                        . json_encode($_SESSION["key"]).","
                        . json_encode("EmailTableParentNode").","
                        . json_encode("EmailTableChildNode").");"
            . "</script>";
        return $string;
    }
    
    function LoadConfigureEmailTable() {
        $string = "<script>"
                    . "ReloadElement("
                        . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_LoadConfigureEmail.php").", " // AjaxFileExecut
                        . json_encode($_SESSION["key"]).", "
                        . json_encode("ConfigureEmailParentNode").", "
                        . json_encode("ConfigureEmailChildNode").");"
            . "</script>";
        return $string;
    }
        
    function GetPOP3SslConnectionStatuses() {
        $string = "<script>"
                    . "GetEmailAddressesAndInitiateConnectionTests("
                        . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_GetEmailAddresses.php").", " // AjaxFileExecut
                        . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_GetPOP3ConnectionStatus.php").", "
                        . json_encode($_SESSION["key"]).");"
                . "</script>";
        return $string;
    }
    
    function GetEmalUsers()  {
        $unixusers = $this->UTILITIES->RASPBERRY->GetUnixUsers();
        foreach($unixusers as $unixuser) {
            if($unixuser['name'] !== "root" and $unixuser['name'] !== "asterisk") {
                $email_users[] = $unixuser['name'];
            }
        }
        return $email_users;
    }
    
    function HiddenConsolePanelAddEmail() {
        $AddEmailPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                               // $UTILITIES
            "IdConsolePanelAddEmail", "none", "none", "",                                                           // $console_panel_id, $console_panel_display                                                                                     // $name_display, $name                                                                                                                    // 
            $this->BUTTONS->Input_Dropdown(
                "IdEntryDebianUser", 
                $this->GetEmalUsers(), // $entry_values
                $this->UTILITIES->Translate("Debian user"), $this->UTILITIES->Translate("select"),         // $entry_label,                                             // $select
                "EnableDropdownConfirmationButton(".json_encode("IdButtonAddEmailNow").")")
          . $this->BUTTONS->Input("IdEntryPOP3Server", "text", $this->UTILITIES->Translate("POP3 server"))
          . $this->BUTTONS->Input("IdEntryPOP3User", "text", $this->UTILITIES->Translate("POP3 user"))
          . $this->BUTTONS->Input("IdEntryPOP3Password", "password", $this->UTILITIES->Translate("POP3 password"))
          . $this->BUTTONS->Input("IdEntryPOP3PasswordConfirmation", "password", $this->UTILITIES->Translate("Confirmation"))
          . $this->BUTTONS->TwinButtons(                                                                    // $field_left 
                "IdButtonCancelAddEmail", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text                                                                             // 
                "IdButtonAddEmailNow",    "", "none", "", "w3-opacity", "", $this->UTILITIES->Translate("Add email")),  // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text                                                                           //                          
            "root", "raspbx", "IdConsoleOutputAddEmail", "IdButtonClearConsoleAddEmail", "");                                                                                            // $JSfunctionclearconsolebutton                                             
        $string = $AddEmailPanel->ConsolePanel();
        return $string;
    }

    function HiddenConsolePanelRemoveEmail() {
        $DeleteEmailPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                               // $UTILITIES
            "IdConsolePanelDeleteEmail", "none",                                                            // $console_panel_id, $console_panel_display
            "none", "",                                                                                     // $name_display, $name                                                                                                                    // 
            $this->BUTTONS->TwinButtons(                                                                    // $field_left 
                "IdButtonCancelDeleteEmail", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text                                                                             // 
                "IdButtonDeleteEmailNow",    "", "", "", "", "", $this->UTILITIES->Translate("Remove email")),  // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text                                                                           //                          
            "root",                                                                                         // $user
            "raspbx",                                                                                       // $host
            "IdConsoleOutputDeleteEmail",                                                                   // idconsole                              
            "IdButtonClearConsoleDeleteEmail",                                                              // $idclearconsolebutton  
            "");                                                                                            // $JSfunctionclearconsolebutton                                             
        $string = $DeleteEmailPanel->ConsolePanel();
        return $string;
    }
    
    

}
