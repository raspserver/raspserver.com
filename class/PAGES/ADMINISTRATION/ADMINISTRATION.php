<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of ADMINISTRATION
 *
 * @author rene
 */
class ADMINISTRATION {
    
    function __construct (
            $UTILITIES,
            $UTILITIES_ADMINISTRATION,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->UTILITIES_ADMINISTRATION = $UTILITIES_ADMINISTRATION;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_LOGIN = $UTILITIES->UTILITIES_LOGIN;
        $this->raspberry_emulated = $UTILITIES->raspberry_emulated; 
        $this->toroot = $UTILITIES->toroot;
    }
    
    function DisplaySettings($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("RASPserver Administration");
        $header = array($this->UTILITIES->Translate("Setting"), $this->UTILITIES->Translate("Status"));
        $table = $this->DisplaySettingsTable();
        $footer = $this->ButtonToSettingsConfigurationTable();
        $TABLEOFSETTINGS = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFSETTINGS->DisplayTableOnCard($w3_responsive_class, "w3-right-align");
        return $string;
    }
    
    function DisplaySettingsTable() {
        $table[] = array(
            $this->UTILITIES->Translate("RASPserver Administrator Password"), 
            $this->UTILITIES->Translate($this->UTILITIES_ADMINISTRATION->IsSetAdministratorPasswordAsString()));
        return $table;
    }
    
