<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of EMAIL
 *
 * @author rene
 */
class EMAIL {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_EMAIL = new UTILITIES_EMAIL($UTILITIES, $BUTTONS);
        $this->toroot = $UTILITIES->toroot;
    }
    
    function DisplayEmailTable($w3_responsive_class) {
        $string = "<div id='EmailTableParentNode'>".$this->DisplayEmailTableChildNode($w3_responsive_class)."</div>";
        $string .= $this->UTILITIES_EMAIL->LoadEmailTable();
        $string .= $this->UTILITIES_EMAIL->GetPOP3SslConnectionStatuses();
        return $string;
    }
        
    function DisplayEmailTableChildNode($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("Email");
        $header = array($this->UTILITIES->Translate("Status")."...", "", "", "", "");
        $table[] = array("", "", "", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='EmailTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "w3-right-align")."</div>";
        return $string;
    }
    
    function DisplayEmailTableChildNodeRefreshed($w3_responsive_class, $email_addresses) {
        $name = $this->UTILITIES->Translate("Email");
        $header = array(
            $this->UTILITIES->Translate("Status"),
            "(uid/gid) ".$this->UTILITIES->Translate("RASPserver User"),
            $this->UTILITIES->Translate("POP3 Server"),
            $this->UTILITIES->Translate("POP3 User"),
            $this->UTILITIES->Translate("POP3 Password"));
        foreach($email_addresses as $email_address) {
            $table[] = array("<a id='IdColumn4:".$email_address["debian_user"].":".$email_address["pop3_server"].":".$email_address["pop3_user"]."'></a>",
                $email_address["uidgiduser"],
            $email_address["pop3_server"],
            $email_address["pop3_user"],
            "***");
        }
        $footer = $this->ButtonToConfigureEmail();
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='EmailTableChildNode' >".$TABLEOFEMAILS->DisplayTableOnCard($w3_responsive_class, "w3-right-align")."</div>";
        return $string;
    }
    
    function ButtonToConfigureEmail() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToEmailConfiguration",
            $this->toroot."/pages/RASPBERRY_Email.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToEmailConfiguration").")",
            $this->UTILITIES->Translate("Configure")                                                                 // $button_text
        );
        return $string;
    }
    
    function ConfigureEmail()  {
        $string = "<div id='ConfigureEmailParentNode'>".$this->ConfigureEmailChildNode()."</div>";
        $string .= $this->UTILITIES_EMAIL->LoadConfigureEmailTable();
        $string .= $this->UTILITIES_EMAIL->GetPOP3SslConnectionStatuses();
        return $string;
    }
    
    function ConfigureEmailChildNode() {
        $name = $this->UTILITIES->Translate("Email");
        $header = array($this->UTILITIES->Translate("Status")."...", "", "", "", "");
        $table[] = array("", "", "", "", "");
        $footer = "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin' style='font-size:20px'></i> <i></i></a>";
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureEmailChildNode'>".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "w3-right-align")."</div>";  
        return $string;
    }
    
    function ConfigureEmailChildNodeRefreshed($email_addresses) {
        $name = $this->UTILITIES->Translate("Email");
        $header = array(
            $this->UTILITIES->Translate("Status"),
            "(uid/gid) ".$this->UTILITIES->Translate("RASPserver User"),
            $this->UTILITIES->Translate("POP3 Server"),
            $this->UTILITIES->Translate("POP3 User"),
            $this->UTILITIES->Translate("POP3 Password"),
            $this->UTILITIES->Translate("Action"));
        $i = 0;
        foreach($email_addresses as $email_address) {
            $table[] = $this->EmailEntry($email_address, $i);
            $i++;
        }
//        $table[] = array($this->AddEMailButton(), "", "", "", "");
        $footer = $this->ButtonToDashboard()." ".$this->AddEMailButton().$this->HiddenConsolePanels();
        $TABLEOFEMAILS = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureEmailChildNode'>".$TABLEOFEMAILS->DisplayTableOnCard("w3-row", "w3-right-align")."</div>";
        echo $string;
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
    
    function EmailEntry($email_address, $i) {
        $array = array(
            "<a id='IdColumn4:".$email_address["debian_user"].":".$email_address["pop3_server"].":".$email_address["pop3_user"]."'></a>",
            "<a id='IdColumn0:".$email_address["debian_user"].":".$email_address["pop3_server"].":".$email_address["pop3_user"].":".$email_address["pop3_server_line_number"].":".$email_address["pop3_user_line_number"]."'>".$email_address["uidgiduser"]."</a>",
            $email_address["pop3_server"],
            $email_address["pop3_user"],
            "***",
            $this->RemoveEMailButton($email_address));
        RETURN $array;
    }
    
    function RemoveEmailButton($email_address) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonDeleteEmail:".$email_address["debian_user"].":".$email_address["pop3_server"].":".$email_address["pop3_user"], "inline", "", "", "",   // $button_id, $button_display, $pointer_events, $color, $class_attribute
            "DeleteEmail("
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_LoadConfigureEmail.php").", "             // AjaxFileRefresh
                . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_GetEmailAddresses.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_GetPOP3ConnectionStatus.php").", "     
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                    // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "
                . json_encode("IdButtonAddEmail").", "  
                . json_encode("IdConsolePanelDeleteEmail").", "                                                              // idpanel
                . json_encode("IdButtonCancelDeleteEmail").", "                                                              // idcancelbutton
                . json_encode("IdButtonDeleteEmailNow").", "                                                                 // idconfirmbutton
                . json_encode("IdButtonClearConsoleDeleteEmail").", "                                                        // idconsolebutton       
                . json_encode("IdConsoleOutputDeleteEmail").", "                                                             // idconsole 
                . json_encode($email_address["debian_user"]).", "                                                            // debian_user
                . json_encode($email_address["pop3_server"]).", "                                                            // pop3_server
                . json_encode($email_address["pop3_user"]).", " 
                . json_encode($email_address["pop3_server_line_number"]).", "
                . json_encode($email_address["pop3_user_line_number"]).", "
                . json_encode("ConfigureEmailParentNode").", "
                . json_encode("ConfigureEmailChildNode").")",  
            $this->UTILITIES->Translate("Remove email address"));// $button_text);
        return $string;
    }
    
    function AddEMailButton()  {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonAddEmail", // $button_id
            "", // $button_display
            "", // $pointer_events
            "", // $color
            "", // $class_attribute,           // w3-animate-opacity
            "AddEmail("
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_LoadConfigureEmail.php").", "             // AjaxFileRefresh
                . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_GetEmailAddresses.php").", "                 // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/EMAIL_GetPOP3ConnectionStatus.php").", "     
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                    // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "
                . json_encode("IdButtonAddEmail").", "                                                          // idbuttonaddemail
                . json_encode("IdConsolePanelAddEmail").", "                                                              // idpanel
                . json_encode("IdEntryDebianUser").", "
                . json_encode("IdEntryPOP3Server").", "  
                . json_encode("IdEntryPOP3User").", "  
                . json_encode("IdEntryPOP3Password").", "  
                . json_encode("IdEntryPOP3PasswordConfirmation").", "  
                . json_encode("IdButtonCancelAddEmail").", "                                                              // idcancelbutton
                . json_encode("IdButtonAddEmailNow").", "                                                                 // idconfirmbutton
                . json_encode("IdButtonClearConsoleAddEmail").", "                                                        // idconsolebutton       
                . json_encode("IdConsoleOutputAddEmail").", "                                                             // idconsole 
                . json_encode($this->UTILITIES->Translate("select")).", "
                . json_encode($this->UTILITIES->Translate("entry_exists")).", "
                . json_encode($this->UTILITIES->Translate("invalid_servername")).", "
                . json_encode($this->UTILITIES->Translate("invalid_email_username")).", "
                . json_encode($this->UTILITIES->Translate("invalid_password")).", "
                . json_encode($this->UTILITIES->Translate("no_match")).", "
                . json_encode("ConfigureEmailParentNode").", "
                . json_encode("ConfigureEmailChildNode").")", 
            $this->UTILITIES->Translate("Add email address"));// $button_text);
        return $string;
    }
    
    function HiddenConsolePanels() {
        $string = $this->UTILITIES_EMAIL->HiddenConsolePanelAddEmail();
        $string .= $this->UTILITIES_EMAIL->HiddenConsolePanelRemoveEmail();
        return $string;
    }
    
    
}


