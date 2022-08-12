<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_SAMBA
 *
 * @author rene
 */
class UTILITIES_SAMBA {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
    }
    
    function HiddenConsolePanelAddSambaShare() {
        $AddSambaSharePanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelAddSambaShare", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                       // $name_display, $name  
            $this->BUTTONS->Input(                                                                              // $field_left 
                "IdEntryNewShare", "text", $this->UTILITIES->Translate("Samba Share")." (//".$this->UTILITIES->RASPBERRY->GetNetworkInterfaces()[0]["ip_address"]."/...)")        // $entry1_id, $entry1_type, $entry1_label
          . $this->BUTTONS->Input(                                                                             // $field_left continuation  
                "IdEntryNewPath", "text", $this->UTILITIES->Translate("Path (/home/... ".$this->UTILITIES->Translate("or")." /media/...)"))        // $entry1_id, $entry1_type, $entry1_label
          . "<p class='w3-margin-left'>".$this->UTILITIES->Translate("writable = ")
          . $this->CreateWritableButtons("1")."</p>"  // $entry1_id, $entry1_type, $entry1_label
          . "<p class='w3-margin-left'>".$this->UTILITIES->Translate("valid users = ")
          . $this->CreateValidUsersButtons("1")."</p>" // $entry1_id, $entry1_type, $entry1_label
          . $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelAddSambaShare", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonAddSambaShareNow",    "", "none", "", "w3-opacity", "", $this->UTILITIES->Translate("Add Samba Share")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputAddSambaShare",                                                                    // $idconsole
            "IdButtonClearConsoleAddSambaShare",                                                                // $idclearconsolebutton  
            "");                                                                                          // $JSfunctionclearconsolebutton                                                                     
        $string = $AddSambaSharePanel->ConsolePanel();
        return $string;
    }
    
    function CreateWritableButtons($prefix) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
                    "WritableNo".$prefix, // $button_id,
                    $button_display,
                    $pointer_events,
                    $color,
                    $class_attribute,           // w3-animate-opacity
                    $button_jsfunction_onclick,
                    "no")." "
                .$this->BUTTONS->ButtonJsFunctionOnClick(
                    "WritableYes".$prefix, // $button_id,
                    $button_display,
                    "", // $pointer_events,
                    $color,
                    "", // $class_attribute,           // w3-animate-opacity
                    $button_jsfunction_onclick,
                    "yes");
        return $string;
    }
    
    function CreateValidUsersButtons($prefix) {
        $unixusers = $this->UTILITIES->RASPBERRY->GetUnixUsers();
        foreach($unixusers as $unixuser) {
            if($unixuser["name"] !== "root") {
                $string .= " ".$this->BUTTONS->ButtonJsFunctionOnClick(
                    "ValidUser".$prefix.":".$unixuser["name"], //$button_id,
                    $button_display,
                    $pointer_events,
                    $color,
                    $class_attribute,           // w3-animate-opacity
                    $button_jsfunction_onclick,
                    $unixuser["name"]); // $button_text);
            }
        }
        return $string;
    }
    
    function HiddenConsolePanelRemoveSambaShare() {
        $AddSambaSharePanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelRemoveSambaShare", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                       // $name_display, $name  
            $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelRemoveSambaShare", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonRemoveSambaShareNow",    "", "", "", "", "", $this->UTILITIES->Translate("Remove Samba Share")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputRemoveSambaShare",                                                                    // $idconsole
            "IdButtonClearConsoleRemoveSambaShare",                                                                // $idclearconsolebutton  
            "");                                                                                                // $JSfunctionclearconsolebutton                                                                     
        $string = $AddSambaSharePanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelConfigureSambaShare() {
        $AddSambaSharePanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelConfigureSambaShare", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                       // $name_display, $name  
            "<p class='w3-margin-left'>".$this->UTILITIES->Translate("writable = ")
          . $this->CreateWritableButtons("2")."</p>"  // $entry1_id, $entry1_type, $entry1_label
          . "<p class='w3-margin-left'>".$this->UTILITIES->Translate("valid users = ")
          . $this->CreateValidUsersButtons("2")."</p>" // $entry1_id, $entry1_type, $entry1_label
          . $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelConfigureSambaShare", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonConfigureSambaShareNow",    "", "none", "", "w3-opacity", "", $this->UTILITIES->Translate("Submit")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputConfigureSambaShare",                                                                    // $idconsole
            "IdButtonClearConsoleConfigureSambaShare",                                                                // $idclearconsolebutton  
            "");                                                                                                // $JSfunctionclearconsolebutton                                                                     
        $string = $AddSambaSharePanel->ConsolePanel();
        return $string;
    }
    
    function CreateCommandDeleteSambaShare($share, $samba_shares) {
        foreach($samba_shares as $samba_share) {
            if($samba_share["share"] === $share) {
                $line_begin = $samba_share["line_begin"];
                $line_end = $samba_share["line_end"];
            }
        }
        $line_end = $this->UTILITIES->RASPBERRY->CountUpSambaLines($line_end);
        $string1 = "sed -i -e \\'".$line_begin.",".$line_end."d\\' /etc/samba/smb.conf";
        $string2 = "sed -i -e '".$line_begin.",".$line_end."d' /etc/samba/smb.conf";
        $string3 = "service smbd restart";
        $string4 = "service smbd restart";
        $string = $string1.":".$string2."°".$string3.":".$string4;
        return $string;
    }
    
    function CreateCommandAddSambaShare($share, $path, $writable, $selected_valid_users_string) {
        $string1 = "sed -i "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\[".$share."\\]\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ path = ".$path."\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ valid users = ".$selected_valid_users_string."\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ writable = ".$writable."\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ browsable = yes\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ guest ok = no\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\r\\' "
                . "/etc/samba/smb.conf";
        $string2 = "sed -i "
                . "-e '/\\[printers\\]/'i'\[".$share."\]' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ path = ".$path."' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ valid users = ".$selected_valid_users_string."' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ writable = ".$writable."' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ browsable = yes' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ guest ok = no' "
                . "-e '/\\[printers\\]/'i'r' "
                . "/etc/samba/smb.conf";
        $string3 = "service smbd restart";
        $string4 = "service smbd restart";
        $string = $string1.":".$string2."°".$string3.":".$string4;
        return $string;
    }
    
    function CreateCommandsDeleteAndAddNewSambaShare($share, $path, $writable, $selected_valid_users_string, $samba_shares) {
        foreach($samba_shares as $samba_share) {
            if($samba_share["share"] === $share) {
                $line_begin = $samba_share["line_begin"];
                $line_end = $samba_share["line_end"];
            }
        }
        $line_end = $this->UTILITIES->RASPBERRY->CountUpSambaLines($line_end);
        $string1 = "sed -i -e \\'".$line_begin.",".$line_end."d\\' /etc/samba/smb.conf";
        $string2 = "sed -i -e '".$line_begin.",".$line_end."d' /etc/samba/smb.conf";
        $string3 = "sed -i "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\[".$share."\\]\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ path = ".$path."\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ valid users = ".$selected_valid_users_string."\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ writable = ".$writable."\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ browsable = yes\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\\\ \\\\ \\\\ guest ok = no\\' "
                . "-e \\'/\\\\[printers\\\\]/\\'i\\'\\r\\' "
                . "/etc/samba/smb.conf";
        $string4 = "sed -i "
                . "-e '/\\[printers\\]/'i'\[".$share."\]' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ path = ".$path."' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ valid users = ".$selected_valid_users_string."' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ writable = ".$writable."' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ browsable = yes' "
                . "-e '/\\[printers\\]/'i'\\ \\ \\ guest ok = no' "
                . "-e '/\\[printers\\]/'i'r' "
                . "/etc/samba/smb.conf";
        $string5 = "service smbd restart";
        $string6 = "service smbd restart";
        $string = $string1.":".$string2."°".$string3.":".$string4."°".$string5.":".$string6;
        return $string;
    }
    
}

//{command:"sed -i -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\[raspbx_home_" + new_username + "\]\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ path = /home/" + new_username + "\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ valid users = " + new_username + "\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ writable = yes\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ browsable = yes\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ guest ok = no\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\r\\\'  /etc/samba/smb2.conf",
//              masked:"sed -i -e '/\\[printers\\]/'i'\[raspbx_home_" + new_username + "\]' -e '/\\[printers\\]/'i'\\ \\ \\ path = /home/" + new_username + "' -e '/\\[printers\\]/'i'\\ \\ \\ valid users = " + new_username + "' -e '/\\[printers\\]/'i'\\ \\ \\ writable = yes' -e '/\\[printers\\]/'i'\\ \\ \\ browsable = yes' -e '/\\[printers\\]/'i'\\ \\ \\ guest ok = no' -e '/\\[printers\\]/'i'\\\\r'  /etc/samba/smb2.conf"},