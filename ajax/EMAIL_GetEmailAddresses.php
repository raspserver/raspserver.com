<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();

$toroot = str_repeat(".", substr_count(str_replace("/RASPserver", "", htmlspecialchars($_SERVER["PHP_SELF"])), "/"));
require $toroot."/class/REQUIRED_RASPBERRY_PHP.php";
$REQUIRED_RASPBERRY_PHP = new REQUIRED_RASPBERRY_PHP($toroot);

$authorization_key = htmlspecialchars($_REQUEST["authorization_key"]);

if($authorization_key === $_SESSION["key"]) {
    $UTILITIES = new UTILITIES();
    $email_addresses = $UTILITIES->RASPBERRY->GetEmailAddresses();
    foreach($email_addresses as $email_address) {
        $result .= $email_address["debian_user"].":"
                .$email_address["pop3_server"].":"
                .$email_address["pop3_user"].":"
                .$email_address["pop3_password"]." ";
    }
    echo trim($result);
}
