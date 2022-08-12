<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of PAGES
 *
 * @author rene
 */
class PAGES {
    
    function __construct(
            
    ) {
        $this->BUTTONS = new BUTTONS();
        $this->UTILITIES =                          new UTILITIES();
        $this->NETWORK_INTERFACES =                 new NETWORK_INTERFACES(
                                                            $this->UTILITIES);
        $this->UTILITIES_LANGUAGES =                new UTILITIES_LANGUAGES(
                                                            $this->UTILITIES);
        $this->LANGUAGES =                          new LANGUAGES(
                                                            $this->UTILITIES, 
                                                            $this->UTILITIES_LANGUAGES, 
                                                            $this->BUTTONS);
        $this->UNIXUSERS =                          new UNIXUSERS(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->UTILITIES_ADMINISTRATION =           new UTILITIES_ADMINISTRATION(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->ADMINISTRATION =                     new ADMINISTRATION(
                                                            $this->UTILITIES,
                                                            $this->UTILITIES_ADMINISTRATION,
                                                            $this->BUTTONS);
        $this->LOGIN =                              new LOGIN(
                                                            $this->UTILITIES,
                                                            $this->UTILITIES_ADMINISTRATION,
                                                            $this->BUTTONS);
        $this->LOCAL_RESOURCES =                    new LOCAL_RESOURCES(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->EMAIL =                              new EMAIL(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->SAMBA =                              new SAMBA(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->SITES =                              new SITES(
                                                            $this->UTILITIES);
        $this->SOFTWARE =                           new SOFTWARE(
                                                            $this->UTILITIES);
        $this->AUTOSSH =                            new AUTOSSH(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->SSH_AUTHORIZED_KEYS =                new SSH_AUTHORIZED_KEYS(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->SSH_HOST_KEYS =                      new SSH_HOST_KEYS(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->SSH_KNOWN_HOSTS =                    new SSH_KNOWN_HOSTS(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->SSH_SETTINGS =                       new SSH_SETTINGS(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->SSH_USER_KEYS =                           new SSH_USER_KEYS(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->PING =                               new PING(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->SETTINGS_SSH =                       new SSH_SETTINGS(
                                                            $this->UTILITIES,
                                                            $this->BUTTONS);
        $this->UTILITIES_PAGES =                    new UTILITIES_PAGES(
                                                            $this->UTILITIES, 
                                                            $this->UTILITIES_ADMINISTRATION, 
                                                            $this->UTILITIES_LANGUAGES, 
                                                            $this->BUTTONS);
        $this->toroot = $this->UTILITIES->toroot;
        $this->raspberry_emulated = $this->UTILITIES->raspberry_emulated;
        $this->UTILITIES_LOGIN = $this->UTILITIES->UTILITIES_LOGIN;
    }
    
    function Home() {
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        $href = $this->RedirectUser();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
                . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
                . "<a onclick='DisableButtonsAndSpinTopbarButton()' href='".$href."'><img src='./img/raspberry-pi-1719219_1920.jpg' "
                            . "style='width:100%'></a>"
            . "</div>"
            . "<footer class='w3-container w3-teal w3-center'>"
                . "<h10>info@raspserver.com</h10>"
            . "</footer>"; 
    }
    
    function RedirectUser() {
        if(!$this->raspberry_emulated) {
            if(!$this->UTILITIES_ADMINISTRATION->IsSetAdministratorPassword()){
                return $this->toroot."/pages/RASPBERRY_Dashboard.php?lang="
                .$this->UTILITIES->lang.$this->UTILITIES->query_string;
            } elseif($this->UTILITIES_LOGIN->IsThisRASPBERRYUserSignedIn()) {
                return $this->toroot."/pages/RASPBERRY_Dashboard.php?lang="
                .$this->UTILITIES->lang.$this->UTILITIES->query_string;
            } else {
                return $this->toroot."/pages/RASPBERRY_Login.php?lang="
                .$this->UTILITIES->lang.$this->UTILITIES->query_string;
            }
        } else {
            if($this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn()) {
                return $this->toroot."/pages/RASPBERRY_Dashboard.php?lang="
                .$this->UTILITIES->lang.$this->UTILITIES->query_string;
            } else {
                return $this->toroot."/pages/RASP_Home.php?lang="
                .$this->UTILITIES->lang.$this->UTILITIES->query_string;
            }
        }
    }
    
    function RASP_Home() {
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                .$this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
                . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
                . $this->UTILITIES_PAGES->NavigationBar(
                    "RASPserver Homepage",
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
            . "<div class='w3-display-container w3-center' style='height:300px;'>"
                . "<div class='w3-display-middle'>"
                    . "<br><br>Raspberry Pi OS Lite Debian version: 11 (bullseye)<br>"
                    . "FreePBX 16.0.19<br>"
                    . "Asterisk version: 19.3.1<br>"
                    . "Dovecot IMAP mail server version 2.3.13<br>"
                    . "Roundcube webmail version 1.5.2<br>"
                    . "Baïkal CalDAV+CardDAV server version 0.9.2<br>"
                    . "<br>".$this->UTILITIES->Translate("Image download coming soon")
                    . "<br><br>".$this->BUTTONS->ButtonJsFunctionOnClick(
                        "IdButtonLogin",
                        "", "", "", "",
                        "StartDemo(".json_encode($this->UTILITIES->lang).", ".json_encode($this->toroot).")",
                        $this->UTILITIES->Translate("Start demo"))
                    . "</div>"
                . "</div>"
            . "</div>";
    }
    
    function RASPBERRY_Login() {
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                .$this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
                . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
                . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("Login"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                    . "<div w3-center'>";
                        if(!$this->raspberry_emulated) {
                            $this->UTILITIES->UTILITIES_LOGIN->DeleteCurrentSessionId();
                            echo "<script>document.getElementById('sidebar_logout').style.display = 'none';document.getElementById('IdButtonTopbar').style.display = 'none';</script>";
                            $this->LOGIN->DisplayLoginButton("w3-row");
                        }
        echo          "</div>"
                . "</div>"
            . "</div>";
    }
    
    function RASPBERRY_Dashboard() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                    . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                .$this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
                . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
                . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("Dashboard"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")                             
                . $this->NETWORK_INTERFACES->DisplayNetworkInterfaces("w3-row")
                . $this->UNIXUSERS->DisplayUnixUsers("w3-third")
                . $this->LANGUAGES->DisplayLanguages("w3-third")
                . $this->PING->DisplayPingTable("w3-third")
                . $this->LOCAL_RESOURCES->DisplayLocalResources("w3-twothird")
                . $this->LOCAL_RESOURCES->DisplayMountsTable("w3-third")
                . $this->SAMBA->DisplaySambaShares("w3-row")
                . $this->EMAIL->DisplayEmailTable("w3-row")
                . $this->AUTOSSH->DisplayAutoSshPortForwardings("w3-twothird")
                . $this->SOFTWARE->DisplaySoftware("w3-third")
//                . $this->SITES->DisplaySites("w3-twothird")
                . $this->SSH_SETTINGS->DisplaySettingsSsh("w3-row")
//                . $this->ADMINISTRATION->DisplaySettings("w3-third") 
            . "</div>";
        
        
    }
    
    function RASPBERRY_Users() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                    . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
                . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
                . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("Raspberry Pi Users"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->UNIXUSERS->ConfigureUnixUsers()
            . "</div>";
    }
    
    function RASPBERRY_Languages() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                .$this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
                . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
                . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("Languages"),
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->LANGUAGES->ConfigureLanguages()
            . "</div>";
    }
    
    function RASPBERRY_Ping() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
                . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
                . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("Ping"),
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->PING->ConfigureDomains()
            . "</div>";
    }
    
    function RASPBERRY_LocalResources() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
               . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
               . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("Local Resources"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->LOCAL_RESOURCES->ConfigureLocalResources()
            . "</div>";
    }
    
    function RASPBERRY_Samba() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
               . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
               . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("Samba"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->SAMBA->ConfigureSambaShares()
            . "</div>";
    }
    
    function RASPBERRY_Email() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
               . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
               . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("Email"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->EMAIL->ConfigureEmail()
            . "</div>";  
    }
    
    function RASPBERRY_SettingsSsh() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
                . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
                . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("SSH Settings"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->SETTINGS_SSH->ConfigureSettingsSsh()
            . "</div>";
    }
    
    function RASPBERRY_SshKeys() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
               . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
               . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("SSH Keys Summary"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->SSH_AUTHORIZED_KEYS->DisplayAuthorizedKeysTable("w3-row")
                . $this->SSH_USER_KEYS->DisplayUserKeysTable("w3-row") 
                . $this->SSH_KNOWN_HOSTS->DisplayKnownHostsTable("w3-row")
                . $this->SSH_HOST_KEYS->DisplayHostKeysTable("w3-row")
            . "</div>";
    }
    
    function RASPBERRY_SshAuthorizedKeys() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
               . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
               . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("SSH Authorized Keys"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->SSH_AUTHORIZED_KEYS->ConfigureAuthorizedKeys()
            . "</div>";
    }
    
    function RASPBERRY_SshUserKeys() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
               . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
               . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("SSH User Keys"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->SSH_USER_KEYS->ConfigureUserKeys()
            . "</div>";  
    }
    
    function RASPBERRY_SshKnownHosts() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
               . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
               . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("SSH Known Hosts"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->SSH_KNOWN_HOSTS->ConfigureKnownHosts()
            . "</div>";
    }
    
    function RASPBERRY_SshHostKeys() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
               . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
               . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("SSH Host Keys"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->SSH_HOST_KEYS->ConfigureHostKeys()
            . "</div>";        
    }
    
    function RASPBERRY_Administration() {
        $this->UTILITIES->LoadHomepageAtSessionExpire();
        $_SESSION["key"] = $this->UTILITIES->GenerateAuthorizationKey();
        echo  "<div id='mySidebar' style='display:none' "
                        . "class='w3-sidebar w3-bar-block w3-card w3-animate-left w3-light-grey'>"
                . $this->UTILITIES_PAGES->Sidebar()
            . "</div>"
            . "<div id='main' class='w3-light-grey'>"
                . $this->UTILITIES_PAGES->TopBarSignInSignOutLanguageButtons()
                . $this->UTILITIES_PAGES->NavigationBar(
                    $this->UTILITIES->Translate("Administration"), 
                    "/index.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string, 
                    "<i class='fa fa-home'></i>")
                . $this->ADMINISTRATION->ConfigureSettings()
            . "</div>";
    }
    
}
