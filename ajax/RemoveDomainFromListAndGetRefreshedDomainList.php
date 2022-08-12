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
$UTILITIES_PING = new UTILITIES_PING($UTILITIES);
$BUTTONS = new BUTTONS();

$domain = strtolower(htmlspecialchars($_REQUEST["domain"]));
$ping_or_traceroute = strtolower(htmlspecialchars($_REQUEST["ping_or_traceroute"]));
$authorization_key = htmlspecialchars($_REQUEST["authorization_key"]);

if($authorization_key === $_SESSION["key"]) {
    $UTILITIES_PING->RemoveDomainFromTheList($domain);
    $PING = new PING($UTILITIES, $BUTTONS);
    $_SESSION["key"] = $UTILITIES->GenerateAuthorizationKey();
    $childnode = $PING->ConfigureDomainsChildNode($ping_or_traceroute);
    echo $childnode;
//    echo "df";
}


