<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of SAMBA
 *
 * @author rene
 */
class SAMBA {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_SAMBA = new UTILITIES_SAMBA($UTILITIES, $BUTTONS);
        $this->samba_shares = $UTILITIES->RASPBERRY->GetSambaShares();
        $this->network_interfaces = $UTILITIES->RASPBERRY->GetNetworkInterfaces();
    }
    
    function DisplaySambaShares($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("Samba Shares");
        $header = array($this->UTILITIES->Translate("Samba Share"),
            $this->UTILITIES->Translate("Path"),
            $this->UTILITIES->Translate("Writable"),
            $this->UTILITIES->Translate("Valid Users"));
        foreach($this->samba_shares as $samba_share) {
            if(!$this->UTILITIES->RASPBERRY->DoesDirectoryExist($samba_share["path"])) {
                $color = "red";
            }
            $table[] = array(
                "//".$this->network_interfaces[0]["ip_address"]."/".$samba_share["share"],
                "<a style='color:".$color.";'>".$samba_share["path"]."</a>",
                $samba_share["writeable"],
                $this->PrepareValidUsersNicely($samba_share["valid users"])
            );
            sort($table);
            unset($color);
        }
        $footer = $this->ButtonToConfigureSambaSharesTable();
        $TABLEOFUNIXUSERS = new TABLE($name, $header, $table, $footer);
        $string = $TABLEOFUNIXUSERS->DisplayTableOnCard($w3_responsive_class, "w3-right-align");
        return $string;
    }
    
    function PrepareValidUsersNicely($valid_users) {
        foreach($valid_users as $valid_user) {
            $string .= ",".$valid_user;
        }
        return substr($string, 1);
    }
    
    function ButtonToConfigureSambaSharesTable() {
        $string = $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToSambaSharesConfiguration",                                                                               // $button_id
            $this->UTILITIES->toroot."/pages/RASPBERRY_Samba.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,  // $button_href    
            "DisableButtonsAndSpinThisButton(".json_encode("IdButtonLinkedToSambaSharesConfiguration").")",                           // $button_jsfunction_onclick
            $this->UTILITIES->Translate("Configure")                                                                          // $button_text
        );
        return $string;
    }
    
    function ConfigureSambaShares() {
        $string = "<div id='ConfigureSambaSharesTableParentNode'>".$this->ConfigureSambaSharesChildNode()."</div>";
        return $string;
    }
    
    function ConfigureSambaSharesChildNode() {
        $name = $this->UTILITIES->Translate("Samba Shares");
        $header = array($this->UTILITIES->Translate("Samba Share"),
            $this->UTILITIES->Translate("Path"),
            $this->UTILITIES->Translate("Writable"),
            $this->UTILITIES->Translate("Valid Users"),
            $this->UTILITIES->Translate("Action"));
        
        foreach($this->samba_shares as $samba_share) {
            if(!$this->UTILITIES->RASPBERRY->DoesDirectoryExist($samba_share["path"])) {
                $color = "red";
            }
            $table[] = array(
                "<a id='idshare:".$samba_share["share"]."'>//".$this->network_interfaces[0]["ip_address"]."/".$samba_share["share"]."</a>",
                "<a id='idpath:".$samba_share["share"]."' style='color:".$color.";'>".$samba_share["path"]."</a>",
                "<a id='idwritable:".$samba_share["share"]."'>".$samba_share["writeable"]."</a>",
                "<a id='idvalidusers:".$samba_share["share"]."'>".$this->PrepareValidUsersNicely($samba_share["valid users"])."</a>",
                $this->RemoveSambaShareButton($samba_share["share"])." "
              . $this->ConfigureSambaShareButton($samba_share["share"])
            );
            sort($table);
            unset($color);
        }
        $footer = $this->ButtonToDashboard()." ".$this->AddSambaShareButton().$this->HiddenConsolePanels();
        $CONFIGURESAMBASHARESTABLE = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureSambaSharesTableChildNode'>".$CONFIGURESAMBASHARESTABLE->DisplayTableOnCard("w3-row", "w3-right-align")."</div>";
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
    
    function AddSambaShareButton() {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonAddSambaShare", "", "", "", "", "AddSambaShare("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/SAMBA_DoesFolderExist.php").", " 
                . json_encode($this->UTILITIES->toroot."/ajax/SAMBA_AddShare.php").", " 
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/SAMBA_LoadConfigureSambaSharesTable.php").", "         // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "
                . json_encode("IdButtonAddSambaShare").", "                                                               // idbuttonadduser
                . json_encode("IdConsolePanelAddSambaShare").", "                                                             // idpanel
                . json_encode("IdEntryNewShare").", "                                                               // identry1
                . json_encode("IdEntryNewPath").", "
                . json_encode("WritableNo1").", "                                                        
                . json_encode("WritableYes1").", " 
                . json_encode("IdButtonCancelAddSambaShare").", "                                                         // idcancelbutton
                . json_encode("IdButtonAddSambaShareNow").", "                                                            // idconfirmbutton
                . json_encode("IdButtonClearConsoleAddSambaShare").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputAddSambaShare").", "                                                            // idconsole
                . json_encode("ConfigureSambaSharesTableParentNode").", "
                . json_encode("ConfigureSambaSharesTableChildNode").", "
                . json_encode($this->UTILITIES->RASPBERRY->GetNetworkInterfaces()[0]["ip_address"]).","
                . json_encode($this->UTILITIES->Translate("invalid sharename")).","
                . json_encode($this->UTILITIES->Translate("invalid path")).")",                                         // 
            $this->UTILITIES->Translate("Add samba share"));
        return $string;
    }
    
    function RemoveSambaShareButton($sambashare) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonRemoveSambaShare:".$sambashare, "inline", "", "", "", "RemoveSambaShare("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/SAMBA_DeleteShare.php").", "  
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/SAMBA_LoadConfigureSambaSharesTable.php").", "         // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "
                . json_encode("IdButtonAddSambaShare").", "                                                               // idbuttonadduser
                . json_encode("IdConsolePanelRemoveSambaShare").", "                                                             // idpanel
                . json_encode("IdButtonCancelRemoveSambaShare").", "                                                         // idcancelbutton
                . json_encode("IdButtonRemoveSambaShareNow").", "                                                            // idconfirmbutton
                . json_encode("IdButtonClearConsoleRemoveSambaShare").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputRemoveSambaShare").", "                                                            // idconsole
                . json_encode("ConfigureSambaSharesTableParentNode").", "
                . json_encode("ConfigureSambaSharesTableChildNode").","
                . json_encode("idshare:".$sambashare).","
                . json_encode($sambashare).")",                            // 
            $this->UTILITIES->Translate("Remove"));                                                     // $button_text
        return $string;
    }
    //user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, 
    
    function ConfigureSambaShareButton($sambashare) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonConfigureSambaShare:".$sambashare, "inline", "", "", "", "ConfigureSambaShare("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/SAMBA_DeleteShareAndCreateNew.php").", "  
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/SAMBA_LoadConfigureSambaSharesTable.php").", "         // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "
                . json_encode("IdButtonAddSambaShare").", "                                                               // idbuttonadduser
                . json_encode("IdConsolePanelConfigureSambaShare").", "                                                             // idpanel
                . json_encode("WritableNo2").", "                                                        
                . json_encode("WritableYes2").", " 
                . json_encode("IdButtonCancelConfigureSambaShare").", "                                                         // idcancelbutton
                . json_encode("IdButtonConfigureSambaShareNow").", "                                                            // idconfirmbutton
                . json_encode("IdButtonClearConsoleConfigureSambaShare").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputConfigureSambaShare").", "                                                            // idconsole
                . json_encode("ConfigureSambaSharesTableParentNode").", "
                . json_encode("ConfigureSambaSharesTableChildNode").", "
                . json_encode("idshare:".$sambashare).","
                . json_encode($sambashare).","
                . json_encode("idwritable:".$sambashare).","
                . json_encode("idvalidusers:".$sambashare).","
                . json_encode("idpath:".$sambashare).")",                                  // 
            $this->UTILITIES->Translate("Configure"));                                      // $button_text
        return $string;
    }
    
    function HiddenConsolePanels() {
        $string = $this->UTILITIES_SAMBA->HiddenConsolePanelAddSambaShare();
        $string .= $this->UTILITIES_SAMBA->HiddenConsolePanelRemoveSambaShare();
        $string .= $this->UTILITIES_SAMBA->HiddenConsolePanelConfigureSambaShare();
        return $string;
    }
    
}
