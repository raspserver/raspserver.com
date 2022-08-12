<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of REQUIRED_RASPBERRY_PHP
 *
 * @author rene
 */
class REQUIRED_RASPBERRY_PHP {
        
    function __construct (
           $toroot
    ) {
        require $toroot."/class/BUTTONS.php";
        require $toroot."/class/CONSOLE_PANEL.php";
        require $toroot."/class/RASPBERRY/RASPBERRY.php";
        require $toroot."/class/RASPBERRY/RASPBERRY_EMULATED.php";
        require $toroot."/class/SITES.php";
        require $toroot."/class/SOFTWARE.php";
        require $toroot."/class/SQL.php";
        require $toroot."/class/PAGES/ADMINISTRATION/ADMINISTRATION.php";
        require $toroot."/class/PAGES/AUTOSSH/AUTOSSH.php";
        require $toroot."/class/PAGES/EMAIL/EMAIL.php";
        require $toroot."/class/PAGES/LANGUAGES/LANGUAGES.php";
        require $toroot."/class/PAGES/LOCAL_RESOURCES/LOCAL_RESOURCES.php";
        require $toroot."/class/PAGES/LOGIN/LOGIN.php";
        require $toroot."/class/NETWORK_INTERFACES.php";
        require $toroot."/class/PAGES/PING/PING.php";
        require $toroot."/class/PAGES/SAMBA/SAMBA.php";
        require $toroot."/class/PAGES/SSH/SSH_AUTHORIZED_KEYS.php";
        require $toroot."/class/PAGES/SSH/SSH_HOST_KEYS.php";
        require $toroot."/class/PAGES/SSH/SSH_USER_KEYS.php";
        require $toroot."/class/PAGES/SSH/SSH_KNOWN_HOSTS.php";
        require $toroot."/class/PAGES/SSH/SSH_SETTINGS.php";
        require $toroot."/class/PAGES/UNIXUSERS/UNIXUSERS.php";
        require $toroot."/class/UTILITIES/UTILITIES.php";
        require $toroot."/class/UTILITIES/UTILITIES_ADMINISTRATION.php";
        require $toroot."/class/UTILITIES/UTILITIES_AUTOSSH.php";
        require $toroot."/class/UTILITIES/UTILITIES_EMAIL.php";
        require $toroot."/class/UTILITIES/UTILITIES_LANGUAGES.php";
        require $toroot."/class/UTILITIES/UTILITIES_LOCAL_RESOURCES.php";
        require $toroot."/class/UTILITIES/UTILITIES_LOGIN.php";
        require $toroot."/class/UTILITIES/UTILITIES_NETWORK_INTERFACES.php";
        require $toroot."/class/UTILITIES/UTILITIES_PAGES.php";
        require $toroot."/class/UTILITIES/UTILITIES_PING.php";
        require $toroot."/class/UTILITIES/UTILITIES_SAMBA.php";
        require $toroot."/class/UTILITIES/UTILITIES_SETTINGS.php";
        require $toroot."/class/UTILITIES/UTILITIES_SSH.php";
        require $toroot."/class/UTILITIES/UTILITIES_UNIXUSERS.php";
        require $toroot."/class/PAGES/PAGES.php";
        require $toroot."/class/TABLE.php";
    }
}
