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

$command = htmlspecialchars($_REQUEST["command"]);
$authorization_key = htmlspecialchars($_REQUEST["authorization_key"]);

if($authorization_key === $_SESSION["key"]) {
    $UTILITIES->RASPBERRY->ExecuteCommandRealtimeOutput($command);
}
