<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of SSH_KNOWN_HOSTS
 *
 * @author rene
 */
class SSH_KNOWN_HOSTS {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_SSH = new UTILITIES_SSH($UTILITIES, $BUTTONS);
        $this->toroot = $UTILITIES->toroot;
    }
    
    function DisplayKnownHostsTable($w3_responsive_class) {
        $string = "<div id='KnownHostsTableParentNode'>".$this->DisplayKnownHostsTableChildNode($w3_responsive_class)."</div>";
        return $string;
    }
    
    function DisplayKnownHostsTableChildNode($w3_responsive_class) {
        $name = $this->UTILITIES->Translate(
                "SSH Known Hosts"
                . "<a class='w3-margin-left'><font size='0'>List of Romote Hosts' Public Keys whose authenticity has been accepted.</font></a>");
        $header = array("(uid/gid) ".$this->UTILITIES->Translate("Raspberry Pi User")."...", "", "", "");
        $table[] = array("", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='KnownHostsTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "")."</div>";
        return $string;
    }
    
    function DisplayKnownHostsTableChildNodeRefreshed($w3_responsive_class, $known_hosts) {
        $name = $this->UTILITIES->Translate(
                "SSH Known Hosts"
                . "<a class='w3-margin-left'><font size='0'>List of Romote Hosts' Public Keys whose authenticity has been accepted.</font></a>");
        $header = array(
            "(uid/gid) ".$this->UTILITIES->Translate("RASPserver User"),
            $this->UTILITIES->Translate("Known Remote Host"),
            $this->UTILITIES->Translate("Remote Host's Public Key SHA256 Fingerprint"),
            $this->UTILITIES->Translate("Remote Host's Public Key MD5 Fingerprint"),
            $this->UTILITIES->Translate("Keytype"),
            $this->UTILITIES->Translate("Keysize"),
            $this->UTILITIES->Translate("Remote Host's Public Key base64-encoded"));
        foreach($known_hosts as $known_host) {
            foreach($known_host['known_hosts_of_user'] as $known_hosts_of_user) {
                $table[] = array(
                    "<pre>".$known_host['uidgiduser']."</pre>",
                    "<pre>".$known_hosts_of_user['known_host']."</pre>",
                    "<pre>".$known_hosts_of_user['fingerprint1']."</pre>",
                    "<pre>".$known_hosts_of_user['fingerprint2']."</pre>",
                    "<pre>".$known_hosts_of_user['keytype2']."</pre>",
                    "<pre>".$known_hosts_of_user['keysize']."</pre>",
                    "<pre>".$known_hosts_of_user['key']."</pre>",
                    );
            }
        }
        $footer = $this->ButtonToSshSettings()." ".$this->ButtonToConfigureSsh();
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='KnownHostsTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "")."</div>";
        return $string;
    }
    
    function ButtonToConfigureSsh() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshKnownHostsConfiguration",
            $this->toroot."/pages/RASPBERRY_SshKnownHosts.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshKnownHostsConfiguration").")",
            $this->UTILITIES->Translate("Configure")                                                                 // $button_text
        );
        return $string;
    }
    
    function ButtonToSshSettings() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSshSettings3",
            $this->toroot."/pages/RASPBERRY_SshSettings.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToSshSettings3").")",
            "<i class='fa fa-arrow-left' aria-hidden='true'></i>"                                                                 // $button_text
        );
        return $string;
    }
    
    function ConfigureKnownHosts() {
        $string = "<div id='ConfigureKnownHostsParentNode'>".$this->ConfigureKnownHostsChildNode()."</div>";
        $string .= $this->UTILITIES_SSH->LoadConfigureKnownHosts();
        return $string;
    }
    
    function ConfigureKnownHostsChildNode() {
        $name = $this->UTILITIES->Translate(
                "SSH Known Hosts"
                . "<a class='w3-margin-left'><font size='0'>List of Romote Hosts' Public Keys whose authenticity has been accepted.</font></a>");
        $header = array($this->UTILITIES->Translate("Action")."...", "", "", "");
        $table[] = array("", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureKnownHostsChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "")."</div>";
        return $string;
    }
    
    function ConfigureKnownHostsChildNodeRefreshed($known_hosts) {
        $name = $this->UTILITIES->Translate(
                "SSH Known Hosts"
                . "<a class='w3-margin-left'><font size='0'>List of Romote Hosts' Public Keys whose authenticity has been accepted.</font></a>");
        $header = array(
            $this->UTILITIES->Translate("Action"),
            "(uid/gid) ".$this->UTILITIES->Translate("RASPserver User"),
            $this->UTILITIES->Translate("Known Remote Host"),
            $this->UTILITIES->Translate("Remote Host's Public Key SHA256 Fingerprint"),
            $this->UTILITIES->Translate("Remote Host's Public Key MD5 Fingerprint"),
            $this->UTILITIES->Translate("Keytype"),
            $this->UTILITIES->Translate("Keysize"),
            $this->UTILITIES->Translate("Remote Host's Public Key base64-encoded"));
        foreach($known_hosts as $known_host) {
            foreach($known_host['known_hosts_of_user'] as $known_host_of_user) {
                $table[] = array(
                    $this->RemoveKnownHostButton($known_host['user'], $known_host_of_user),
                    "<pre>".$known_host['uidgiduser']."</pre>",
                    "<pre>".$known_host_of_user['known_host']."</pre>",
                    "<pre>".$known_host_of_user['fingerprint1']."</pre>",
                    "<pre>".$known_host_of_user['fingerprint2']."</pre>",
                    "<pre>".$known_host_of_user['keytype2']."</pre>",
                    "<pre>".$known_host_of_user['keysize']."</pre>",
                    "<pre>".$known_host_of_user['key']."</pre>",
                    );
            }
        }
        $footer = $this->ButtonToSshKeysSummary().$this->HiddenConsolePanels();
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureKnownHostsChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "")."</div>";
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
    
    function RemoveKnownHostButton($user, $known_host_of_user) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonDeleteKnownHost:".$user.":".$known_host_of_user["line_number_in_known_hosts"], "inline", "", "", "",   // $button_id, $button_display, $pointer_events, $color, $class_attribute
            "DeleteKnownHost("
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/SSH_LoadConfigureKnownHosts.php").", "             // AjaxFileRefresh  
                . json_encode($_SESSION["key"]).", ".json_encode("raspbx").", "
                . json_encode("IdButtonLinkedToSshKeysSummary").", "
                . json_encode("IdConsolePanelDeleteKnownHost").", "
                . json_encode("IdButtonCancelDeleteKnownHost").", "
                . json_encode("IdButtonDeleteKnownHostNow").", "
                . json_encode("IdButtonClearConsoleDeleteKnownHost").", "
                . json_encode("IdConsoleOutputDeleteKnownHost").", "
                . json_encode("ConfigureKnownHostsParentNode").", "
                . json_encode("ConfigureKnownHostsChildNode").", "
                . json_encode($user).", "
                . json_encode($known_host_of_user["line_number_in_known_hosts"]).")",  
            $this->UTILITIES->Translate("Remove"));// $button_text);
        return "<pre>".$string."</pre>";
    }
    
    function HiddenConsolePanels() {
        $string = $this->UTILITIES_SSH->HiddenConsolePanelDeleteKnownHost();
        return $string;
    }
    
}
