<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of UTILITIES
 *
 * @author rene
 */
class UTILITIES {
    
    function __construct (
            
    ) {
        $this->toroot = str_repeat(".", substr_count(str_replace("/RASPserver", "", htmlspecialchars($_SERVER["PHP_SELF"])), "/"));
        $this->mysqli_credentials = $this->GetMySqliCredentials();
        $this->SQL = new SQL($this->mysqli_credentials);
        $this->raspberry_emulated = $this->GetRaspberryEmulated();
        $this->RASPBERRY = $this->GetRASPBERRY();
        $this->UTILITIES_LOGIN = new UTILITIES_LOGIN($this->SQL, $this->raspberry_emulated);
        $this->lang = $this->GetLanguage();
        $this->query_string = $this->GetQueryString();
        $this->session_cache_expire = 20*60*1000;
    }
    
    function GetMySqliCredentials() {
        try {
            $mysqli_credentials = file_get_contents($this->toroot."/credentials/mysqli_credentials");
//            $mysqli_credentials = $this->FILE_OPERATIONS->ReadFile(
//                    $this->toroot."/credentials/mysqli_credentials")[0];
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $mysqli_credentials;
    }
    
    function GetRaspberryEmulated() {
        if(explode(":", $this->mysqli_credentials)[4] === "1") {
            $raspberry_emulated = true;
        } else {
            $raspberry_emulated = false;
        }
        return $raspberry_emulated;
    }
    
    function GetRASPBERRY() {
        if(!$this->raspberry_emulated) {
            $RASPBERRY = new RASPBERRY($this->SQL);
        } else {
            $RASPBERRY = new RASPBERRY_EMULATED($this->SQL);    
        }
        return $RASPBERRY;
    }
    
    function GetLanguage() {
        if(!$this->raspberry_emulated
                or ($this->raspberry_emulated and $this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn())
                ) {
            $lang = $this->GetSetting("language");
        } else {
            $lang = "en";
        }     
        if(isset($_GET["lang"])) {
            $lang = htmlspecialchars($_GET["lang"]);
        }
        return $lang;
    }
    
    function GetQueryString() {
        foreach($_GET as $query_string_variable => $value) {
            if ($query_string_variable !== "lang") {
                $query_string .= "&".$query_string_variable."=".htmlspecialchars($value);                        
            }
        }
        return $query_string;
    }
    
    function GetSetting($setting) {
        if(!$this->raspberry_emulated) {
            $value = $this->SQL->mySQLiQuery("SELECT value FROM settings WHERE BINARY "
                    . "setting='".$setting."' AND "
                    . "session_id IS NULL",
                    $this->mysqli_credentials)[0]['value'];
        } elseif ($this->raspberry_emulated and $this->UTILITIES_LOGIN->IsThisRASPserverUserSignedIn()) {
            $value = $this->SQL->mySQLiQuery("SELECT value FROM settings WHERE BINARY "
                    . "setting='".$setting."' AND "
                    . "session_id=".json_encode(session_id()))[0]['value'];
        } else {
            return false;
        }
        return $value;
    }
    
    function Translate($english) {
        if ($this->lang === "en") {
            $translation = $english;
        }
        else {
            $translation = $this->SQL->mySQLiQuery(
                    "SELECT ".$this->lang." FROM translations WHERE BINARY "
                    . "en=".json_encode($english))[0][$this->lang];
            if (empty($translation)) {
            $translation = $english;
            }
        }
        return $translation;
    }
    
    function IsThisPageThisPage($thispage) {
        $phpself = htmlspecialchars($_SERVER["PHP_SELF"])."?lang=".$this->lang.$this->query_string;
        if($thispage === str_replace("/RASPserver", "", $phpself)) {
            return true;
        } else {
            return false;
        }
    }
    
    function Console($idconsole, $user, $host, $content) {
        if($user === "root") {
            $string = "<pre>"
                        . "<div id='".$idconsole."' class='w3-margin'>"
                            . "root@".$host.":~# "
                            . $content
                        . "</div>"
                    . "<pre>";
        } else {
            $string = "<pre>"
                        . "<div id='".$idconsole."' class='w3-margin'>"
                            . "<a style='color:green;'>".$user."@".$host."</a>"
                            . "<a>:"."</a>"
                            . "<a style='color:blue;'>~$ </a>"
                            . $content
                        . "</div>"
                    . "<pre>";
        }
        return $string;
    }
    
    function GenerateAuthorizationKey() {
        $length=40;
        $chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ0123456789"; 
        $i = 0; 
        while ($i < $length) { 
            $num = rand() % strlen($chars); 
            $tmp = substr($chars, $num, 1); 
            $pass = $pass . $tmp; 
            $i++; 
        } 
        return $pass; 
    }
    
    function DisplayOnCard($string, $name, $responsive_class) {
        echo    "<div class='".$responsive_class." w3-container w3-section'>"
                    . "<h2><p class='w3-container w3-section'>".$name."</p></h3>"
                    . "<div class='w3-card '>"
                        . "<div class='w3-responsive'>"
                            . "<nobr>"
                                . $string
                            . "</nobr>"
                        . "</div>"
                    . "</div>"
                . "</div>";
    }
    
    function LoadHomepageAtSessionExpire() {
        echo "<script>"
                . "setTimeout(() => {"
                        ."window.location.href = ".json_encode($this->toroot."/index.php?lang=".$this->GetSetting("language")).";"
                    ."}, ".json_encode($this->session_cache_expire).");"
            . "</script>";
    }
    
    function ProvideUidGidFormattedUserList($unixusers) {
        foreach($unixusers as $unixuser) {
            $uidgid_formatted_unixusers[] = "(".$unixuser["uid"]."/".$unixuser["gid"].") ".$unixuser["name"];
        }
        return $uidgid_formatted_unixusers;
    }
    
    function RemoveUsersFromFormattedList($uidgid_formatted_unixusers, $user_keys) {
        foreach($user_keys as $user_key) {    
            $all_users[] = $user_key["user"];
        }
        foreach($uidgid_formatted_unixusers as $uidgid_formatted_unixuser) {
            $user = explode(" ", $uidgid_formatted_unixuser)[1];
            if(!in_array($user, $all_users)) {
                if(!$this->RASPBERRY->DoesThisUserHaveASshFolder($user)) {
                    $uidgid_formatted_unixuserswithoutkeys[] = $uidgid_formatted_unixuser." ";
                } else {
                    $uidgid_formatted_unixuserswithoutkeys[] = $uidgid_formatted_unixuser;
                }
            }
        }
        return $uidgid_formatted_unixuserswithoutkeys;
    }
    
    function GetEmailAdressesOfThisUser($user, $email_addresses) {
        foreach($email_addresses as $email_address) {
            if($email_address["debian_user"] === $user) {
                $email_addresses_of_user[] = $email_address;
            }
        }
        return $email_addresses_of_user;
    }
    
    function CreateStringsToDeleteEmailAddresses($user, $email_addresses_of_user, $email_addresses) {
        $lines_to_delete = [];
        foreach($email_addresses_of_user as $email_address_of_user) {
            $lines_to_delete[] = $email_address_of_user["pop3_user_line_number"];
            if($this->GetNumberOfEmailAddressesOfPop3ServerWithoutUser($email_address_of_user["pop3_server"], $user, $email_addresses) === 0
                    && !in_array($email_address_of_user["pop3_server_line_number"], $lines_to_delete)) {
                $lines_to_delete[] = $email_address_of_user["pop3_server_line_number"];
            }
        }
        if(count($lines_to_delete) > 0) {
            sort($lines_to_delete);
            $string1 = "sed -i ";
            $string2 = "sed -i "; 
            foreach($lines_to_delete as $line_to_delete) {
                $string1 .= "-e \\'".$line_to_delete."d\\' ";
                $string2 .= "-e '".$line_to_delete."d' ";
            }
            $string1 .= "/etc/fetchmailrc";
            $string2 .= "/etc/fetchmailrc";
            $string = $string1.":".$string2;
        } else {
            $string = "";
        }
        return $string;
    }
    
    function GetNumberOfEmailAddressesOfPop3ServerWithoutUser($pop3_server, $user, $email_addresses) {
        $number_of_email_addresses_of_pop3_server_without_user = 0;
        foreach($email_addresses as $email_address) {
            if($email_address["pop3_server"] === $pop3_server && $email_address["debian_user"] !== $user) {
                $number_of_email_addresses_of_pop3_server_without_user++;
            }
        }
        return $number_of_email_addresses_of_pop3_server_without_user;
    }
    
    function CreateStringsToDeleteOrModifySambaShares($user, $samba_shares) {
        foreach($samba_shares as $samba_share) {
            if(in_array($user, $samba_share["valid users"]) and count($samba_share["valid users"]) === 1) {
                // delete
                $samba_shares_to_delete[] = $samba_share;
            } elseif (in_array($user, $samba_share["valid users"]) and count($samba_share["valid users"]) > 1) {
                // modify
                $samba_shares_to_purge_user_as_valid_user[] = $samba_share;
            }
        }
        $array1 = $this->CreateStringsToDeleteSambaShares($samba_shares_to_delete);
        $array2 = $this->CreateStringsToPurgeUserAsValidUser($user, $samba_shares_to_purge_user_as_valid_user);
        if(count($samba_shares_to_delete) !== 0 and count($array2) !== 0) {
            $string1 = "sed -i ";
            $string2 = "sed -i ";
            foreach($array1 as $inner_part) {
                $string1 .= "-e \\'".explode(":", $inner_part)[0]."\\' ";
                $string2 .= "-e '".explode(":", $inner_part)[1]."' ";
            }
            foreach($array2 as $inner_part) {
                $string1 .= "-e \\'".explode(":", $inner_part)[0]."\\' ";
                $string2 .= "-e '".explode(":", $inner_part)[1]."' ";
            }
            $string1 .= "/etc/samba/smb.conf";
            $string2 .= "/etc/samba/smb.conf";
        } elseif(count($samba_shares_to_delete) === 0 and count($array2) === 0) {
            $string1 = "";
            $string2 = "";
        } elseif(count($samba_shares_to_delete) !== 0) {
            $string1 = "sed -i ";
            $string2 = "sed -i ";
            foreach($array1 as $inner_part) {
                $string1 .= "-e \\'".explode(":", $inner_part)[0]."\\' ";;
                $string2 .= "-e '".explode(":", $inner_part)[1]."' ";;
            }
            $string1 .= "/etc/samba/smb.conf";
            $string2 .= "/etc/samba/smb.conf";
        } elseif(count($array2) !== 0) {
            $string1 = "sed -i ";
            $string2 = "sed -i ";
            foreach($array2 as $inner_part) {
                $string1 .= "-e \\'".explode(":", $inner_part)[0]."\\' ";
                $string2 .= "-e '".explode(":", $inner_part)[1]."' ";
            }
            $string1 .= "/etc/samba/smb.conf";
            $string2 .= "/etc/samba/smb.conf";
        }
        return $string1.":".$string2;
    }
    
    function CreateStringsToDeleteSambaShares($samba_shares_to_delete) {
        $smb_conf = file("/etc/samba/smb.conf");
        foreach($samba_shares_to_delete as $samba_share_to_delete) {
            $line_end = $this->RASPBERRY->CountUpSambaLines($samba_share_to_delete["line_end"]);
            $inner_part1 = $samba_share_to_delete["line_begin"].",".$line_end."d";
            $inner_part2 = $samba_share_to_delete["line_begin"].",".$line_end."d";
            $array[] = $inner_part1.":".$inner_part2;
        }
        return $array;
    }
    
    function CreateStringsToPurgeUserAsValidUser($user, $samba_shares_to_purge_user_as_valid_user) {
        $smb_conf = file("/etc/samba/smb.conf");
        foreach($samba_shares_to_purge_user_as_valid_user as $samba_share_to_purge_user_as_valid_user) {
            foreach($samba_share_to_purge_user_as_valid_user["valid users"] as $valid_user) {
                if($valid_user !== $user) {
                    $valid_users .= ",".$valid_user;
                }
            }
            for($i = $samba_share_to_purge_user_as_valid_user["line_begin"]; $i < $samba_share_to_purge_user_as_valid_user["line_end"]; $i++) {
                if(!strpos("valid users", $smb_conf[$i])) {
                    $line = $i - 2;
                    $inner_part1 = $line."c\\\\ \\\\ \\\\ valid users = ".substr($valid_users, 1);
                    $inner_part2 = $line."c\\ \\ \\ valid users = ".substr($valid_users, 1);
                }
            }
            $array[] = $inner_part1.":".$inner_part2;
            unset($valid_users);
        }
        return $array;
    }
    
    function GetFstabEntryByPartitionOrUuid($partition_or_uuid, $fstabentries) {
        foreach($fstabentries as $fstabentry) {
            if($fstabentry["partition"] === $partition_or_uuid and $partition_or_uuid !== "") {
                $fstab_entry = [
                    "partition" => $partition_or_uuid,
                    "uuid" => "",
                    "mountpoint" => $fstabentry["mountpoint"],
                    "type" => $fstabentry["type"],
                    "options" => $fstabentry["options"],
                    "dump" => $fstabentry["dump"],
                    "pass" => $fstabentry["pass"]
                ];
            }
            if($fstabentry["uuid"] === $partition_or_uuid and $partition_or_uuid !== "") {
                $fstab_entry = [
                    "partition" => "",
                    "uuid" => $fstabentry["uuid"],
                    "mountpoint" => $fstabentry["mountpoint"],
                    "type" => $fstabentry["type"],
                    "options" => $fstabentry["options"],
                    "dump" => $fstabentry["dump"],
                    "pass" => $fstabentry["pass"]
                ];
            }
        }
        return $fstab_entry;
    }
    
    function PlayLivestream($m3u8) {
        $string = "<div class='w3-center w3-margin'>"  
                        . "<video id='video' width='368' height='207' controls></video>"
                    . "</div>"
                    . "<script src='https://cdn.jsdelivr.net/hls.js/latest/hls.js'></script>"
                    . "<script>"
                    . "if(Hls.isSupported()) {"
                        . "var video = document.getElementById('video');"
                        . "var hls = new Hls();"
                        . "hls.loadSource(".json_encode($m3u8).");"
                        . "hls.attachMedia(video);"
                        . "hls.startLoad();"
                    . "}"
                . "</script>";
        return $string;
    }
    
    
    
    
    
    
}
