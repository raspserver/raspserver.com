<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_SSH
 *
 * @author rene
 */
class UTILITIES_SSH {
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
    }
    
    function LoadKeysTable() {
        $string = "<script>"
                    . "ReloadElements("
                        . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadKeysTable.php").", " // AjaxFileExecut
                        . json_encode($_SESSION["key"]).","
                        . json_encode("AuthorizedKeysTableParentNode°HostKeysTableParentNode°KnownHostsTableParentNode°UserKeysTableParentNode").","
                        . json_encode("AuthorizedKeysTableChildNode°HostKeysTableChildNode°KnownHostsTableChildNode°UserKeysTableChildNode").");"
            . "</script>";
        return $string;
    }
    
    function LoadConfigureAuthorizedKeys() {
        $string = "<script>"
                    . "ReloadElement("
                        . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureAuthorizedKeys.php").", " // AjaxFileExecut
                        . json_encode($_SESSION["key"]).","
                        . json_encode("ConfigureAuthorizedKeysParentNode").","
                        . json_encode("ConfigureAuthorizedKeysChildNode").");"
            . "</script>";
        return $string;
    }
    
    function LoadConfigureHostKeys() {
        $string = "<script>"
                    . "ReloadElement("
                        . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureHostKeys.php").", " // AjaxFileExecut
                        . json_encode($_SESSION["key"]).","
                        . json_encode("ConfigureHostKeysParentNode").","
                        . json_encode("ConfigureHostKeysChildNode").");"
            . "</script>";
        return $string;
    }
    
    function LoadConfigureKnownHosts() {
        $string = "<script>"
                    . "ReloadElement("
                        . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureKnownHosts.php").", " // AjaxFileExecut
                        . json_encode($_SESSION["key"]).","
                        . json_encode("ConfigureKnownHostsParentNode").","
                        . json_encode("ConfigureKnownHostsChildNode").");"
            . "</script>";
        return $string;
    }
    
    function LoadConfigureUserKeys() {
        $string = "<script>"
                    . "ReloadElement("
                        . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureUserKeys.php").", " // AjaxFileExecut
                        . json_encode($_SESSION["key"]).","
                        . json_encode("ConfigureUserKeysParentNode").","
                        . json_encode("ConfigureUserKeysChildNode").");"
            . "</script>";
        return $string;
    }
    
    function HiddenConsolePanelRegenerateHostKeys() {
        $RegenerateHostKeysPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                               // $UTILITIES
            "IdConsolePanelRegenerateHostKeys", "none", "none", "",                                                           // $console_panel_id, $console_panel_display                                                                                     // $name_display, $name                                                                                                                    // 
            $this->BUTTONS->TwinButtons(                                                                    // $field_left 
                "IdButtonCancelRegenerateHostKeys", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text                                                                             // 
                "IdButtonRegenerateHostKeysNow",    "", "none", "", "", "", $this->UTILITIES->Translate("Regenerate Host Keys")),  // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text                                                                           //                          
            "root", "raspbx", "IdConsoleOutputRegenerateHostKeys", "IdButtonClearConsoleRegenerateHostKeys", "");                                                                                            // $JSfunctionclearconsolebutton                                             
        $string = $RegenerateHostKeysPanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelDeleteAuthorizedKey() {
        $DeleteAuthorizedKey = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                               // $UTILITIES
            "IdConsolePanelDeleteAuthorizedKey", "none", "none", "",                                                           // $console_panel_id, $console_panel_display                                                                                     // $name_display, $name                                                                                                                    // 
            $this->BUTTONS->TwinButtons(                                                                    // $field_left 
                "IdButtonCancelDeleteAuthorizedKey", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text                                                                             // 
                "IdButtonDeleteAuthorizedKeyNow",    "", "none", "", "", "", $this->UTILITIES->Translate("Delete Authorized Key")),  // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text                                                                           //                          
            "root", "raspbx", "IdConsoleOutputDeleteAuthorizedKey", "IdButtonClearConsoleDeleteAuthorizedKey", "");                                                                                            // $JSfunctionclearconsolebutton                                             
        $string = $DeleteAuthorizedKey->ConsolePanel();
        return $string; 
    }
    
    function HiddenConsolePanelDeleteKnownHost() {
        $DeleteKnownHost = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                               // $UTILITIES
            "IdConsolePanelDeleteKnownHost", "none", "none", "",                                                           // $console_panel_id, $console_panel_display                                                                                     // $name_display, $name                                                                                                                    // 
            $this->BUTTONS->TwinButtons(                                                                    // $field_left 
                "IdButtonCancelDeleteKnownHost", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text                                                                             // 
                "IdButtonDeleteKnownHostNow",    "", "none", "", "", "", $this->UTILITIES->Translate("Delete Known Host")),  // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text                                                                           //                          
            "root", "raspbx", "IdConsoleOutputDeleteKnownHost", "IdButtonClearConsoleDeleteKnownHost", "");                                                                                            // $JSfunctionclearconsolebutton                                             
        $string = $DeleteKnownHost->ConsolePanel();
        return $string; 
    }
    
    function HiddenConsolePanelRemoveUserKeys() {
        $RemoveUserKeys = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                               // $UTILITIES
            "IdConsolePanelRemoveUserKeys", "none", "none", "",                                                           // $console_panel_id, $console_panel_display                                                                                     // $name_display, $name                                                                                                                    // 
            $this->BUTTONS->TwinButtons(                                                                    // $field_left 
                "IdButtonCancelRemoveUserKeys", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text                                                                             // 
                "IdButtonRemoveUserKeysNow",    "", "none", "", "", "", $this->UTILITIES->Translate("Remove User Keys")),  // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text                                                                           //                          
            "root", "raspbx", "IdConsoleOutputRemoveUserKeys", "IdButtonClearConsoleRemoveUserKeys", "");                                                                                            // $JSfunctionclearconsolebutton                                             
        $string = $RemoveUserKeys->ConsolePanel();
        return $string; 
    }
    
    function HiddenConsolePanelGenerateUserKeys($uidgid_formatted_unixuserswithoutkeys) {
        $GenerateUserKeys = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                               // $UTILITIES
            "IdConsolePanelGenerateUserKeys", "none", "none", "",                                                           // $console_panel_id, $console_panel_display                                                                                     // $name_display, $name                                                                                                                    // 
            $this->BUTTONS->Input_Dropdown(
                    "IdEntryUser", // $entry_id, 
                    $uidgid_formatted_unixuserswithoutkeys, // $entry_values, 
                    $this->UTILITIES->Translate("Raspberry Pi User"), // $entry_label, 
                    $this->UTILITIES->Translate("select"), // $select, 
                    "UpdateConsolePromptAndEnableDropdownConfirmationButton("
                    . json_encode("IdButtonGenerateUserKeysNow").", "
                    . json_encode("IdConsoleOutputGenerateUserKeys").", "
                    . json_encode("IdEntryUser").", "
                    . json_encode("raspbx").")") // $JsFunctionOnChange)
          . $this->BUTTONS->Input_Dropdown(
                  "IdEntryKeytype", // $entry_id, 
                  array("rsa -b 4096", "dsa", "ecdsa -b 521", "ed25519"), // $entry_valuse, 
                  $this->UTILITIES->Translate("Keytype"), // $entry_label, 
                  $this->UTILITIES->Translate("select"), // $select, 
                  "") // $JsFunctionOnChange)
          . $this->BUTTONS->TwinButtons(                                                                    // $field_left 
                "IdButtonCancelGenerateUserKeys", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text                                                                             // 
                "IdButtonGenerateUserKeysNow",    "", "none", "", "w3-opacity", "", $this->UTILITIES->Translate("Generate User Keys")),  // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text                                                                           //                          
            "root", "raspbx", "IdConsoleOutputGenerateUserKeys", "IdButtonClearConsoleGenerateUserKeys", "");                                                                                            // $JSfunctionclearconsolebutton                                             
        $string = $GenerateUserKeys->ConsolePanel();
        return $string;
    }
    
    
    
    
}
