<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of SETTINGS_SSH
 *
 * @author rene
 */
class SSH_SETTINGS {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->toroot = $UTILITIES->toroot;
    }
    
    function DisplaySettingsSsh($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("SSH Settings");
        $header = array($this->UTILITIES->Translate("Setting"), $this->UTILITIES->Translate("Value"));
        $table = $this->DisplaySettingsSshTable();
        $footer = $this->ButtonToSettingsSshConfigurationTable();
        $TABLEOFSETTINGS = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFSETTINGS->DisplayTableOnCard($w3_responsive_class, "w3-right-align");
        return $string;
    }
    
    function DisplaySettingsSshTable() {
        $settings_ssh = $this->UTILITIES->RASPBERRY->GetSettingsSsh();
        foreach($settings_ssh as $setting_ssh) {
            $table[] = array($setting_ssh["setting"], $setting_ssh["value"]);
        } 
        return $table;
    }
    
    function ButtonToSettingsSshConfigurationTable() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshSettings",
            $this->toroot."/pages/RASPBERRY_SshSettings.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshSettings").")",
            $this->UTILITIES->Translate("Configure")                                                                 // $button_text
        );
        return $string;
    }
    
    function ConfigureSettingsSsh() {
        $string = "<div id='ConfigureSettingsSshParentNode'>".$this->ConfigureSettingsSshChildNode()."</div>";
        return $string;
    }
    
    function ConfigureSettingsSshChildNode() {
        $name = $this->UTILITIES->Translate("SSH Settings");
        $header = array("<div class='w3-display-container'>".$this->UTILITIES->Translate("Setting")."<span class='w3-display-right w3-margin-right'>".$this->UTILITIES->Translate("Value")."</span></div>");
        $table = $this->ConfigureSettingsSshTable();
        $footer = $this->ButtonToDashboard()." ".$this->ButtonToSshKeys().$this->HiddenConsolePanel();
        $CONFIGURESETTINGSTABLE = new TABLE($name, $header, $table, $footer);
        $string = $CONFIGURESETTINGSTABLE->DisplayTableOnCard("w3-row", "");
        return "<div id='ConfigureSettingsSshChildNode' >".$string."</div>";
    }
    
    function ButtonToSshKeys() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshKeys",
            $this->toroot."/pages/RASPBERRY_SshKeys.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshKeys").")",
            $this->UTILITIES->Translate("Manage SSH Keys")                                                                 // $button_text
        );
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
    
    function ConfigureSettingsSshTable() {
        $settings_ssh = $this->UTILITIES->RASPBERRY->GetSettingsSsh();
        foreach($settings_ssh as $setting_ssh) {
            $table[] = array("<div class='w3-display-container'>".$this->OpenSettingSshConsoleButton($setting_ssh)."<span class='w3-display-right w3-margin-right'>".$setting_ssh["value"]."</span></div>");
        }
        return $table;
    }
    
    function OpenSettingSshConsoleButton($setting) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
                "IdButtonOpenSettingSshConsole:".$setting["setting"], // $button_id,
                "inline", // $button_display,
                "", // $pointer_events,
                "", // $color,
                "", // $class_attribute,           // w3-animate-opacity
                "OpenConfigureSshSettingPanel("
                    . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "   
                    . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureSshSettingsTable.php").", "
                    . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "  
                    . json_encode("IdButtonOpenSettingSshConsole:".$setting["setting"]).", "
                    . json_encode("IdButtonLinkedToDashboard").", "
                    . json_encode("IdButtonLinkedToSshKeys").", "
                    . json_encode("IdSettingSshPanel").", "
                    . json_encode("IdButtonConfigureSshSettingSetting1").", "
                    . json_encode("IdButtonConfigureSshSettingSetting2").", "
                    . json_encode("IdButtonConfigureSshSettingSetting3").", "
                    . json_encode("IdButtonConfigureSshSettingSetting4").", "
                    . json_encode($setting["setting1"]).", "
                    . json_encode($setting["setting2"]).", "
                    . json_encode($setting["setting3"]).", "
                    . json_encode($setting["setting4"]).", "
                    . json_encode($setting["value"]).", "
                    . json_encode("IdButtonClearConsoleConfigureSshSetting").", "
                    . json_encode("IdConsoleOutputConfigureSshSetting").", "
                    . json_encode(ConfigureSettingsSshParentNode).", "
                    . json_encode(ConfigureSettingsSshChildNode).", "
                    . json_encode($setting["file"]).", "
                    . json_encode($setting["setting"]).","
                . json_encode("idnote").")", // $button_jsfunction_onclick,
                $setting["setting"] // $button_text
            );
        return $string;
    }
    
    function HiddenConsolePanel() {
        $ConfigureSettingPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                               // $UTILITIES
            "IdSettingSshPanel", "none", "none", "",                                                           // $console_panel_id, $console_panel_display                                                                                     // $name_display, $name                                                                                                                    // 
            $this->BUTTONS->TwinButtons(                                                                    // $field_left 
                "IdButtonConfigureSshSettingSetting1", "", "", "", "", "", $this->UTILITIES->Translate("no"),     
                "IdButtonConfigureSshSettingSetting2", "", "", "", "", "", $this->UTILITIES->Translate("yes"))
          . "<p id='idnote' class='w3-center'></p><br>"
          . $this->BUTTONS->TwinButtons(                                                                    // $field_left 
                "IdButtonConfigureSshSettingSetting3", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),     
                "IdButtonConfigureSshSettingSetting4", "", "", "", "", "", $this->UTILITIES->Translate("Confirm")),                     
            "root", "raspbx", "IdConsoleOutputConfigureSshSetting", "IdButtonClearConsoleConfigureSshSetting", "");
        $string = $ConfigureSettingPanel->ConsolePanel();
        return $string;
    
    }
    
}   
    
    
