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
    $BUTTONS = new BUTTONS();
    
    $SSH_HOST_KEYS = new SSH_HOST_KEYS($UTILITIES, $BUTTONS);
    
    $ssh_query_output = $UTILITIES->RASPBERRY->SSHKeysQuery();
    
    $host_keys = $UTILITIES->RASPBERRY->GetSshHostKeys($ssh_query_output);
    
    $string_HOST_KEYS = $SSH_HOST_KEYS->ConfigureHostKeysChildNodeRefreshed($host_keys);

    echo $string_HOST_KEYS;
}
