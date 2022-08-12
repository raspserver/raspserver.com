<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of RASPBERRY_EMULATED
 *
 * @author rene
 */
class RASPBERRY_EMULATED {
    
    function __construct (
            $SQL
    ) {
        $this->SQL = $SQL;
    }
    
    function GetNetworkInterfaces() {   
        $network_interfaces[] = array(
            "network_interface" => "eth0",
            "ip_address"        => "192.168.1.100",
            "netmask"           => "255.255.255.0",
            "broadcast"         => "192.168.1.255",
            "ip_address_ipv6_global" => $this->GetSettingFromSQL("ip_address_ipv6_global"),
            "ip_address_ipv6_local" => $this->GetSettingFromSQL("ip_address_ipv6_local"),
            "mac_address"       => $this->GetSettingFromSQL("mac_address"));
        return $network_interfaces;
    }
    
    function CreateFakeIpAddressesMacAddresses() {
        $this->SetSetting("ip_address_ipv6_global", "2a02:8070:784:c00:".str_shuffle("1319").":".str_shuffle("8a2e").":".str_shuffle("0370").":".str_shuffle("7347")."/64");
        $this->SetSetting("ip_address_ipv6_local", "fe80::".str_shuffle("dbee").":".str_shuffle("e72e").":".str_shuffle("b0db").":".str_shuffle("1c4a")."/64");
        $this->SetSetting("mac_address", strtoupper($this->GenerateFakeHexDecPairs(6)));
    }
    
    function GetUnixUsers() {
        $table = $this->SQL->mySQLiQuery("SELECT uid,gid,name FROM raspberry_emulation WHERE BINARY "
                . "session_id=".json_encode(session_id())." "
                . "ORDER BY uid,gid,name");
        foreach($table as $row) {
            $unixusers[] = array("uid" => intval($row['uid']),
                                 "gid" => intval($row['gid']),
                                 "name" => $row['name']);
        }
        return $unixusers;
    }
    
    function GetEmailAddresses() {
        $unixusers = $this->GetUnixUsers();
        $table = $this->SQL->mySQLiQuery("SELECT debian_user,pop3_server,pop3_user,pop3_password,pop3_server_line_number,pop3_user_line_number FROM email_accounts_emulated WHERE BINARY "
                . "session_id=".json_encode(session_id())." "
                . "ORDER BY debian_user,pop3_server,pop3_user");
        foreach($table as $row) {
            $email_addresses[] = array("uidgiduser" => $this->GetFormattedUidGidByUsername($row['debian_user'], $unixusers),
                                       "debian_user" => $row['debian_user'],
                                       "pop3_server" => $row['pop3_server'],
                                       "pop3_user" => $row['pop3_user'],
                                       "pop3_password" => $row['pop3_password'],
                                       "pop3_server_line_number" => intval($row['pop3_server_line_number']),
                                       "pop3_user_line_number" => intval($row['pop3_user_line_number'])); 
        }
        sort($email_addresses);
        return $email_addresses;
    }
    
    function CreateEmailAccount($debian_user, $pop3_server, $pop3_user, $pop3_password, $pop3_server_line_number, $pop3_user_line_number) {
        $this->SQL->mySQLiQuery("INSERT INTO email_accounts_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode($debian_user).","
                .json_encode($pop3_server).","
                .json_encode($pop3_user).","
                .json_encode($pop3_password).","
                .json_encode($pop3_server_line_number).","
                .json_encode($pop3_user_line_number).")");
    }
    
    function CreateSambaShare($share, $path, $writable, $browsable, $guest_ok, $line_begin, $line_end) {
        $this->SQL->mySQLiQuery("INSERT INTO samba_shares_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode($share).","
                .json_encode($path).","
                .json_encode($writable).","
                .json_encode($browsable).","
                .json_encode($guest_ok).","
                .json_encode($line_begin).","
                .json_encode($line_end).")");
    }
    
    function CreateValidUser($share, $user) {
        $this->SQL->mySQLiQuery("INSERT INTO valid_users_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode($share).","
                .json_encode($user).")");
    }
    
    function IsThisUnixUserPasswordWorking($user, $password) {
        $hashed_password = $this->SQL->mySQLiQuery("SELECT hashed_password FROM raspberry_emulation WHERE BINARY "
                . "name=".json_encode($user)." AND BINARY "
                . "session_id=".json_encode(session_id()))[0]['hashed_password'];
        if(password_verify($password, $hashed_password)) {
            return true;
        } else {
            return false;
        }
    }
    
    function CreateUsersOnDemoStart($lang) {
        $this->CreateUser(0, 0, "root");
        $this->CreateUser(1000, 1000, "pi");
        $this->CreateUser(1001, 1001, "asterisk");
        $this->CreateUser(1002, 1002, "john_doe");
        $this->CreateUser(1003, 1003, "jane_roe");
        $this->CreateUser(1004, 1004, "info");
        $this->CreateEmailAccount("john_doe", "raspserver.com", "john.doe@raspserver.com", "secret", 6, 7);
        $this->CreateEmailAccount("jane_roe", "raspserver.com", "jane_roe@raspserver.com", "secret", 6, 8);
        $this->CreateEmailAccount("info", "raspserver.com", "info@raspserver.com", "secret", 6, 9);
        $this->CreateSambaShare("john_doe", "/home/john_doe", "yes", "yes", "no", 213, 218);
        $this->CreateSambaShare("jane_roe", "/home/jane_roe", "yes", "yes", "no", 220, 225);
        $this->CreateSambaShare("info", "/home/info", "yes", "yes", "no", 227, 232);
        $this->CreateValidUser("john_doe", "john_doe");
        $this->CreateValidUser("jane_roe", "jane_roe");
        $this->CreateValidUser("info", "info");
        $this->SetSetting("", "");
        $this->SetSetting("language", $lang);
        $this->SetSetting("ping", "google.com");
        $this->SetSetting("ping", "youtube.com");
        $this->SetSetting("ping", "facebook.com");
        $this->SetSetting("ping", "twitter.com");
        $this->SetSetting("ping", "instagram.com");
        $this->SetSetting("ping", "yahoo.com");
        $this->SetSetting("PermitRootLogin", "yes");
        $this->SetSetting("PasswordAuthentication", "no");
        $this->SetSetting("UsePAM", "no");
        $this->SetSetting("HashKnownHosts", "no");
        $this->CreateFakeRSAKey("root", "raspbx");
        $this->CreateFakeDSAKey("pi", "raspbx");
        $this->CreateFakeECDSAKey("asterisk", "raspbx");  
        $this->CreateFakeED25519Key("john_doe", "raspbx");
        $this->CreateFakeHostKeys("raspbx");
        $this->CreateFakeKnownHosts();
        $this->CreateFakeAuthorizedKeys();
        $this->CreateFakeIpAddressesMacAddresses();
        $this->CreateResources();
        $this->CreateFstab();
        $languages_available = $this->GetLanguagesAvailable();
        foreach($languages_available as $language) {
            if($language['token'] === $lang) {
                $this->SetSetting($language['token'], "1");
            } else {
                $this->SetSetting($language['token'], "1");
            }
        }
    }
    
