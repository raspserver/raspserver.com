<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of SOFTWARE
 *
 * @author rene
 */
class SOFTWARE {
    
    function __construct (
            $UTILITIES
    ) {
        $this->UTILITIES = $UTILITIES; 
    }
    
    function DisplaySoftware($w3_responsive_class) {
        $softwares = $this->UTILITIES->RASPBERRY->GetSoftwares();
        $name = $this->UTILITIES->Translate("Software");
        $header = array(
            $this->UTILITIES->Translate("Category"),
            $this->UTILITIES->Translate("Name"),
            $this->UTILITIES->Translate("Version")
                );
        foreach($softwares as $software) {
            $table[] = array(
                    $software['category'],
                    $software['name'],
                    $software['version']
                );
        }
        $footer = "";
        $TABLEOFNETWORKINTERFACES = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFNETWORKINTERFACES->DisplayTableOnCard($w3_responsive_class, "w3-right-align");
        return $string;
    }
    
}
