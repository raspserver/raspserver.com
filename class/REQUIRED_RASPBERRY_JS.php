<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of REQUIRED_RASPBERRY_JS
 *
 * @author rene
 */
class REQUIRED_RASPBERRY_JS {
        
    function __construct (
           $toroot
    ) {
        echo "<script>";
            require $toroot."/js/ADMINISTRATION.js";
            require $toroot."/js/EMAIL.js";
            require $toroot."/js/LANGUAGES.js";
            require $toroot."/js/LOCAL_RESOURCES.js";
            require $toroot."/js/LOGIN.js";
            require $toroot."/js/PING.js";
            require $toroot."/js/RASPSERVER.js";
            require $toroot."/js/SAMBA.js";
            require $toroot."/js/SETTINGS.js";
            require $toroot."/js/SSH.js";
            require $toroot."/js/UNIXUSERS.js";
            require $toroot."/js/UTILITIES.js";
            require $toroot."/js/W3CSS.js";
        echo "</script>";
    }
}
