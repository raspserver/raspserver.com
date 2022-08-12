<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of PING
 *
 * @author rene
 */
class PING {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_PING = new UTILITIES_PING($UTILITIES, $BUTTONS);
        $this->toroot = $UTILITIES->toroot;
        $this->domains = $this->UTILITIES_PING->GetDomains();
    }
    
    function DisplayPingTable($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("Ping")." / ".$this->UTILITIES->Translate("Traceroute");
        $header = array($this->UTILITIES->Translate("Sites"));
        foreach($this->domains as $domain) {
            $table[] = array($domain['value']);
        }
        $footer = $this->ButtonToProvidePingingTable();
        $TABLEOFPINGDOMAINS = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFPINGDOMAINS->DisplayTableOnCard($w3_responsive_class, "");
        return $string;
    }

    function ButtonToProvidePingingTable() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToPinging",
            $this->toroot."/pages/RASPBERRY_Ping.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToPinging").")",
            $this->UTILITIES->Translate("Let's do this!")                                                                 // $button_text
        );
        return $string;
    }
    
    function ConfigureDomains() {
        $string = "<div id='ConfigureDomainsTableParentNode'>".$this->ConfigureDomainsChildNode("ping")."</div>";
        $string .= "<a class='w3-margin-left'><a class='w3-margin-left'>".$this->ButtonToDashboard()."</a></a>";
        return $string;
    }
    
    function ConfigureDomainsChildNode($ping_or_traceroute) {
        $CONSOLE_PANEL = new CONSOLE_PANEL(
                $this->UTILITIES,                                   // $UTILITIES,
                "",                                                 // $console_panel_id
                "",                                                 // $console_panel_display
                "",                                                 // $name_display
                $this->TogglePingOrTraceroute($ping_or_traceroute), // $name
                $this->FieldLeftDomains($ping_or_traceroute),       // $field_left
                "asterisk",                                         // $user
                "raspbx",                                           // $host
                "IdConsoleOutput",                                  // $idconsole
                "IdButtonClearConsole",                             // $idclearconsolebutton ); 
                "ClearConsoleClearEntryAndUnbold("                  // $JSfunctionclearconsolebutton
                    . json_encode("").", "                              // href
                    . json_encode("asterisk").", "                      // user
                    . json_encode("raspbx").", "                        // host
                    . json_encode("IdConsoleOutput").", "               // idconsole
                    . json_encode("IdEntry").", "                       // identry
                    . json_encode("IdButtonDomain").")");               // iddomainlist    
        $string =  "<div id='ConfigureDomainsTableChildNode'>".$CONSOLE_PANEL->ConsolePanel()."</div>";
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
    
    function TogglePingOrTraceroute($ping_or_traceroute) {
        if($this->UTILITIES->raspberry_emulated) { $pointer_events = "none"; }
        if($ping_or_traceroute === "ping") { $button_display_ping = "inline"; $button_display_traceroute = "none"; }
        else                               { $button_display_ping = "none"; $button_display_traceroute = "inline"; }
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonTogglePing", $button_display_ping, $pointer_events, "", "",                        // $button_id, ...display, $pointer_events, $color, $class_attribute
            "TogglePingOrTraceroute("                                                                   // JSFunctipononclick
                . json_encode("IdButtonTogglePing").", ".json_encode("IdButtonToggleTraceroute").", "   // id_button_toggle_ping, id_button_toggle_traceroute
                . json_encode("IdButtonDomainping:").", ".json_encode("IdButtonDomaintraceroute:").", " // id_button_domain_ping, id_button_domain_traceroute
                . json_encode("IdInputAndLabelPing").", ".json_encode("IdInputAndLabelTraceroute").", " // id_entry_ping, id_entry_traceroute     
                . json_encode("IdButtonPing").", ".json_encode("IdButtonTraceroute").")",               // id_button_ping, id_button_traceroute
            $this->UTILITIES->Translate("Ping"))                                                        // $button_text
          . $this->BUTTONS->ButtonJsFunctionOnClick(                                                   // JSFunctipononclick
            "IdButtonToggleTraceroute", $button_display_traceroute, $pointer_events, "", "",            // $button_id, ...display, $pointer_events, $color, $class_attribute
            "TogglePingOrTraceroute("                                                                   // JSFunctipononclick
                . json_encode("IdButtonTogglePing").", ".json_encode("IdButtonToggleTraceroute").", "   // id_button_toggle_ping, id_button_toggle_traceroute
                . json_encode("IdButtonDomainping:").", ".json_encode("IdButtonDomaintraceroute:").", " // id_button_domain_ping, id_button_domain_traceroute
                . json_encode("IdInputAndLabelPing").", ".json_encode("IdInputAndLabelTraceroute").", " // id_entry_ping, id_entry_traceroute  
                . json_encode("IdButtonPing").", ".json_encode("IdButtonTraceroute").")",               // id_button_ping, id_button_traceroute
            $this->UTILITIES->Translate("Traceroute"));                                                 // $button_text
        return $string;
    }
    
    function FieldLeftDomains($ping_or_traceroute) {
        if($ping_or_traceroute === "ping") {
            $input_and_label_ping_display = null; $button_display_ping = null;
            $input_and_label_traceroute_display = "none"; $button_display_traceroute = "none";
        } else {
            $input_and_label_ping_display =  "none"; $button_display_ping = "none";
            $input_and_label_traceroute_display =  null; $button_display_traceroute = null;
        }
        $string = $this->ListOfDomains($ping_or_traceroute)
                . $this->BUTTONS->OneInputOneButton(
                    "IdInputAndLabelPing" , $input_and_label_ping_display, "IdEntryping",    // $input_and_label_id, $input_and_label_display, $input_id
                    $this->UTILITIES->Translate("Domain"),              // $input_label
                    "IdButtonPing",                                         // $button_id
                    $button_display_ping, "", "", "",                       // $button_display, $pointer_events, $color, $class_attribute
                    "PingOrTracerouteThisEntry("
                        . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandRealtimeOutput.php").", " // AjaxFileExecut
                        . json_encode($this->UTILITIES->toroot."/ajax/AddDomainToListAndGetRefreshedDomainList.php").", " 
                        . json_encode($_SESSION["key"]).", "                      // authorization_key
                        . json_encode("asterisk").", "                            // user
                        . json_encode('raspbx').", "                              // host
                        . json_encode("IdButtonTogglePing").", "                  // idButtonToggle
                        . json_encode("IdButtonDomain").", "                      // iddomainlist
                        . json_encode("IdEntryping").", "                         // identry
                        . json_encode("IdConsoleOutput").", "                     // idconsole
                        . json_encode("IdButtonClearConsole").", "                // IdButtonClearConsole,
                        . json_encode("ping -w 10 -c 4 ").","                     // commandwithoutdomain
                        . json_encode($this->UTILITIES->raspberry_emulated).")", 
                    $this->UTILITIES->Translate("Ping this domain"))
                . $this->BUTTONS->OneInputOneButton(
                    "IdInputAndLabelTraceroute" , $input_and_label_traceroute_display, "IdEntrytraceroute",    // $input_and_label_id, $input_and_label_display, $input_id
                    $this->UTILITIES->Translate("Domain"),                // $input_label
                    "IdButtonTraceroute",                                           // $button_id
                    $button_display_traceroute, "", "", "",                         // $button_display, $pointer_events, $color, $class_attribute
                    "PingOrTracerouteThisEntry("
                        . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandRealtimeOutput.php").", " // AjaxFileExecut
                        . json_encode($this->UTILITIES->toroot."/ajax/AddDomainToListAndGetRefreshedDomainList.php").", " 
                        . json_encode($_SESSION["key"]).", "                      // authorization_key
                        . json_encode("asterisk").", "                            // user
                        . json_encode('raspbx').", "                              // host
                        . json_encode("IdButtonToggleTraceroute").", "            // idButtonToggle
                        . json_encode("IdButtonDomain").", "                      // iddomainlist
                        . json_encode("IdEntrytraceroute").", "                   // identry
                        . json_encode("IdConsoleOutput").", "                     // idconsole
                        . json_encode("IdButtonClearConsole").", "                // IdButtonClearConsole,
                        . json_encode("traceroute ").","                          // commandwithoutdomain
                        . json_encode($this->UTILITIES->raspberry_emulated).")", 
                    $this->UTILITIES->Translate("Traceroute this domain"));
        return $string;
    }
    
    function ListOfDomains($ping_or_traceroute) {
        $string = "<div  class='w3-card'>"
                    . "<div class='w3-responsive'>"
                        . "<nobr>"
                            . "<ul class='w3-ul'>";
        foreach($this->domains as $domain) {
                      $string .= $this->CreateDomainListItem($domain['value'], $ping_or_traceroute);
        }       
                $string .=    "</ul>"
                        . "</nobr>"
                    . "</div>"
                . "</div>";
        return $string;
    }
    
    function CreateDomainListItem($domain, $ping_or_traceroute) {
        if($ping_or_traceroute === "ping") {
            $button_display_ping = "inline";
            $button_display_traceroute = "none";
        } else {
            $button_display_ping = "none";
            $button_display_traceroute = "inline";
        }
        $string =  "<li class='w3-display-container'>"
                      . $this->BUTTONS->ButtonJsFunctionOnClick(
                            "IdButtonDomainping:".$domain,                          // $button_id
                            $button_display_ping, "", "", "",                       // $button_display, $pointer_events, $color, $class_attribute
                            "PingOrTracerouteThisDomain("                           // $button_jsfunction_onclick
                              . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandRealtimeOutput.php").", " // AjaxFileExecut
                              . json_encode($this->UTILITIES->toroot."/ajax/AddDomainToListAndGetRefreshedDomainList.php").", " 
                              . json_encode($_SESSION["key"]).", "                      // authorization_key
                              . json_encode("asterisk").", "                            // user
                              . json_encode('raspbx').", "                              // host
                              . json_encode("IdButtonTogglePing").", "                  // idButtonToggle
                              . json_encode("IdButtonDomain").", "                      // iddomainlist
                              . json_encode("IdEntryping").", "                         // identry
                              . json_encode("IdConsoleOutput").", "                     // idconsole
                              . json_encode("IdButtonClearConsole").", "                // IdButtonClearConsole,
                              . json_encode("").", "                                    // newdomain
                              . json_encode("ping -w 10 -c 4 ".$domain).","             // command  
                              . json_encode($this->UTILITIES->raspberry_emulated).")",  // raspberry_emulated                        
                            $domain)                                                // $button_text
                     . $this->BUTTONS->ButtonJsFunctionOnClick(
                            "IdButtonDomaintraceroute:".$domain,                    // $button_id
                            $button_display_traceroute, "", "", "",                 // $button_display, $pointer_events, $color, $class_attribute
                            "PingOrTracerouteThisDomain("                           // $button_jsfunction_onclick
                              . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandRealtimeOutput.php").", " // AjaxFileExecut
                              . json_encode($this->UTILITIES->toroot."/ajax/AddDomainToListAndGetRefreshedDomainList.php").", " 
                              . json_encode($_SESSION["key"]).", "                      // authorization_key
                              . json_encode("asterisk").", "                            // user
                              . json_encode('raspbx').", "                              // host
                              . json_encode("IdButtonToggleTraceroute").", "            // idButtonToggle
                              . json_encode("IdButtonDomain").", "                      // iddomainlist
                              . json_encode("IdEntrytraceroute").", "                   // identry
                              . json_encode("IdConsoleOutput").", "                     // idconsole
                              . json_encode("IdButtonClearConsole").", "                // IdButtonClearConsole,
                              . json_encode("").", "                                    // newdomain
                              . json_encode("traceroute ".$domain).","                  // command  
                              . json_encode($this->UTILITIES->raspberry_emulated).")",  // raspberry_emulated                              
                            $domain)                                                // $button_text   
                     . "<span id='IdButtonSpan:".$domain."' onclick='DeleteItemFromListGetRefreshedDomainList("   // 
                                        . json_encode($this->UTILITIES->toroot."/ajax/RemoveDomainFromListAndGetRefreshedDomainList.php").", "                          
                                        . json_encode($_SESSION["key"]).", "            //
                                        . json_encode($domain).", "                     //
                                        . json_encode("IdButtonDomain").", "            // iddomainlist
                                        . json_encode("IdConsoleOutput").", "           // idconsole
                                        . json_encode("IdButtonTogglePing").")' "       // idButtonToggleping
                     . "class='w3-button w3-display-right'>&times;</span>"              
                 . "</li>";
        return $string;
    }
    
    
    
}
