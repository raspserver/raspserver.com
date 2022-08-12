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

$path = htmlspecialchars($_REQUEST["new_path"]);
$authorization_key = htmlspecialchars($_REQUEST["authorization_key"]);

if($authorization_key === $_SESSION["key"]) {
    
    $result = $UTILITIES->RASPBERRY->IsSambaDirectoryValid($path);
    
//    if(is_dir($path) and 
//            ((substr($path, 0, 7) === "/media/") or (substr($path, 0, 6) === "/home/")) and
//            substr($path, strlen($path) - 1) !== "/") {
//        $result = true;
//    } else {
//        $result = false;
//    }
    echo $result;
}