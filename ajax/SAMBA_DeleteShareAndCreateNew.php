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
$path = htmlspecialchars($_REQUEST["path"]);
$writable = htmlspecialchars($_REQUEST["writable"]);
$selected_valid_users_string = htmlspecialchars($_REQUEST["selected_valid_users_string"]);
$authorization_key = htmlspecialchars($_REQUEST["authorization_key"]);

if($authorization_key === $_SESSION["key"]) {
    $samba_shares = $UTILITIES->RASPBERRY->GetSambaShares();
    $string = $UTILITIES_SAMBA->CreateCommandsDeleteAndAddNewSambaShare($share, $path, $writable, $selected_valid_users_string, $samba_shares);
    echo $string;
}