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
    
    $SSH_AUTHORIZED_KEYS = new SSH_AUTHORIZED_KEYS($UTILITIES, $BUTTONS);
    $SSH_HOST_KEYS = new SSH_HOST_KEYS($UTILITIES, $BUTTONS);
    $SSH_KNOWN_HOSTS = new SSH_KNOWN_HOSTS($UTILITIES, $BUTTONS);
    $SSH_USER_KEYS = new SSH_USER_KEYS($UTILITIES, $BUTTONS);
    
    $ssh_query_output = $UTILITIES->RASPBERRY->SSHKeysQuery();
    $unixusers = $UTILITIES->RASPBERRY->GetUnixUsers();
    
    $authorized_keys = $UTILITIES->RASPBERRY->GetSshAuthorizedKeys($ssh_query_output, $unixusers);
    $host_keys = $UTILITIES->RASPBERRY->GetSshHostKeys($ssh_query_output);
    $known_hosts = $UTILITIES->RASPBERRY->GetKnownHosts($ssh_query_output, $unixusers);
    $user_keys = $UTILITIES->RASPBERRY->GetSshUserKeys($ssh_query_output, $unixusers);
    
    $string_AUTHORIZED_KEYS = $SSH_AUTHORIZED_KEYS->DisplayAuthorizedKeysTableChildNodeRefreshed("w3-row", $authorized_keys);
    $string_HOST_KEYS = $SSH_HOST_KEYS->DisplayHostKeysTableChildNodeRefreshed("w3-row", $host_keys);
    $string_KNOWN_HOSTS = $SSH_KNOWN_HOSTS->DisplayKnownHostsTableChildNodeRefreshed("w3-row", $known_hosts);
    $string_USER_KEYS = $SSH_USER_KEYS->DisplayUserKeysTableChildNodeRefreshed("w3-row", $user_keys);
    
    echo $string_AUTHORIZED_KEYS."°".$string_HOST_KEYS."°".$string_KNOWN_HOSTS."°".$string_USER_KEYS;
}
