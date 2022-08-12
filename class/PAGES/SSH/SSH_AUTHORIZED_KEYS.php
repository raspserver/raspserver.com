<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of SSH_AUTHORIZED_KEYS
 *
 * @author rene
 */
class SSH_AUTHORIZED_KEYS {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_SSH = new UTILITIES_SSH($UTILITIES, $BUTTONS);
        $this->toroot = $UTILITIES->toroot;
    }
    
    function DisplayAuthorizedKeysTable($w3_responsive_class) {
        $string = "<div id='AuthorizedKeysTableParentNode'>".$this->DisplayAuthorizedKeysTableChildNode($w3_responsive_class)."</div>";
        return $string;
    }
    
    function DisplayAuthorizedKeysTableChildNode($w3_responsive_class) {
        $name = $this->UTILITIES->Translate(
                "SSH Authorized Keys"
                . "<a class='w3-margin-left'><font size='0'>Authorized clients' public keys grant remote users access to your Raspberry Pi and are configured separately for each Raspberry Pi user.</font></a>");
        $header = array("(uid/gid) ".$this->UTILITIES->Translate("Raspberry Pi User")." ...", "", "", "");
        $table[] = array("", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='AuthorizedKeysTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "w3-right-align")."</div>";
        return $string;
    }
    
    function DisplayAuthorizedKeysTableChildNodeRefreshed($w3_responsive_class, $authorized_keys) {
        $name = $this->UTILITIES->Translate(
                "SSH Authorized Keys"
                . "<a class='w3-margin-left'><font size='0'>Authorized clients' public keys grant remote users access to your Raspberry Pi and are configured separately for each Raspberry Pi user.</font></a>");
        $header = array(
            "(uid/gid) ".$this->UTILITIES->Translate("RASPserver User"),
            $this->UTILITIES->Translate("Client's Public Key SHA256 Fingerprint"),
            $this->UTILITIES->Translate("Client's Public Key MD5 Fingerprint"),
            $this->UTILITIES->Translate("Keytype"),
            $this->UTILITIES->Translate("Keysize"),
            $this->UTILITIES->Translate("Client's Public Key base64-encoded"),
            $this->UTILITIES->Translate("Client's comment"));
        foreach($authorized_keys as $authorized_key) {
            foreach($authorized_key['authorized_keys_of_user'] as $authorized_keys_of_user) {
                $table[] = array(
                    "<pre>".$authorized_key['uidgiduser']."</pre>",
                    "<pre>".$authorized_keys_of_user['fingerprint1']."</pre>",
                    "<pre>".$authorized_keys_of_user['fingerprint2']."</pre>",
                    "<pre>".$authorized_keys_of_user['keytype2']."</pre>",
                    "<pre>".$authorized_keys_of_user['keysize']."</pre>",
                    "<pre>".$authorized_keys_of_user['key']."</pre>",
                    "<pre>".$authorized_keys_of_user['comment']."</pre>"
                    );
            }
        }
        $footer = $this->ButtonToSshSettings()." ".$this->ButtonToConfigureSsh();
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='AuthorizedKeysTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "w3-right-align")."</div>";
        return $string;
    }
    
    function ButtonToConfigureSsh() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshAuthorizedKeysConfiguration",
            $this->toroot."/pages/RASPBERRY_SshAuthorizedKeys.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshAuthorizedKeysConfiguration").")",
            $this->UTILITIES->Translate("Configure")                                                                 // $button_text
        );
        return $string;
    }
    
    function ButtonToSshSettings() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshSettings1",
            $this->toroot."/pages/RASPBERRY_SshSettings.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshSettings1").")",
            "<i class='fa fa-arrow-left' aria-hidden='true'></i>"                                                                 // $button_text
        );
        return $string;
    }
    
    function ConfigureAuthorizedKeys() {
        $string = "<div id='ConfigureAuthorizedKeysParentNode'>".$this->ConfigureAuthorizedKeysChildNode()."</div>";
        $string .= $this->UTILITIES_SSH->LoadConfigureAuthorizedKeys();
        return $string;
    }
    
    function ConfigureAuthorizedKeysChildNode() {
        $name = $this->UTILITIES->Translate(
                "SSH Authorized Keys"
                . "<a class='w3-margin-left'><font size='0'>Authorized clients' public keys grant remote users access to your Raspberry Pi and are configured separately for each Raspberry Pi user.</font></a>");
        $header = array($this->UTILITIES->Translate("Action")." ...", "", "", "");
        $table[] = array("", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureAuthorizedKeysChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "w3-right-align")."</div>";
        return $string;
    }
    
    function ConfigureAuthorizedKeysChildNodeRefreshed($authorized_keys) {
        $name = $this->UTILITIES->Translate(
                "SSH Authorized Keys"
                . "<a class='w3-margin-left'><font size='0'>Authorized clients' public keys grant remote users access to your Raspberry Pi and are configured separately for each Raspberry Pi user.</font></a>");
        $header = array(
            $this->UTILITIES->Translate("Action"),
            "(uid/gid) ".$this->UTILITIES->Translate("RASPserver User"),
            $this->UTILITIES->Translate("Client's Public Key SHA256 Fingerprint"),
            $this->UTILITIES->Translate("Client's Public Key MD5 Fingerprint"),
            $this->UTILITIES->Translate("Keytype"),
            $this->UTILITIES->Translate("Keysize"),
            $this->UTILITIES->Translate("Client's Public Key base64-encoded"),
            $this->UTILITIES->Translate("Client's comment"));
        foreach($authorized_keys as $authorized_key) {
            foreach($authorized_key['authorized_keys_of_user'] as $authorized_key_of_user) {
                $table[] = array(
                    $this->RemoveAuthorizedKeyButton($authorized_key['user'], $authorized_key_of_user),
                    "<pre>".$authorized_key['uidgiduser']."</pre>",
                    "<pre>".$authorized_key_of_user['fingerprint1']."</pre>",
                    "<pre>".$authorized_key_of_user['fingerprint2']."</pre>",
                    "<pre>".$authorized_key_of_user['keytype2']."</pre>",
                    "<pre>".$authorized_key_of_user['keysize']."</pre>",
                    "<pre>".$authorized_key_of_user['key']."</pre>",
                    "<pre>".$authorized_key_of_user['comment']."</pre>"
                    );
            }
        }
        $footer = $this->ButtonToSshKeysSummary().$this->HiddenConsolePanels();
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureAuthorizedKeysChildNode'>".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "w3-right-align")."</div>";
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
    
    function RemoveAuthorizedKeyButton($user, $authorized_key_of_user) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonDeleteAuthorizedKey:".$user.":".$authorized_key_of_user["line_number_in_authorized_keys"], "inline", "", "", "",   // $button_id, $button_display, $pointer_events, $color, $class_attribute
            "DeleteAuthorizedKey("
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureAuthorizedKeys.php").", "             // AjaxFileRefresh  
                . json_encode($_SESSION["key"]).", ".json_encode("raspbx").", "
                . json_encode("IdButtonLinkedToSshKeysSummary").", "
                . json_encode("IdConsolePanelDeleteAuthorizedKey").", "
                . json_encode("IdButtonCancelDeleteAuthorizedKey").", "
                . json_encode("IdButtonDeleteAuthorizedKeyNow").", "
                . json_encode("IdButtonClearConsoleDeleteAuthorizedKey").", "
                . json_encode("IdConsoleOutputDeleteAuthorizedKey").", "
                . json_encode("ConfigureAuthorizedKeysParentNode").", "
                . json_encode("ConfigureAuthorizedKeysChildNode").", "
                . json_encode($user).", "
                . json_encode($authorized_key_of_user["line_number_in_authorized_keys"]).")",  
            $this->UTILITIES->Translate("Remove"));// $button_text);
        return "<pre>".$string."</pre>";
    }
    
    function HiddenConsolePanels() {
        $string = $this->UTILITIES_SSH->HiddenConsolePanelDeleteAuthorizedKey();
        return $string;
    }
    
}
