<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();

$toroot = str_repeat(".", substr_count(str_replace("/RASPserver", "", htmlspecialchars($_SERVER["PHP_SELF"])), "/"));
require $toroot."/class/REQUIRED_RASPBERRY_PHP.php";
$REQUIRED_RASPBERRY_PHP = new REQUIRED_RASPBERRY_PHP($toroot);

$UTILITIES = new UTILITIES();
$BUTTONS = new BUTTONS();

$user = htmlspecialchars($_REQUEST["user"]);
$authorization_key = htmlspecialchars($_REQUEST["authorization_key"]);

if($authorization_key === $_SESSION["key"]) {
//    $_SESSION["key"] = $UTILITIES->GenerateAuthorizationKey();
    $email_addresses = $UTILITIES->RASPBERRY->GetEmailAddresses();
    $email_addresses_of_user = $UTILITIES->GetEmailAdressesOfThisUser($user, $email_addresses);
    $emails_delete = $UTILITIES->CreateStringsToDeleteEmailAddresses($user, $email_addresses_of_user, $email_addresses);
    if($emails_delete !== "") {
        $string .= "°".$emails_delete;
    }
    $samba_shares = $UTILITIES->RASPBERRY->GetSambaShares();
    $samba_delete = $UTILITIES->CreateStringsToDeleteOrModifySambaShares($user, $samba_shares);
    if($samba_delete !== ":") {
        $string .= "°".$samba_delete;
    }
    $string .= "°smbpasswd -x ".$user.":smbpasswd -x ".$user;
    $string .= "°userdel ".$user.":userdel ".$user;
    $string .= "°rm -r /home/".$user.":rm -r /home/".$user;
    $string .= "°service smbd restart:service smbd restart";
    
    echo substr($string, 2);
//    echo $result;
}
