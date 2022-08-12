<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of SITES
 *
 * @author rene
 */
class SITES {
    
    function __construct (
            $UTILITIES
    ) {
        $this->UTILITIES = $UTILITIES; 
    }
    
    function DisplaySites($w3_responsive_class) {
    $ip_address = $this->UTILITIES->RASPBERRY->GetNetworkInterfaces()[0]["ip_address"];
       $name = $this->UTILITIES->Translate("Sites");
       $header = array($this->UTILITIES->Translate("Site"), $this->UTILITIES->Translate("Description"));
       if(!$this->UTILITIES->raspberry_emulated) {
            $table[] = array("<a href='http://".$ip_address."/freepbx/' target='_blank'>http://".$ip_address."/freepbx/</a>", $this->UTILITIES->Translate("Graphical user interface that manages"));
            $table[] = array("<a href='https://".$ip_address."/freepbx/' target='_blank'>https://".$ip_address."/freepbx/</a>", $this->UTILITIES->Translate("Asterisk, a voice over IP and telephony server"));
            $table[] = array("<a href='http://".$ip_address."/baikal/admin/' target='_blank'>http://".$ip_address."/baikal/admin/</a>", $this->UTILITIES->Translate("Lightweight CalDAV+CardDAV server"));
            $table[] = array("<a href='https://".$ip_address."/baikal/admin/' target='_blank'>https://".$ip_address."/baikal/admin/</a>", $this->UTILITIES->Translate("access contacts and calendars from every device"));
            $table[] = array("<a href='http://".$ip_address."/roundcubemail/' target='_blank'>http://".$ip_address."/roundcubemail/</a>", $this->UTILITIES->Translate("Browser-based multilingual IMAP client"));
            $table[] = array("<a href='https://".$ip_address."/roundcubemail/' target='_blank'>https://".$ip_address."/roundcubemail/</a>", $this->UTILITIES->Translate("with an application-like user interface"));
       } else {
            $table[] = array("http://".$ip_address."/freepbx/", $this->UTILITIES->Translate("Graphical user interface that manages"));
            $table[] = array("https://".$ip_address."/freepbx/", $this->UTILITIES->Translate("Asterisk, a voice over IP and telephony server"));
            $table[] = array("http://".$ip_address."/baikal/admin/", $this->UTILITIES->Translate("Lightweight CalDAV+CardDAV server"));
            $table[] = array("https://".$ip_address."/baikal/admin/", $this->UTILITIES->Translate("access contacts and calendars from every device"));
            $table[] = array("http://".$ip_address."/roundcubemail/", $this->UTILITIES->Translate("Browser-based multilingual IMAP client"));
            $table[] = array("https://".$ip_address."/roundcubemail/", $this->UTILITIES->Translate("with an application-like user interface"));
       }
       $footer = "";
       $TABLEOFSITES = new TABLE($name, $header, $table, $footer);
       $string = $TABLEOFSITES->DisplayTableOnCard($w3_responsive_class, "w3-right-align");
       return $string;
    }
    
}
