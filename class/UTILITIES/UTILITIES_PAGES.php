<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES_PAGES1
 *
 * @author rene
 */
class UTILITIES_PAGES {
    
    function __construct (
            $UTILITIES,
            $UTILITIES_ADMINISTRATION,
            $UTILITIES_LANGUAGES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->UTILITIES_ADMINISTRATION = $UTILITIES_ADMINISTRATION;
        $this->UTILITIES_LANGUAGES = $UTILITIES_LANGUAGES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_LOGIN = $UTILITIES->UTILITIES_LOGIN;
        $this->toroot = $UTILITIES->toroot;
        $this->raspberry_emulated = $UTILITIES->raspberry_emulated; 
    }
    
    function Sidebar() {
        if(!$this->raspberry_emulated
            or ($this->raspberry_emulated and $this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn())) {
            $string =   "<button class='w3-bar-item w3-button w3-large' onclick='w3_close()'>&times</button>"
                . "<a href='".$this->toroot."/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'                             class='w3-bar-item w3-button'><i class='fa fa-home'></i></a>"
                . "<a href='".$this->toroot."/pages/RASPBERRY_Dashboard.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'         class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Dashboard')."</a>"
                . "<a href='".$this->toroot."/pages/RASPBERRY_Users.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'             class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Users')."</a>"
                . "<a href='".$this->toroot."/pages/RASPBERRY_Languages.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'         class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Languages')."</a>"
                . "<a href='".$this->toroot."/pages/RASPBERRY_Ping.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'              class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Ping / Traceroute')."</a>"         
                . "<a href='".$this->toroot."/pages/RASPBERRY_LocalResources.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'    class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Devices')."</a>"
                . "<a href='".$this->toroot."/pages/RASPBERRY_Samba.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'             class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Samba')."</a>"
                . "<a href='".$this->toroot."/pages/RASPBERRY_Email.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'             class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Email')."</a>"
                . "<button class='w3-button w3-block w3-left-align' onclick='myAccFunc()'>SSH <i class='fa fa-caret-down'></i></button>"
                . "<div id='Accordion' class='w3-hide w3-white w3-car'>"
                    . "<a href='".$this->toroot."/pages/RASPBERRY_SshSettings.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'       class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('SSH Settings')."</a>"
                    . "<a href='".$this->toroot."/pages/RASPBERRY_SshKeys.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'           class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('SSH Keys Summary')."</a>"
                    . "<a href='".$this->toroot."/pages/RASPBERRY_SshAuthorizedKeys.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."' class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('SSH Authorized Keys')."</a>"
                    . "<a href='".$this->toroot."/pages/RASPBERRY_SshUserKeys.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'       class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('SSH User Keys')."</a>"
                    . "<a href='".$this->toroot."/pages/RASPBERRY_SshKnownHosts.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'     class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('SSH Known Hosts')."</a>"
                    . "<a href='".$this->toroot."/pages/RASPBERRY_SshHostKeys.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'       class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('SSH Host Keys')."</a>"
                . "</div>"
                . "<a href='".$this->toroot."/pages/RASPBERRY_Administration.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'    class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Admin')."</a>"
              . $this->GetLogoutStartQuitDemoSidebarItem();
        } else {
            $string =   "<button class='w3-bar-item w3-button w3-large' onclick='w3_close()'>&times</button>"
              . "<a href='".$this->toroot."/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string."'class='w3-bar-item w3-button'><i class='fa fa-home'></i></a>"
              . $this->GetLogoutStartQuitDemoSidebarItem();
        }
        return $string;
    }
    
    function GetLogoutStartQuitDemoSidebarItem() {
        if($this->raspberry_emulated and $this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn()) {
            $string = "<a onclick='QuitDemo(".json_encode($this->UTILITIES->lang).", ".json_encode($this->toroot).")' class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Quit demo')."</a>";
        } elseif($this->raspberry_emulated and !$this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn()) {
            $string = "<a onclick='StartDemo(".json_encode($this->UTILITIES->lang).", ".json_encode($this->toroot).")' class='w3-bar-item w3-button'>".$this->UTILITIES->Translate('Start demo')."</a>";
        }
        if(!$this->raspberry_emulated and 
            $this->UTILITIES_ADMINISTRATION->IsSetAdministratorPassword() and
            $this->UTILITIES_LOGIN->IsThisRASPBERRYUserSignedIn()) {
            $string = "<a id='sidebar_logout' onclick='LoadLoginPage("
                    . json_encode($this->toroot."/pages/RASPBERRY_Login.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string).")' "
                    . "class='w3-bar-item w3-button'>"
                    . $this->UTILITIES->Translate('Logout')
                    . "</a>";
        }
        return $string;
    }
    
