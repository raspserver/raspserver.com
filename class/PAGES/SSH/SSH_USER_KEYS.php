<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of SSH_KEY_PAIRS
 *
 * @author rene
 */
class SSH_USER_KEYS {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_SSH = new UTILITIES_SSH($UTILITIES, $BUTTONS);
        $this->toroot = $UTILITIES->toroot;
    }
    
    function DisplayUserKeysTable($w3_responsive_class) {
        $string = "<div id='UserKeysTableParentNode'>".$this->DisplayUserKeysTableChildNode($w3_responsive_class)."</div>";
        $string .= $this->UTILITIES_SSH->LoadKeysTable();
        return $string;
    }
    
    function DisplayUserKeysTableChildNode($w3_responsive_class) {
        $name = $this->UTILITIES->Translate(
                "SSH User Keys (user authentication)"
                . "<a class='w3-margin-left'><font size='0'>Public and Private Key Pairs are Identity Keys that authenticate a user when logging into an SSH server</font></a>");
        $header = array("(uid/gid) ".$this->UTILITIES->Translate("Raspberry Pi User")."...", "", "", "");
        $table[] = array("", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='UserKeysTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "")."</div>";
        return $string;
    }
    
    function DisplayUserKeysTableChildNodeRefreshed($w3_responsive_class, $user_keys) {
        $name = $this->UTILITIES->Translate(
                "SSH User Keys (user authentication)"
                . "<a class='w3-margin-left'><font size='0'>Public and Private Key Pairs are Identity Keys that authenticate a user when logging into an SSH server</font></a>");
        $header = array(
            "(uid/gid) ".$this->UTILITIES->Translate("RASPserver User"),
            $this->UTILITIES->Translate("Created"),
            $this->UTILITIES->Translate("SHA256 Fingerprint"),
            $this->UTILITIES->Translate("MD5 Fingerprint"),
            $this->UTILITIES->Translate("Path"),
            $this->UTILITIES->Translate("Keytype"),
            $this->UTILITIES->Translate("Keysize"),
            $this->UTILITIES->Translate("Base64-encoded Public Key"),
            $this->UTILITIES->Translate("Comment"));
        foreach($user_keys as $user_key) { $table[] = array(
                "<pre>".$user_key['uidgiduser']."</pre>", "<pre>".$user_key['date']."</pre>", "<pre>".$user_key['fingerprint1']."</pre>", "<pre>".$user_key['fingerprint2']."</pre>",
                "<pre>".$user_key['path']."</pre>", "<pre>".$user_key['keytype2']."</pre>", "<pre>".$user_key['keysize']."</pre>",
                "<pre>".$user_key['key']."</pre>", "<pre>".$user_key['comment']."</pre>");
        }
        $footer = $this->ButtonToSshSettings()." ".$this->ButtonToConfigureSsh();
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='UserKeysTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "")."</div>";
        return $string;
    }
    
    function ButtonToConfigureSsh() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshUserKeysConfiguration",
            $this->toroot."/pages/RASPBERRY_SshUserKeys.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshUserKeysConfiguration").")",
            $this->UTILITIES->Translate("Configure")                                                                 // $button_text
        );
        return $string;
    }
    
    function ButtonToSshSettings() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshSettings2",
            $this->toroot."/pages/RASPBERRY_SshSettings.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshSettings2").")",
            "<i class='fa fa-arrow-left' aria-hidden='true'></i>"                                                                 // $button_text
        );
        return $string;
    }
    
    function ConfigureUserKeys() {
        $string = "<div id='ConfigureUserKeysParentNode'>".$this->ConfigureUserKeysChildNode()."</div>";
        $string .= $this->UTILITIES_SSH->LoadConfigureUserKeys();
        return $string;
    }
    
    function ConfigureUserKeysChildNode() {
        $name = $this->UTILITIES->Translate(
                "SSH User Keys (user authentication)"
                . "<a class='w3-margin-left'><font size='0'>Public and Private Key Pairs are Identity Keys that authenticate a user when logging into an SSH server</font></a>");
        $header = array($this->UTILITIES->Translate("Action")."...", "", "", "");
        $table[] = array("", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureUserKeysChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "")."</div>";
        return $string;
    }
    
    function ConfigureUserKeysChildNodeRefreshed($user_keys, $root_has_a_ssh_folder) {
        $unixusers = $this->UTILITIES->RASPBERRY->GetUnixUsers();
        $uidgid_formatted_unixusers = $this->UTILITIES->ProvideUidGidFormattedUserList($unixusers);
        $uidgid_formatted_unixuserswithoutkeys = $this->UTILITIES->RemoveUsersFromFormattedList($uidgid_formatted_unixusers, $user_keys);
        $number_unixuserswithoutkeys = count($uidgid_formatted_unixuserswithoutkeys);
        $name = $this->UTILITIES->Translate(
                "SSH User Keys (user authentication)"
                . "<a class='w3-margin-left'><font size='0'>Public and Private Key Pairs are Identity Keys that authenticate a user when logging into an SSH server</font></a>");
        $header = array(
            $this->UTILITIES->Translate("Action"),
            "(uid/gid) ".$this->UTILITIES->Translate("RASPserver User"),
            $this->UTILITIES->Translate("Created"),
            $this->UTILITIES->Translate("RPi User's Public Key SHA256 Fingerprint"),
            $this->UTILITIES->Translate("RPi User's Public Key MD5 Fingerprint"),
            $this->UTILITIES->Translate("Path"),
            $this->UTILITIES->Translate("Keytype"),
            $this->UTILITIES->Translate("Keysize"),
            $this->UTILITIES->Translate("Raspberry Pi User's Base64-encoded Public Key"),
            $this->UTILITIES->Translate("Comment"));
        foreach($user_keys as $user_key) { $table[] = array(
                "<pre>".$this->RemoveUserKeysButton($user_key, $number_unixuserswithoutkeys)."</pre>","<pre>".$user_key['uidgiduser']."</pre>", "<pre>".$user_key['date']."</pre>", "<pre>".$user_key['fingerprint1']."</pre>", "<pre>".$user_key['fingerprint2']."</pre>",
                "<pre>".$user_key['path']."</pre>", "<pre>".$user_key['keytype2']."</pre>", "<pre>".$user_key['keysize']."</pre>",
                "<pre>".$user_key['key']."</pre>", "<pre>".$user_key['comment']."</pre>");
        }
        if($number_unixuserswithoutkeys === 0) {
            $footer = $this->ButtonToSshKeysSummary()."<br><br>".$this->UTILITIES->Translate("All users have keys.").$this->HiddenConsolePanels($uidgid_formatted_unixuserswithoutkeys);
        } else {
            $footer = $this->ButtonToSshKeysSummary()." ".$this->GenerateUserKeysButton($root_has_a_ssh_folder).$this->HiddenConsolePanels($uidgid_formatted_unixuserswithoutkeys);
        }
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureUserKeysChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "")."</div>";
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
    
    function RemoveUserKeysButton($user_key ,$number_unixuserswithoutkeys) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonRemoveUserKeys:".$user_key['user'].":".$user_key['path'], "inline", "", "", "",   // $button_id, $button_display, $pointer_events, $color, $class_attribute
            "RemoveUserKeys("
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureUserKeys.php").", "             // AjaxFileRefresh  
                . json_encode($_SESSION["key"]).", ".json_encode("raspbx").", "  
                . json_encode("IdButtonLinkedToSshKeysSummary").", "
                . json_encode("IdButtonGenerateUserKeys").", "
                . json_encode("IdConsolePanelRemoveUserKeys").", "
                . json_encode("IdButtonCancelRemoveUserKeys").", "
                . json_encode("IdButtonRemoveUserKeysNow").", "
                . json_encode("IdButtonClearConsoleRemoveUserKeys").", "
                . json_encode("IdConsoleOutputRemoveUserKeys").", "
                . json_encode("ConfigureUserKeysParentNode").", "
                . json_encode("ConfigureUserKeysChildNode").", "
                . json_encode($user_key["user"]).", "
                . json_encode($user_key['path']).", "
                . json_encode($number_unixuserswithoutkeys).")",  
            $this->UTILITIES->Translate("Remove"));// $button_text);
        return "<pre>".$string."</pre>";
    }
    
    function GenerateUserKeysButton($root_has_a_ssh_folder) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonGenerateUserKeys", "", "", "", "",   // $button_id, $button_display, $pointer_events, $color, $class_attribute
            "GenerateUserKeys("
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureUserKeys.php").", "             // AjaxFileRefresh  
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "  
                . json_encode("IdButtonLinkedToSshKeysSummary").", "
                . json_encode("IdButtonGenerateUserKeys").", "
                . json_encode("IdConsolePanelGenerateUserKeys").", "
                . json_encode("IdButtonCancelGenerateUserKeys").", "
                . json_encode("IdButtonGenerateUserKeysNow").", "
                . json_encode("IdButtonClearConsoleGenerateUserKeys").", "
                . json_encode("IdConsoleOutputGenerateUserKeys").", "
                . json_encode("ConfigureUserKeysParentNode").", "
                . json_encode("ConfigureUserKeysChildNode").","
                . json_encode("IdEntryUser").","
                . json_encode("IdEntryKeytype").","
                . json_encode($this->UTILITIES->Translate("select")).","
                . json_encode($root_has_a_ssh_folder).")",  
            $this->UTILITIES->Translate("Generate User Keys"));// $button_text);
        return $string;
    }
    
    function HiddenConsolePanels($uidgid_formatted_unixuserswithoutkeys) {
        $string = $this->UTILITIES_SSH->HiddenConsolePanelRemoveUserKeys();
        $string .= $this->UTILITIES_SSH->HiddenConsolePanelGenerateUserKeys($uidgid_formatted_unixuserswithoutkeys);
        return $string;
    }
    
}
