<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_NETWORK_INTERFACES1
 *
 * @author rene
 */
class UTILITIES_NETWORK_INTERFACES {
    
    function __construct (
            $UTILITIES
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->RASPBERRY = $UTILITIES->RASPBERRY;
    }
    
    function GetNetworkInterfaces() {
        $network_interfaces = $this->RASPBERRY->GetNetworkInterfaces(); 
        return $network_interfaces; 
    }
    
    
    
}