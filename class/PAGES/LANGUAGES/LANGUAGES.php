<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of LANGUAGES
 *
 * @author rene
 */
class LANGUAGES {
    
    function __construct (
            $UTILITIES,
            $UTILITIES_LANGUAGES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->UTILITIES_LANGUAGES = new UTILITIES_LANGUAGES($UTILITIES, $BUTTONS);
        $this->BUTTONS = $BUTTONS;
        $this->languages_available = $UTILITIES_LANGUAGES->GetLanguagesAvailable();
        $this->languages_set = $UTILITIES_LANGUAGES->GetLanguagesSet();
        $this->lang = $UTILITIES->lang;
        $this->toroot = $UTILITIES->toroot;
    }
    
    function DisplayLanguages($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("Languages");
        $header = array($this->UTILITIES->Translate("Language"), "");
        foreach($this->languages_available as $language){
            if($this->UTILITIES_LANGUAGES->IsThisLanguageSet($language['token'], $this->languages_set)) {
                $availability = $this->UTILITIES->Translate("enabled");
            } else {
                $availability = $this->UTILITIES->Translate("disabled");
            }
            if ($language['token'] === $this->lang) { $color = "orange"; }
            $row = array("<a style='color:".$color.";'>".$language['token_upper_case']."</a> "
                    .$this->UTILITIES->Translate($language['language']), 
                     $this->UTILITIES->Translate($availability));
            $table[] = $row; unset($row); unset($color);
        }
        $footer = $this->ButtonToConfigureLanguages();
        $TABLEOFLANGUAGES = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFLANGUAGES->DisplayTableOnCard($w3_responsive_class, "w3-right-align");
        return $string;
    }
    
    function ButtonToConfigureLanguages() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToLanguagesConfiguration",
            $this->toroot."/pages/RASPBERRY_Languages.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToLanguagesConfiguration").")",
            $this->UTILITIES->Translate("Configure")                                                                 // $button_text
        );
        return $string;
    }
    
    function ConfigureLanguages() {
        $name = $this->UTILITIES->Translate("Languages");
        $header = array($this->UTILITIES->Translate("Language"), "");
        $table = $this->LanguagesConfigurationTable();
        $footer = $this->ButtonToDashboard();
        $TABLEOFLANGUAGES = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFLANGUAGES->DisplayTableOnCard("w3-row", "w3-right-align");
        return $string;
    }
    
    function ButtonToDashboard() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToDashboard",
            $this->toroot."/pages/RASPBERRY_Dashboard.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToDashboard").")",
            "<i class='fa fa-arrow-left' aria-hidden='true'></i>"                                                                 // $button_text
        );
        return $string;
    }
    
    function LanguagesConfigurationTable() {
        foreach($this->languages_available as $language) {
            if(!$this->UTILITIES_LANGUAGES->IsThisLanguageSet($language["token"])) {
                $pointer_events_token_upper_case = "none";
                $button_text = $this->UTILITIES->Translate("Enable");
                $button_jsfunction_onclick = "EnableLanguage("
                                                .json_encode($language['token']).", "
                                                .json_encode($this->toroot).", "
                                                .json_encode($_SESSION["key"]).")";
            } else {
                $button_text = $this->UTILITIES->Translate("Disable");
                $button_jsfunction_onclick = "DisableLanguage("
                                                .json_encode($language['token']).", "
                                                .json_encode($this->toroot).", "
                                                .json_encode($_SESSION["key"]).")";
            }
            if($language["token"] === $this->lang) {
                $color_token_upper_case = "orange";
                $pointer_events_button = "none";
                $color_button = "gray";
            }
            if(!$this->UTILITIES_LANGUAGES->IsThisLanguageSet($language["token"])) {
                $color_token_upper_case = "gray";
                $color_language = "gray";
            }
            $button = $this->ButtonJsFunctionOnClick(
                "IdButton:".$language['token'],                 // $button_id
                $pointer_events_button,                                       // $button_display,
                $color_button,
                $button_jsfunction_onclick,                     // $button_jsfunction_onclick,
                $button_text);                                  // $button_text
            $row = array(
               "<a id='language_token:".$language['token']."' style='color:".$color_token_upper_case.";pointer-events:".$pointer_events_token_upper_case.";' onclick='DisableButtonsAndSpinTopbarButton(". json_encode($language['token']).")' "
                . "href='?lang=".$language['token'].$this->UTILITIES->query_string."'>"
                .$language['token_upper_case']."</a> "
             . "<a id='language:".$language['token']."' style='color:".$color_language.";'>"
                    .$this->UTILITIES->Translate($language['language'])
             ." </a>",
              "<a id='languageconfigurationtablespinner:".$language['token']."' style='display:none;' class='w3-margin-right'></a>".$button);
            $table[] = $row; unset($row); unset($color_token_upper_case); unset($color_language); unset($color_button); unset($pointer_events_token_upper_case); unset($pointer_events_button); 
       }
       return $table;
    }
    
    function ButtonJsFunctionOnClick(
            $button_id,
            $pointer_events,
            $color,
            $button_jsfunction_onclick,
            $button_text
    ) {
        $string =    "<a "
                        . "id='".$button_id."' "
                        . "style='display:inline;pointer-events:".$pointer_events.";color:".$color.";' "
                        . "class='w3-button w3-round-large  w3-border w3-animate-opacity' "
                        . "onclick='".$button_jsfunction_onclick."'>"
                        . $button_text
                    ."</a>";
        return $string;
    }
    
}