    function ConfigureSettings() {
        $name = $this->UTILITIES->Translate("RASPserver Administration");
        $header = array($this->UTILITIES->Translate("Setting"));
        $table = $this->ConfigureSettingsTable();
        $footer = $this->ButtonToDashboard();
        $CONFIGURESETTINGSTABLE = new TABLE($name, $header, $table, $footer);
        $string = $CONFIGURESETTINGSTABLE->DisplayTableOnCard("w3-row", "");
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
    
    function ButtonToSettingsConfigurationTable() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToAdministration",
            $this->toroot."/pages/RASPBERRY_Administration.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToAdministration").")",
            $this->UTILITIES->Translate("Configure")                                                                 // $button_text
        );
        return $string;
    }
    
    function ConfigureSettingsTable() {
        if(!$this->raspberry_emulated) {
            if($this->UTILITIES_ADMINISTRATION->IsSetAdministratorPassword()) {
                $table[] = array($this->CreateDisablePasswordButtons());
                $table[] = array($this->CreateChangePasswordButtons());
            } else {
                $table[] = array($this->CreateEnablePasswordButtons());
            }
        } else {
            $table[] =  array("<a "
                                . "style='pointer-events:none;' "
                                    . "class='w3-button w3-round-large w3-light-gray  w3-border w3-animate-opacity'>"
                                    . $this->UTILITIES->Translate("In demo disabled: Enable RASPserver Administrator Password")
                                ."</a>");
        }
        return $table;
    }
    
    function CreateEnablePasswordButtons() {
        // $button_id, $button_jsfunction_onclick, $button_text,
        // $id_hidden_parts,
        // $entry1_display, $entry1_id, $entry1_type, $entry1_label,
        // $entry2_display, $entry2_id, $entry2_type, $entry2_label,
        // $button_cancel_id, $button_cancel_display, $button_cancel_jsfunction_onclick, $button_cancel_text,
        // $button_confirmation_id, $button_confirmation_display, $button_confirmation_jsfunction_onclick, $button_confirmation_text,
        // $notification_id, $notification
        $string = $this->BUTTONS->AccordionButtonDoubleEntry(
            "IdButtonEnableAdministratorPassword",
            "EnableAdministratorPassword("
                . json_encode("IdButtonLinkedToDashboard").")",
            $this->UTILITIES->Translate("Enable RASPserver Administrator Password"),
            "IdEnablePasswordHiddenParts",
            "", "IdEnablePasswordEntryPassword", "password", $this->UTILITIES->Translate("Password"),
            "", "IdEnablePasswordEntryPasswordConfirmation", "password", $this->UTILITIES->Translate("Confirmation"),
            "EnableAdministratorPasswordCancelIdButton", "", 
            "EnableAdministratorPasswordCancel("
                . json_encode("IdButtonLinkedToDashboard").")",
            $this->UTILITIES->Translate("Cancel"),
            "EnableAdministratorPasswordNowIdButton", "",                
            "EnableAdministratorPasswordNow("
                . json_encode($this->toroot).", ". json_encode($_SESSION["key"]).", "
                . json_encode("EnableAdministratorPasswordCancelIdButton").", "
                . json_encode("EnableAdministratorPasswordNowIdButton").", "
                . json_encode("EnableAdministratorPasswordNotificationId").", "
                . json_encode($this->UTILITIES->Translate("no entry")).", "
                . json_encode($this->UTILITIES->Translate("no match")).", "
                . json_encode($this->UTILITIES->Translate("invalid password")).")",
            $this->UTILITIES->Translate("Confirm"),
            "EnableAdministratorPasswordNotificationId", "");
        return $string;
    }
    
    function CreateDisablePasswordButtons() {
        // $button_id, $button_jsfunction_onclick, $button_text,
        // $id_hidden_parts,
        // $entry1_display, $entry1_id, $entry1_type, $entry1_label,
        // $entry2_display, $entry2_id, $entry2_type, $entry2_label,
        // $button_cancel_id, $button_cancel_display, $button_cancel_jsfunction_onclick, $button_cancel_text,
        // $button_confirmation_id, $button_confirmation_display, $button_confirmation_jsfunction_onclick, $button_confirmation_text,
        // $notification_id, $notification
        $string = $this->BUTTONS->AccordionButtonDoubleEntry(
            "IdButtonDisableAdministratorPassword",
            "DisableAdministratorPassword("
                . json_encode("IdButtonLinkedToDashboard").")",
            $this->UTILITIES->Translate("Disable RASPserver Administrator Password"),
            "IdDisablePasswordHiddenParts",
            "none", "", "", "",
            "none", "", "", "",
            "DisableAdministratorPasswordCancelIdButton", "", 
            "DisableAdministratorPasswordCancel("
                . json_encode("IdButtonLinkedToDashboard").")",
            $this->UTILITIES->Translate("Cancel"),
            "DisableAdministratorPasswordNowIdButton", "",                                                        // 
            "AjaxDisableAdministratorPasswordNow("
                . json_encode($this->toroot).", "
                . json_encode($_SESSION["key"]).", "
                . json_encode("DisableAdministratorPasswordCancelIdButton").", "
                . json_encode("DisableAdministratorPasswordNowIdButton").")",
            $this->UTILITIES->Translate("Disable"),
            "", "");
        return $string;
    }
    
    function CreateChangePasswordButtons() {
        // $button_id, $button_jsfunction_onclick, $button_text,
        // $id_hidden_parts,
        // $entry1_display, $entry1_id, $entry1_type, $entry1_label,
        // $entry2_display, $entry2_id, $entry2_type, $entry2_label,
        // $button_cancel_id, $button_cancel_display, $button_cancel_jsfunction_onclick, $button_cancel_text,
        // $button_confirmation_id, $button_confirmation_display, $button_confirmation_jsfunction_onclick, $button_confirmation_text,
        // $notification_id, $notification
        $string = $this->BUTTONS->AccordionButtonDoubleEntry(
            "IdButtonChangeAdministratorPassword", 
            "ChangeAdministratorPassword("
                . json_encode("IdButtonLinkedToDashboard").")",
            $this->UTILITIES->Translate("Change RASPserver Administrator Password"),
            "IdChangePasswordHiddenParts",
            "", "IdChangePasswordEntryPassword", "password", $this->UTILITIES->Translate("Password"),
            "", "IdChangePasswordEntryPasswordConfirmation", "password", $this->UTILITIES->Translate("Confirmation"), 
            "ChangeAdministratorPasswordCancelIdButton", "", 
            "ChangeAdministratorPasswordCancel("
                . json_encode("IdButtonLinkedToDashboard").")",                                                                                // 
            $this->UTILITIES->Translate("Cancel"),
            "ChangeAdministratorPasswordNowIdButton", "",                                                         // 
            "ChangeAdministratorPasswordNow("                                       
                . json_encode($this->toroot).", "
                . json_encode($_SESSION["key"]).", "
                . json_encode("ChangeAdministratorPasswordCancelIdButton").", "
                . json_encode("ChangeAdministratorPasswordNowIdButton").", "
                . json_encode("ChangeAdministratorPasswordNotificationId").", "
                . json_encode($this->UTILITIES->Translate("no entry")).", "
                . json_encode($this->UTILITIES->Translate("no match")).", "
                . json_encode($this->UTILITIES->Translate("invalid password")).")", 
            $this->UTILITIES->Translate("Confirm"),
            "ChangeAdministratorPasswordNotificationId", "");                                                      // 
        return $string;
    }
    
}



