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
$pop3_server = htmlspecialchars($_REQUEST["pop3_server"]);
$pop3_user = htmlspecialchars($_REQUEST["pop3_user"]);
$pop3_password = htmlspecialchars($_REQUEST["pop3_password"]);

if($authorization_key === $_SESSION["key"]) {
    $UTILITIES = new UTILITIES();
    $POP3ServerConnectionStatus = $UTILITIES->RASPBERRY->GetPOP3ServerConnectionStatus($pop3_server, $pop3_user, $pop3_password);    
    echo $POP3ServerConnectionStatus;
}
