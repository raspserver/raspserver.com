<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of AUTOSSH
 *
 * @author rene
 */
class AUTOSSH {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
    }
    
    function DisplayAutoSshPortForwardings($w3_responsive_class) {
        $string = "<div class='".$w3_responsive_class."'>".$this->DisplayAutoSshLocalPortForwardings("w3-row")
                .$this->DisplayAutoSshRemotePortForwardings("w3-row")."</div>";
        return $string;
    }
    
    function DisplayAutoSshLocalPortForwardings($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("AutoSSH Local Port Forwardings");
        $header = array($this->UTILITIES->Translate("Status"),
            $this->UTILITIES->Translate("Local User"),
            $this->UTILITIES->Translate("Command"),
            $this->UTILITIES->Translate("[SSH_SERVER PORT]"),
            $this->UTILITIES->Translate("[LOCAL_IP:]LOCAL_PORT:DESTINATION:DESTINATION_PORT"),
            $this->UTILITIES->Translate("[USER@]SSH_SERVER")
        );
        $table = $this->GetAutoSshLocalPortForwardingsTable();
        $footer = "";
        $TABLEOFLOCALPORTFORWARDINGS = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFLOCALPORTFORWARDINGS->DisplayTableOnCard($w3_responsive_class, "");
        return $string;
    }
    
    function GetAutoSshLocalPortForwardingsTable() {
        $local_port_forwardings = $this->UTILITIES->RASPBERRY->GetAutosshLocalForwardings();
        foreach($local_port_forwardings as $local_port_forwarding) {
            if($local_port_forwarding["STATUS"] === "+OK") {
                $status = "<a style='color:green;'>+OK</a>";
            } elseif($local_port_forwarding["STATUS"] === "-ERR") {
                $status = "<a style='color:red;'>-ERR</a>";
            }
            if($local_port_forwarding["SSH_SERVER_PORT"] !== "") {
                $port = "-p ".$local_port_forwarding["SSH_SERVER_PORT"];
            } else {
                $port = "";
            }
            if($local_port_forwarding["LOCAL_IP"] !== "") {
                $expression1 = $local_port_forwarding["LOCAL_IP"].":"
                                .$local_port_forwarding["LOCAL_PORT"].":"
                                .$local_port_forwarding["DESTINATION"].":"
                                .$local_port_forwarding["DESTINATION_PORT"];    
            } else {
                $expression1 = $local_port_forwarding["LOCAL_PORT"].":"
                                .$local_port_forwarding["DESTINATION"].":"
                                .$local_port_forwarding["DESTINATION_PORT"];    
            }
            if($local_port_forwarding["REMOTE_USER"] !== "") {
                $expression2 = $local_port_forwarding["REMOTE_USER"]."@"
                                .$local_port_forwarding["SSH_SERVER"];
            } else {
                $expression2 = $local_port_forwarding["SSH_SERVER"];
            }
            $table[] = [
                $status,
                $local_port_forwarding["LOCAL_USER"],
                "autossh -f -N -L ",
                $port,
                $expression1,
                $expression2
            ];
        }
        return $table;
    }
    
    function DisplayAutoSshRemotePortForwardings($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("AutoSSH Remote Port Forwardings");
        $header = array($this->UTILITIES->Translate("Status"),
            $this->UTILITIES->Translate("Local User"),
            $this->UTILITIES->Translate("Command"),
            $this->UTILITIES->Translate("[SSH_SERVER PORT]"),
            $this->UTILITIES->Translate("[REMOTE:]REMOTE_PORT:DESTINATION:DESTINATION_PORT"),
            $this->UTILITIES->Translate("[USER@]SSH_SERVER")
        );
        $table = $this->GetAutoSshRemotePortForwardingsTable();
        $footer = "";
        $TABLEOFLOCALPORTFORWARDINGS = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFLOCALPORTFORWARDINGS->DisplayTableOnCard($w3_responsive_class, "");
        return $string;
    }
    
    function GetAutoSshRemotePortForwardingsTable() {
        $remote_port_forwardings = $this->UTILITIES->RASPBERRY->GetAutosshRemoteForwardings();
        foreach($remote_port_forwardings as $remote_port_forwarding) {
            if($remote_port_forwarding["STATUS"] === "+OK") {
                $status = "<a style='color:green;'>+OK</a>";
            } elseif($remote_port_forwarding["STATUS"] === "-ERR") {
                $status = "<a style='color:red;'>-ERR</a>";
            }
            if($remote_port_forwarding["SSH_SERVER_PORT"] !== "") {
                $port = "-p ".$remote_port_forwarding["SSH_SERVER_PORT"];
            } else {
                $port = "";
            }
            if($remote_port_forwarding["REMOTE"] !== "") {
                $expression1 = $remote_port_forwarding["REMOTE"].":"
                                .$remote_port_forwarding["REMOTE_PORT"].":"
                                .$remote_port_forwarding["DESTINATION"].":"
                                .$remote_port_forwarding["DESTINATION_PORT"];    
            } else {
                $expression1 = $remote_port_forwarding["REMOTE_PORT"].":"
                                .$remote_port_forwarding["DESTINATION"].":"
                                .$remote_port_forwarding["DESTINATION_PORT"];    
            }
            if($remote_port_forwarding["REMOTE_USER"] !== "") {
                $expression2 = $remote_port_forwarding["REMOTE_USER"]."@"
                                .$remote_port_forwarding["SSH_SERVER"];
            } else {
                $expression2 = $remote_port_forwarding["SSH_SERVER"];
            }
            $table[] = [
                $status,
                $remote_port_forwarding["LOCAL_USER"],
                "autossh -f -N -R ",
                $port,
                $expression1,
                $expression2
            ];
        }
        return $table;
    }
    
}
