<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of SSH
 *
 * @author rene
 */
class SSH_HOST_KEYS {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_SSH = new UTILITIES_SSH($UTILITIES, $BUTTONS);
        $this->toroot = $UTILITIES->toroot;
    }
    
    function DisplayHostKeysTable($w3_responsive_class) {
        $string = "<div id='HostKeysTableParentNode'>".$this->DisplayHostKeysTableChildNode($w3_responsive_class)."</div>";
        return $string;
    }
    
    function DisplayHostKeysTableChildNode($w3_responsive_class) {
        $name = $this->UTILITIES->Translate(
                "SSH Host Keys (host authentication)"
                . "<a class='w3-margin-left'><font size='0'>Host keys authenticate your Raspberry Pi to clients</font></a>");
        $header = array($this->UTILITIES->Translate("Created")."...", "", "", "");
        $table[] = array("", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='HostKeysTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "")."</div>";
        return $string;
    }
    
    function DisplayHostKeysTableChildNodeRefreshed($w3_responsive_class, $host_keys) {
        $name = $this->UTILITIES->Translate(
                "SSH Host Keys (host authentication)"
                . "<a class='w3-margin-left'><font size='0'>Host keys authenticate your Raspberry Pi to clients</font></a>");
        $header = array(
            $this->UTILITIES->Translate("Created"),
            $this->UTILITIES->Translate("Raspberry Pi's SHA256 Public Key Fingerprint"),
            $this->UTILITIES->Translate("Raspberry Pi's MD5 Public Key Fingerprint"),
            $this->UTILITIES->Translate("Path"),
            $this->UTILITIES->Translate("Keytype"),
            $this->UTILITIES->Translate("Keysize"),
            $this->UTILITIES->Translate("Raspberry Pi's Base64-encoded Public Key"),
            $this->UTILITIES->Translate("Comment"));
        foreach($host_keys as $host_key) {
            $table[] = array(
                "<pre>".$host_key['date']."</pre>", "<pre>".$host_key['fingerprint1']."</pre>", "<pre>".$host_key['fingerprint2']."</pre>",
                "<pre>".$host_key['path']."</pre>", "<pre>".$host_key['keytype2']."</pre>", "<pre>".$host_key['keysize']."</pre>",
                "<pre>".$host_key['key']."</pre>", "<pre>".$host_key['comment']."</pre>");
        }
        $footer = $this->ButtonToSshSettings()." ".$this->ButtonToConfigureSsh();
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='HostKeysTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "")."</div>";
        return $string;
    }
    
    function ButtonToConfigureSsh() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshHostKeysConfiguration",
            $this->toroot."/pages/RASPBERRY_SshHostKeys.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshHostKeysConfiguration").")",
            $this->UTILITIES->Translate("Configure")                                                                 // $button_text
        );
        return $string;
    }
    
    function ButtonToSshSettings() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshSettings4",
            $this->toroot."/pages/RASPBERRY_SshSettings.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshSettings4").")",
            "<i class='fa fa-arrow-left' aria-hidden='true'></i>"                                                                 // $button_text
        );
        return $string;
    }
    
    function ConfigureHostKeys() {
        $string = "<div id='ConfigureHostKeysParentNode'>".$this->ConfigureHostKeysChildNode()."</div>";
        $string .= $this->UTILITIES_SSH->LoadConfigureHostKeys();
        return $string;
    }
    
    function ConfigureHostKeysChildNode() {
        $name = $this->UTILITIES->Translate(
                "SSH Host Keys (host authentication)"
                . "<a class='w3-margin-left'><font size='0'>Host keys authenticate your Raspberry Pi to clients</font></a>");
        $header = array($this->UTILITIES->Translate("Created")."...", "", "", "");
        $table[] = array("", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureHostKeysChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "")."</div>";
        return $string;
    }
    
    function ConfigureHostKeysChildNodeRefreshed($host_keys) {
        $name = $this->UTILITIES->Translate(
                "SSH Host Keys (host authentication)"
                . "<a class='w3-margin-left'><font size='0'>Host keys authenticate your Raspberry Pi to clients</font></a>");
        $header = array(
            $this->UTILITIES->Translate("Created"),
            $this->UTILITIES->Translate("Raspberry Pi's SHA256 Public Key Fingerprint"),
            $this->UTILITIES->Translate("Raspberry Pi's MD5 Public Key Fingerprint"),
            $this->UTILITIES->Translate("Path"),
            $this->UTILITIES->Translate("Keytype"),
            $this->UTILITIES->Translate("Keysize"),
            $this->UTILITIES->Translate("Raspberry Pi's Base64-encoded Public Key"),
            $this->UTILITIES->Translate("Comment"));
        foreach($host_keys as $host_key) {
            $table[] = array(
                "<pre>".$host_key['date']."</pre>", "<pre>".$host_key['fingerprint1']."</pre>", "<pre>".$host_key['fingerprint2']."</pre>",
                "<pre>".$host_key['path']."</pre>", "<pre>".$host_key['keytype2']."</pre>", "<pre>".$host_key['keysize']."</pre>",
                "<pre>".$host_key['key']."</pre>", "<pre>".$host_key['comment']."</pre>");
        }
        $footer = $this->ButtonToSshKeysSummary()." ".$this->RegenerateHostKeysButton().$this->HiddenConsolePanels();
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureHostKeysChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "")."</div>";
        return $string;
    }
    
    function ButtonToSshKeysSummary() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshKeysSummary",
            $this->toroot."/pages/RASPBERRY_SshKeys.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshKeysSummary").")",
            "<i class='fa fa-arrow-left' aria-hidden='true'></i>"                                                                 // $button_text
        );
        return $string;
    }
    
    function RegenerateHostKeysButton() {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonRegenerateHostKeys", // $button_id
            "", // $button_display
            "", // $pointer_events
            "", // $color
            "", // $class_attribute,           // w3-animate-opacity
            "RegenerateHostKeys("
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureHostKeys.php").", "             // AjaxFileRefresh  
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "  
                . json_encode("IdButtonLinkedToSshKeysSummary").", "
                . json_encode("IdButtonRegenerateHostKeys").", "
                . json_encode("IdConsolePanelRegenerateHostKeys").", "
                . json_encode("IdButtonCancelRegenerateHostKeys").", "
                . json_encode("IdButtonRegenerateHostKeysNow").", "
                . json_encode("IdButtonClearConsoleRegenerateHostKeys").", "
                . json_encode("IdConsoleOutputRegenerateHostKeys").", "
                . json_encode("ConfigureHostKeysParentNode").", "
                . json_encode("ConfigureHostKeysChildNode").")", 
            $this->UTILITIES->Translate("Regenerate Host Keys"));// $button_text);
        return $string;
    }
    
    function HiddenConsolePanels() {
        $string = $this->UTILITIES_SSH->HiddenConsolePanelRegenerateHostKeys();
        return $string;
    }
    
    //put your code here
}