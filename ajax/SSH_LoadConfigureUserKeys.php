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

    $SSH_USER_KEYS = new SSH_USER_KEYS($UTILITIES, $BUTTONS);
    
    $ssh_query_output = $UTILITIES->RASPBERRY->SSHKeysQuery();
    $unixusers = $UTILITIES->RASPBERRY->GetUnixUsers();
    $root_has_a_ssh_folder = $UTILITIES->RASPBERRY->DoesRootHaveASshFolder($ssh_query_output);

    $user_keys = $UTILITIES->RASPBERRY->GetSshUserKeys($ssh_query_output, $unixusers);

    $string_USER_KEYS = $SSH_USER_KEYS->ConfigureUserKeysChildNodeRefreshed($user_keys, $root_has_a_ssh_folder);

    echo $string_USER_KEYS;
}
