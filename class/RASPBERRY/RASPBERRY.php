<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of RASPBERRY
 *
 * @author rene
 */
class RASPBERRY {

    function __construct(
            $SQL
    ) {
        $this->SQL = $SQL;
    }

    function ExecuteCommand($command) {
        $output = array();
        $retval = null;
        exec($command, $output, $retval);
        if (!$retval) {
            return $output;
        } else {
            throw new Exception($command . "<br>return value " . $retval . $this->GetExitCodeDescription(intval($retval)));
        }
    }

    function ExecuteCommandAsUser($command, $user) {
        try {
            $this->SQL->mySQLiQuery("INSERT INTO helper VALUES ('-c \"$command\" - " . $user . "')");
            $output = $this->ExecuteCommand("sudo /root/.rasp/helper");
            array_shift($output); // remove first empty line of wrapping helper function
            array_pop($output); // remove return value '0' of wrapping helper function
            return $output;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    function ExecuteCommandRealtimeOutput($command) {
        $output_buffering_size = str_repeat(" ", 4096);
//        $cmd = "script -q -c '".$command."' /dev/null";
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"));
        $pipes = array();
        $process = proc_open($command, $descriptorspec, $pipes);
//            fwrite($pipes[0], "");
//            fclose($pipes[0]);
        if (is_resource($process)) {
            while ($output = fgets($pipes[1])) {
                echo $output_buffering_size . $this->FormatOutput($output);
                flush();
            }
        }
        fclose($pipes[0]);
        fclose($pipes[1]);
        $exitcode = proc_close($process);
        $this->HandleExitCode($exitcode, $output_buffering_size);
    }

    function ExecuteCommandAsUserRealtimeOutput($command, $user) {
        $output_buffering_size = str_repeat(" ", 4096);
        $this->SQL->mySQLiQuery("INSERT INTO helper VALUES ('-c \"$command\" - " . $user . "')");
        $cmd = "script -q -c 'sudo /root/.rasp/helper' /dev/null";
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"));
        $pipes = array();
        $process = proc_open($cmd, $descriptorspec, $pipes);
        if (is_resource($process)) {
            $index = 0;
            $array = array();
            while ($output = fgets($pipes[1])) {
                $array[$index] = $this->FormatOutput($output);
                echo $output_buffering_size . $array[$index - 1];
                flush();
                $index++;
            }
        }
        fclose($pipes[0]);
        fclose($pipes[1]);
        $exitcode = intval($array[$index - 1][strlen($array[$index - 1]) - 5][0]);
        $this->HandleExitCode($exitcode, $output_buffering_size);
    }

    function HandleExitCode($exitcode, $output_buffering_size) {
        if ($exitcode !== 0) {
            echo $output_buffering_size . "Exit Code " . strval($exitcode) . $this->GetExitCodeDescription($exitcode) . "<br>";
        }
    }

    function GetExitCodeDescription($exitcode) {
        switch ($exitcode) {
            case 1:
                return ": General Error";
            case 2:
                return ": Misuse of Shell Built-in";
            case 126:
                return ": Cannot Execute";
            case 127:
                return ": Command Not Found";
            case 128:
                return ": Invalid Argument To Exit";
            case 255:
                return ": Exit Status Out of Range";
            default:
        }
    }

    function FormatOutput($output) {
        $output2 = str_replace("\n", "<br>°", $output);
        $output3 = str_replace("%", "%<br>°", $output2);
        $array = explode("°", $output3);
        foreach ($array as $line) {
            if (!strpos($line, "%")) {
                $output4 .= trim($line);
            }
        }
        $output5 = str_replace("\r", "", $output4);
        return $output5;
    }

    function GetNetworkInterfaces() {
        try {
            $output = $this->ExecuteCommand("ifconfig");
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        $ip_address_ipv4 = explode(" ", $output[1])[9];
        $netmask = explode(" ", $output[1])[12];
        $broadcast = explode(" ", $output[1])[15];
        $mac_address = explode(" ", $output[4])[9];
        $ip_address_ipv6_global = explode(" ", $output[2])[9]."/".explode(" ", $output[2])[12];
        $ip_address_ipv6_local = explode(" ", $output[3])[9]."/".explode(" ", $output[3])[12];
        $network_interfaces[] = array(
            "network_interface" => "eth0",
            "ip_address" => $ip_address_ipv4,
            "netmask" => $netmask,
            "broadcast" => $broadcast,
            "ip_address_ipv6_global" => $ip_address_ipv6_global,
            "ip_address_ipv6_local" => $ip_address_ipv6_local,
            "mac_address" => $mac_address);
        return $network_interfaces;
    }

    function GetUnixUsers() {
        $unixusers[] = array("uid" => 0, "gid" => 0, "name" => "root");
        try {
            $file = file("/etc/passwd");
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        foreach ($file as $line) {
            if (intval(explode(":", trim($line))[2]) >= 1000 AND intval(explode(":", trim($line))[2] < 2000)) {
                $name = explode(":", trim($line))[0];
                $uid = intval(explode(":", trim($line))[2]);
                $gid = intval(explode(":", trim($line))[3]);
                $unixusers[] = array("uid" => $uid, "gid" => $gid, "name" => $name);
            }
        }
        sort($unixusers);
        return $unixusers;
    }

    function IsThisUnixUserPasswordWorking($user, $password) {
        try {
            $whoami_iam = $this->ExecuteCommand("echo " . $password . " | su -c 'whoami' - " . $user)[1];
            if ($whoami_iam === $user) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    function GetEmailAddresses() {
        $unixusers = $this->GetUnixUsers();
        $fetchmailrc = $this->ExecuteCommandAsUser("cat /etc/fetchmailrc", "root");
        $pop3_servers = $this->GetPOP3_servers($fetchmailrc);
        for ($i = 0; $i < count($pop3_servers); $i++) {
            $j = $pop3_servers[$i]["line_number"] + 1;
            while (substr(trim($fetchmailrc[$j]), 0, 4) === "user" or substr(trim($fetchmailrc[$j]), 0, 1) === "#" or $fetchmailrc[$j] === "") {
                if(substr(trim($fetchmailrc[$j]), 0, 4) === "user") {
                    $debian_user = explode(" ", $this->TrimExcessWhiteSpaces($fetchmailrc[$j]))[5];
                    $pop3_server = $pop3_servers[$i]["pop3_server"];
                    $pop3_user = trim(explode(" ", $this->TrimExcessWhiteSpaces($fetchmailrc[$j]))[1], "\\\'\\\',");
                    $pop3_password = trim(explode(" ", $this->TrimExcessWhiteSpaces($fetchmailrc[$j]))[3], "\\\'\\\',");
                    $email_addresses[] = array(
                        "uidgiduser" => $this->GetFormattedUidGidByUsername($debian_user, $unixusers),
                        "debian_user" => $debian_user,
                        "pop3_server" => $pop3_server,
                        "pop3_user" => $pop3_user,
                        "pop3_password" => $pop3_password,
                        "pop3_server_line_number" => $pop3_servers[$i]["line_number"] + 1,
                        "pop3_user_line_number" => $j + 1);
                }
                $j++;
            }
        }
        sort($email_addresses);
        return $email_addresses;
    }

    function GetPOP3_servers($fetchmailrc) {
        $line_number = 0;
        foreach ($fetchmailrc as $row) {
            if (substr(trim($row), 0, 4) === "poll" and strpos($row, "POP3")) {
                $pop3_servers[] = array("pop3_server" => explode(" ", $this->TrimExcessWhiteSpaces($row))[1], "line_number" => $line_number);
            }
            $line_number++;
        }
        return $pop3_servers;
    }
    
    function TrimExcessWhiteSpaces($string) {
        $string_trimmed = trim(preg_replace("/\s+/", " ", $string));
        return $string_trimmed;
    }
    
    function PurgeAllWhiteSpaces($string)  {
        $string_purged = preg_replace("/\s+/", "", $string);
        return $string_purged;
    }

    function GetPOP3ServerConnectionStatus($pop3_server, $pop3_user, $pop3_password) {
        $output = $this->ExecuteCommand("echo 'user " . $pop3_user . "\npass " . $pop3_password . "\nquit' | timeout -k 10 5 openssl s_client -connect " . $pop3_server . ":995 -quiet");
        if (explode(" ", $output[2])[0] === "+OK") {
            return "+OK";
        } elseif (explode(" ", $output[2])[0] === "-ERR") {
            return "-ERR";
        }
    }
    
    function SSHKeysQuery() {
        $ssh_query_output = $this->ExecuteCommandAsUser(
                $this->CreateSSHQueryStringHostKeys()
              . $this->CreateSSHQueryStringAuthorizedKeys()
              . $this->CreateSSHQueryStringUserKeys()
              . $this->CreateSSHQueryStringKnownHosts()
              . $this->CreateSSHQueryStringGetRootDirectory()
                , "root");
        return $ssh_query_output;
    }
    
    function CreateSSHQueryStringHostKeys() {
        $string = "find /etc/ssh/ -name \'ssh_host_*_key.pub\' "
                    . "-exec echo command: cat {} \\\; "
                    . "-exec cat {} \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec echo date -r {} +%Y-%m-%d\ \%H:%M:%S\ \%Z \\\; "
                    . "-exec date -r {} +%Y-%m-%d\\\ \\\%H:%M:%S\\\ \\\%Z \\\;; ";
        return $string;
    }
    
    function CreateSSHQueryStringAuthorizedKeys() {
        $string = "find /root/.ssh/ -name \'authorized_keys\' "
                    . "-exec echo command: cat {} \\\; "
                    . "-exec cat {} \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec ssh-keygen -f {} -l -E md5 \\\;; "
                . "find /home/*/.ssh/ -name \'authorized_keys\' "
                    . "-exec echo command: cat {} \\\; "
                    . "-exec cat {} \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec ssh-keygen -f {} -l -E md5 \\\;; ";
        return $string;
    }
    
    function CreateSSHQueryStringUserKeys() {
        $string = "find /root/.ssh/ -name \'*.pub\' "
                    . "-exec echo command: cat {} \\\; "
                    . "-exec cat {} \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec echo date -r {} +%Y-%m-%d\ \%H:%M:%S\ \%Z \\\; "
                    . "-exec date -r {} +%Y-%m-%d\\\ \\\%H:%M:%S\\\ \\\%Z \\\;; "
                . "find /home/*/.ssh/ -name \'*.pub\' "
                    . "-exec echo command: cat {} \\\; "
                    . "-exec cat {} \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec echo date -r {} +%Y-%m-%d\ \%H:%M:%S\ \%Z \\\; "
                    . "-exec date -r {} +%Y-%m-%d\\\ \\\%H:%M:%S\\\ \\\%Z \\\;; ";
        return $string;
    }
    
    function CreateSSHQueryStringKnownHosts() {
        $string = "find /root/.ssh/ -name \'known_hosts\' "
                    . "-exec echo command: cat {} \\\; "
                    . "-exec cat {} \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec ssh-keygen -f {} -l -E md5 \\\;; "
                . "find /home/*/.ssh/ -name \'known_hosts\' "
                    . "-exec echo command: cat {} \\\; "
                    . "-exec cat {} \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec ssh-keygen -f {} -l -E sha256 \\\; "
                    . "-exec echo command: ssh-keygen -f {} -l -E md5 \\\; "
                    . "-exec ssh-keygen -f {} -l -E md5 \\\;; ";
        return $string;
    }
    
    function CreateSSHQueryStringGetRootDirectory() {
        $string = "echo ls -a /root;"
                . "ls -a /root";
        return $string;
    }
    
    function GetSshAuthorizedKeys($ssh_query_output, $unixusers) {
        $authorized_keys = [];
        for($i = 0; $i < count($ssh_query_output); $i++) {
            if(substr($ssh_query_output[$i], strlen($ssh_query_output[$i]) - 21, 21) === "/.ssh/authorized_keys") {
                $user = substr(substr($ssh_query_output[$i], 0, strlen($ssh_query_output[$i]) - 21), strrpos(substr($ssh_query_output[$i], 0, strlen($ssh_query_output[$i]) - 21), "/") + 1);
                
                $j=1;
                while(substr($ssh_query_output[$i + $j], 0, 8) !== "command:") {
                    $j++;
                }
                for($k = $i + 1; $k < $i + $j; $k++) {
                    $keytype1 = explode(" ", $ssh_query_output[$k])[0];
                    $key = explode(" ", $ssh_query_output[$k])[1];
                    $comment = explode(" ", $ssh_query_output[$k])[2];
                    $keysize = explode(" ", $ssh_query_output[$k + $j])[0];
                    $fingerprint_type1 = substr(explode(" ", $ssh_query_output[$k + $j])[1], 0, strpos(explode(" ", $ssh_query_output[$k + $j])[1], ":"));
                    $fingerprint1 = substr(explode(" ", $ssh_query_output[$k + $j])[1], strpos(explode(" ", $ssh_query_output[$k + $j])[1], ":") + 1);
                    $keytype2 = trim(explode(" ", $ssh_query_output[$k + $j])[3], "()");
                    $fingerprint_type2 = substr(explode(" ", $ssh_query_output[$k + 2 * $j])[1], 0, strpos(explode(" ", $ssh_query_output[$k + 2 * $j])[1], ":"));
                    $fingerprint2 = substr(explode(" ", $ssh_query_output[$k + 2 * $j])[1], strpos(explode(" ", $ssh_query_output[$k + 2 * $j])[1], ":") + 1);
                    $authorized_keys_of_user[] = array(
                        "keytype1" => $keytype1,
                        "key" => $key,
                        "comment" => $comment,
                        "line_number_in_authorized_keys" => $k - $i,
                        "keysize" => $keysize,
                        "fingerprint_type1" => $fingerprint_type1,
                        "fingerprint1" => $fingerprint1,
                        "keytype2" => $keytype2,
                        "fingerprint_type2" => $fingerprint_type2,
                        "fingerprint2" => $fingerprint2
                    );
                }
                $authorized_keys[] = array(
                    "uidgiduser" => $this->GetFormattedUidGidByUsername($user, $unixusers),
                    "user" => $user,
                    "authorized_keys_of_user" => $authorized_keys_of_user
                );
                unset($authorized_keys_of_user);
            }
        }
        sort($authorized_keys);
        return $authorized_keys;
    }
    
    function GetSshHostKeys($ssh_query_output) {
        $host_keys = [];
        for($i = 0; $i < count($ssh_query_output); $i++) {
            if(substr($ssh_query_output[$i], 0, 31) === "command: cat /etc/ssh/ssh_host_") {
                $path = substr($ssh_query_output[$i], 13);
                $keytype1 = explode(" ", $ssh_query_output[$i + 1])[0];
                $key = explode(" ", $ssh_query_output[$i + 1])[1];
                $comment = explode(" ", $ssh_query_output[$i + 1])[2];
                $keysize = explode(" ", $ssh_query_output[$i + 3])[0];
                $fingerprint_type1 = substr(explode(" ", $ssh_query_output[$i + 3])[1], 0, strpos(explode(" ", $ssh_query_output[$i + 3])[1], ":"));
                $fingerprint1 = substr(explode(" ", $ssh_query_output[$i + 3])[1], strpos(explode(" ", $ssh_query_output[$i + 3])[1], ":") + 1);
                $keytype2 = trim(explode(" ", $ssh_query_output[$i + 3])[3], "()");
                $fingerprint_type2 = substr(explode(" ", $ssh_query_output[$i + 5])[1], 0, strpos(explode(" ", $ssh_query_output[$i + 5])[1], ":"));
                $fingerprint2 = substr(explode(" ", $ssh_query_output[$i + 5])[1], strpos(explode(" ", $ssh_query_output[$i + 5])[1], ":") + 1);
                $date = $ssh_query_output[$i + 7]; //date("Y-m-d H:i:s", $ssh_query_output[$i + 7]);
                $host_keys[] = array("path" =>  $path, "keytype1" => $keytype1, "key" => $key, "comment" => $comment,"keysize" => $keysize, 
                    "fingerprint_type1" => $fingerprint_type1, "fingerprint1" => $fingerprint1, "keytype2" => $keytype2,
                    "fingerprint_type2" => $fingerprint_type2, "fingerprint2" => $fingerprint2,"date" => $date);
            }
        }
        sort($host_keys);
        return $host_keys;
    }
    
    function GetKnownHosts($ssh_query_output, $unixusers) {
        $known_hosts = [];
        for($i = 0; $i < count($ssh_query_output); $i++) {
            if(substr($ssh_query_output[$i], strlen($ssh_query_output[$i]) - 17, 17) === "/.ssh/known_hosts") {
                $user = substr(substr($ssh_query_output[$i], 0, strlen($ssh_query_output[$i]) - 17), strrpos(substr($ssh_query_output[$i], 0, strlen($ssh_query_output[$i]) - 17), "/") + 1);
                $j=1;
                while(substr($ssh_query_output[$i + $j], 0, 8) !== "command:") {
                    $j++;
                }
                for($k = $i + 1; $k < $i + $j; $k++) {
                    $known_host = explode(" ", $ssh_query_output[$k])[0];
                    $keytype1 = explode(" ", $ssh_query_output[$k])[1];
                    $key = explode(" ", $ssh_query_output[$k])[2];
                    $keysize = explode(" ", $ssh_query_output[$k + $j])[0];
                    $fingerprint_type1 = substr(explode(" ", $ssh_query_output[$k + $j])[1], 0, strpos(explode(" ", $ssh_query_output[$k + $j])[1], ":"));
                    $fingerprint1 = substr(explode(" ", $ssh_query_output[$k + $j])[1], strpos(explode(" ", $ssh_query_output[$k + $j])[1], ":") + 1);
                    $keytype2 = trim(explode(" ", $ssh_query_output[$k + $j])[3], "()");
                    $fingerprint_type2 = substr(explode(" ", $ssh_query_output[$k + 2 * $j])[1], 0, strpos(explode(" ", $ssh_query_output[$k + 2 * $j])[1], ":"));
                    $fingerprint2 = substr(explode(" ", $ssh_query_output[$k + 2 * $j])[1], strpos(explode(" ", $ssh_query_output[$k + 2 * $j])[1], ":") + 1);
                    $known_hosts_of_user[] = array(
                        "known_host" => $known_host,
                        "keytype1" => $keytype1,
                        "key" => $key,
                        "line_number_in_known_hosts" => $k - $i,
                        "keysize" => $keysize,
                        "fingerprint_type1" => $fingerprint_type1,
                        "fingerprint1" => $fingerprint1,
                        "keytype2" => $keytype2,
                        "fingerprint_type2" => $fingerprint_type2,
                        "fingerprint2" => $fingerprint2
                    );
                }
                $known_hosts[] = array(
                    "uidgiduser" => $this->GetFormattedUidGidByUsername($user, $unixusers),
                    "user" => $user,
                    "known_hosts_of_user" => $known_hosts_of_user
                );
                unset($known_hosts_of_user);
            }
        }
        sort($known_hosts);
        return $known_hosts;
    }
    
    function GetSshUserKeys($ssh_query_output, $unixusers) {
        $user_keys = [];
        for($i = 0; $i < count($ssh_query_output); $i++) {
            if((substr($ssh_query_output[$i], 0, 19) === "command: cat /root/" or substr($ssh_query_output[$i], 0, 19) === "command: cat /home/") and
                    substr($ssh_query_output[$i], strlen($ssh_query_output[$i]) - 4, 4) === ".pub") {
                if(substr($ssh_query_output[$i], 0, 19) === "command: cat /root/") { $user = "root"; } 
                  else {$user = substr(substr($ssh_query_output[$i], 19), 0, strpos(substr($ssh_query_output[$i], 19), "/")); }
                $path = substr($ssh_query_output[$i], 13);
                $keytype1 = explode(" ", $ssh_query_output[$i + 1])[0];
                $key = explode(" ", $ssh_query_output[$i + 1])[1];
                $comment = explode(" ", $ssh_query_output[$i + 1])[2];
                $keysize = explode(" ", $ssh_query_output[$i + 3])[0];
                $fingerprint_type1 = substr(explode(" ", $ssh_query_output[$i + 3])[1], 0, strpos(explode(" ", $ssh_query_output[$i + 3])[1], ":"));
                $fingerprint1 = substr(explode(" ", $ssh_query_output[$i + 3])[1], strpos(explode(" ", $ssh_query_output[$i + 3])[1], ":") + 1);
                $keytype2 = trim(explode(" ", $ssh_query_output[$i + 3])[3], "()");
                $fingerprint_type2 = substr(explode(" ", $ssh_query_output[$i + 5])[1], 0, strpos(explode(" ", $ssh_query_output[$i + 5])[1], ":"));
                $fingerprint2 = substr(explode(" ", $ssh_query_output[$i + 5])[1], strpos(explode(" ", $ssh_query_output[$i + 5])[1], ":") + 1);
                $date = $ssh_query_output[$i + 7];
                $user_keys[] = array("uidgiduser" => $this->GetFormattedUidGidByUsername($user, $unixusers), "user" =>  $user, "path" =>  $path, "keytype1" => $keytype1, "key" => $key, "comment" => $comment,"keysize" => $keysize, 
                    "fingerprint_type1" => $fingerprint_type1, "fingerprint1" => $fingerprint1, "keytype2" => $keytype2,
                    "fingerprint_type2" => $fingerprint_type2, "fingerprint2" => $fingerprint2, "date" => $date);
            }
            
        }
        sort($user_keys);
        return $user_keys;
    }
    
    function DoesRootHaveASshFolder($ssh_query_output) {
        $result = false;
        for($i = 0; $i < count($ssh_query_output); $i++) {
            if(substr($ssh_query_output[$i], 0, 11) === "ls -a /root") {
                for($j = $i + 1; $j < count($ssh_query_output) - 1; $j++) {
                    $array[] = $ssh_query_output[$j];
                }
                if(in_array(".ssh", $array)) {
                    $result = true;
                }
            }
        }
        return $result;
    }
    
    function GetFormattedUidGidByUsername($username, $unixusers) {
        foreach($unixusers as $unixuser) {
            if($unixuser['name'] === $username) {
                $string = "(".$unixuser['uid']."/".$unixuser['gid'].") ".$username;
            }
        }
        return $string;
    }
    
    function DoesThisUserHaveASshFolder($user) {
        if($user === "root") {
            $result = true;
        } else {
            $array = scandir("/home/".$user);
            if(in_array(".ssh", $array)) {
                $result = true;
            } else {
                $result = false;
            }
        }
        return $result;
    }
    
    function GetSetting($configuration_file, $setting) {
        $string = $this->TrimExcessWhiteSpaces($this->ExecuteCommand("sed -n '/^ *".$setting."/'p ".$configuration_file )[0]);
        $value = explode(" ", $string)[1];
        if($value === null) {
            $value = "unset";
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
    
    function GetSoftwares() {
        $softwares[] = array("category" => "Operation System",
                             "name" => explode(" ", trim(trim($this->ExecuteCommand("cat /etc/os-release")[0], "PRETTY_NAME="),"\""))[0],
                             "version" => trim(trim($this->ExecuteCommand("cat /etc/os-release")[3], "VERSION="),"\""));
        $softwares[] = array("category" => "Linux",
                             "name" => "Kernel",
                             "version" => trim(explode(" ", $this->ExecuteCommand("uname -a")[0])[2], "+"));
        $softwares[] = array("category" => "Database",
                             "name" => trim(explode("-", explode(" ", $this->ExecuteCommand("mysql -V")[0])[5])[1], ","),
                             "version" => explode("-", explode(" ", $this->ExecuteCommand("mysql -V")[0])[5])[0]);
        $softwares[] = array("category" => "PBX",
                             "name" => explode(" ", $this->ExecuteCommand("asterisk -V")[0])[0],
                             "version" => explode(" ", $this->ExecuteCommand("asterisk -V")[0])[1]);
        $softwares[] = array("category" => "Utility",       // explode(" ", $this->ExecuteCommand("fwconsole -V")[0])[4],
                             "name" => "FreePBX",           // explode(" ", $this->ExecuteCommand("fwconsole -V")[0])[3],
                             "version" => "16.0.21.3");     // explode(" ", $this->ExecuteCommand("fwconsole -V")[0])[5]);
        $softwares[] = array("category" => "File server",
                             "name" => "Samba",
                             "version" => "4.13.13");       // explode("-", explode(" ", $this->ExecuteCommand("samba -V")[0])[1])[0]);
        $softwares[] = array("category" => "Mail server",
                             "name" => "Dovecot",
                             "version" => explode(" ", $this->ExecuteCommand("dovecot --version")[0])[0]);
        $softwares[] = array("category" => "Retrieve e-mail",
                             "name" => explode(" ", $this->ExecuteCommand("fetchmail -V")[0])[2],
                             "version" => substr(explode(" ", $this->ExecuteCommand("fetchmail -V")[0])[4], 0, strpos(explode(" ", $this->ExecuteCommand("fetchmail -V")[0])[4], "+")));
        $softwares[] = array("category" => explode(" ", trim($this->ExecuteCommand("cat /var/www/roundcubemail/index.php")[3], "|  |"))[1]." ".explode(" ", trim($this->ExecuteCommand("cat /var/www/roundcubemail/index.php")[3], "|  |"))[2]." ".explode(" ", trim($this->ExecuteCommand("cat /var/www/roundcubemail/index.php")[3], "|  |"))[3],
                             "name" => explode(" ", trim($this->ExecuteCommand("cat /var/www/roundcubemail/index.php")[3], "|  |"))[0],
                             "version" => explode(" ", trim($this->ExecuteCommand("cat /var/www/roundcubemail/index.php")[4],  "|  |"))[1]);
        $softwares[] = array("category" => "CalDAV/CardDAV",
                             "name" => "Baïkal",
                             "version" => "0.9.2");
        $softwares[] = array("category" => "Utility",
                             "name" => "RASPserver",
                             "version" => "1.0");
        return $softwares;
    }
    
    function GetSambaShares() {
        $homes_begin = intval($this->ExecuteCommand("sed -n '/\[homes\]/'= /etc/samba/smb.conf")[0]);
        $homes_end = intval($this->ExecuteCommand("sed -n '/\[printers\]/'= /etc/samba/smb.conf")[0]);
        $smb_conf = file("/etc/samba/smb.conf");
        for($i = $homes_begin; $i < $homes_end - 1; $i++) {
            if(substr($this->TrimExcessWhiteSpaces($smb_conf[$i]), 0, 1) === "[") {
                $j = 1;
                while(substr($this->TrimExcessWhiteSpaces($smb_conf[$i + $j]), 0, 1) !== "[") {
                    $j++;
                }
                $k = $j - 1;
                while($this->TrimExcessWhiteSpaces($smb_conf[$i + $k]) === "") {
                    $k--;
                }
                $sections[] = [
                    "line_begin" => $i,
                    "line_end" => ($i + $k)];
            }
        } 
        foreach($sections as $section) {
            for($i = $section["line_begin"]; $i <= $section["line_end"]; $i++) {
                if(substr($smb_conf[$i], 0, 1) === "[") {
                    $share = trim(trim($smb_conf[$i]), "[]");
                }
                if(trim(explode("=", $smb_conf[$i])[0]) === "path") {
                    $path = trim(explode("=", $smb_conf[$i])[1]);
                }
                if(trim(explode("=", $smb_conf[$i])[0]) === "valid users") {
                    $valid_users = explode(",", $this->PurgeAllWhiteSpaces(explode("=", $smb_conf[$i])[1]));
                }
                if(trim(explode("=", $smb_conf[$i])[0]) === "writable") {
                    $writable = trim(explode("=", $smb_conf[$i])[1]);
                }
                if(trim(explode("=", $smb_conf[$i])[0]) === "browsable") {
                    $browsable = trim(explode("=", $smb_conf[$i])[1]);
                }
                if(trim(explode("=", $smb_conf[$i])[0]) === "guest ok") {
                    $guest_ok = trim(explode("=", $smb_conf[$i])[1]);
                }
            }
            $samba_shares[] = [ "share" => $share,
                                "path" => $path,
                                "valid users" => $valid_users,
                                "writeable" => $writable,
                                "browsable" => $browsable,
                                "guest ok" => $guest_ok,
                                "line_begin" => $section["line_begin"] + 1,
                                "line_end" => $section["line_end"] + 1];
            unset($share); unset($path); unset($valid_users); unset($writable); unset($browsable);  unset($guest_ok);
        }
        return $samba_shares;
    }
    
    function DoesDirectoryExist($path) {
        $result = is_dir($path);
        return $result;
    }
    
    function ScanDirectory($path) {
        $array = scandir($path);
        foreach($array as $element) {
            if(substr($element, 0, 1) !== "." and $element !== "lost+found") {
                $directory[] = $element;
            }
        }
        return $directory;
    }
    
    function CountUpSambaLines($line_end) {
        $smb_conf = file("/etc/samba/smb.conf");
        WHILE(trim($smb_conf[$line_end]) === "") {
            $line_end++;
        }
        return $line_end;
    }
    
    function IsSambaDirectoryValid($path) {
        if(is_dir($path) and 
            ((substr($path, 0, 7) === "/media/") or (substr($path, 0, 6) === "/home/")) and
            substr($path, strlen($path) - 1) !== "/") {
        $result = true;
        } else {
            $result = false;
        }
        return $result;
    }
    
    function GetMmcDevices() {
        $devices_mmc = array();
        $output = $this->ExecuteCommand("sed -n '/mmcblk/'p /proc/partitions");
        foreach($output as $line) {
            if(!strpos(explode(" ", $this->TrimExcessWhiteSpaces($line))[3], "p")
                    and !in_array(explode(" ", $this->TrimExcessWhiteSpaces($line))[3], $devices_mmc)) {
                    $devices_mmc[] = [
                            "device" => explode(" ", $this->TrimExcessWhiteSpaces($line))[3],
                            "device_size" => explode(" ", $this->TrimExcessWhiteSpaces($line))[2]
                        ];
            }
        }
        return $devices_mmc;
    }
    
    function GetSdDevices() {
        $devices_sd = array();
        $output = $this->ExecuteCommand("sed -n '/sd/'p /proc/partitions");
        foreach($output as $line) {
            if(strlen(explode(" ", $this->TrimExcessWhiteSpaces($line))[3]) === 3
                    and !in_array(explode(" ", $this->TrimExcessWhiteSpaces($line))[3], $devices_sd)) {
                    $devices_sd[] = [
                            "device" => explode(" ", $this->TrimExcessWhiteSpaces($line))[3],
                            "device_size" => explode(" ", $this->TrimExcessWhiteSpaces($line))[2]
                    ];
            }
        }
        return $devices_sd;
    }
    
    function GetPartitionsOfMmcDevice($device_mmc) {
        $partitions_of_mmc_device = array();
        $output = $this->ExecuteCommand("sed -n '/".$device_mmc."/'p /proc/partitions");
        foreach($output as $line) {
            if(substr(explode(" ", $this->TrimExcessWhiteSpaces($line))[3], strlen($device_mmc), 1) === "p") {
                $partition = explode(" ", $this->TrimExcessWhiteSpaces($line))[3];
                $partition_size = explode(" ", $this->TrimExcessWhiteSpaces($line))[2];
                $partitions_of_mmc_device[] = [
                        "partition" => $partition,
                        "partition_size" => $partition_size
                ];
            }
        }
        return $partitions_of_mmc_device;
    }
    
    function GetPartitionsOfSdDevice($device_sd) {
        $partitions_of_sd_device = array();
        $output = $this->ExecuteCommand("sed -n '/".$device_sd."/'p /proc/partitions");
        foreach($output as $line) {
            if(strlen(explode(" ", $this->TrimExcessWhiteSpaces($line))[3]) === 4) {
                $partition = explode(" ", $this->TrimExcessWhiteSpaces($line))[3];
                $partition_size = explode(" ", $this->TrimExcessWhiteSpaces($line))[2];
                $partitions_of_sd_device[] = [
                        "partition" => $partition,
                        "partition_size" => $partition_size
                ];
            }
        }
        return $partitions_of_sd_device;
    }
    
    function GetFdiskOutput() {
        $output_fdisk = $this->ExecuteCommandAsUser("fdisk -l", "root");
        return $output_fdisk;
    }
    
    function GetPartitionIdAndType($partition, $output_fdisk) {
        foreach($output_fdisk as $line) {
            if(substr(explode(" ", $line)[0], 5) === $partition) {
                $array = explode(" ", $this->TrimExcessWhiteSpaces($line));
                if($array[1] === "*") {
                    for($i = 0; $i < 6; $i++) {
                        $string .= $array[$i]. " ";
                    }
                    $string .= $array[6];
                    $pos = strlen($string);
                    $partition_array = [
                        "partition" => $partition,
                        "id" => $array[6],
                        "type" => substr($this->TrimExcessWhiteSpaces($line), $pos + 1)
                    ];
                    return $partition_array;
                } else {
                    for($i = 0; $i < 5; $i++) {
                        $string .= $array[$i]. " ";
                    }
                    $string .= $array[5];
                    $pos = strlen($string);
                    $partition_array = [
                        "partition" => $partition,
                        "id" => $array[5],
                        "type" => substr($this->TrimExcessWhiteSpaces($line), $pos + 1)
                    ];
                    return $partition_array;
                }  
            }
        }
    }
    
    function ReadLsblk($partition) {
        $output = $this->TrimExcessWhiteSpaces($this->ExecuteCommand("lsblk -f -J | sed -n '/\"name\":\"".$partition."\"/'p")[0]);
        $fstype = trim(explode(":", explode(" ", $output)[1])[1], "\",");
        $fsver = trim(explode(":", explode(" ", $output)[2])[1], "\",");
        $label = trim(explode(":", explode(" ", $output)[3])[1], "\",");
        $uuid = trim(explode(":", explode(" ", $output)[4])[1], "\",");
        $fsavail = trim(explode(":", explode(" ", $output)[5])[1], "\",");
        $fsuse = trim(explode(":", explode(" ", $output)[6])[1], "\",");
        $mountpoint = trim(explode(":", explode(" ", $output)[7])[1], "\"},");
        
        $lsblk = [
            "name" => $partition,
            "fstype" => str_replace("null", null, $fstype),
            "fsver" => str_replace("null", null, $fsver),
            "label" => str_replace("null", null, $label),
            "uuid" => str_replace("null", null, $uuid),
            "fsavail" => str_replace("null", null, $fsavail),
            "fsuse" => str_replace("null", null, $fsuse),
            "mountpoint" => str_replace("null", null, $mountpoint)
        ];
        return $lsblk;
    }
    
    function ReadFstab() {
        $output1 = $this->ExecuteCommand("sed -n '/^ *\/dev\//'p /etc/fstab");
        $output2 = $this->ExecuteCommand("sed -n '/^ *UUID=/'p /etc/fstab");
        $output = array_merge($output1, $output2);
        foreach($output as $line) {
            if(substr(explode(" ", $this->TrimExcessWhiteSpaces($line))[0], 0, 5) === "/dev/") {
                $partition = substr(explode(" ", $this->TrimExcessWhiteSpaces($line))[0], 5);
            }
            if(substr(explode(" ", $this->TrimExcessWhiteSpaces($line))[0], 0, 5) === "UUID=") {
                $uuid = substr(explode(" ", $this->TrimExcessWhiteSpaces($line))[0], 5);
            }
            $fstabentries[] = [
                        "partition" => $partition,
                        "uuid" => $uuid,
                        "mountpoint" => explode(" ", $this->TrimExcessWhiteSpaces($line))[1],
                        "type" => explode(" ", $this->TrimExcessWhiteSpaces($line))[2],
                        "options" => explode(" ", $this->TrimExcessWhiteSpaces($line))[3],
                        "dump" => explode(" ", $this->TrimExcessWhiteSpaces($line))[4],
                        "pass" => explode(" ", $this->TrimExcessWhiteSpaces($line))[5]
            ];
            unset($partition); unset($uuid);
        }
        return $fstabentries;
    }
    
    function ReadPsAux() {
        $output = $this->ExecuteCommand("ps aux");
        foreach($output as $line) {
            $trimmed_line = $this->TrimExcessWhiteSpaces($line);
            $array = explode(" ", $trimmed_line);
            $length = strlen($array[0].$array[1].$array[2].$array[3]
                            .$array[4].$array[5].$array[6].$array[7]
                            .$array[8].$array[9]) + 10;
            $COMMAND = substr($trimmed_line, $length);
            $psaux[] = [
                "USER" => $array[0], "PID" => $array[1],   "CPU" => $array[2],
                "MEM" => $array[3],  "VSZ" => $array[4],   "RSS" => $array[5],
                "TTY" => $array[6],  "STAT" => $array[7],  "START" => $array[8],
                "TIME" => $array[9], "COMMAND" => $COMMAND
            ];
        }
        return $psaux;
    }
    
    function GetAutosshLocalForwardings() {
        $psaux = $this->ReadPsAux();
        foreach($psaux as $line) {
            $array = explode(" ", $line["COMMAND"]); $length = count($array);
            if($array[0] === "/usr/lib/autossh/autossh" and $array[$length - 3] === "-L") {
                if($array[1] === "-p") {
                    $SSH_SERVER_PORT = $array[2];
                } else {
                    $SSH_SERVER_PORT = ""; 
                }
                $array2 = explode(":", $array[$length - 2]);
                $length2 = count($array2);
                $DESTINATION_PORT = $array2[$length2 - 1];
                $DESTINATION = $array2[$length2 - 2];
                $LOCAL_PORT = $array2[$length2 - 3];
                if($length2 === 4) {
                    $LOCAL_IP = $array2[0];
                } else {
                    $LOCAL_IP = "";
                }
                $array3 = explode("@", $array[$length - 1]);
                $length3 = count($array3);
                $SSH_SERVER = $array3[$length3 - 1];
                if($length3 === 2) {
                    $REMOTE_USER = $array3[0];
                } else {
                    $REMOTE_USER = "";
                }
                $status = $this->GetAutosshConnectionStatus(substr($line["COMMAND"], 25), $psaux);
                $local_port_forwardings[] = [
                    "LOCAL_USER" => $line["USER"],
                    "LOCAL_IP" => $LOCAL_IP,
                    "LOCAL_PORT" => $LOCAL_PORT,
                    "DESTINATION" => $DESTINATION,
                    "DESTINATION_PORT" => $DESTINATION_PORT,
                    "SSH_SERVER_PORT" => $SSH_SERVER_PORT,
                    "REMOTE_USER" => $REMOTE_USER,
                    "SSH_SERVER" => $SSH_SERVER,
                    "STATUS" => $status
                ];
            }
        }
        return $local_port_forwardings;
    }
    
    function GetAutosshRemoteForwardings() {
        $psaux = $this->ReadPsAux();
        foreach($psaux as $line) {
            $array = explode(" ", $line["COMMAND"]); $length = count($array);
            if($array[0] === "/usr/lib/autossh/autossh" and $array[$length - 3] === "-R") {
                if($array[1] === "-p") {
                    $SSH_SERVER_PORT = $array[2];
                } else {
                    $SSH_SERVER_PORT = ""; 
                }
                $array2 = explode(":", $array[$length - 2]);
                $length2 = count($array2);
                $DESTINATION_PORT = $array2[$length2 - 1];
                $DESTINATION = $array2[$length2 - 2];
                $REMOTE_PORT = $array2[$length2 - 3];
                if($length2 === 4) {
                    $REMOTE = $array2[0];
                } else {
                    $REMOTE = "";
                }
                $array3 = explode("@", $array[$length - 1]);
                $length3 = count($array3);
                $SSH_SERVER = $array3[$length3 - 1];
                if($length3 === 2) {
                    $REMOTE_USER = $array3[0];
                } else {
                    $REMOTE_USER = "";
                }
                $status = $this->GetAutosshConnectionStatus(substr($line["COMMAND"], 25), $psaux);
                $remote_port_forwardings[] = [
                    "LOCAL_USER" => $line["USER"],
                    "REMOTE" => $REMOTE,
                    "REMOTE_PORT" => $REMOTE_PORT,
                    "DESTINATION" => $DESTINATION,
                    "DESTINATION_PORT" => $DESTINATION_PORT,
                    "SSH_SERVER_PORT" => $SSH_SERVER_PORT,
                    "REMOTE_USER" => $REMOTE_USER,
                    "SSH_SERVER" => $SSH_SERVER,
                    "STATUS" => $status
                ];
            }
        }
        return $remote_port_forwardings;
    }
    
    function GetAutosshConnectionStatus($search, $psaux) {
        $status = "-ERR";
        foreach($psaux as $line) {
            if(explode(" ", $line["COMMAND"])[0] === "/usr/bin/ssh" and strpos($line["COMMAND"], $search)) {
                $status = "+OK";
            }
        }
        return $status;
    }
    
    

// ssh -o BatchMode=yes -o ConnectTimeout=5 -p 20259 root@raspbx1.spdns.de echo ok
    
//    sed:
//    nach der Zeile, die 'pop.gmx.net' enthält, neue Zeile 'text' einfügen
//    cat file | sed '/pop.gmx.net/'a'text' -i file
//    
//    die Zeile, die 'pop.gmx.net' enthält, löschen
//    cat file | sed '/pop.gmx.net/'d -i file
//    
//    am Ende der Datei neue Zeile text einfügen:
//    cat file | sed '$'a'text' -i file
//    
//    Zeile, die 'rhz@gmx.de' enthält, ausgeben
//    cat test | sed -n '/rhz@gmx.de/'p
//    
//    Zeile, die mit 'poll' beginnt, ausgeben
//    cat test | sed -n '/^poll/'p
//    
//    Zeilennummer der Zeile, die 'pop' enthält, ausgeben
//    cat test | sed -n '/poll/'=
//    
//    Zeile 6 ausgeben
//    cat test | sed -n 6p
//    
//    Zeilen 6-8 ausgeben
//    cat test | sed -n 6,8p
//    
//    
//    
}