    function TopBarSignInSignOutLanguageButtons() {
        $this->RedirectAnonymousUser();
        $string =   "<div class='w3-teal w3-cell-row'>"
                      . "<div class='w3-cell w3-cell-middle'>"
                          . "<button id='openNav' class='w3-button w3-teal w3-xlarge w3-hover-grey' onclick='w3_open()'>&#9776; RASPserver<font size='0'>.com</font></button>"
                      . "</div>"
                      . "<div class='w3-container w3-cell w3-cell-middle'>"
                          . "<div class='w3-row'>"
                              . "<div class='w3-cell w3-cell-middle w3-small'>"
                                  . "<div class='w3-col' style='width:102px;'>"   
                                      . $this->TopBarButton()
                                  . "</div>"
                              . "</div>"
                              . "<div class='w3-cell w3-cell-middle'>"
                                  . "<div class='w3-rest'>".$this->LanguageButtons()."</div>"
                              . "</div>"
                          . "</div>"
                      . "</div>"
                  . "</div>";
        return $string;
    }
    
    function TopBarButton() {
        if(!$this->raspberry_emulated) {
            if(!($this->UTILITIES_ADMINISTRATION->IsSetAdministratorPassword()
                and $this->UTILITIES_LOGIN->IsThisRASPBERRYUserSignedIn())) {
                $display = "none";
            }
            $string = $this->BUTTONS->ButtonJsFunctionOnClick(
                        "IdButtonTopbar",
                        $display, "", "", "",
                        "LoadLoginPage(".json_encode($this->toroot."/pages/RASPBERRY_Login.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string).")",
                        "Logout");
        } else {
            $string = $this->StartEndDemoButton();
        }
        return $string;
    }
    
    function StartEndDemoButton() {
        if($this->UTILITIES->IsThisPageThisPage("/pages/RASP_Home.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string)) {
            $string = $this->BUTTONS->ButtonJsFunctionOnClick(
                        "IdButtonTopbar",
                        "none", "", "", "",
                        "StartDemo(".json_encode($this->UTILITIES->lang).", ".json_encode($this->toroot).")",
                        $this->UTILITIES->Translate("Demo"));
        } elseif($this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn()) {
            $string = $this->BUTTONS->ButtonJsFunctionOnClick(
                        "IdButtonTopbar",
                        "", "", "", "",
                        "QuitDemo(".json_encode($this->UTILITIES->lang).", ".json_encode($this->toroot).")",
                         $this->UTILITIES->Translate("Quit"));
        } else {
            $string = $this->BUTTONS->ButtonJsFunctionOnClick(
                        "IdButtonTopbar",
                        "none", "", "", "",
                        "",
                        "");
        }
        return $string;
    }
    
    function RedirectAnonymousUser() {
        if(!$this->raspberry_emulated) {
            if(($this->UTILITIES_ADMINISTRATION->IsSetAdministratorPassword()
                and !$this->UTILITIES_LOGIN->IsThisRASPBERRYUserSignedIn()
                and !$this->UTILITIES->IsThisPageThisPage("/pages/RASPBERRY_Login.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string)
                and !$this->UTILITIES->IsThisPageThisPage("/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string))
                ) {
                $href_login = $this->toroot."/pages/RASPBERRY_Login.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string;
                echo "<script>window.location.href = '".$href_login."';</script>";
            }
        } else {
            if((!$this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn()
                and !$this->UTILITIES->IsThisPageThisPage("/pages/RASP_Home.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string)
                and !$this->UTILITIES->IsThisPageThisPage("/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string))
                ) {
                $href_login = $this->toroot."/pages/RASP_Home.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string;
                echo "<script>window.location.href = '".$href_login."';</script>";
            }
        }
    }
    
    function LanguageButtons() {
        $languagesset = $this->UTILITIES_LANGUAGES->GetLanguagesSet();
        if(count($languagesset) > 1) {
            foreach($languagesset as $language) {
                $string .= "<a id='lang_".$language['token']."' "
                        . "onclick='DisableButtonsAndSpinTopbarButton(".json_encode($language['token']).")' "
                        . "href='?lang=".$language['token'].$this->UTILITIES->query_string."'";
                if($this->UTILITIES->lang === $language['token']) {
                    $string .= " style='color:orange;'";
                    $this->UTILITIES_LANGUAGES->SetLanguage($language['token']);
                }
                $string .= ">".$language['token_upper_case']."</a>"." ";
            }
        }
        return trim($string);
    }
    
    function NavigationBar($name, $button_href, $button_text) {
        $string =   "<div class='w3-bar w3-gray w3-border w3-border-dark-gray w3-padding'>"
                      . "<div class='w3-bar-item'>".$name."</div>"
                      . "<a id='IdButtonNavigationBar' href='".$this->toroot.$button_href."' "
                            . "onclick='DisableButtonsAndSpinThisButton("
                                . json_encode("IdButtonNavigationBar").")'"
                            . "class='w3-bar-item w3-right w3-button w3-round-large w3-dark-gray'>"
                            . $button_text
                      . "</a>"
                  . "</div>";
        return $string;
    }
    
    
    
}
