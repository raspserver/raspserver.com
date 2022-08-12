<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of LOGIN
 *
 * @author rene
 */
class LOGIN {
    
    function __construct (
            $UTILITIES,
            $UTILITIES_ADMINISTRATION,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->UTILITIES_ADMINISTRATION = $UTILITIES_ADMINISTRATION;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_LOGIN = $UTILITIES->UTILITIES_LOGIN;
        $this->toroot = $UTILITIES->toroot;
    }
    
    function LoginButton() {
        $string = $this->BUTTONS->SingleEntrySubmitButton(
            "IdLogin",                                                                                                      // $form_id
            "IdLoginPassword", "Password", "password", $this->UTILITIES->Translate("Password"),                             // $entry_id, $entry_name, $entry_input_type, $entry_label
            "IdLoginButton", $this->UTILITIES->Translate("Login"),                                                          // $button_id, $button_text,                                                   // $button_id, $button_text
            "RestoreLoginForm("                                                                                             // $button_jsfunction_onsubmit 
                .json_encode($this->UTILITIES->Translate("Verifying password")."...").", "
                .json_encode("IdLoginNotification").")",
            htmlspecialchars($_SERVER["PHP_SELF"])."?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,          // $button_action_onsubmit
            "IdLoginNotification"                                                                                           // $notification_id
        );
        return $string;
    }
    
    function DisplayLoginButton($w3_responsive_class) {
        $this->UTILITIES->DisplayOnCard($this->LoginButton(), "", $w3_responsive_class);
        $this->ValidateLoginEntry();
    }
    
    function ValidateLoginEntry() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $password = htmlspecialchars($_POST["Password"]);
            if(!$this->UTILITIES_ADMINISTRATION->IsThisAdministratorPasswordWorking($password)) {
                echo "<script>"
                        . "document.getElementById('IdLoginPassword').value = ".json_encode($password).";"
                        . "document.getElementById('IdLoginNotification').innerHTML = ".json_encode($this->UTILITIES->Translate("Invalid password")).";"
                    . "</script>";
            } else {
                $this->UTILITIES_LOGIN->SaveCurrentSessionID();
                $dashboard = $this->toroot."/pages/RASPBERRY_Dashboard.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string;
                echo "<script>"
                        . "DisableButtons();"
                        . "document.getElementById('IdLoginButton').style.pointerEvents = 'none';"
                        . "document.getElementById('IdLoginPassword').value = ".json_encode($password).";"
                        . "RestoreLoginForm("
                            .json_encode($this->UTILITIES->Translate("Success - logging in")."...").", "
                            .json_encode("IdLoginNotification").");"
                        . "window.location.href = '".$dashboard."';"
                    . "</script>";
            }
        }
    }
    
    
    
}
