<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of TABLE
 *
 * @author rene
 */
class TABLE {
    
    function __construct(
            $name,
            $header,
            $rows,
            $footer
    ) {
        $this->name = $name;
        $this->header = $header;
        $this->rows = $rows;
        $this->footer = $footer;
    }
    
    function CreateTable($w3_right_align) { 
        $string =   "<table class='w3-table w3-bordered'>"
                        ."<tr>";
                        $i = 0; $j = 0; // i:row, j:column 
                        foreach($this->header as $column) {
                            if($j < (count($this->header) -1 ) or count($this->header) === 1) {
                                $string .= "<th class=''>".$column."</th>";
                            } else {
                                $string .= "<th class='".$w3_right_align."'>".$column."</th>";
                            }
                            $j++;
                        }
        $string .=      "</tr>";
                        $i++;
                        foreach ($this->rows as $row) {
                            $string .= "<tr>";
                            $j = 0;
                            foreach ($row as $column) {
                                if($j < (count($row)-1) or count($this->header) === 1) {
                                    $string .= "<td class=''>".$column."</td>";
                                } else {
                                    $string .= "<td class='".$w3_right_align."'>".$column."</td>";
                                }
                                $j++;
                            }
                            $string .= "</tr>"; $i++;
                        }
        $string .=  "</table>";
        return $string;
    }
    
    function DisplayTableOnCard($w3_responsive_class, $w3_right_align) {
        $string =  "<div class='".$w3_responsive_class." w3-container w3-section'>"
                    . "<h2><p class='w3-container w3-section'>".$this->name."</p></h2>"
                    . "<div class='w3-card'>"
                        . "<nobr>"
                            . "<div class='w3-responsive'>"
                                . $this->CreateTable($w3_right_align)
                            . "</div>"
                        . "</nobr>"
                    . "</div>"
                    . "<p class='w3-container'>".$this->footer."</p>"
                . "</div>";
        return $string;
    }
    
    
    
}
