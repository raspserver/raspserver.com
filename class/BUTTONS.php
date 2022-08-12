<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of BUTTONS
 *
 * @author rene
 */
class BUTTONS {
    
    function __construct (
            
    ) {
        
    }
    
    function ButtonLinkedTo(
            $button_id, $button_href, $button_jsfunction_onclick, $button_text
    )  {
        $string =    "<a "
                        . "id='".$button_id."' "
                        . "class='w3-button w3-round-large w3-border' "
                        . "href='".$button_href."' "
                        . "onclick='".$button_jsfunction_onclick."'>"
                        . $button_text
                    . "</a>";
        return $string;
    }
    
    function ButtonJsFunctionOnClick(
            $button_id,
            $button_display,
            $pointer_events,
            $color,
            $class_attribute,           // w3-animate-opacity
            $button_jsfunction_onclick,
            $button_text
    ) {
        $string =    "<a "
                        . "id='".$button_id."' "
                        . "style='display:".$button_display.";pointer-events:".$pointer_events.";color:".$color.";' "
                        . "class='w3-button w3-round-large  w3-border ".$class_attribute."' "
                        . "onclick='".$button_jsfunction_onclick."'>"
                        . $button_text
                    ."</a>";
        return $string;
    }
    
    function SingleEntrySubmitButton(
            $form_id,
            $entry_id, $entry_name, $entry_input_type, $entry_label,
            $button_id, $button_text,
            $button_jsfunction_onsubmit,
            $button_action_onsubmit,
            $notification_id
    )  {
        $string =   "<form id='".$form_id."' method='post' onsubmit='".$button_jsfunction_onsubmit."' action='".$button_action_onsubmit."' class='w3-row-padding w3-content'>"
                        . "<input type='hidden' name='form' value='".$form_id."'>"
                        . "<p>"
                            . "<input id='".$entry_id."' class='w3-input w3-border w3-round' name='".$entry_name."' type='".$entry_input_type."' required>"
                            . "<label>".$entry_label."</label>"
                        . "</p>"
                        . "<div class='w3-center'>"
                            . "<p>"
                                . "<input id='".$button_id."' class='w3-button w3-round-large w3-gray w3-section' type='submit' value='".$button_text."'>"
                            . "</p>"
                        . "</div>"
                        . "<p id='".$notification_id."' class='w3-center'></p>"
                    ."</form>";
        return $string;
    }
    
    function AccordionButtonDoubleEntry(
            $button_id, $button_jsfunction_onclick, $button_text,
            $id_hidden_parts,
            $entry1_display, $entry1_id, $entry1_type, $entry1_label,
            $entry2_display, $entry2_id, $entry2_type, $entry2_label,
            $button_cancel_id, $button_cancel_display, $button_cancel_jsfunction_onclick, $button_cancel_text,
            $button_confirmation_id, $button_confirmation_display, $button_confirmation_jsfunction_onclick, $button_confirmation_text,
            $notification_id, $notification   
    ) {
        $string = $this->ButtonJsFunctionOnClick($button_id, "inline", "", "", "", $button_jsfunction_onclick, $button_text)
                            . "<div id='".$id_hidden_parts."' style='display:none;' class='w3-row w3-margin-top'>"
                                . "<div style='display:".$entry1_display.";' class='w3-quarter w3-margin-right'>"
                                    . "<input id='".$entry1_id."' class='w3-input w3-border w3-round w3-animate-opacity' type='".$entry1_type."'>"
                                    . "<label>".$entry1_label."</label>"  
                                . "</div>"
                                . "<div style='display:".$entry2_display.";' class='w3-quarter w3-margin-right'>"
                                    . "<input id='".$entry2_id."' class='w3-input w3-border w3-round w3-animate-opacity' type='".$entry2_type."'>"
                                    . "<label>".$entry2_label."</label>" 
                                . "</div>"
                                . "<div class='w3-quarter w3-container'>"
                                . $this->ButtonJsFunctionOnClick($button_cancel_id, $button_cancel_display, "", "", "", $button_cancel_jsfunction_onclick, $button_cancel_text)." "      
                                . $this->ButtonJsFunctionOnClick($button_confirmation_id, $button_confirmation_display, "", "", "", $button_confirmation_jsfunction_onclick, $button_confirmation_text)
                                . "<a id='".$notification_id."' style='display:none;' class='w3-margin-left'>".$notification."</a>"
                                . "</div>"
                            . "</div>";
        return $string;
    }
        
