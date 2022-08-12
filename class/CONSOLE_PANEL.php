<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of CONSOLE_PANEL
 *
 * @author rene
 */
class CONSOLE_PANEL {
    
    function __construct (
            $UTILITIES,
            $console_panel_id,
            $console_panel_display,
            $name_display,
            $name,
            $field_left,
            $user,
            $host,
            $idconsole,
            $idclearconsolebutton,
            $JSfunctionclearconsolebutton
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->console_panel_id = $console_panel_id;
        $this->console_panel_display = $console_panel_display;
        $this->name_display = $name_display;
        $this->name = $name;
        $this->field_left = $field_left;
        $this->user = $user;
        $this->host = $host;
        $this->idconsole = $idconsole;
        $this->idclearconsolebutton = $idclearconsolebutton;
        $this->JSfunctionclearconsolebutton = $JSfunctionclearconsolebutton;
    }
    
    function ConsolePanel() {
        $string = "<div id='".$this->console_panel_id."' style='display:".$this->console_panel_display.";' class='w3-row w3-container w3-section'>"
                    . "<div style='display:".$this->name_display.";'>"  
                        . "<h2><p  class='w3-container w3-section'>".$this->name."</p></h2>"
                    . "</div>"
                    . "<div class='w3-card'>"
                        . "<div class='w3-row'>"
                            . $this->ConsolePanelLeft()
                            . $this->ConsolePanelRight()
                        . "</div>"
                    . "</div>"
                . "</div>";   
        return $string;
    }
    
    function ConsolePanelLeft() {
        $string = "<div class='w3-cell w3-quarter'>"
                    . "<div class='w3-col'>"
                        . "<div class='w3-responsive w3-margin-top w3-margin-bottom'>"
                            . "<nobr>"
                                . $this->field_left 
                            . "</nobr>"
                        . "</div>"
                    . "</div>"
                . "</div>";
        return $string;
    }
    
    function ConsolePanelRight() {
        $string = "<div class='w3-cell w3-threequarter'>"
                    . "<div class='w3-col' >"
                        . "<div class='w3-responsive w3-margin-left w3-margin-right w3-margin-top w3-margin-bottom w3-border w3-gray'>"
                            . "<nobr>"
                                . $this->UTILITIES->Console($this->idconsole, $this->user, $this->host, "")
                            . "</nobr>"
                        . "</div>"
                        . "<a id='".$this->idclearconsolebutton."' class='w3-margin-left w3-margin-bottom w3-button w3-round-large w3-light-gray w3-border' "
                            . "onclick='".$this->JSfunctionclearconsolebutton."'>"
                                . $this->UTILITIES->Translate("Clear console")
                        . "</a>"
                    . "</div>"
                . "</div>";
        return $string;
    }
    
    

    
}
