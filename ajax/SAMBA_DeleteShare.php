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
$UTILITIES_SAMBA = new UTILITIES_SAMBA($UTILITIES, $BUTTONS);

$share = htmlspecialchars($_REQUEST["share"]);
$authorization_key = htmlspecialchars($_REQUEST["authorization_key"]);

if($authorization_key === $_SESSION["key"]) {
    
    $samba_shares = $UTILITIES->RASPBERRY->GetSambaShares();
    $string = $UTILITIES_SAMBA->CreateCommandDeleteSambaShare($share, $samba_shares);
    echo $string;
}