    function Input(
            $entry_id, $entry_type, $entry_label
    ) {
        $string = "<div class='w3-margin-left w3-margin-right'>"
                    . "<input id='".$entry_id."' class='w3-input w3-border w3-round w3-animate-opacity w3-margin-right' type='".$entry_type."'>"
                    . "<label>".$entry_label."</label>"
                . "</div>";
        return $string;
    }
    
    function Input_Dropdown(
            $entry_id, $entry_values, $entry_label, $select, $JsFunctionOnChange
    ) {
        $string = "<div class='w3-margin-left w3-margin-right'>"
                    .$this->CreateDropdownInput($entry_id, $entry_values, $entry_label, $JsFunctionOnChange, $select)
                . "</div>";
        return $string;
    }
    
    function CreateDropdownInput($entry_id, $entry_values, $entry_label, $JsFunctionOnChange, $select) {
        $dropdowninput =      "<select id='".$entry_id."' class='w3-select w3-border w3-animate-opacity' onchange='".$JsFunctionOnChange."'>"
                            . "<option value='".$select."' selected disabled>(".$select.")</option>";
        foreach($entry_values as $entry_value) {
            $dropdowninput .=     "<option value='".$entry_value."'>".$entry_value."</option>";
        }
        $dropdowninput .=     "</select>"
                            . "<br><label>".$entry_label."</label>";
        return $dropdowninput;
    }
    
    function TwinButtons(
            $button_cancel_id, $button_cancel_display, $button_cancel_pointer_events, $button_cancel_color, $button_cancel_class_attribute, $button_cancel_jsfunction_onclick, $button_cancel_text,
            $button_confirmation_id, $button_confirmation_display, $button_confirmation_pointer_events, $button_confirmation_color, $button_confirmation_class_attribute, $button_confirmation_jsfunction_onclick, $button_confirmation_text
    ) {
        $string = "<div class='w3-center'>"
                    . "<p>"
                        .$this->ButtonJsFunctionOnClick($button_cancel_id, $button_cancel_display, $button_cancel_pointer_events, $button_cancel_color, $button_cancel_class_attribute, $button_cancel_jsfunction_onclick, $button_cancel_text)." "      
                        .$this->ButtonJsFunctionOnClick($button_confirmation_id, $button_confirmation_display, $button_confirmation_pointer_events, $button_confirmation_color, $button_confirmation_class_attribute, $button_confirmation_jsfunction_onclick, $button_confirmation_text)
                    . "</p>"
                . "</div>";
        return $string;
    }
    
    function OneInputOneButton(
            $input_and_label_id, $input_and_label_display, $input_id, $input_label,
            $button_id, $button_display, $pointer_events, $color, $class_attribute, $button_jsfunction_onclick, $button_text
    ) {
        $string = "<div id='".$input_and_label_id."'  style='display:".$input_and_label_display.";' class='w3-margin-top w3-margin-left w3-margin-right'>"
                    . "<input id='".$input_id."' class='w3-input w3-border w3-round w3-margin-right'>"
                    . "<label>".$input_label."</label>"
                . "</div>"
                . "<div class='w3-center'>"
                    . "<p>"
                        .$this->ButtonJsFunctionOnClick(
                                $button_id,
                                $button_display,
                                $pointer_events,
                                $color,
                                $class_attribute,           // w3-animate-opacity
                                $button_jsfunction_onclick,
                                $button_text)     
                    . "</p>"
                . "</div>";
        return $string;
    }
    
}