    function CreateResources() {
        
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("mmcblk0").","
                .json_encode("15539200").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $uuid_mmcblk0p1 = str_shuffle("F021")."-".str_shuffle("066F");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("mmcblk0").","
                .json_encode("15539200").","
                .json_encode("mmcblk0p1").","
                .json_encode("262144").","
                .json_encode("c").","
                .json_encode("W95 FAT32 (LBA)").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("mmcblk0").","
                .json_encode("15539200").","
                .json_encode("mmcblk0p1").","
                .json_encode("262144").","
                .json_encode("c").","
                .json_encode("W95 FAT32 (LBA)").","
                .json_encode("vfat").","
                .json_encode("FAT32").","
                .json_encode("boot").","
                .json_encode($uuid_mmcblk0p1).","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("mmcblk0").","
                .json_encode("15539200").","
                .json_encode("mmcblk0p1").","
                .json_encode("262144").","
                .json_encode("c").","
                .json_encode("W95 FAT32 (LBA)").","
                .json_encode("vfat").","
                .json_encode("FAT32").","
                .json_encode("boot").","
                .json_encode($uuid_mmcblk0p1).","
                .json_encode("202.8M").","
                .json_encode("20%").","
                .json_encode("/boot").")");
        $uuid_mmcblk0p2 = str_shuffle("99f9cf68")."-".str_shuffle("e6fa")."-".str_shuffle("4b90")."-".str_shuffle("aeee")."-".str_shuffle("7fa3e9ed5c2d");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("mmcblk0").","
                .json_encode("15539200").","
                .json_encode("mmcblk0p2").","
                .json_encode("15272960").","
                .json_encode("83").","
                .json_encode("Linux").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("mmcblk0").","
                .json_encode("15539200").","
                .json_encode("mmcblk0p2").","
                .json_encode("15272960").","
                .json_encode("83").","
                .json_encode("Linux").","
                .json_encode("ext4").","
                .json_encode("1.0").","
                .json_encode("rootfs").","
                .json_encode($uuid_mmcblk0p2).","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("mmcblk0").","
                .json_encode("15539200").","
                .json_encode("mmcblk0p2").","
                .json_encode("15272960").","
                .json_encode("83").","
                .json_encode("Linux").","
                .json_encode("ext4").","
                .json_encode("1.0").","
                .json_encode("rootfs").","
                .json_encode($uuid_mmcblk0p2).","
                .json_encode("28.2G").","
                .json_encode("39%").","
                .json_encode("/").")");
        
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("sda").","
                .json_encode("976762584").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $uuid_sda1 = str_shuffle("8941")."-".str_shuffle("BA23");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("sda").","
                .json_encode("976762584").","
                .json_encode("sda1").","
                .json_encode("976761560").","
                .json_encode("7").","
                .json_encode("HPFS/NTFS/exFAT").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("sda").","
                .json_encode("976762584").","
                .json_encode("sda1").","
                .json_encode("976761560").","
                .json_encode("7").","
                .json_encode("HPFS/NTFS/exFAT").","
                .json_encode("vfat").","
                .json_encode("FAT32").","
                .json_encode("").","
                .json_encode($uuid_sda1).","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(true).","
                .json_encode("sda").","
                .json_encode("976762584").","
                .json_encode("sda1").","
                .json_encode("976761560").","
                .json_encode("7").","
                .json_encode("HPFS/NTFS/exFAT").","
                .json_encode("vfat").","
                .json_encode("FAT32").","
                .json_encode("").","
                .json_encode($uuid_sda1).","
                .json_encode("931.3G").","
                .json_encode("0%").","
                .json_encode("/media/".$uuid_sda1).")");
        
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("sdb").","
                .json_encode("976762584").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $uuid_sdb1 = str_shuffle("5F4E096F761C8F39");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("sdb").","
                .json_encode("976762584").","
                .json_encode("sdb1").","
                .json_encode("976761560").","
                .json_encode("7").","
                .json_encode("HPFS/NTFS/exFAT").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("sdb").","
                .json_encode("976762584").","
                .json_encode("sdb1").","
                .json_encode("976761560").","
                .json_encode("7").","
                .json_encode("HPFS/NTFS/exFAT").","
                .json_encode("ntfs").","
                .json_encode("").","
                .json_encode("").","
                .json_encode($uuid_sdb1).","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(true).","
                .json_encode("sdb").","
                .json_encode("976762584").","
                .json_encode("sdb1").","
                .json_encode("976761560").","
                .json_encode("7").","
                .json_encode("HPFS/NTFS/exFAT").","
                .json_encode("ntfs").","
                .json_encode("").","
                .json_encode("").","
                .json_encode($uuid_sdb1).","
                .json_encode("931.4G").","
                .json_encode("0%").","
                .json_encode("/media/".$uuid_sdb1).")");
        
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("sdc").","
                .json_encode("976762584").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $uuid_sdc1 = str_shuffle("55d838f7")."-".str_shuffle("e64a")."-".str_shuffle("464f")."-".str_shuffle("b20f")."-".str_shuffle("f3ab876dd046");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("sdc").","
                .json_encode("976762584").","
                .json_encode("sdc1").","
                .json_encode("976761560").","
                .json_encode("83").","
                .json_encode("Linux").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(false).","
                .json_encode("sdc").","
                .json_encode("976762584").","
                .json_encode("sdc1").","
                .json_encode("976761560").","
                .json_encode("83").","
                .json_encode("Linux").","
                .json_encode("ext4").","
                .json_encode("1.0").","
                .json_encode("").","
                .json_encode($uuid_sdc1).","
                .json_encode("").","
                .json_encode("").","
                .json_encode("").")");
        $this->SQL->mySQLiQuery("INSERT INTO resources_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode(true).","
                .json_encode("sdc").","
                .json_encode("976762584").","
                .json_encode("sdc1").","
                .json_encode("976761560").","
                .json_encode("83").","
                .json_encode("Linux").","
                .json_encode("ext4").","
                .json_encode("1.0").","
                .json_encode("").","
                .json_encode($uuid_sdc1).","
                .json_encode("869.2G").","
                .json_encode("0%").","
                .json_encode("/media/".$uuid_sdc1).")");
    }
    
    function CreateFstab() {
        $this->SQL->mySQLiQuery("INSERT INTO fstab_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode("mmcblk0p1").","
                .json_encode("").","
                .json_encode("/boot").","
                .json_encode("vfat").","
                .json_encode("defaults,flush").","
                .json_encode("0").","
                .json_encode("2").")");
        
        $this->SQL->mySQLiQuery("INSERT INTO fstab_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode("mmcblk0p2").","
                .json_encode("").","
                .json_encode("/").","
                .json_encode("ext4").","
                .json_encode("defaults,noatime").","
                .json_encode("0").","
                .json_encode("1").")");
        
        $uuid_sda1 = $this->SQL->mySQLiQuery("SELECT uuid FROM resources_emulated WHERE BINARY "
                . "partit=".json_encode("sda1")." AND BINARY "
                . "fstype=".json_encode("vfat")." AND BINARY "
                . "session_id=".json_encode(session_id()))[0]["uuid"];
        $this->SQL->mySQLiQuery("INSERT INTO fstab_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode("").","
                .json_encode($uuid_sda1).","
                .json_encode("/media/".$uuid_sda1).","
                .json_encode("vfat").","
                .json_encode("umask=0000").","
                .json_encode("0").","
                .json_encode("2").")");
        
        $uuid_sdb1 = $this->SQL->mySQLiQuery("SELECT uuid FROM resources_emulated WHERE BINARY "
                . "partit=".json_encode("sdb1")." AND BINARY "
                . "fstype=".json_encode("ntfs")." AND BINARY "
                . "session_id=".json_encode(session_id()))[0]["uuid"];
        $this->SQL->mySQLiQuery("INSERT INTO fstab_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode("").","
                .json_encode($uuid_sdb1).","
                .json_encode("/media/".$uuid_sdb1).","
                .json_encode("ntfs").","
                .json_encode("umask=0000").","
                .json_encode("0").","
                .json_encode("2").")");
        
        $uuid_sdc1 = $this->SQL->mySQLiQuery("SELECT uuid FROM resources_emulated WHERE BINARY "
                . "partit=".json_encode("sdc1")." AND BINARY "
                . "fstype=".json_encode("ext4")." AND BINARY "
                . "session_id=".json_encode(session_id()))[0]["uuid"];
        $this->SQL->mySQLiQuery("INSERT INTO fstab_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode("").","
                .json_encode($uuid_sdc1).","
                .json_encode("/media/".$uuid_sdc1).","
                .json_encode("ext4").","
                .json_encode("defaults").","
                .json_encode("0").","
                .json_encode("2").")");
    }
    
    function SSHKeysQuery() {
        return $ssh_query_output;
    }
    
    function DoesRootHaveASshFolder($ssh_query_output) {
        return true;
    }
    
    function DoesThisUserHaveASshFolder($user) {
        return true;
    }
    
    function GetSshAuthorizedKeys($ssh_query_output, $unixusers) {
        $authorized_keys_of_user[] = array(
                        "keytype1" => $this->GetSettingFromSQL("authorized_keys_root_1_keytype1"),
                        "key" => $this->GetSettingFromSQL("authorized_keys_root_1_key"),
                        "comment" => $this->GetSettingFromSQL("authorized_keys_root_1_comment"),
                        "line_number_in_authorized_keys" => $this->GetSettingFromSQL("authorized_keys_root_1_line_number_in_authorized_keys"),
                        "keysize" => $this->GetSettingFromSQL("authorized_keys_root_1_keysize"),
                        "fingerprint_type1" => $this->GetSettingFromSQL("authorized_keys_root_1_fingerprint_type1"),
                        "fingerprint1" => $this->GetSettingFromSQL("authorized_keys_root_1_fingerprint1"),
                        "keytype2" => $this->GetSettingFromSQL("authorized_keys_root_1_keytype2"),
                        "fingerprint_type2" => $this->GetSettingFromSQL("authorized_keys_root_1_fingerprint_type2"),
                        "fingerprint2" => $this->GetSettingFromSQL("authorized_keys_root_1_fingerprint2")
                    );
        if($this->GetSettingFromSQL("authorized_keys_root_1_keytype1") !== null) {
            $authorized_keys[] = array(
                    "uidgiduser" => $this->GetFormattedUidGidByUsername("root", $unixusers),
                    "user" => "root",
                    "authorized_keys_of_user" => $authorized_keys_of_user
            );
        }
        unset($authorized_keys_of_user);
        $authorized_keys_of_user[] = array(
                        "keytype1" => $this->GetSettingFromSQL("authorized_keys_pi_1_keytype1"),
                        "key" => $this->GetSettingFromSQL("authorized_keys_pi_1_key"),
                        "comment" => $this->GetSettingFromSQL("authorized_keys_pi_1_comment"),
                        "line_number_in_authorized_keys" => $this->GetSettingFromSQL("authorized_keys_pi_1_line_number_in_authorized_keys"),
                        "keysize" => $this->GetSettingFromSQL("authorized_keys_pi_1_keysize"),
                        "fingerprint_type1" => $this->GetSettingFromSQL("authorized_keys_pi_1_fingerprint_type1"),
                        "fingerprint1" => $this->GetSettingFromSQL("authorized_keys_pi_1_fingerprint1"),
                        "keytype2" => $this->GetSettingFromSQL("authorized_keys_pi_1_keytype2"),
                        "fingerprint_type2" => $this->GetSettingFromSQL("authorized_keys_pi_1_fingerprint_type2"),
                        "fingerprint2" => $this->GetSettingFromSQL("authorized_keys_pi_1_fingerprint2")
                    );
        if($this->GetSettingFromSQL("authorized_keys_pi_1_keytype1") !== null) {
            $authorized_keys[] = array(
                    "uidgiduser" => $this->GetFormattedUidGidByUsername("pi", $unixusers),
                    "user" => "pi",
                    "authorized_keys_of_user" => $authorized_keys_of_user
            );
        }
        sort($authorized_keys);
        return $authorized_keys;
    }
    
    function CreateFakeAuthorizedKeys() {
        $this->SetSetting("authorized_keys_root_1_keytype1", "ssh-ed25519");
        $this->SetSetting("authorized_keys_root_1_key", "AAAAC3NzaC1lZDI1NTE5AAAAIM".str_shuffle("BN5pgUPi7KdxacQ476ky4LKG0hjlMhhulkmvzs7Y3i"));
        $this->SetSetting("authorized_keys_root_1_comment", "root@client");
        $this->SetSetting("authorized_keys_root_1_line_number_in_authorized_keys", 1);
        $this->SetSetting("authorized_keys_root_1_keysize", 256);
        $this->SetSetting("authorized_keys_root_1_fingerprint_type1", "SHA256");
        $this->SetSetting("authorized_keys_root_1_fingerprint1", str_shuffle("BWV3KpLKQWk/Ycs7SgmgW5zD6cPvVbYu612N+LJ9KAw"));
        $this->SetSetting("authorized_keys_root_1_keytype2", "ED25519");
        $this->SetSetting("authorized_keys_root_1_fingerprint_type2", "MD5");
        $this->SetSetting("authorized_keys_root_1_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        
        $this->SetSetting("authorized_keys_pi_1_keytype1", "ssh-ed25519");
        $this->SetSetting("authorized_keys_pi_1_key", "AAAAC3NzaC1lZDI1NTE5AAAAIM".str_shuffle("BN5pgUPi7KdxacQ476ky4LKG0hjlMhhulkmvzs7Y3i"));
        $this->SetSetting("authorized_keys_pi_1_comment", "pi@client");
        $this->SetSetting("authorized_keys_pi_1_line_number_in_authorized_keys", 1);
        $this->SetSetting("authorized_keys_pi_1_keysize", 256);
        $this->SetSetting("authorized_keys_pi_1_fingerprint_type1", "SHA256");
        $this->SetSetting("authorized_keys_pi_1_fingerprint1", str_shuffle("BWV3KpLKQWk/Ycs7SgmgW5zD6cPvVbYu612N+LJ9KAw"));
        $this->SetSetting("authorized_keys_pi_1_keytype2", "ED25519");
        $this->SetSetting("authorized_keys_pi_1_fingerprint_type2", "MD5");
        $this->SetSetting("authorized_keys_pi_1_fingerprint2", $this->GenerateFakeHexDecPairs(16));
    }
    
    function GetKnownHosts($ssh_query_output, $unixusers) {
        $known_hosts_of_user[] = array(
                        "known_host" => $this->GetSettingFromSQL("known_hosts_pi_1_known_host"),
                        "keytype1" => $this->GetSettingFromSQL("known_hosts_pi_1_keytype1"),
                        "key" => $this->GetSettingFromSQL("known_hosts_pi_1_key"),
                        "line_number_in_known_hosts" => $this->GetSettingFromSQL("known_hosts_pi_1_line_number_in_known_hosts"),
                        "keysize" => $this->GetSettingFromSQL("known_hosts_pi_1_keysize"),
                        "fingerprint_type1" => $this->GetSettingFromSQL("known_hosts_pi_1_fingerprint_type1"),
                        "fingerprint1" => $this->GetSettingFromSQL("known_hosts_pi_1_fingerprint1"),
                        "keytype2" => $this->GetSettingFromSQL("known_hosts_pi_1_keytype2"),
                        "fingerprint_type2" => $this->GetSettingFromSQL("known_hosts_pi_1_fingerprint_type2"),
                        "fingerprint2" => $this->GetSettingFromSQL("known_hosts_pi_1_fingerprint2")
                    );
        if($this->GetSettingFromSQL("known_hosts_pi_1_known_host") !== null) {
            $known_hosts[] = array(
                    "uidgiduser" => $this->GetFormattedUidGidByUsername("pi", $unixusers),
                    "user" => "pi",
                    "known_hosts_of_user" => $known_hosts_of_user);
        }
        unset($known_hosts_of_user);
        $known_hosts_of_user[] = array(
                        "known_host" => $this->GetSettingFromSQL("known_hosts_pi_2_known_host"),
                        "keytype1" => $this->GetSettingFromSQL("known_hosts_pi_2_keytype1"),
                        "key" => $this->GetSettingFromSQL("known_hosts_pi_2_key"),
                        "line_number_in_known_hosts" => $this->GetSettingFromSQL("known_hosts_pi_2_line_number_in_known_hosts"),
                        "keysize" => $this->GetSettingFromSQL("known_hosts_pi_2_keysize"),
                        "fingerprint_type1" => $this->GetSettingFromSQL("known_hosts_pi_2_fingerprint_type1"),
                        "fingerprint1" => $this->GetSettingFromSQL("known_hosts_pi_2_fingerprint1"),
                        "keytype2" => $this->GetSettingFromSQL("known_hosts_pi_2_keytype2"),
                        "fingerprint_type2" => $this->GetSettingFromSQL("known_hosts_pi_2_fingerprint_type2"),
                        "fingerprint2" => $this->GetSettingFromSQL("known_hosts_pi_2_fingerprint2")
                    );
        if($this->GetSettingFromSQL("known_hosts_pi_2_known_host") !== null) {
            $known_hosts[] = array(
                    "uidgiduser" => $this->GetFormattedUidGidByUsername("pi", $unixusers),
                    "user" => "pi",
                    "known_hosts_of_user" => $known_hosts_of_user);
        }
        sort($known_hosts);
        return $known_hosts;
    }
    
    function CreateFakeKnownHosts() {
        $this->SetSetting("known_hosts_pi_1_known_host", "|1|".str_shuffle("bCZ402AoSz8TmsoSTLB7XqKsNlI")."=|".str_shuffle("lMNaPrALw3mqh6swIGuGmpX7Rc8")."=");
        $this->SetSetting("known_hosts_pi_1_keytype1", "ecdsa-sha2-nistp521");
        $this->SetSetting("known_hosts_pi_1_key", "AAAAE2VjZHNhLXNoYTItbmlzdHAyNTYAAA".str_shuffle("AIbmlzdHAyNTYAAABBBCausMe/hNEIIFhigC728H7u8B0oShWMqb+D+/zot80eWMPMEeJBFfg0racOYeiBCeJt2mWjLOzrkvHZSR91nH8")."=");
        $this->SetSetting("known_hosts_pi_1_line_number_in_known_hosts", 1);
        $this->SetSetting("known_hosts_pi_1_keysize", 256);
        $this->SetSetting("known_hosts_pi_1_fingerprint_type1", "SHA256");
        $this->SetSetting("known_hosts_pi_1_fingerprint1", str_shuffle("lL0vRVBfJo33d+Vic8qpQuoQuC790AjPemhrjH3AdBc"));
        $this->SetSetting("known_hosts_pi_1_keytype2", "ECDSA");
        $this->SetSetting("known_hosts_pi_1_fingerprint_type2", "MD5");
        $this->SetSetting("known_hosts_pi_1_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        
        $this->SetSetting("known_hosts_pi_2_known_host", "raspserver.com,176.95.46.123");
        $this->SetSetting("known_hosts_pi_2_keytype1", "ecdsa-sha2-nistp521");
        $this->SetSetting("known_hosts_pi_2_key", "AAAAE2VjZHNhLXNoYTItbmlzdHAyNTYAAA".str_shuffle("AIbmlzdHAyNTYAAABBBCausMe/hNEIIFhigC728H7u8B0oShWMqb+D+/zot80eWMPMEeJBFfg0racOYeiBCeJt2mWjLOzrkvHZSR91nH8")."=");
        $this->SetSetting("known_hosts_pi_2_line_number_in_known_hosts", 2);
        $this->SetSetting("known_hosts_pi_2_keysize", 256);
        $this->SetSetting("known_hosts_pi_2_fingerprint_type1", "SHA256");
        $this->SetSetting("known_hosts_pi_2_fingerprint1", str_shuffle("lL0vRVBfJo33d+Vic8qpQuoQuC790AjPemhrjH3AdBc"));
        $this->SetSetting("known_hosts_pi_2_keytype2", "ECDSA");
        $this->SetSetting("known_hosts_pi_2_fingerprint_type2", "MD5");
        $this->SetSetting("known_hosts_pi_2_fingerprint2", $this->GenerateFakeHexDecPairs(16));
    }
    
    function GetSshHostKeys($ssh_query_output) {
        $host_keys[] = array(
            "path" => $this->GetSettingFromSQL("host_key_DSA_path"), 
            "keytype1" => $this->GetSettingFromSQL("host_key_DSA_keytype1"), 
            "key" => $this->GetSettingFromSQL("host_key_DSA_key_part1").$this->GetSettingFromSQL("host_key_DSA_key_part2").$this->GetSettingFromSQL("host_key_DSA_key_part3"), 
            "comment" => $this->GetSettingFromSQL("host_key_DSA_comment"),
            "keysize" => $this->GetSettingFromSQL("host_key_DSA_keysize"), 
            "fingerprint_type1" => $this->GetSettingFromSQL("host_key_DSA_fingerprint_type1"), 
            "fingerprint1" => $this->GetSettingFromSQL("host_key_DSA_fingerprint1"), 
            "keytype2" => $this->GetSettingFromSQL("host_key_DSA_keytype2"),
            "fingerprint_type2" => $this->GetSettingFromSQL("host_key_DSA_fingerprint_type2"), 
            "fingerprint2" => $this->GetSettingFromSQL("host_key_DSA_fingerprint2"),
            "date" => $this->GetSettingFromSQL("host_key_DSA_date"));
        $host_keys[] = array(
            "path" => $this->GetSettingFromSQL("host_key_ECDSA_path"), 
            "keytype1" => $this->GetSettingFromSQL("host_key_ECDSA_keytype1"), 
            "key" => $this->GetSettingFromSQL("host_key_ECDSA_key_part1").$this->GetSettingFromSQL("host_key_ECDSA_key_part2").$this->GetSettingFromSQL("host_key_ECDSA_key_part3"), 
            "comment" => $this->GetSettingFromSQL("host_key_ECDSA_comment"),
            "keysize" => $this->GetSettingFromSQL("host_key_ECDSA_keysize"), 
            "fingerprint_type1" => $this->GetSettingFromSQL("host_key_ECDSA_fingerprint_type1"), 
            "fingerprint1" => $this->GetSettingFromSQL("host_key_ECDSA_fingerprint1"), 
            "keytype2" => $this->GetSettingFromSQL("host_key_ECDSA_keytype2"),
            "fingerprint_type2" => $this->GetSettingFromSQL("host_key_ECDSA_fingerprint2"),
            "fingerprint2" => $this->GetSettingFromSQL("host_key_ECDSA_fingerprint2"),
            "date" => $this->GetSettingFromSQL("host_key_ECDSA_date"));
        $host_keys[] = array(
            "path" =>  $this->GetSettingFromSQL("host_key_ED25519_path"), 
            "keytype1" => $this->GetSettingFromSQL("host_key_ED25519_keytype1"), 
            "key" => $this->GetSettingFromSQL("host_key_ED25519_key_part1").$this->GetSettingFromSQL("host_key_ED25519_key_part2").$this->GetSettingFromSQL("host_key_ED25519_key_part3"), 
            "comment" => $this->GetSettingFromSQL("host_key_ED25519_comment"),
            "keysize" => $this->GetSettingFromSQL("host_key_ED25519_keysize"), 
            "fingerprint_type1" => $this->GetSettingFromSQL("host_key_ED25519_fingerprint_type1"), 
            "fingerprint1" => $this->GetSettingFromSQL("host_key_ED25519_fingerprint1"), 
            "keytype2" => $this->GetSettingFromSQL("host_key_ED25519_keytype2"),
            "fingerprint_type2" => $this->GetSettingFromSQL("host_key_ED25519_fingerprint2"),
            "fingerprint2" => $this->GetSettingFromSQL("host_key_ED25519_fingerprint2"),
            "date" => $this->GetSettingFromSQL("host_key_ED25519_date"));
        $host_keys[] = array(
            "path" =>  $this->GetSettingFromSQL("host_key_RSA_path"), 
            "keytype1" => $this->GetSettingFromSQL("host_key_RSA_keytype1"), 
            "key" => $this->GetSettingFromSQL("host_key_RSA_key_part1").$this->GetSettingFromSQL("host_key_RSA_key_part2").$this->GetSettingFromSQL("host_key_RSA_key_part3"), 
            "comment" => $this->GetSettingFromSQL("host_key_RSA_comment"),
            "keysize" => $this->GetSettingFromSQL("host_key_RSA_keysize"), 
            "fingerprint_type1" => $this->GetSettingFromSQL("host_key_RSA_fingerprint_type1"), 
            "fingerprint1" => $this->GetSettingFromSQL("host_key_RSA_fingerprint1"), 
            "keytype2" => $this->GetSettingFromSQL("host_key_RSA_keytype2"),
            "fingerprint_type2" => $this->GetSettingFromSQL("host_key_RSA_fingerprint2"),
            "fingerprint2" => $this->GetSettingFromSQL("host_key_RSA_fingerprint2"),
            "date" => $this->GetSettingFromSQL("host_key_RSA_date"));
        sort($host_keys);
        return $host_keys;
    }
    
    function CreateFakeHostKeys($host) {
        $this->SetSetting("host_key_DSA_path", "/etc/ssh/ssh_host_dsa_key.pub");
        $this->SetSetting("host_key_DSA_keytype1", "ssh-dss");
        $this->SetSetting("host_key_DSA_key_part1", "AAAAB3NzaC1kc3MAAACBA".str_shuffle("OwDb/8yj+UaZ29QoXrGb2bMtddmrBnGFGmI6ZZbqHjstAZHVTXwUTVSHtyV/GXc70e8wyTUL5i/tZ9Vi5PhJIdGVxG66vEehgQMga+sv6KrbKxHna7rrYY8XXV4eUP448eRiKMmEF5GIzjWaKYt8u7I2fy8u2Dleap1IL03hLKtAAAAFQCl"));
        $this->SetSetting("host_key_DSA_key_part2", str_shuffle("B5yv5GxH659OJutQW+7RKIexdwAAAIBwDBUtVY/7Y6eQqvjBMgL8uVfDih/NhBy90qmVi3aQXU46b0cCoF22DRkM/nbksRiGLnzwrCFQ1HLOZychZ0Fq/mJGq9cLZ+w6aDPoknqnrTDf4PnZxz46ZuoxTPiridYbsUfnvvWn4mmRmKE1Vtce9W9yqo2Ch/PiWoE3P1Z/"));
        $this->SetSetting("host_key_DSA_key_part3", str_shuffle("pwAAAIBFbJwBhE+AUT2JOHmd9Ds8nSG8U1Mg+3xY5Jp1Nm5KvLish9DPLM/n5Tf9iSETo7vLczibmzzfmV8DQH+h1canhlg3z1aCg4crBnDepKqY/bMMSlWfLsl5YX9cEjOLdrI4Kh3hZr3qCfkIJpIJCT+kZsA7cx2bPI7pA4kZxgt0+A")."==");
        $this->SetSetting("host_key_DSA_comment", "root@raspbx");
        $this->SetSetting("host_key_DSA_keysize", 1024);
        $this->SetSetting("host_key_DSA_fingerprint_type1", "SHA256");
        $this->SetSetting("host_key_DSA_fingerprint1", str_shuffle("/PI/DoFLGM3b33xwdNWzMoj4b0wGlM2UiklXLcDxFg8"));
        $this->SetSetting("host_key_DSA_keytype2", "DSA");
        $this->SetSetting("host_key_DSA_fingerprint_type2", "MD5");
        $this->SetSetting("host_key_DSA_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        $this->SetSetting("host_key_DSA_date", date("Y-m-d H:m:s T"));
        
        $this->SetSetting("host_key_ECDSA_path", "/etc/ssh/ssh_host_ecdsa_key.pub");
        $this->SetSetting("host_key_ECDSA_keytype1", "ecdsa-sha2-nistp521");
        $this->SetSetting("host_key_ECDSA_key_part1", "AAAAE2VjZHNhLXNoYTItbmlzdHAyNTYAAA".str_shuffle("AIbmlzdHAyNTYAAABBBCausMe/hNEIIFhigC728H7u8B0oShWMqb+D+/zot80eWMPMEeJBFfg0racOYeiBCeJt2mWjLOzrkvHZSR91nH8")."=");
        $this->SetSetting("host_key_ECDSA_key_part2", "");
        $this->SetSetting("host_key_ECDSA_key_part3", "");
        $this->SetSetting("host_key_ECDSA_comment", "root@raspbx");
        $this->SetSetting("host_key_ECDSA_keysize", 256);
        $this->SetSetting("host_key_ECDSA_fingerprint_type1", "SHA256");
        $this->SetSetting("host_key_ECDSA_fingerprint1", str_shuffle("lL0vRVBfJo33d+Vic8qpQuoQuC790AjPemhrjH3AdBc"));
        $this->SetSetting("host_key_ECDSA_keytype2", "ECDSA");
        $this->SetSetting("host_key_ECDSA_fingerprint_type2", "MD5");
        $this->SetSetting("host_key_ECDSA_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        $this->SetSetting("host_key_ECDSA_date", date("Y-m-d H:m:s T"));
        
        $this->SetSetting("host_key_ED25519_path", "/etc/ssh/ssh_host_ed25519_key.pub");
        $this->SetSetting("host_key_ED25519_keytype1", "ssh-ed25519");
        $this->SetSetting("host_key_ED25519_key_part1", "AAAAC3NzaC1lZDI1NTE5AAAAIM".str_shuffle("BN5pgUPi7KdxacQ476ky4LKG0hjlMhhulkmvzs7Y3i"));
        $this->SetSetting("host_key_ED25519_key_part2", "");
        $this->SetSetting("host_key_ED25519_key_part3", "");
        $this->SetSetting("host_key_ED25519_comment", "root@raspbx");
        $this->SetSetting("host_key_ED25519_keysize", 256);
        $this->SetSetting("host_key_ED25519_fingerprint_type1", "SHA256");
        $this->SetSetting("host_key_ED25519_fingerprint1", str_shuffle("BWV3KpLKQWk/Ycs7SgmgW5zD6cPvVbYu612N+LJ9KAw"));
        $this->SetSetting("host_key_ED25519_keytype2", "ED25519");
        $this->SetSetting("host_key_ED25519_fingerprint_type2", "MD5");
        $this->SetSetting("host_key_ED25519_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        $this->SetSetting("host_key_ED25519_date", date("Y-m-d H:m:s T"));
         
        $this->SetSetting("host_key_RSA_path", "/etc/ssh/ssh_host_rsa_key.pub");
        $this->SetSetting("host_key_RSA_keytype1", "ssh-rsa");
        $this->SetSetting("host_key_RSA_key_part1", "AAAAB3NzaC1yc2EAAAADAQABAAABgQD".str_shuffle("ESC2OkaRvOmVFIa3Grq5Cjr3eFtiEQTdghJIXkhO4pisDDR0+KTmCL+YE83H5ey/XM4umyk37iJOqER4hy6pNULaY8pEo+NNhKFfGGL0s5P/OeBGozAUVsxupt+zdctkqCnmLmzHVYYGRlHMRmGSuQcANnzNk36x6DElw5Xoc"));
        $this->SetSetting("host_key_RSA_key_part2", str_shuffle("iI88cFqXaiDgjQ0A0sHldzvEhqA0L7IHxpI4a5dwXPyOcK+Dm9d+YLjpekXE+ti6b/UFr0bh0plKjanvzP6eIGXCaNK+gNn+0CJp1l61M8Jc8QMusSiPVzCtwpgyuUONcRpNbat5nxdT6cizobNQ5t+1XB1HLapB3I9TAXf/wOgAC39U4amAwyz6R7t+s3HAT6/KKIKW"));
        $this->SetSetting("host_key_RSA_key_part3", str_shuffle("IRPyyyus2HPtq5Gi92aQ6CEYTHTcS2usnizcoV2f2BNybmfgqle3Ipbv+1m3iAN0ZDELWGIw1kcay1xVAlnhZhkWN/DFnQ+7XgKts5w4Cnfr0jDAdj/2XRlUgD3nvnvCStXAghBTiRYirVU")."=");
        $this->SetSetting("host_key_RSA_comment", "root@raspbx");
        $this->SetSetting("host_key_RSA_keysize", 3072);
        $this->SetSetting("host_key_RSA_fingerprint_type1", "SHA256");
        $this->SetSetting("host_key_RSA_fingerprint1", str_shuffle("Vg7IBlKrhlSKtXvd4HAhpviNnn/LvqgOs0GOhMwL+9o"));
        $this->SetSetting("host_key_RSA_keytype2", "RSA");
        $this->SetSetting("host_key_RSA_fingerprint_type2", "MD5");
        $this->SetSetting("host_key_RSA_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        $this->SetSetting("host_key_RSA_date", date("Y-m-d H:m:s T"));
    }
    
    function GetSshUserKeys($ssh_query_output, $unixusers) {
        foreach($unixusers as $unixuser) {
            if($this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_keytype2") === "RSA") {
                $user_keys[] = array(
                "uidgiduser" => $this->GetFormattedUidGidByUsername($unixuser["name"], $unixusers), 
                "user" =>  $unixuser["name"], 
                "path" =>  $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_path"), 
                "keytype1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_keytype1"), 
                "key" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_key_part1").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_key_part2").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_key_part3").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_key_part4"), 
                "comment" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_comment"), 
                "keysize" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_keysize"), 
                "fingerprint_type1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_fingerprint_type1"), 
                "fingerprint1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_fingerprint1"), 
                "keytype2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_keytype2"),  
                "fingerprint_type2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_fingerprint_type2"),  
                "fingerprint2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_fingerprint2"), 
                "date" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_RSA_date"));
            }
            if($this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_keytype2") === "DSA") {
                $user_keys[] = array(
                "uidgiduser" => $this->GetFormattedUidGidByUsername($unixuser["name"], $unixusers), 
                "user" =>  $unixuser["name"], 
                "path" =>  $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_path"), 
                "keytype1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_keytype1"), 
                "key" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_key_part1").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_key_part2").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_key_part3").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_key_part4"), 
                "comment" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_comment"), 
                "keysize" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_keysize"), 
                "fingerprint_type1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_fingerprint_type1"), 
                "fingerprint1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_fingerprint1"), 
                "keytype2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_keytype2"),  
                "fingerprint_type2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_fingerprint_type2"),  
                "fingerprint2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_fingerprint2"), 
                "date" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_DSA_date"));
            }
            if($this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_keytype2") === "ECDSA") {
                $user_keys[] = array(
                "uidgiduser" => $this->GetFormattedUidGidByUsername($unixuser["name"], $unixusers), 
                "user" =>  $unixuser["name"], 
                "path" =>  $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_path"), 
                "keytype1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_keytype1"), 
                "key" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_key_part1").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_key_part2").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_key_part3").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_key_part4"), 
                "comment" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_comment"), 
                "keysize" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_keysize"), 
                "fingerprint_type1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_fingerprint_type1"), 
                "fingerprint1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_fingerprint1"), 
                "keytype2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_keytype2"),  
                "fingerprint_type2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_fingerprint_type2"),  
                "fingerprint2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_fingerprint2"), 
                "date" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ECDSA_date"));
            }
            if($this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_keytype2") === "ED25519") {
                $user_keys[] = array(
                "uidgiduser" => $this->GetFormattedUidGidByUsername($unixuser["name"], $unixusers), 
                "user" =>  $unixuser["name"], 
                "path" =>  $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_path"), 
                "keytype1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_keytype1"), 
                "key" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_key_part1").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_key_part2").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_key_part3").$this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_key_part4"), 
                "comment" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_comment"), 
                "keysize" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_keysize"), 
                "fingerprint_type1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_fingerprint_type1"), 
                "fingerprint1" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_fingerprint1"), 
                "keytype2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_keytype2"),  
                "fingerprint_type2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_fingerprint_type2"),  
                "fingerprint2" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_fingerprint2"), 
                "date" => $this->GetSettingFromSQL("user_key_".$unixuser["name"]."_ED25519_date"));
            }
        }
        sort($user_keys);
        return $user_keys;
    }
    
    function CreateFakeRSAKey($user, $host) {
        if($user === "root") {
            $path = "/root/.ssh/id_rsa.pub";
        } else {
            $path = "/home/".$user."/.ssh/id_rsa.pub";
        }
        $this->SetSetting("user_key_".$user."_RSA_path", $path);
        $this->SetSetting("user_key_".$user."_RSA_keytype1", "ssh-rsa");
        $this->SetSetting("user_key_".$user."_RSA_key_part1", "AAAAB3NzaC1yc2EAAAADAQABAAACAQD".str_shuffle("ZgPWHtj7+9ibFpKK6GgpI2wIdKkfA9G7PmQaLCfmlukwef+gu9k5PoSr/uZE+lXLgiQxonUy1P14NQBf0UlPFdJBw96z7hyM3cN1Gw4qf6/PTKSrphxLjJQq9JPCx3eBgQ36GGQ6rIrZ838ddDfIcif6HXQb/dt0gvzXN2Zz3"));
        $this->SetSetting("user_key_".$user."_RSA_key_part2", str_shuffle("dBA9CWoqwVD0JAC1CmTO+Koeztj9JdsdXRzPauy3LDg6m/Q0o0CQZ9HWQGWxZuwbuBRlEr8PdwNJsIiW7K5imI1VehSiNEjO5k45NcYXtRI3LaJBZinuHRcHXuo/sBdFDrM/EXxe/w2g29fATcR2wFUDNY+xmc6zxaYta3fYotJ4QydTvlGHRUmAjKgDzR7XguCFZMV5"));
        $this->SetSetting("user_key_".$user."_RSA_key_part3", str_shuffle("1HXOtP88d2lS32SWdBcSYMjesgnveTYrKuGKNv4Ut6D673YURPytZdKhkw6nsr/pdR1g12f4l++bJGRSFPz3zDjXKcVUPCK8NdRHzvwrPVF3dwdc72q7NHNZa7//888np2RN+5e2k/yZ0LJr8NrGcxcKRBoSmDV8vW1IGEGKs8ccYw4xY3v/c+Jy3FJ5nfVB85S3aTOL"));
        $this->SetSetting("user_key_".$user."_RSA_key_part4", str_shuffle("L7i9qqIUFdRYGIzOyfxuh5ZzzPYSWChL1QnOfjS+1vQTItCxR97u2f72lxeWY2WNIfajWdO1wo8YA4Tlmxp3wYxoyiZj6N5LMdRgd5ClM8Oqh09SRw")."==");
        $this->SetSetting("user_key_".$user."_RSA_comment", $user."@".$host);
        $this->SetSetting("user_key_".$user."_RSA_keysize", 4096);
        $this->SetSetting("user_key_".$user."_RSA_fingerprint_type1", "SHA256");
        $this->SetSetting("user_key_".$user."_RSA_fingerprint1", str_shuffle("AFx5Gu1Wedwuab4JIAbMDcLENSuYHDTlt4+kP/fdzNM"));
        $this->SetSetting("user_key_".$user."_RSA_keytype2", "RSA");
        $this->SetSetting("user_key_".$user."_RSA_fingerprint_type2", "MD5");
        $this->SetSetting("user_key_".$user."_RSA_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        $this->SetSetting("user_key_".$user."_RSA_date", date("Y-m-d H:m:s T"));
    }
    
    function CreateFakeDSAKey($user, $host) {
        if($user === "root") {
            $path = "/root/.ssh/id_dsa.pub";
        } else {
            $path = "/home/".$user."/.ssh/id_dsa.pub";
        }
        $this->SetSetting("user_key_".$user."_DSA_path", $path);
        $this->SetSetting("user_key_".$user."_DSA_keytype1", "ssh-dss");
        $this->SetSetting("user_key_".$user."_DSA_key_part1", "AAAAB3NzaC1kc3MAAACBA".str_shuffle("KP9ykkW7UrGvE6KLx7DEL+oHTOcCYdbRvCdH9R7z7fsAc/etjhi0EreWbPPEMOG9XrP5QMiemQrOtqj4SfzJaUkgNagNrmATpnevGOQwR28KQxQldAqKOQLbiD0B+72r3M/jj39XkC/H3OJzhmx+1MNObawqshGkbVrSD0f68CfAAAAFQD3"));
        $this->SetSetting("user_key_".$user."_DSA_key_part2", str_shuffle("Dg3MTvNBupgH/VA5gneP4FZbPQAAAIEAo3zXk2ujQ+Q5U3Zzh+kAp7mcMYvGP4uldZ3YQa6Or9oTVG8yDF7cLNaB4tKNDMRCu7EAMuShVYiRvGCYmWD6lKW3+g+VFa/oiAv9/2ef+of6i64DV4qXWAFIK2AP8ALz4xtvq+7s4Q17wKH02VNgnR/R8RNIlNDmKuWuRy2D"));
        $this->SetSetting("user_key_".$user."_DSA_key_part3", str_shuffle("pc8AAACAYGMhBR5JtOAefpq271N2sFF/mEllCz5jKtB0aU2rqGPk98aXlZDnQ/LukFxdmdTCAySs9Zs5d0ZWEHSXw+mk2O45H0ceFDk/xkAZsbPSYdIJGNrMdMAL1aT5FnV2bOytsMejD/1Uu2C409hHpI3rFBQ2z1+DihlhYfVXffb4gS4")."=");
        $this->SetSetting("user_key_".$user."_RSA_key_part4", "");
        $this->SetSetting("user_key_".$user."_DSA_comment", $user."@".$host);
        $this->SetSetting("user_key_".$user."_DSA_keysize", 1024);
        $this->SetSetting("user_key_".$user."_DSA_fingerprint_type1", "SHA256");
        $this->SetSetting("user_key_".$user."_DSA_fingerprint1", str_shuffle("gfE+2bV2eGD/nvTXLs7vIZHxDWACxsIaNnxA0XS8Rhc"));
        $this->SetSetting("user_key_".$user."_DSA_keytype2", "DSA");
        $this->SetSetting("user_key_".$user."_DSA_fingerprint_type2", "MD5");
        $this->SetSetting("user_key_".$user."_DSA_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        $this->SetSetting("user_key_".$user."_DSA_date", date("Y-m-d H:m:s T"));
    }
    
    function CreateFakeECDSAKey($user, $host) {
        if($user === "root") {
            $path = "/root/.ssh/id_ecdsa.pub";
        } else {
            $path = "/home/".$user."/.ssh/id_ecdsa.pub";
        }
        $this->SetSetting("user_key_".$user."_ECDSA_path", $path);
        $this->SetSetting("user_key_".$user."_ECDSA_keytype1", "ecdsa-sha2-nistp521");
        $this->SetSetting("user_key_".$user."_ECDSA_key_part1", "AAAAE2VjZHNhLXNoYTItbmlzdHA1MjEAAAAIbmlzdHA1MjEAAACFBA".str_shuffle("FewZHOZ6bUyTfV+u0hRISs3uTrny7yaxPEqtQYoIUlZ96wz+tvOLnVlg09m0bCUyqrMN0uYTDDrQqSkLZ9Cvgr8gFXRg6XkxB+/YCjiAOHhpYOY4ZcqlUu+VLlem5v1Ggri27Kdmnfmr5YAyUAnvkXiits93raS5fOPUjOVu3SZrd4LQ")."==");
        $this->SetSetting("user_key_".$user."_ECDSA_key_part2", "");
        $this->SetSetting("user_key_".$user."_ECDSA_key_part3", "");
        $this->SetSetting("user_key_".$user."_ECDSA_key_part4", "");
        $this->SetSetting("user_key_".$user."_ECDSA_comment", $user."@".$host);
        $this->SetSetting("user_key_".$user."_ECDSA_keysize", 521);
        $this->SetSetting("user_key_".$user."_ECDSA_fingerprint_type1", "SHA256");
        $this->SetSetting("user_key_".$user."_ECDSA_fingerprint1", str_shuffle("GcvddBlQL8c04gSrqTLz4bQ0CB7vKoBtcW/uOOiCe5M"));
        $this->SetSetting("user_key_".$user."_ECDSA_keytype2", "ECDSA");
        $this->SetSetting("user_key_".$user."_ECDSA_fingerprint_type2", "MD5");
        $this->SetSetting("user_key_".$user."_ECDSA_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        $this->SetSetting("user_key_".$user."_ECDSA_date", date("Y-m-d H:m:s T"));
    }
    
    function CreateFakeED25519Key($user, $host) {
        if($user === "root") {
            $path = "/root/.ssh/id_ed25519.pub";
        } else {
            $path = "/home/".$user."/.ssh/id_ed25519.pub";
        }
        $this->SetSetting("user_key_".$user."_ED25519_path", $path);
        $this->SetSetting("user_key_".$user."_ED25519_keytype1", "ssh-ed25519");
        $this->SetSetting("user_key_".$user."_ED25519_key_part1", "AAAAC3NzaC1lZDI1NTE5AAAAI".str_shuffle("OjGygDT0fwF/IcxWuxLQL3DjBweUL3KoATMz7pMaCot"));
        $this->SetSetting("user_key_".$user."_ED25519_key_part2", "");
        $this->SetSetting("user_key_".$user."_ED25519_key_part3", "");
        $this->SetSetting("user_key_".$user."_ED25519_key_part4", "");
        $this->SetSetting("user_key_".$user."_ED25519_comment", $user."@".$host);
        $this->SetSetting("user_key_".$user."_ED25519_keysize", 256);
        $this->SetSetting("user_key_".$user."_ED25519_fingerprint_type1", "SHA256");
        $this->SetSetting("user_key_".$user."_ED25519_fingerprint1", str_shuffle("tMO7f8t7LB7GsW3L3HvkE3vwSTx9xumWAQeIX5nAtoo"));
        $this->SetSetting("user_key_".$user."_ED25519_keytype2", "ED25519");
        $this->SetSetting("user_key_".$user."_ED25519_fingerprint_type2", "MD5");
        $this->SetSetting("user_key_".$user."_ED25519_fingerprint2", $this->GenerateFakeHexDecPairs(16));
        $this->SetSetting("user_key_".$user."_ED25519_date", date("Y-m-d H:m:s T"));
    }
    
    function GenerateFakeHexDecPairs($pairs) {
        $chars = "abcdef012345678";
        for($i= 0; $i < $pairs; $i++) {
            $num = rand(0, strlen($chars) - 1);
            $fingerprint .= ":".substr($chars, $num, 1);
            $num = rand(0, strlen($chars) - 1);
            $fingerprint .= substr($chars, $num, 1);
        }
        return substr($fingerprint, 1);
    }
    
    function GetSettingFromSQL($setting) {
        $value = $this->SQL->mySQLiQuery("SELECT value FROM settings WHERE BINARY "
                . "setting=".json_encode($setting)." AND BINARY "
                . "session_id=".json_encode(session_id()))[0]['value'];
        return $value;
    }
    
    function DeleteSettingSQL($setting) {
        $this->SQL->mySQLiQuery("DELETE FROM settings WHERE BINARY "
                     . "setting =".json_encode($setting)." AND "
                     . "session_id=".json_encode(session_id()));
    }
    
    function GetSetting($configuration_file, $setting) {
        if($configuration_file === "/etc/ssh/sshd_config" and $setting === "PermitRootLogin") {
            $value = $this->GetSettingFromSQL("PermitRootLogin");
        }
        if($configuration_file === "/etc/ssh/sshd_config" and $setting === "PasswordAuthentication") {
            $value = $this->GetSettingFromSQL("PasswordAuthentication");
        }
        if($configuration_file === "/etc/ssh/sshd_config" and $setting === "UsePAM") {
            $value = $this->GetSettingFromSQL("UsePAM");
        }
        if($configuration_file === "/etc/ssh/ssh_config" and $setting === "HashKnownHosts") {
            $value = $this->GetSettingFromSQL("HashKnownHosts");
        }
        $setting_array = array(
            "file" => $configuration_file,
            "setting" => $setting, 
            "value" => $value,
            "setting1" => "no",
            "setting2" => "yes",
                );
        return $setting_array;
    }
    
    function GetSettingsSsh() {
        $settings_ssh[] = $this->GetSetting("/etc/ssh/sshd_config", "PermitRootLogin");
        $settings_ssh[] = $this->GetSetting("/etc/ssh/sshd_config", "PasswordAuthentication");
        $settings_ssh[] = $this->GetSetting("/etc/ssh/sshd_config", "UsePAM");
        $settings_ssh[] = $this->GetSetting("/etc/ssh/ssh_config", "HashKnownHosts");
        return $settings_ssh;
    }
    
    function UpdateSetting($setting, $new_value) {
        $this->SQL->mySQLiQuery("UPDATE settings SET value=".json_encode($new_value)." WHERE BINARY "
                    . "setting=".json_encode($setting)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
    }
    
    function CreateUser($uid, $gid, $name) {
        $this->SQL->mySQLiQuery("INSERT INTO raspberry_emulation VALUES ("
                .json_encode(session_id()).","
                .json_encode($uid).","
                .json_encode($gid).","
                .json_encode($name).","
                .json_encode(password_hash("unknown", PASSWORD_DEFAULT)).")");
    }
    
    function SetSetting($setting, $value) {
        $this->SQL->mySQLiQuery("INSERT INTO settings VALUES ("
                .json_encode(session_id()).","
                .json_encode($setting).","
                .json_encode($value).")");
    }
    
    function GetLanguagesAvailable() {
        $languages_available = $this->SQL->mySQLiQuery("SELECT * FROM languages ORDER BY token");
        return $languages_available;
    }
    
    function DeleteUsersOnDemoEnd() {
        $this->SQL->mySQLiQuery("DELETE FROM raspberry_emulation WHERE BINARY "
                . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("DELETE FROM email_accounts_emulated WHERE BINARY "
                . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("DELETE FROM settings WHERE BINARY "
                . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("DELETE FROM samba_shares_emulated WHERE BINARY "
                . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("DELETE FROM valid_users_emulated WHERE BINARY "
                . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("DELETE FROM resources_emulated WHERE BINARY "
                . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("DELETE FROM fstab_emulated WHERE BINARY "
                . "session_id=".json_encode(session_id()));
    }
    
    function DeductOne($line_number, $email_addresses) {
        foreach($email_addresses as $email_address) {
            if($email_address['pop3_user_line_number'] > $line_number) {
                $this->SQL->mySQLiQuery("UPDATE email_accounts_emulated SET pop3_user_line_number=".json_encode($email_address['pop3_user_line_number'] - 1)." WHERE BINARY "
                    . "debian_user=".json_encode($email_address['debian_user'])." AND BINARY "
                    . "pop3_server=".json_encode($email_address['pop3_server'])." AND BINARY "
                    . "pop3_user=".json_encode($email_address['pop3_user'])." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            }
            if($email_address['pop3_server_line_number'] > $line_number) {
                $this->SQL->mySQLiQuery("UPDATE email_accounts_emulated SET pop3_server_line_number=".json_encode($email_address['pop3_server_line_number'] - 1)." WHERE BINARY "
                    . "debian_user=".json_encode($email_address['debian_user'])." AND BINARY "
                    . "pop3_server=".json_encode($email_address['pop3_server'])." AND BINARY "
                    . "pop3_user=".json_encode($email_address['pop3_user'])." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            }
        }
    }
    
    function AddOne($line_number, $email_addresses) {
        foreach($email_addresses as $email_address) {
            if($email_address['pop3_user_line_number'] > $line_number) {
                $this->SQL->mySQLiQuery("UPDATE email_accounts_emulated SET pop3_user_line_number=".json_encode($email_address['pop3_user_line_number'] + 1)." WHERE BINARY "
                    . "debian_user=".json_encode($email_address['debian_user'])." AND BINARY "
                    . "pop3_server=".json_encode($email_address['pop3_server'])." AND BINARY "
                    . "pop3_user=".json_encode($email_address['pop3_user'])." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            }
            if($email_address['pop3_server_line_number'] > $line_number) {
                $this->SQL->mySQLiQuery("UPDATE email_accounts_emulated SET pop3_server_line_number=".json_encode($email_address['pop3_server_line_number'] + 1)." WHERE BINARY "
                    . "debian_user=".json_encode($email_address['debian_user'])." AND BINARY "
                    . "pop3_server=".json_encode($email_address['pop3_server'])." AND BINARY "
                    . "pop3_user=".json_encode($email_address['pop3_user'])." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            }
        }
    }
    
    function DoesLineContainServerOrAccount($line_number, $email_addresses) {
        foreach($email_addresses as $email_address) {
            if($email_address['pop3_server_line_number'] === $line_number) {
                return "server";
            }
            if($email_address['pop3_user_line_number'] === $line_number) {
                return "account";
            }
        }
    }
    
    function DoesPOP3ServerExist($pop3_server, $email_addresses) {
        $result = false;
        foreach($email_addresses as $email_address) {
            if($email_address['pop3_server'] === $pop3_server) {
                return $email_address['pop3_server_line_number'];
            }
        }
        return $result;
    }
    
    function GetNumberOfPOP3Servers($email_addresses) {
        $pop3_servers = [];
        foreach($email_addresses as $email_address) {
            if(!in_array($email_address['pop3_server'], $pop3_servers)) {
                $pop3_servers[] = $email_address['pop3_server'];
            }
        }
        return count($pop3_servers);
    }
    
    function AddEmailAccountToFetchmailrc($debian_user, $pop3_server, $pop3_user, $pop3_password) {
        $email_addresses = $this->GetEmailAddresses();
        $number_of_pop3_servers = $this->GetNumberOfPOP3Servers($email_addresses);
        $result = $this->DoesPOP3ServerExist($pop3_server, $email_addresses);
        if(!$result) {
            $pop3_server_line_number = 6 + $number_of_pop3_servers + count($email_addresses);
            $pop3_user_line_number = $pop3_server_line_number + 1;
        } else {
            $pop3_server_line_number = $result;
            $pop3_user_line_number = $pop3_server_line_number + 1;
            $this->AddOne($pop3_server_line_number, $email_addresses);
        }
        $this->CreateEmailAccount($debian_user, $pop3_server, $pop3_user, $pop3_password, $pop3_server_line_number, $pop3_user_line_number);
    }
    
    function TrimExcessWhiteSpaces($string) {
        $string_trimmed = trim(preg_replace("/\s+/", " ", $string));
        return $string_trimmed;
    }
    
    function DeleteLinesInFetchmailrc($lines_to_delete) {
        $email_addresses = $this->GetEmailAddresses();
        foreach($lines_to_delete as $line_to_delete) {
            if($this->DoesLineContainServerOrAccount($line_to_delete, $email_addresses) === "account") {
                $this->SQL->mySQLiQuery("DELETE FROM email_accounts_emulated WHERE BINARY "
                     . "pop3_user_line_number =".json_encode($line_to_delete)." AND "
                     . "session_id=".json_encode(session_id()));
            }
        }
        foreach($lines_to_delete as $server_line_to_delete) {
             $this->DeductOne($server_line_to_delete, $email_addresses);
        }
    }
    
    function DeductSegment($segment_to_delete) {
        $samba_shares = $this->GetSambaShares();
        foreach($samba_shares  as $samba_share) {
            if($samba_share["line_begin"] > $segment_to_delete[0]) {
                $new_line_begin = $samba_share["line_begin"] - ($segment_to_delete[1] - $segment_to_delete[0] + 1);
                $new_line_end = $samba_share["line_end"] - ($segment_to_delete[1] - $segment_to_delete[0] + 1);
                $this->SQL->mySQLiQuery("UPDATE samba_shares_emulated SET line_begin=".json_encode($new_line_begin)." WHERE BINARY "
                    . "share=".json_encode($samba_share["share"])." AND BINARY "
                    . "session_id=".json_encode(session_id()));
                $this->SQL->mySQLiQuery("UPDATE samba_shares_emulated SET line_end=".json_encode($new_line_end)." WHERE BINARY "
                    . "share=".json_encode($samba_share["share"])." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            }
        }
    }
    
    function AddSambaShare($share, $path, $writable, $browsable, $guest_ok, $valid_users_string) {
        $samba_shares = $this->GetSambaShares();
        if(count($samba_shares) === 0) {
            $new_line_begin = 213;
            $new_line_end = 218;
        } else {
            foreach($samba_shares as $samba_share) {
                $lines_begin[] = $samba_share["line_begin"];
                $lines_end[] = $samba_share["line_end"];
            }
            $new_line_begin = max($lines_begin) + 7;
            $new_line_end = max($lines_end) + 7;
        }
        $this->SQL->mySQLiQuery("INSERT INTO samba_shares_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode($share).","
                .json_encode($path).","
                .json_encode($writable).","
                .json_encode($browsable).","
                .json_encode($guest_ok).","
                .json_encode($new_line_begin).","
                .json_encode($new_line_end).")");
        $valid_users = explode(",", $valid_users_string);
        foreach($valid_users as $valid_user) {
            $this->SQL->mySQLiQuery("INSERT INTO valid_users_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode($share).","
                .json_encode($valid_user).")");
        }
    }
    
    function DeleteSambaShare($segments_to_delete) {
        foreach($segments_to_delete as $segment_to_delete) {
            $line_begin = intval(explode(",", $segment_to_delete)[0]);
            $line_end = intval(explode(",", $segment_to_delete)[1]);
            $line_end--;
            $share = $this->SQL->mySQLiQuery("SELECT share FROM samba_shares_emulated WHERE BINARY "
                     . "line_begin =".json_encode($line_begin)." AND "
                     . "line_end = ".json_encode($line_end)." AND "
                     . "session_id=".json_encode(session_id()))[0]["share"];
            $this->SQL->mySQLiQuery("DELETE FROM samba_shares_emulated WHERE BINARY "
                     . "share = ".json_encode($share)." AND "
                     . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("DELETE FROM valid_users_emulated WHERE BINARY "
                     . "share =".json_encode($share)." AND "
                     . "session_id=".json_encode(session_id()));
        }
        foreach($segments_to_delete as $segment_to_delete) {
            $line_begin = intval(explode(",", $segment_to_delete)[0]);
            $line_end = intval(explode(",", $segment_to_delete)[1]);
            $this->DeductSegment([$line_begin, $line_end]);
        }
    }
    
    function PurgeSambaUsers($commands_valid_users) {
        $samba_shares = $this->GetSambaShares();
        foreach($commands_valid_users as $command_valid_users) {
            $line = intval(substr($command_valid_users, 0, strpos($command_valid_users, "c")));
            $valid_users_string = trim(explode("=", $command_valid_users)[1]);
            foreach($samba_shares as $samba_share) {
                if($line >= $samba_share["line_begin"] and $line <= $samba_share["line_end"]) {
                    $share = $samba_share["share"];
                    $this->SQL->mySQLiQuery("DELETE FROM valid_users_emulated WHERE BINARY "
                    . "share=".json_encode($share)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
                    $valid_users = explode(",", $valid_users_string);
                    foreach($valid_users as $valid_user) {
                       $this->CreateValidUser($share, $valid_user);
                    }
                }
            }
        }
    }
    
    function SetSshSetting($sed_commands) {
        $sed_command = $this->TrimExcessWhiteSpaces($sed_commands[0]);
        $setting = substr(explode(" ", $sed_command)[1], 1);
        $value_new = substr(explode(" ", $sed_command)[3], 0, strlen(explode(" ", $sed_command)[3]) - 1);
        $this->UpdateSetting($setting, $value_new);
    }
    
    function ExtractSedCommands($command, $file) {
        $string1 = trim($command, "sed -i -e ");
        $string2 = trim($string1, $file);
        $haystack = $string2;
        $needle = "\\'";
        $positions = [];
        $pos_last = 0;
        while(($pos_last = strpos($haystack, $needle, $pos_last)) !== false) {
            $positions[] = $pos_last;
            $pos_last = $pos_last + strlen($needle);
        }
        $i = 0;
        foreach($positions as $position) {
            if($i % 2 === 0) {
                $pos_start[] = $position;
            } else {
                $pos_end[] = $position;
            }
            $i++;
        }
        for($i = 0; $i < count($pos_start); $i++) {
            $sed_commands[] = trim(substr($string2, $pos_start[$i], $pos_end[$i] - $pos_start[$i]), "\\'");
        }
        return $sed_commands;
    }
    
    function GenerateSSHUserKeys($string, $type) {
        if(substr($string, 0, 6) === "/root/") {
            $user = "root";
//            $path = "/root/.ssh/";
        } else {
            $string1 = substr($string, 6);
            $pos1 = strpos($string1, "/");
            $user = substr($string1, 0, $pos1);
//            $path = "/home/".$user."/.ssh/";
        }
        $output_buffering_size = str_repeat(" ", 4096);
        switch ($type) {
            case "RSA":
                $this->CreateFakeRSAKey($user, "raspbx");
                break;
            case "DSA":
                $this->CreateFakeDSAKey($user, "raspbx");
                break;
            case "ECDSA":
                $this->CreateFakeECDSAKey($user, "raspbx");
                break;
            case "ED25519":
                $this->CreateFakeED25519Key($user, "raspbx");
                break;
        }
        echo  $output_buffering_size."^Generating public/private "
                . strtolower($this->GetSettingFromSQL("user_key_".$user."_".$type."_keytype2"))
                . " key pair.<br>"
            . $output_buffering_size."^Your identification has been saved in "
                . substr($this->GetSettingFromSQL("user_key_".$user."_".$type."_path"), 0, strlen($this->GetSettingFromSQL("user_key_".$user."_".$type."_path")) - 4)
                . "<br>"
            . $output_buffering_size."^Your public key has been saved in "
                . $this->GetSettingFromSQL("user_key_".$user."_".$type."_path")
                . "<br>"
            . $output_buffering_size."^The key fingerprint is:<br>"
            . $output_buffering_size."^"
                . $this->GetSettingFromSQL("user_key_".$user."_".$type."_fingerprint_type1").":"
                . $this->GetSettingFromSQL("user_key_".$user."_".$type."_fingerprint1")." "
                . $this->GetSettingFromSQL("user_key_".$user."_".$type."_comment")
                . "<br>"
            . $output_buffering_size."^The key's randomart image is:<br>"
            . $output_buffering_size."^+--["
                . $this->GetSettingFromSQL("user_key_".$user."_".$type."_keytype2")." "
                . $this->GetSettingFromSQL("user_key_".$user."_".$type."_keysize")
                . "]--+<br>"
        . $output_buffering_size."^|".str_shuffle("   o=ooB*=+      ")."|<br>"
        . $output_buffering_size."^|".str_shuffle("   +  =+*o=B     ")."|<br>"
        . $output_buffering_size."^|".str_shuffle("  o .  o.=++o. . ")."|<br>"
        . $output_buffering_size."^|".str_shuffle(" . o   o oo o E  ")."|<br>"
        . $output_buffering_size."^|".str_shuffle("  o + + S .. o   ")."|<br>"
        . $output_buffering_size."^|".str_shuffle("   + + B .  +    ")."|<br>"
        . $output_buffering_size."^|".str_shuffle("    . o .    o   ")."|<br>"
        . $output_buffering_size."^|".str_shuffle("              .  ")."|<br>"
        . $output_buffering_size."^|".str_shuffle("                 ")."|<br>"
        . $output_buffering_size."^+----[SHA256]-----+"; flush();
    }
    
    function DeleteSSHUserKey($string, $type) {
        if(substr($string, 0, 6) === "/root/") {
            $user = "root";
        } else {
            $string1 = substr($string, 6);
            $pos1 = strpos($string1, "/");
            $user = substr($string1, 0, $pos1);
        }
        $this->DeleteKey($user, $type);
    }
    
    function DeleteKey($user, $type) {
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_path");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_keytype1");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_key_part1");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_key_part2");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_key_part3");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_key_part4");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_comment");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_keysize");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_fingerprint_type1");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_fingerprint1");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_keytype2");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_fingerprint_type2");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_fingerprint2");
        $this->DeleteSettingSQL("user_key_".$user."_".$type."_date");
    }
    
    function DeleteHostKeys() {
        $array = ["DSA", "ECDSA", "ED25519", "RSA"];
        foreach($array as $type) {
            $this->DeleteSettingSQL("host_key_".$type."_path");
            $this->DeleteSettingSQL("host_key_".$type."_keytype1");
            $this->DeleteSettingSQL("host_key_".$type."_key_part1");
            $this->DeleteSettingSQL("host_key_".$type."_key_part2");
            $this->DeleteSettingSQL("host_key_".$type."_key_part3");
            $this->DeleteSettingSQL("host_key_".$type."_comment");
            $this->DeleteSettingSQL("host_key_".$type."_keysize");
            $this->DeleteSettingSQL("host_key_".$type."_fingerprint_type1");
            $this->DeleteSettingSQL("host_key_".$type."_fingerprint1");
            $this->DeleteSettingSQL("host_key_".$type."_keytype2");
            $this->DeleteSettingSQL("host_key_".$type."_fingerprint_type2");
            $this->DeleteSettingSQL("host_key_".$type."_fingerprint2");
            $this->DeleteSettingSQL("host_key_".$type."_date");
        }
    }
    
    function DeleteKnownHost($string1, $string2) {
        if(substr($string2, 0, 6) === "/root/") {
            $user = "root";
        } else {
            $string3 = substr($string2, 6);
            $pos1 = strpos($string3, "/");
            $user = substr($string3, 0, $pos1);
        }
        $line_number_in_known_hosts = substr(trim($string1, "\\\'\\\'"), 0, strlen(trim($string1, "\\\'\\\'")) - 1);
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_known_host");
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_keytype1");
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_key");
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_line_number_in_known_hosts");
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_keysize");
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_fingerprint_type1");
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_fingerprint1");
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_keytype2");
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_fingerprint_type2");
        $this->DeleteSettingSQL("known_hosts_".$user."_".$line_number_in_known_hosts."_fingerprint2");
        $i = intval($line_number_in_known_hosts) + 1;
        while($this->GetSettingFromSQL("known_hosts_".$user."_".$i."_known_host") !== null) {
            $j = $i - 1;
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_known_host")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_known_host")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_keytype1")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_keytype1")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_key")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_key")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_line_number_in_known_hosts")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_line_number_in_known_hosts")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_keysize")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_keysize")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_fingerprint_type1")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_fingerprint_type1")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_fingerprint1")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_fingerprint1")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_keytype2")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_keytype2")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_fingerprint_type2")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_fingerprint_type2")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE settings SET setting=".json_encode("known_hosts_".$user."_".$j."_fingerprint2")." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$i."_fingerprint2")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            
            $this->SQL->mySQLiQuery("UPDATE settings SET value=".json_encode($j)." WHERE BINARY "
                    . "setting=".json_encode("known_hosts_".$user."_".$j."_line_number_in_known_hosts")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $i++;
        }
    }
    
    function DeleteAuthorizedKey($string1, $string2) {
        if(substr($string2, 0, 6) === "/root/") {
            $user = "root";
        } else {
            $string3 = substr($string2, 6);
            $pos1 = strpos($string3, "/");
            $user = substr($string3, 0, $pos1);
        }
        $line_number_in_authorized_keys = substr(trim($string1, "\\\'\\\'"), 0, strlen(trim($string1, "\\\'\\\'")) - 1);
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_keytype1");
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_key");
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_comment");
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_line_number_in_authorized_keys");
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_keysize");
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_fingerprint_type1");
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_fingerprint1");
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_keytype2");
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_fingerprint_type2");
        $this->DeleteSettingSQL("authorized_keys_".$user."_".$line_number_in_authorized_keys."_fingerprint2");
    }
    
    function DeleteFstabMountPoint($string) {
        $string1 = trim($string, "\'\'d");
        $string2 = str_replace("\\\/", "/", $string1);
        $string3 = substr($string2, 1);
        $mountpoint = substr($string3, 0, strlen($string3) - 1);
        $uuid = substr($mountpoint, strrpos($mountpoint, "/") + 1);   
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(false)." WHERE BINARY "
                    . "uuid=".json_encode($uuid)." AND BINARY "
                    . "mountpoint=".json_encode($mountpoint)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(true)." WHERE BINARY "
                    . "uuid=".json_encode($uuid)." AND BINARY "
                    . "mountpoint=".json_encode("")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("DELETE FROM fstab_emulated WHERE BINARY "
                    . "mountpoint=".json_encode($mountpoint)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        
    }
    
    function CreateFstabMountPoint($string1, $string2, $string3, $string4, $string5, $string6) {
        $uuid = substr($string1, strrpos($string1, "=") + 1);
        $mountpoint = $string2;
        $type = $string3;
        $options = $string4;
        $dump = $string5;
        $pass = trim($string6, "\'");
        $this->SQL->mySQLiQuery("INSERT INTO fstab_emulated VALUES ("
                .json_encode(session_id()).","
                .json_encode("").","
                .json_encode($uuid).","
                .json_encode($mountpoint).","
                .json_encode($type).","
                .json_encode($options).","
                .json_encode($dump).","
                .json_encode($pass).")");
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(true)." WHERE BINARY "
                    . "uuid=".json_encode($uuid)." AND BINARY "
                    . "mountpoint=".json_encode($mountpoint)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(false)." WHERE BINARY "
                    . "uuid=".json_encode($uuid)." AND BINARY "
                    . "mountpoint=".json_encode("")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
    }
    
    function WipeFileSystem($string) {
        $partition = substr($string, 5);
        $flag2 = $this->SQL->mySQLiQuery("SELECT active FROM resources_emulated WHERE BINARY "
                    . "partit=".json_encode($partition)." AND BINARY "
                    . "fstype='' AND BINARY "
                    . "session_id=".json_encode(session_id()))[0]["active"];
        if($flag2 === "0") {
            $this->SQL->mySQLiQuery("UPDATE resources_emulated SET mountpoint='flag' WHERE BINARY "
                        . "partit=".json_encode($partition)." AND BINARY "
                        . "active=".json_encode(true)." AND BINARY "
                        . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(false)." WHERE BINARY "
                        . "partit=".json_encode($partition)." AND BINARY "
                        . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(true)." WHERE BINARY "
                        . "partit=".json_encode($partition)." AND BINARY "
                        . "fstype=".json_encode("")." AND BINARY "
                        . "session_id=".json_encode(session_id()));
            $this->SQL->mySQLiQuery("UPDATE resources_emulated SET fstype='',fsver='',uuid='' WHERE BINARY "
                        . "partit=".json_encode($partition)." AND BINARY "
                        . "session_id=".json_encode(session_id()));
        }
        
    }
    
    function DeletePartition($string) {
        $string1 = substr($string, 5);
        $partition = substr($string1, 0, strlen($string1) - 4);
        $device = substr($partition, 0, strlen($partition) - 1);
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(false)." WHERE BINARY "
                    . "device=".json_encode($device)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(true)." WHERE BINARY "
                    . "device=".json_encode($device)." AND BINARY "
                    . "partit=".json_encode("")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $output_buffering_size = str_repeat(" ", 4096);
        echo  $output_buffering_size."^Welcome to fdisk (util-linux 2.36.1).<br>"
            . $output_buffering_size."^Changes will remain in memory only, until you decide to write them.<br>"
            . $output_buffering_size."^Be careful before using the write command.<br>"
            . $output_buffering_size."^<br>"
            . $output_buffering_size."^<br>"
            . $output_buffering_size."^Command (m for help): Selected partition 1<br>"
            . $output_buffering_size."^Partition 1 has been deleted.<br>"
            . $output_buffering_size."^<br>"
            . $output_buffering_size."^Command (m for help): The partition table has been altered.<br>"
            . $output_buffering_size."^Calling ioctl() to re-read partition table.<br>"
            . $output_buffering_size."^Syncing disks.<br>"; flush();
    }
    
    function CreatePartition($string) {
        $device = substr($string, 5);
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(false)." WHERE BINARY "
                    . "device=".json_encode($device)." AND BINARY "
                    . "partit=".json_encode("")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=".json_encode(true)." WHERE BINARY "
                    . "device=".json_encode($device)." AND BINARY "
                    . "partit=".json_encode($device."1")." AND BINARY "
                    . "fstype=".json_encode("")." AND BINARY "
                    . "mountpoint=".json_encode("")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $output_buffering_size = str_repeat(" ", 4096);
        echo  $output_buffering_size."^Welcome to fdisk (util-linux 2.36.1).<br>"
            . $output_buffering_size."^Changes will remain in memory only, until you decide to write them.<br>"
            . $output_buffering_size."^Be careful before using the write command.<br>"
            . $output_buffering_size."^<br>"
            . $output_buffering_size."^Device does not contain a recognized partition table.<br>"
            . $output_buffering_size."^Created a new DOS disklabel with disk identifier 0x756419ec.<br>"
            . $output_buffering_size."^<br>"
            . $output_buffering_size."^Command (m for help): Partition type<br>"
            . $output_buffering_size."^p   primary (0 primary, 0 extended, 4 free)<br>"
            . $output_buffering_size."^e   extended (container for logical partitions)<br>"
            . $output_buffering_size."^Select (default p): Partition number (1-4, default 1):<br>"
            . $output_buffering_size."^First sector (2048-1953525167, default 2048):<br>"
            . $output_buffering_size."^Last sector, +/-sectors or +/-size{K,M,G,T,P} (2048-1953525167, default 1953525167):<br>"
            . $output_buffering_size."^Created a new partition 1 of type 'Linux' and of size 931,5 GiB.<br>"
            . $output_buffering_size."^<br>"
            . $output_buffering_size."^Command (m for help): The partition table has been altered.<br>"
            . $output_buffering_size."^Calling ioctl() to re-read partition table.<br>"
            . $output_buffering_size."^Syncing disks.<br>"; flush();
    }
    
    function MakeFileSystem($string1, $string2) {
        $partition = substr($string2, 5);
        $device = substr($partition, 0, strlen($partition) - 1);
        $fstype = $string1;
        $output_buffering_size = str_repeat(" ", 4096);
        if($fstype === "vfat") {
            $fsver = "FAT32";
            $uuid = str_shuffle("8941")."-".str_shuffle("BA23");
            echo $output_buffering_size."^mkfs.fat 4.2 (2021-01-31)"; flush();
        } elseif($fstype === "ntfs") {
            $fsver = "";
            $uuid = str_shuffle("5F4E096F761C8F39");
            echo $output_buffering_size."^Cluster size has been automatically set to 4096 bytes.<br>"
               . $output_buffering_size."^Creating NTFS volume structures.<br>"
               . $output_buffering_size."^mkntfs completed successfully. Have a nice day."; flush();
        } elseif($fstype === "ext4") {
            $fsver = "1.0";
            $uuid = str_shuffle("55d838f7")."-".str_shuffle("e64a")."-".str_shuffle("464f")."-".str_shuffle("b20f")."-".str_shuffle("f3ab876dd046");
            echo  $output_buffering_size."^mke2fs 1.46.2 (28-Feb-2021)<br>"
                . $output_buffering_size."^Creating filesystem with 244190390 4k blocks and 61054976 inodes<br>"
                . $output_buffering_size."^Filesystem UUID: 1e4287a2-f7ad-4f3d-8101-fe7ff01edeac<br>"
                . $output_buffering_size."^Superblock backups stored on blocks:<br>"
                . $output_buffering_size."^32768, 98304, 163840, 229376, 294912, 819200, 884736, 1605632, 2654208,<br>"
                . $output_buffering_size."^4096000, 7962624, 11239424, 20480000, 23887872, 71663616, 78675968,<br>"
                . $output_buffering_size."^102400000, 214990848<br>"
                . $output_buffering_size."^<br>"
                . $output_buffering_size."^Allocating group tables:    0/7453         done<br>"
                . $output_buffering_size."^Writing inode tables:    0/7453         done<br>"
                . $output_buffering_size."^Creating journal (262144 blocks): done<br>"
                . $output_buffering_size."^Writing superblocks and filesystem accounting information:    0/7453         done"; flush();
        }
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=true WHERE BINARY "
                    . "device=".json_encode($device)." AND BINARY "
                    . "partit=".json_encode("")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET fstype=".json_encode($fstype).",fsver=".json_encode($fsver).",uuid=".json_encode($uuid)." WHERE BINARY "
                    . "device=".json_encode($device)." AND BINARY "
                    . "partit=".json_encode($partition)." AND BINARY "
                    . "active=false AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=false WHERE BINARY "
                    . "device=".json_encode($device)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET active=true,mountpoint='' WHERE BINARY "
                    . "device=".json_encode($device)." AND BINARY "
                    . "mountpoint=".json_encode("flag")." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET mountpoint=".json_encode("/media/".$uuid)." WHERE BINARY "
                    . "partit=".json_encode($partition)." AND BINARY "
                    . "fstype=".json_encode($fstype)." AND BINARY "
                    . "active=false AND BINARY "
                    . "session_id=".json_encode(session_id()));
    }
    
    function SetPartitionType($string1, $string2) {
        $string3 = substr($string1, 2);
        $pos = strpos($string3, "\'");
        $id = substr($string3, 0, $pos);
        $device = substr($string2, 5);
        $partit = $device."1";
        if($id === "7") {
            $partit_id = "7";
            $partit_type = "HPFS/NTFS/exFAT";
        } elseif($id === "83") {
            $partit_id = "83";
            $partit_type = "Linux";
        }
        $this->SQL->mySQLiQuery("UPDATE resources_emulated SET partit_id=".json_encode($partit_id).",partit_type=".json_encode($partit_type)." WHERE BINARY "
                    . "partit=".json_encode($partit)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
    }

    function ExecuteCommandAsUserRealtimeOutput($command, $user) {
        
        $array = explode(" ", $this->TrimExcessWhiteSpaces($command));
        
        // Set partition type
        if(substr($command, 0, 13) === "echo -e \'t\'" and $array[6] === "fdisk") {
            $this->SetPartitionType($array[3], $array[7]); 
        }
        
        // Make File System
        if(substr($command, 0, 8) === "mkfs -t ") {
            $this->MakeFileSystem($array[2], $array[count($array) - 1]); 
        }
        
        // Create Partition
        if(substr($command, 0, 13) === "echo -e \'n\'" and $array[9] === "fdisk") {
            $this->CreatePartition($array[10]);
        }
        
        // Delete Partition
        if(substr($command, 0, 17) === "echo -e \'d /dev/"
                and $array[6] === "fdisk") {
            $this->DeletePartition($array[3]);
        }
        
        // Wipe File System
        if(substr($command, 0, 23) === "wipefs -a --force /dev/") {
            $this->WipeFileSystem($array[3]);
        }
        
        // Creat fstab mount point
        if(substr($command, 0, 18) === "sed -i -e \'$\'a\'" 
                and substr($command, strlen($command) - 11) === " /etc/fstab") {
            $this->CreateFstabMountPoint($array[3], $array[4], $array[5], $array[6], $array[7], $array[8]);
        }
        
        // Delete fstab mount point
        if(substr($command, 0, 10) === "sed -i -e " 
                and substr($command, strlen($command) - 12) === "d /etc/fstab") {
            $this->DeleteFstabMountPoint($array[3]);
        }
        
        // Delete Authorized Key
        if(substr($command, 0, 10) === "sed -i -e "
                and substr($command, strlen($command) - 21) === "/.ssh/authorized_keys") {
            $this->DeleteAuthorizedKey($array[3], $array[4]);
        }
        
        // Delete Known Host
        if(substr($command, 0, 10) === "sed -i -e "
                and substr($command, strlen($command) - 17) === "/.ssh/known_hosts") {
            $this->DeleteKnownHost($array[3], $array[4]);
        }
        
        // Delete Host Keys
        if($command ==="rm /etc/ssh/ssh_host_*") {
            $this->DeleteHostKeys();
        }
        
        // Regenrate Host Keys
        if($command === "ssh-keygen -A") {
            $this->CreateFakeHostKeys("raspbx");
            $output_buffering_size = str_repeat(" ", 4096);
            echo $output_buffering_size."<br>"; flush();
            echo $output_buffering_size."ssh-keygen: generating new host keys: RSA DSA ECDSA ED25519"; flush();
        }
        
        // Create SSH Keys
        if(substr($command, 0, 29) === "ssh-keygen -t rsa -b 4096 -N " and
                substr($command, strlen($command) - 12) === "/.ssh/id_rsa") {
            $this->GenerateSSHUserKeys($array[8] , "RSA");
        }
        
        if(substr($command, 0, 21) === "ssh-keygen -t dsa -N " and
                substr($command, strlen($command) - 12) === "/.ssh/id_dsa") {
            $this->GenerateSSHUserKeys($array[6] , "DSA");
        }
        
        if(substr($command, 0, 30) === "ssh-keygen -t ecdsa -b 521 -N " and
                substr($command, strlen($command) - 14) === "/.ssh/id_ecdsa") {
            $this->GenerateSSHUserKeys($array[8] , "ECDSA");
        }
        
        if(substr($command, 0, 25) === "ssh-keygen -t ed25519 -N " and
                substr($command, strlen($command) - 16) === "/.ssh/id_ed25519") {
            $this->GenerateSSHUserKeys($array[6] , "ED25519");
        }
        
        // Delete SSH Ussr Keys
        if(substr($command, 0, 3) === "rm " and 
                substr($command, strlen($command) - 13) === "/.ssh/id_rsa*") {
            $this->DeleteSSHUserKey($array[1] , "RSA");
        }
        
        if(substr($command, 0, 3) === "rm " and 
                substr($command, strlen($command) - 13) === "/.ssh/id_dsa*") {
            $this->DeleteSSHUserKey($array[1] , "DSA");
        }
        
        if(substr($command, 0, 3) === "rm " and 
                substr($command, strlen($command) - 15) === "/.ssh/id_ecdsa*") {
            $this->DeleteSSHUserKey($array[1] , "ECDSA");
        }
        
        if(substr($command, 0, 3) === "rm " and 
                substr($command, strlen($command) - 17) === "/.ssh/id_ed25519*") {
            $this->DeleteSSHUserKey($array[1] , "ED25519");
        }
        
        // Set SSHD Settings
        if(substr($command, 0, 10) === "sed -i -e "
                and substr($command, strlen($command) - 21) === " /etc/ssh/sshd_config") {
            $sed_commands = $this->ExtractSedCommands($command, "/etc/ssh/sshd_config");
            $this->SetSshSetting($sed_commands);
        }
        
        // Set SSH Settings
        if(substr($command, 0, 10) === "sed -i -e "
                and substr($command, strlen($command) - 20) === " /etc/ssh/ssh_config") {
            $sed_commands = $this->ExtractSedCommands($command, "/etc/ssh/ssh_config");
            $this->SetSshSetting($sed_commands);
        }
        
        // DELETE EMAIL ACCOUNTS
        if(substr($command, 0, 10) === "sed -i -e "
                and substr($command, strlen($command) - 17) === " /etc/fetchmailrc"
                and substr($array[3], strlen($array[3]) - 3) === "d\'") {
            $sed_commands = $this->ExtractSedCommands($command, "/etc/fetchmailrc");
            foreach($sed_commands as $sed_command) {
                if(substr($sed_command, strlen($sed_command) - 1) === "d") {
                    $lines_to_delete[] = intval(substr($sed_command, 0, strlen($sed_command) - 1));
                }
            }
            $this->DeleteLinesInFetchmailrc($lines_to_delete);
        }
        
        // ADD EMAIL ACCOUNT
        if(substr($command, 0, 17) === "sed -i -e \'/poll"
                and substr($command, strlen($command) - 17) === " /etc/fetchmailrc") {
            $debian_user = $array[12];
            $pop3_server = substr($array[4], 1);
            $pop3_user = trim($array[8], "\\\'\\\',");
            $pop3_password = trim($array[10], "\\\'\\\',");
            $this->AddEmailAccountToFetchmailrc($debian_user, $pop3_server, $pop3_user, $pop3_password);
        }
        
        // Purge Samba Users, Delete Samba Shares
        if(substr($command, 0, 10) === "sed -i -e " 
                and substr($command, strlen($command) - 20) === " /etc/samba/smb.conf" 
                and !strpos($command, "printers")) {
            $sed_commands = $this->ExtractSedCommands($command, "/etc/samba/smb.conf");
            foreach($sed_commands as $sed_command) {
                if(substr($sed_command, strlen($sed_command) - 1) === "d") {
                    $segments_to_delete[] = substr($sed_command, 0, strlen($sed_command) - 1);
                } elseif(strpos($sed_command, "c")) {
                   $commands_valid_users[] = $sed_command;
                }
            }
            $this->PurgeSambaUsers($commands_valid_users);
            $this->DeleteSambaShare($segments_to_delete);
        }
        
        // Add Sambashare
        if(substr($command, 0, 10) === "sed -i -e " 
                and substr($command, strlen($command) - 20) === " /etc/samba/smb.conf" 
                and strpos($command, "printers")) {
            $pos1 = strrpos($array[3], "]");
            $string1 = substr($array[3], 0, $pos1 - 1);
            $pos2 = strrpos($string1, "[");
            $share = substr($string1, $pos2 + 1);
            $path = substr($array[10], 0, strlen($array[10]) - 2);
            $valid_users_string = substr($array[18], 0, strlen($array[18]) - 2);
            $writable = substr($array[25], 0, strlen($array[25]) - 2);
            $browsable = substr($array[32], 0, strlen($array[32]) - 2);
            $guest_ok = substr($array[40], 0, strlen($array[40]) - 2);
            $this->AddSambaShare($share, $path, $writable, $browsable, $guest_ok, $valid_users_string);
        }
                
        if(substr($command, 0, 7) === "useradd") {
            $new_user = $array[6];
            $new_uid = $array[2];
            $this->Useradd($new_user, $new_uid);
        }
        
        if(substr($command, 0, 10) === "usermod -u") {
            $new_user = $array[3];
            $new_uid = $array[2];
            $this->UsermodU($new_user, $new_uid);
        }
        
        if(substr($command, 0, 10) === "usermod -l") {
            $new_user = $array[2];
            $old_user = $array[3];
            $this->UsermodL($new_user, $old_user);
        }
        
        if(substr($command, 0, 11) === "groupmod -g") {
            $new_user = $array[3];
            $new_gid = $array[2];
            $this->GroupmodG($new_user, $new_gid);
        }
        
        if(substr($command, strlen($command) - 8, 8) === "chpasswd") {
            $new_user = explode(":", $array[1])[0];
            $new_password = explode(":", $array[1])[2];
            $this->Chpasswd($new_user, $new_password);
        }
        
        if(substr($command, 0, 7) === "userdel") {
            $username = $array[1];
            $this->Userdel($username);
        }
        
        $output_buffering_size = str_repeat(" ", 4096);
        echo $output_buffering_size."<br>0"; flush();
    }
    
    function Useradd($user, $uid) {
        $this->SQL->mySQLiQuery("INSERT INTO raspberry_emulation (session_id,uid,name) VALUES ("
                .json_encode(session_id()).","
                .$uid.","
                .json_encode($user).")");
    }
    
    function UsermodU($user, $uid) {
        $this->SQL->mySQLiQuery("UPDATE raspberry_emulation SET uid='".$uid."' WHERE BINARY "
                . "name=".json_encode($user)." AND BINARY "
                . "session_id=".json_encode(session_id()));
    }
    
    function UsermodL($new_user, $old_user) {
        $this->SQL->mySQLiQuery("UPDATE raspberry_emulation SET "
                . "name=".json_encode($new_user)." WHERE BINARY "
                . "name=".json_encode($old_user)." AND BINARY "
                . "session_id=".json_encode(session_id()));
    }
    
    function GroupmodG($user, $gid) {
        $this->SQL->mySQLiQuery("UPDATE raspberry_emulation SET gid='".$gid."' WHERE BINARY "
                . "name=".json_encode($user)." AND BINARY "
                . "session_id=".json_encode(session_id()));
    }
    
    function Chpasswd($username, $changedpassword) {
        $this->SQL->mySQLiQuery("UPDATE raspberry_emulation SET "
                . "hashed_password=".json_encode(password_hash($changedpassword, PASSWORD_DEFAULT))." WHERE BINARY "
                . "name=".json_encode($username)." AND BINARY "
                . "session_id=".json_encode(session_id()));
    }
    
    function Userdel($username) {
        $this->SQL->mySQLiQuery("DELETE FROM raspberry_emulation WHERE BINARY "
                . "name=".json_encode($username)." AND BINARY "
                . "session_id=".json_encode(session_id()));
        $array = ["RSA", "DSA", "ECDSA", "ED25519"];
        foreach($array as $element) {
            $this->DeleteKey($username, $element);
        }
    }
    
    function Ping($host, $port, $timeout) { 
        $tB = microtime(true); 
        $fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
        $tA = microtime(true);
        $time = round((($tA - $tB) * 1000), 4);
        if($time > 1000) {
            return "Exit Code 1";
        } elseif (!$fP) { 
            return "Exit Code 2"; 
        } else {
            return $time;
        }
    }
    
    function ExecuteCommandRealtimeOutput($command) {
        $array = explode(" ", $command);
        
        if(substr($command, 0, 4) === "ping") {
            $output_buffering_size = str_repeat(" ", 4096);
            $domain = $array[5];
            $result = $this->Ping($domain, 80, 10);
            if(substr($result, 0, 11) === "Exit Code 2") {
                echo $output_buffering_size."Exit Code 2: Misuse of Shell Built-in<br>"; flush();
            } elseif(substr($result, 0, 11) === "Exit Code 1") {
                echo $output_buffering_size."Exit Code 1: General Error<br>"; flush();
            } else {
                $ipaddress = gethostbyname($domain);
                echo $output_buffering_size
                        ."PING ".$domain." (".$ipaddress.") 56(84) bytes of data.<br>"; flush();
                $time = array();
                $icmp_seq = 1;
                $time[$icmp_seq] = $this->Ping($domain, 80, 10);
                $tB = microtime(true); 
                echo $output_buffering_size
                        . "64 bytes from ".$ipaddress.": icmp_seq=".$icmp_seq." ttl=60 time="
                        . number_format(round($time[$icmp_seq], 1), 1)." ms<br>"; flush();
                $icmp_seq++; 
                $time[$icmp_seq] = $this->Ping($domain, 80, 10);
//                sleep(1);
                echo $output_buffering_size
                        . "64 bytes from ".$ipaddress.": icmp_seq=".$icmp_seq." ttl=60 time="
                        . number_format(round($time[$icmp_seq], 1), 1)." ms<br>"; flush();
                $icmp_seq++;
                $time[$icmp_seq] = $this->Ping($domain, 80, 10);
//                sleep(1);
                echo $output_buffering_size
                        . "64 bytes from ".$ipaddress.": icmp_seq=".$icmp_seq." ttl=60 time="
                        . number_format(round($time[$icmp_seq], 1), 1)." ms<br>"; flush();
                $icmp_seq++;
                $time[$icmp_seq] = $this->Ping($domain, 80, 10);
                $tA = microtime(true); 
//                sleep(1);
                echo $output_buffering_size
                        . "64 bytes from ".$ipaddress.": icmp_seq=".$icmp_seq." ttl=60 time="
                        . number_format(round($time[$icmp_seq], 1), 1)." ms<br>"; flush();
                echo $output_buffering_size
                        . "<br>"; flush();
                echo $output_buffering_size
                        . "--- ".$domain." ping statistics ---<br>"; flush();
                echo $output_buffering_size
                        . "4 packets transmitted, 4 received, 0% packet loss, time ".round((($tA - $tB) * 1000 + 3000), 0)." ms<br>"; flush();
                $mean = array_sum($time) / count($time);
                foreach($time as $key => $num) {
                    $devs[$key] = pow($num - $mean, 2);
                }
                $mdev = sqrt(array_sum($devs) / count($devs));
                echo $output_buffering_size
                        . "rtt min/avg/max/mdev = "
                        . number_format(round(min($time), 3), 3)."/"
                        . number_format(round(array_sum($time)/count($time), 3), 3)."/"
                        . number_format(round(max($time), 3), 3)."/"
                        . number_format(round($mdev, 3), 3)." ms<br>"; flush();
            }
        }
    }
    
    function GetPOP3ServerConnectionStatus($pop3_server, $pop3_user, $pop3_password) {
        return "+OK";
    }
    
    function GetFormattedUidGidByUsername($username, $unixusers) {
        foreach($unixusers as $unixuser) {
            if($unixuser['name'] === $username) {
                $string = "(".$unixuser['uid']."/".$unixuser['gid'].") ".$username;
            }
        }
        return $string;
    }
    
    function GetSambaShares() {
        $table = $this->SQL->mySQLiQuery("SELECT share,path,writeable,browsable,guest_ok,line_begin,line_end FROM samba_shares_emulated WHERE BINARY "
                . "session_id=".json_encode(session_id())." "
                . "ORDER BY share,path");
        foreach($table as $row) {
            $table2 = $this->SQL->mySQLiQuery("SELECT valid_users FROM valid_users_emulated WHERE BINARY "
                . "session_id=".json_encode(session_id())." AND "
                . "share=".json_encode($row['share']));
            foreach($table2 as $row2) {
                $valid_users[] = $row2["valid_users"];
            }
            $samba_shares[] = ["share" => $row['share'],
                               "path" => $row['path'],
                               "valid users" => $valid_users,
                               "writeable" => $row['writeable'],
                               "browsable" => $row['browsable'],
                               "guest ok" => $row['guest_ok'],
                               "line_begin" => intval($row['line_begin']),
                               "line_end" => intval($row['line_end'])]; 
            unset($valid_users);
        }
        sort($samba_shares);
        return $samba_shares;
    }
    
    function DoesDirectoryExist($path) {
        return true;
    }
    
    function CountUpSambaLines($line_end) {
        $line_end++;
        return $line_end;
    }
    
    function IsSambaDirectoryValid($path) {
        if(((substr($path, 0, 7) === "/media/") or (substr($path, 0, 6) === "/home/")) and
            substr($path, strlen($path) - 1) !== "/") {
//        if(substr($path, strlen($path) - 1) !== "/") {
        $result = true;
        } else {
            $result = false;
        }
        return $result;
    }
    
    function GetSoftwares() {
        $softwares[] = array("category" => "Operation System",
                             "name" => "Raspbian",
                             "version" => "11 (bullseye)");
        $softwares[] = array("category" => "Linux",
                             "name" => "Kernel",
                             "version" => "5.15.32");
        $softwares[] = array("category" => "Database",
                             "name" => "MariaDB",
                             "version" => "10.5.15");
        $softwares[] = array("category" => "PBX",
                             "name" => "Asterisk",
                             "version" => "19.3.1");
        $softwares[] = array("category" => "Utility",       // explode(" ", $this->ExecuteCommand("fwconsole -V")[0])[4],
                             "name" => "FreePBX",           // explode(" ", $this->ExecuteCommand("fwconsole -V")[0])[3],
                             "version" => "16.0.21.3");     // explode(" ", $this->ExecuteCommand("fwconsole -V")[0])[5]);
        $softwares[] = array("category" => "File server",
                             "name" => "Samba",
                             "version" => "4.13.13");       // explode("-", explode(" ", $this->ExecuteCommand("samba -V")[0])[1])[0]);
        $softwares[] = array("category" => "Mail server",
                             "name" => "Dovecot",
                             "version" => "2.3.13");
        $softwares[] = array("category" => "Retrieve e-mail",
                             "name" => "fetchmail",
                             "version" => "6.4.16");
        $softwares[] = array("category" => "Webmail IMAP Client",
                             "name" => "Roundcube",
                             "version" => "1.5.2");
        $softwares[] = array("category" => "CalDAV/CardDAV",
                             "name" => "Baïkal",
                             "version" => "0.9.2");
        $softwares[] = array("category" => "Utility",
                             "name" => "RASPserver",
                             "version" => "1.0");
        return $softwares;
    }
    
    function GetMmcDevices() {   
        $mmcblk0 = "mmcblk0";
        $size_mmcblk0 = $this->SQL->mySQLiQuery("SELECT device_size FROM resources_emulated WHERE BINARY "
                . "device=".json_encode($mmcblk0)." AND BINARY "
                . "session_id=".json_encode(session_id()))[0]["device_size"];
        $devices_mmc[] = [
            "device" => $mmcblk0,
            "device_size" => $size_mmcblk0
        ];
        return $devices_mmc;
    }
    
    function GetSdDevices() {
        $sda = "sda";
        $size_sda = $this->SQL->mySQLiQuery("SELECT device_size FROM resources_emulated WHERE BINARY "
                . "device=".json_encode($sda)." AND BINARY "
                . "session_id=".json_encode(session_id()))[0]["device_size"];
        $sdb = "sdb";
        $size_sdb = $this->SQL->mySQLiQuery("SELECT device_size FROM resources_emulated WHERE BINARY "
                . "device=".json_encode($sdb)." AND BINARY "
                . "session_id=".json_encode(session_id()))[0]["device_size"];
        $sdc = "sdc";
        $size_sdc = $this->SQL->mySQLiQuery("SELECT device_size FROM resources_emulated WHERE BINARY "
                . "device=".json_encode($sdc)." AND BINARY "
                . "session_id=".json_encode(session_id()))[0]["device_size"];
        $devices_sd[] = [
            "device" => $sda,
            "device_size" => $size_sda
        ];
        $devices_sd[] = [
            "device" => $sdb,
            "device_size" => $size_sdb
        ];
        $devices_sd[] = [
            "device" => $sdc,
            "device_size" => $size_sdc
        ];
        return $devices_sd;
    }
    
    function GetPartitionsOfMmcDevice($device_mmc) {
        $result1 = $this->SQL->mySQLiQuery("SELECT partit FROM resources_emulated WHERE BINARY "
                . "device=".json_encode($device_mmc)." AND BINARY "
                . "active=".json_encode(true)." AND BINARY "
                . "session_id=".json_encode(session_id()));
        foreach($result1 as $element) {
            if(!in_array($element["partit"], $partitions) and $element["partit"] !== "") {
                $partitions[] = $element["partit"];
                $sizes_partition[] = $this->SQL->mySQLiQuery("SELECT partit_size FROM resources_emulated WHERE BINARY "
                    . "partit=".json_encode($element["partit"])." AND BINARY "
                    . "session_id=".json_encode(session_id()))[0]["partit_size"];
            }
        }
        for($i = 0; $i < count($partitions); $i++) {
            $partitions_of_mmc_device[] = [
                    "partition" => $partitions[$i],
                    "partition_size" => $sizes_partition[$i]
                ];
        }
        if(count($result1) === 0) { //.i.e. partition is mounted
            $result2 = $this->SQL->mySQLiQuery("SELECT partit FROM resources_emulated WHERE BINARY "
                . "device=".json_encode($device_mmc)." AND BINARY "
                . "session_id=".json_encode(session_id()));
            $i = count($result2) - 1;
            foreach($result2 as $element) {
                if(!in_array($element["partit"], $partitions) and $element["partit"] !== "") {
                    $partitions[] = $element["partit"];
                    $sizes_partition[] = $this->SQL->mySQLiQuery("SELECT partit_size FROM resources_emulated WHERE BINARY "
                        . "partit=".json_encode($element["partit"])." AND BINARY "
                        . "session_id=".json_encode(session_id()))[0]["partit_size"];
                }
            }
            for($i = 0; $i < count($partitions); $i++) {
                $partitions_of_mmc_device[] = [
                        "partition" => $partitions[$i],
                        "partition_size" => $sizes_partition[$i]
                    ];
            }
        }
        return $partitions_of_mmc_device;
    }
    
    function GetPartitionsOfSdDevice($device_sd) {
        $result1 = $this->SQL->mySQLiQuery("SELECT partit FROM resources_emulated WHERE BINARY "
                . "device=".json_encode($device_sd)." AND BINARY "
                . "active=".json_encode(true)." AND BINARY "
                . "session_id=".json_encode(session_id()));
        $partitions = array();
        $sizes_partition = array();
        foreach($result1 as $element) {
            if(!in_array($element["partit"], $partitions) and $element["partit"] !== "") {
                $partitions[] = $element["partit"];
                $sizes_partition[] = $this->SQL->mySQLiQuery("SELECT partit_size FROM resources_emulated WHERE BINARY "
                    . "partit=".json_encode($element["partit"])." AND BINARY "
                    . "session_id=".json_encode(session_id()))[0]["partit_size"];
            }
        }
        for($i = 0; $i < count($partitions); $i++) {
            $partitions_of_sd_device[] = [
                    "partition" => $partitions[$i],
                    "partition_size" => $sizes_partition[$i]
                ];
        }
        if(count($result1) === 0) { //.i.e. partition is mounted
            $result2 = $this->SQL->mySQLiQuery("SELECT partit FROM resources_emulated WHERE BINARY "
                . "device=".json_encode($device_sd)." AND BINARY "
                . "session_id=".json_encode(session_id()));
            foreach($result2 as $element) {
                if(!in_array($element["partit"], $partitions) and $element["partit"] !== "") {
                    $partitions[] = $element["partit"];
                    $sizes_partition[] = $this->SQL->mySQLiQuery("SELECT size_partition FROM resources_emulated WHERE BINARY "
                        . "partit=".json_encode($element["partit"])." AND BINARY "
                        . "session_id=".json_encode(session_id()))[0]["partit_size"];
                }
            }
            for($i = 0; $i < count($partitions); $i++) {
                $partitions_of_sd_device[] = [
                        "partition" => $partitions[$i],
                        "partition_size" => $sizes_partition[$i]
                ];
            }
        }
        return $partitions_of_sd_device;
    }
    
    function GetFdiskOutput() {
        $output_fdisk = "";
        return $output_fdisk;
    }
    
    function GetPartitionIdAndType($partition, $output_fdisk) {
        $result = $this->SQL->mySQLiQuery("SELECT partit_id,partit_type FROM resources_emulated WHERE BINARY "
                    . "partit=".json_encode($partition)." AND BINARY "
                    . "fstype='' AND BINARY "
                    . "session_id=".json_encode(session_id()))[0];
        $partition_array = [
                        "partition" => $partition,
                        "id" => $result["partit_id"],
                        "type" => $result["partit_type"]
                    ];
        return $partition_array;
    }
    
    function ReadLsblk($partition) {
        $result1 = $this->SQL->mySQLiQuery("SELECT * FROM resources_emulated WHERE BINARY "
                    . "partit=".json_encode($partition)." AND BINARY "
                    . "active=".json_encode(true)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
        foreach($result1 as $line) {
            if($line["partit"] !== "") {
                $lsblk = [
                    "name" => $line["partit"],
                    "fstype" => $line["fstype"],
                    "fsver" => $line["fsver"],
                    "label" => $line["label"],
                    "uuid" => $line["uuid"],
                    "fsavail" => "",
                    "fsuse" => "",
                    "mountpoint" => ""
                ];
            }
        }
        if(count($result1) === 0) {
            $result2 = $this->SQL->mySQLiQuery("SELECT * FROM resources_emulated WHERE BINARY "
                    . "partit=".json_encode($partition)." AND BINARY "
                    . "session_id=".json_encode(session_id()));
            $i = count($result2) - 1;
            $lsblk = [
                    "name" => $result2[$i]["partit"],
                    "fstype" => $result2[$i]["fstype"],
                    "fsver" => $result2[$i]["fsver"],
                    "label" => $result2[$i]["label"],
                    "uuid" => $result2[$i]["uuid"],
                    "fsavail" => $result2[$i]["fsavail"],
                    "fsuse" => $result2[$i]["fsuse"],
                    "mountpoint" => $result2[$i]["mountpoint"]
            ];
        }
        return $lsblk;
    }
    
    function ReadFstab() {
        $result = $this->SQL->mySQLiQuery("SELECT * FROM fstab_emulated WHERE BINARY "
                    . "session_id=".json_encode(session_id()));
        foreach($result as $entry) {
            $fstabentries[] = [
                "partition" => $entry["partit"],
                "uuid" => $entry["uuid"],
                "mountpoint" => $entry["mountpoint"],
                "type" => $entry["type"],
                "options" => $entry["options"],
                "dump" => $entry["dump"],
                "pass" => $entry["pass"]
            ];
        }
        return $fstabentries;
    }
    
    function GetAutosshLocalForwardings() {
        $local_port_forwardings[] = [
                    "LOCAL_USER" => "root",
                    "LOCAL_IP" => "*",
                    "LOCAL_PORT" => "10000",
                    "DESTINATION" => "192.168.1.1",
                    "DESTINATION_PORT" => "443",
                    "SSH_SERVER_PORT" => "",
                    "REMOTE_USER" => "root",
                    "SSH_SERVER" => "raspserver.com",
                    "STATUS" => "+OK"
                ];
        return $local_port_forwardings;
    }
    
    function GetAutosshRemoteForwardings() {
        $remote_port_forwardings[] = [
                    "LOCAL_USER" => "root",
                    "REMOTE" => "",
                    "REMOTE_PORT" => "11000",
                    "DESTINATION" => "localhost",
                    "DESTINATION_PORT" => "22",
                    "SSH_SERVER_PORT" => "",
                    "REMOTE_USER" => "root",
                    "SSH_SERVER" => "raspserver.com",
                    "STATUS" => "+OK"
                ];
        return $remote_port_forwardings;
    }
    
}
