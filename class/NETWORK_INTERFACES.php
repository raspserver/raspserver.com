<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of NETWORK_INTERFACES
 *
 * @author rene
 */
class NETWORK_INTERFACES {
    
    function __construct (
            $UTILITIES
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->UTILITIES_NETWORK_INTERFACES = new UTILITIES_NETWORK_INTERFACES($this->UTILITIES);
    }
    
    function DisplayNetworkInterfaces($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("Network");
        $network_interfaces = $this->UTILITIES_NETWORK_INTERFACES->GetNetworkInterfaces();
        $header = array(
            $this->UTILITIES->Translate("Network Interface"),
            $this->UTILITIES->Translate("IP Address")." ipv4",
            $this->UTILITIES->Translate("Netmask")." ipv4",
            $this->UTILITIES->Translate("Broadcast Address")." ipv4",
            $this->UTILITIES->Translate("IP Address")." ipv6 global",
            $this->UTILITIES->Translate("IP Address")." ipv6 local",
            $this->UTILITIES->Translate("MAC Address"));
        foreach($network_interfaces as $network_interface) {
            $table[] = array(
                $network_interface['network_interface'],
                $network_interface['ip_address'],
                $network_interface['netmask'],
                $network_interface['broadcast'],
                $network_interface['ip_address_ipv6_global'],
                $network_interface['ip_address_ipv6_local'],
                $network_interface['mac_address']);
        }
        $footer = "";
        $TABLEOFNETWORKINTERFACES = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFNETWORKINTERFACES->DisplayTableOnCard($w3_responsive_class, "w3-right-align");
        return $string;
    }
    
    
    
}
