<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of LOCAL_RESOURCES
 *
 * @author rene
 */
class LOCAL_RESOURCES {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
        $this->UTILITIES_LOCAL_RESOURCES = new UTILITIES_LOCAL_RESOURCES ($UTILITIES, $BUTTONS);
    }
    
    function DisplayMountsTable($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("Mounts");
        $header = [
            $this->UTILITIES->Translate("Device or Partition"),
            $this->UTILITIES->Translate("fsavail"),
            $this->UTILITIES->Translate("fsuse%"),
            $this->UTILITIES->Translate("Mount Point")
        ];   
        $table_mmc = $this->GetEmmcMounts();
        $table_sd = $this->GetSdMounts();
        if(count($table_sd) === 0) {
            $table = $table_mmc;
        } else {
            $table = array_merge($table_mmc, $table_sd);
        }
        $footer = "";
        $MOUNTSTABLE = new TABLE($name, $header, $table, $footer);
        $string = $MOUNTSTABLE->DisplayTableOnCard($w3_responsive_class, "");
        return $string;
    }
    
    function GetEmmcMounts() {
        $devices_mmc = $this->UTILITIES->RASPBERRY->GetMmcDevices();
        foreach($devices_mmc as $device_mmc) {
            $partitions_of_mmc_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfMmcDevice($device_mmc["device"]);
            if(count($partitions_of_mmc_device) === 0) {
                $table_mmc[] = [
                    "/dev/".$device_mmc["device"],
                    "", "", ""
                        ];
            } else {
                foreach($partitions_of_mmc_device as $partition_of_mmc_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_mmc_device["partition"]);
                    $table_mmc[] = [
                            "/dev/".$lsblk["name"],
                            $lsblk["fsavail"],
                            $lsblk["fsuse"],
                            "<a style='color:green;'>".$lsblk["mountpoint"]."</a>"
                        ];
                }
            }
        }
        return $table_mmc;
    }
    
    function GetSdMounts() {
        $devices_sd = $this->UTILITIES->RASPBERRY->GetSdDevices();
        
        foreach($devices_sd as $device_sd) {
            $partitions_of_sd_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfSdDevice($device_sd["device"]);
            if(count($partitions_of_sd_device) === 0) {
                $table_sd[] = [
                    "/dev/".$device_sd["device"],
                    "", "", ""
                        ];
            } else {
                foreach($partitions_of_sd_device as $partition_of_sd_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_sd_device["partition"]);
                    $table_sd[] = [
                            "/dev/".$lsblk["name"],
                            $lsblk["fsavail"],
                            $lsblk["fsuse"],
                            "<a style='color:green;'>".$lsblk["mountpoint"]."</a>"
                        ];
                }
            }
        }
        
        return $table_sd;
    }
    
    function DisplayLocalResources($w3_responsive_class) {
        $string = "<div id='DisplayLocalResourcesParentNode'>".$this->DisplayLocalResourcesChildNode($w3_responsive_class)."</div>";
        $string .= $this->UTILITIES_LOCAL_RESOURCES->LoadDisplayLocalResourcesTable();
        return $string;
    }
    
    function DisplayLocalResourcesChildNode($w3_responsive_class) {
        $name = $this->UTILITIES->Translate("Devices");
        $header = [
            $this->UTILITIES->Translate("Device"),
            $this->UTILITIES->Translate("Device Size"),
            $this->UTILITIES->Translate("Partition"),
            $this->UTILITIES->Translate("Partition Size"),
            $this->UTILITIES->Translate("Partition Id"),
            $this->UTILITIES->Translate("Partition Type"),
            $this->UTILITIES->Translate("fs fstype"),
            $this->UTILITIES->Translate("fs fsver"),
            $this->UTILITIES->Translate("fs label"),
            $this->UTILITIES->Translate("fs uuid"),
            $this->UTILITIES->Translate("fstab mount point"),
            $this->UTILITIES->Translate("fstab type"),
            $this->UTILITIES->Translate("fstab options"),
            $this->UTILITIES->Translate("fstab dump"),
            $this->UTILITIES->Translate("fstab pass")
        ];
        $fstabentries = $this->UTILITIES->RASPBERRY->ReadFstab();
        $table_mmc = $this->GetTableMmc($fstabentries);
        $table_sd = $this->GetTableSd($fstabentries);
        if(count($table_sd) > 0) {
            $table = array_merge($table_mmc, $table_sd);
        } else {
            $table = $table_mmc;
        }
        $footer = $this->ButtonToConfigureLocalResources1();
        $TABLEOFLOCALRESOURCES = new TABLE($name, $header, $table, $footer);
        $string = "<div id='DisplayLocalResourcesChildNode'>".$TABLEOFLOCALRESOURCES->DisplayTableOnCard($w3_responsive_class, "")."</div>";
        return $string;
    }
    
    function ButtonToConfigureLocalResources1() {
        $string =    "<a "
                        . "id='ResourcesSpinner' "
                        . "class='w3-button w3-round-large w3-border' "
                        . "style='pointer-events:none;'>"
                        . "<i class='fa fa-spinner fa-spin'></i>"
                    . "</a>";
        return $string;
    }
    
    function GetTableMmc($fstabentries) {
        $devices_mmc = $this->UTILITIES->RASPBERRY->GetMmcDevices();
        foreach($devices_mmc as $device_mmc) {
            $partitions_of_mmc_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfMmcDevice($device_mmc["device"]);
            if(count($partitions_of_mmc_device) === 0) {
                $table_mmc[] = [
                    "/dev/".$device_mmc["device"],
                    $device_mmc["device_size"],
                    "", "", "", "", "", "", "", "", "", "", "", "", ""
                        ];
            } else {
                $i = 0;
                foreach($partitions_of_mmc_device as $partition_of_mmc_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_mmc_device["partition"]);
                    $fstab_entry = $this->UTILITIES->GetFstabEntryByPartitionOrUuid($partition_of_mmc_device["partition"], $fstabentries);
                    if($i === 0) {
                        $table_mmc[] = [
                            "/dev/".$device_mmc["device"],
                            $device_mmc["device_size"],
                            "/dev/".$partition_of_mmc_device["partition"],
                            $partition_of_mmc_device["partition_size"],
                            "<a id='idpartition_id:".$partition_of_mmc_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idpartition_type:".$partition_of_mmc_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            $lsblk["fstype"],
                            $lsblk["fsver"],
                            $lsblk["label"],
                            $lsblk["uuid"],
                            $fstab_entry["mountpoint"],
                            $fstab_entry["type"],
                            $fstab_entry["options"],
                            $fstab_entry["dump"],
                            $fstab_entry["pass"]
                        ];
                    } else {
                        $table_mmc[] = [
                            "",
                            "",
                            "/dev/".$partition_of_mmc_device["partition"],
                            $partition_of_mmc_device["partition_size"],
                            "<a id='idpartition_id:".$partition_of_mmc_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idpartition_type:".$partition_of_mmc_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            $lsblk["fstype"],
                            $lsblk["fsver"],
                            $lsblk["label"],
                            $lsblk["uuid"],
                            $fstab_entry["mountpoint"],
                            $fstab_entry["type"],
                            $fstab_entry["options"],
                            $fstab_entry["dump"],
                            $fstab_entry["pass"]
                        ];
                    }
                    $i++;  
                }
            }
        }
        return $table_mmc;
    }
    
    function GetTableSd($fstabentries) {
        $devices_sd = $this->UTILITIES->RASPBERRY->GetSdDevices();
        foreach($devices_sd as $device_sd) {
            $partitions_of_sd_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfSdDevice($device_sd["device"]);
            if(count($partitions_of_sd_device) === 0) {
                $table_sd[] = [
                    "/dev/".$device_sd["device"],
                    $device_sd["device_size"],
                    "", "", "", "", "", "", ""
                        ];
            } else {
                $i = 0;
                foreach($partitions_of_sd_device as $partition_of_sd_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_sd_device["partition"]);
                    $fstab_entry = $this->UTILITIES->GetFstabEntryByPartitionOrUuid($lsblk["uuid"], $fstabentries);
                    if($i === 0) {
                        $table_sd[] = [
                            "/dev/".$device_sd["device"],
                            $device_sd["device_size"],
                            "/dev/".$partition_of_sd_device["partition"],
                            $partition_of_sd_device["partition_size"],
                            "<a id='idpartition_id:".$partition_of_sd_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idpartition_type:".$partition_of_sd_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            $lsblk["fstype"],
                            $lsblk["fsver"],
                            $lsblk["label"],
                            $lsblk["uuid"],
                            $fstab_entry["mountpoint"],
                            $fstab_entry["type"],
                            $fstab_entry["options"],
                            $fstab_entry["dump"],
                            $fstab_entry["pass"]
                        ];
                    } else {
                        $table_sd[] = [
                            "",
                            "",
                            "/dev/".$partition_of_sd_device["partition"],
                            $partition_of_sd_device["partition_size"],
                            "<a id='idpartition_id:".$partition_of_sd_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idpartition_type:".$partition_of_sd_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            $lsblk["fstype"],
                            $lsblk["fsver"],
                            $lsblk["label"],
                            $lsblk["uuid"],
                            $fstab_entry["mountpoint"],
                            $fstab_entry["type"],
                            $fstab_entry["options"],
                            $fstab_entry["dump"],
                            $fstab_entry["pass"]
                        ];
                    }
                $i++;
                }
            }
        }
        return $table_sd;
    }
    
    function DisplayLocalResourcesChildNodeRefreshed($w3_responsive_class, $partitions_id_type) {
        $name = $this->UTILITIES->Translate("Devices");
        $header = [
            $this->UTILITIES->Translate("Device"),
            $this->UTILITIES->Translate("Device Size"),
            $this->UTILITIES->Translate("Partition"),
            $this->UTILITIES->Translate("Partition Size"),
            $this->UTILITIES->Translate("Partition Id"),
            $this->UTILITIES->Translate("Partition Type"),
            $this->UTILITIES->Translate("fs fstype"),
            $this->UTILITIES->Translate("fs fsver"),
            $this->UTILITIES->Translate("fs label"),
            $this->UTILITIES->Translate("fs uuid"),
            $this->UTILITIES->Translate("fstab mount point"),
            $this->UTILITIES->Translate("fstab type"),
            $this->UTILITIES->Translate("fstab options"),
            $this->UTILITIES->Translate("fstab dump"),
            $this->UTILITIES->Translate("fstab pass")
        ];
        $fstabentries = $this->UTILITIES->RASPBERRY->ReadFstab();
        $table_mmc = $this->GetTableMmc2($fstabentries, $partitions_id_type);
        $table_sd = $this->GetTableSd2($fstabentries, $partitions_id_type);
        if(count($table_sd) > 0) {
            $table = array_merge($table_mmc, $table_sd);
        } else {
            $table = $table_mmc;
        }
        $footer = $this->ButtonToConfigureLocalResources2();
        $TABLEOFLOCALRESOURCES = new TABLE($name, $header, $table, $footer);
        $string = "<div id='DisplayLocalResourcesChildNode'>".$TABLEOFLOCALRESOURCES->DisplayTableOnCard($w3_responsive_class, "")."</div>";
        return $string;
    }
    
    function ButtonToConfigureLocalResources2() {
        $string = $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToLocalResourcesConfiguration",                                                                               // $button_id
            $this->UTILITIES->toroot."/pages/RASPBERRY_LocalResources.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,  // $button_href    
            "DisableButtonsAndSpinThisButton(".json_encode("IdButtonLinkedToLocalResourcesConfiguration").")",                           // $button_jsfunction_onclick
            $this->UTILITIES->Translate("Configure")                                                                          // $button_text
        );
        return $string;
    }
    
    function GetTableMmc2($fstabentries, $partitions_id_type) {
        $devices_mmc = $this->UTILITIES->RASPBERRY->GetMmcDevices();
        foreach($devices_mmc as $device_mmc) {
            $partitions_of_mmc_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfMmcDevice($device_mmc["device"]);
            if(count($partitions_of_mmc_device) === 0) {
                $table_mmc[] = [
                    "/dev/".$device_mmc["device"],
                    $device_mmc["device_size"],
                    "", "", "", "", "", "", "", "", "", "", "", "", ""
                        ];
            } else {
                $i = 0;
                foreach($partitions_of_mmc_device as $partition_of_mmc_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_mmc_device["partition"]);
                    $fstab_entry = $this->UTILITIES->GetFstabEntryByPartitionOrUuid($partition_of_mmc_device["partition"], $fstabentries);
                    if($i === 0) {
                        $table_mmc[] = [
                            "/dev/".$device_mmc["device"],
                            $device_mmc["device_size"],
                            "/dev/".$partition_of_mmc_device["partition"],
                            $partition_of_mmc_device["partition_size"],
                            "<a id='idpartition_id:".$partition_of_mmc_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_mmc_device["partition"], $partitions_id_type)["id"]."</a>",
                            "<a id='idpartition_type:".$partition_of_mmc_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_mmc_device["partition"], $partitions_id_type)["type"]."</a>",
                            $lsblk["fstype"],
                            $lsblk["fsver"],
                            $lsblk["label"],
                            $lsblk["uuid"],
                            $fstab_entry["mountpoint"],
                            $fstab_entry["type"],
                            $fstab_entry["options"],
                            $fstab_entry["dump"],
                            $fstab_entry["pass"]
                        ];
                    } else {
                        $table_mmc[] = [
                            "",
                            "",
                            "/dev/".$partition_of_mmc_device["partition"],
                            $partition_of_mmc_device["partition_size"],
                            "<a id='idpartition_id:".$partition_of_mmc_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_mmc_device["partition"], $partitions_id_type)["id"]."</a>",
                            "<a id='idpartition_type:".$partition_of_mmc_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_mmc_device["partition"], $partitions_id_type)["type"]."</a>",
                            $lsblk["fstype"],
                            $lsblk["fsver"],
                            $lsblk["label"],
                            $lsblk["uuid"],
                            $fstab_entry["mountpoint"],
                            $fstab_entry["type"],
                            $fstab_entry["options"],
                            $fstab_entry["dump"],
                            $fstab_entry["pass"]
                        ];
                    }
                    $i++;  
                }
            }
        }
        return $table_mmc;
    }
    
    function GetTableSd2($fstabentries, $partitions_id_type) {
        $devices_sd = $this->UTILITIES->RASPBERRY->GetSdDevices();
        foreach($devices_sd as $device_sd) {
            $partitions_of_sd_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfSdDevice($device_sd["device"]);
            if(count($partitions_of_sd_device) === 0) {
                $table_sd[] = [
                    "/dev/".$device_sd["device"],
                    $device_sd["device_size"],
                    "", "", "", "", "", "", ""
                        ];
            } else {
                $i = 0;
                foreach($partitions_of_sd_device as $partition_of_sd_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_sd_device["partition"]);
                    $fstab_entry = $this->UTILITIES->GetFstabEntryByPartitionOrUuid($lsblk["uuid"], $fstabentries);
                    if($i === 0) {
                        $table_sd[] = [
                            "/dev/".$device_sd["device"],
                            $device_sd["device_size"],
                            "/dev/".$partition_of_sd_device["partition"],
                            $partition_of_sd_device["partition_size"],
                            "<a id='idpartition_id:".$partition_of_sd_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_sd_device["partition"], $partitions_id_type)["id"]."</a>",
                            "<a id='idpartition_type:".$partition_of_sd_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_sd_device["partition"], $partitions_id_type)["type"]."</a>",
                            $lsblk["fstype"],
                            $lsblk["fsver"],
                            $lsblk["label"],
                            $lsblk["uuid"],
                            $fstab_entry["mountpoint"],
                            $fstab_entry["type"],
                            $fstab_entry["options"],
                            $fstab_entry["dump"],
                            $fstab_entry["pass"]
                        ];
                    } else {
                        $table_sd[] = [
                            "",
                            "",
                            "/dev/".$partition_of_sd_device["partition"],
                            $partition_of_sd_device["partition_size"],
                            "<a id='idpartition_id:".$partition_of_sd_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_sd_device["partition"], $partitions_id_type)["id"]."</a>",
                            "<a id='idpartition_type:".$partition_of_sd_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_sd_device["partition"], $partitions_id_type)["type"]."</a>",
                            $lsblk["fstype"],
                            $lsblk["fsver"],
                            $lsblk["label"],
                            $lsblk["uuid"],
                            $fstab_entry["mountpoint"],
                            $fstab_entry["type"],
                            $fstab_entry["options"],
                            $fstab_entry["dump"],
                            $fstab_entry["pass"]
                        ];
                    }
                $i++;
                }
            }
        }
        return $table_sd;
    }
    
    function ConfigureLocalResources() {
        $string = "<div id='ConfigureLocalResourcesTableParentNode'>".$this->ConfigureLocalResourcesChildNode()."</div>";
        $string .= $this->UTILITIES_LOCAL_RESOURCES->LoadConfigureLocalResourcesTable();
        return $string;
    }
    
    function ConfigureLocalResourcesChildNode() {
        $name = $this->UTILITIES->Translate("Devices");
        $header = [
            $this->UTILITIES->Translate("Action"),
            $this->UTILITIES->Translate("Device"),
            $this->UTILITIES->Translate("Device Size"),
            $this->UTILITIES->Translate("Partition"),
            $this->UTILITIES->Translate("Partition Size"),
            $this->UTILITIES->Translate("Partition Id"),
            $this->UTILITIES->Translate("Partition Type"),
            $this->UTILITIES->Translate("fs fstype"),
            $this->UTILITIES->Translate("fs fsver"),
            $this->UTILITIES->Translate("fs label"),
            $this->UTILITIES->Translate("fs uuid"),
            $this->UTILITIES->Translate("fstab mount point"),
            $this->UTILITIES->Translate("fstab type"),
            $this->UTILITIES->Translate("fstab options"),
            $this->UTILITIES->Translate("fstab dump"),
            $this->UTILITIES->Translate("fstab pass") 
        ];
        $fstabentries = $this->UTILITIES->RASPBERRY->ReadFstab();
        $table_mmc = $this->GetTableConfigureMmc($fstabentries);
        $table_sd = $this->GetTableConfigureSd($fstabentries);
        if(count($table_sd) > 0) {
            $table = array_merge($table_mmc, $table_sd);
        } else {
            $table = $table_mmc;
        }
        $footer = $this->ButtonToDashboard1().$this->HiddenConsolePanels();
        $TABLEOFLOCALRESOURCES = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureLocalResourcesTableChildNode'>".$TABLEOFLOCALRESOURCES->DisplayTableOnCard("w3-row", "w3-right-align")."</div>";
        $string .= "<script>"
                    . "DisableButtons();"
                . "</script>";
        return $string;
    }
    
    function ButtonToDashboard1() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToDashboard",
            $this->toroot."/pages/RASPBERRY_Dashboard.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToDashboard").")",
            "<i class='fa fa-spinner fa-spin'></i>"                                                                 // $button_text
        );
        return $string;
    }
    
    function GetTableConfigureMmc($fstabentries) {
        $devices_mmc = $this->UTILITIES->RASPBERRY->GetMmcDevices();
        foreach($devices_mmc as $device_mmc) {
            $partitions_of_mmc_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfMmcDevice($device_mmc["device"]);
            if(count($partitions_of_mmc_device) === 0) {
                $table_mmc[] = [
                    "", "<a id='iddevice:".$device_mmc["device"]."'>/dev/".$device_mmc["device"]."</a>",
                    "<a id='iddevice_size:".$device_mmc["device"]."'>".$device_mmc["device_size"]."</a>",
                    "", "", "", "", "", "", "", "", "", "", "", "", "", ""
                        ];
            } else {
                $i = 0;
                foreach($partitions_of_mmc_device as $partition_of_mmc_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_mmc_device["partition"]);
                    $fstab_entry = $this->UTILITIES->GetFstabEntryByPartitionOrUuid($partition_of_mmc_device["partition"], $fstabentries);
                    if($i === 0) {
                        $table_mmc[] = [
                            "(".$this->UTILITIES->Translate("mounted").")",
                            "<a id='iddevice:".$device_mmc["device"]."'>/dev/".$device_mmc["device"]."</a>",
                            "<a id='iddevice_size:".$device_mmc["device"]."'>".$device_mmc["device_size"]."</a>",
                            "<a id='idpartition:".$partition_of_mmc_device["partition"]."'>/dev/".$partition_of_mmc_device["partition"]."</a>",
                            "<a id='idpartition_size:".$partition_of_mmc_device["partition"]."'>".$partition_of_mmc_device["partition_size"]."</a>",
                            "<a id='idpartition_id:".$partition_of_mmc_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idpartition_type:".$partition_of_mmc_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idfilesystem_fstype:".$partition_of_mmc_device["partition"]."'>".$lsblk["fstype"]."</a>",
                            "<a id='idfilesystem_fsver:".$partition_of_mmc_device["partition"]."'>".$lsblk["fsver"]."</a>",
                            "<a id='idfilesystem_label:".$partition_of_mmc_device["partition"]."'>".$lsblk["label"]."</a>",
                            "<a id='idfilesystem_uuid:".$partition_of_mmc_device["partition"]."'>".$lsblk["uuid"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["mountpoint"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["type"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["options"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["dump"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["pass"]."</a>"
                            
                        ];
                    } else {
                        $table_mmc[] = [
                            "(".$this->UTILITIES->Translate("mounted").")",
                            "",
                            "",
                            "<a id='idpartition:".$partition_of_mmc_device["partition"]."'>/dev/".$partition_of_mmc_device["partition"]."</a>",
                            "<a id='idpartition_size:".$partition_of_mmc_device["partition"]."'>".$partition_of_mmc_device["partition_size"]."</a>",
                            "<a id='idpartition_id:".$partition_of_mmc_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idpartition_type:".$partition_of_mmc_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idfilesystem_fstype:".$partition_of_mmc_device["partition"]."'>".$lsblk["fstype"]."</a>",
                            "<a id='idfilesystem_fsver:".$partition_of_mmc_device["partition"]."'>".$lsblk["fsver"]."</a>",
                            "<a id='idfilesystem_label:".$partition_of_mmc_device["partition"]."'>".$lsblk["label"]."</a>",
                            "<a id='idfilesystem_uuid:".$partition_of_mmc_device["partition"]."'>".$lsblk["uuid"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["mountpoint"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["type"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["options"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["dump"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["pass"]."</a>"
                        ];
                    }
                    $i++;  
                }
            }
        }
        return $table_mmc;
    }
    
    function GetTableConfigureSd($fstabentries) {
        $devices_sd = $this->UTILITIES->RASPBERRY->GetSdDevices();
        
        foreach($devices_sd as $device_sd) {
            $partitions_of_sd_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfSdDevice($device_sd["device"]);
            if(count($partitions_of_sd_device) === 0) {
                $table_sd[] = [
                    $this->CreatePartitionButton($device_sd["device"]),
                    "<a id='iddevice:".$device_sd["device"]."'>/dev/".$device_sd["device"]."</a>",
                    "<a id='iddevice_size:".$device_sd["device"]."'>".$device_sd["device_size"]."</a>",
                    "", "", "", "", "", "", "", "", "", "", "", "", ""
                        ];
            } else {
                $i = 0;
                foreach($partitions_of_sd_device as $partition_of_sd_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_sd_device["partition"]);
                    $fstab_entry = $this->UTILITIES->GetFstabEntryByPartitionOrUuid($lsblk["uuid"], $fstabentries);
                    $buttons = $this->ManageSdPartitionsButtons(
                            $device_sd["device"],
                            $partition_of_sd_device["partition"],
                            $lsblk["fstype"],
                            $lsblk["uuid"],
                            $lsblk["mountpoint"],
                            $fstab_entry["mountpoint"]);
                    if($i === 0) {
                        $table_sd[] = [
                            $buttons,
                            "<a id='iddevice:".$device_sd["device"]."'>/dev/".$device_sd["device"]."</a>",
                            "<a id='iddevice_size:".$device_sd["device"]."'>".$device_sd["device_size"]."</a>",
                            "<a id='idpartition:".$partition_of_sd_device["partition"]."'>/dev/".$partition_of_sd_device["partition"]."</a>",
                            "<a id='idpartition_size:".$partition_of_sd_device["partition"]."'>".$partition_of_sd_device["partition_size"]."</a>",
                            "<a id='idpartition_id:".$partition_of_sd_device["partition"]."'><i class='fa fa-spinner fa-spin'></i></a>",
                            "<a id='idpartition_type:".$partition_of_sd_device["partition"]."'><i class='fa fa-spinner fa-spin'></i></a>",
                            "<a id='idfilesystem_fstype:".$partition_of_sd_device["partition"]."'>".$lsblk["fstype"]."</a>",
                            "<a id='idfilesystem_fsver:".$partition_of_sd_device["partition"]."'>".$lsblk["fsver"]."</a>",
                            "<a id='idfilesystem_label:".$partition_of_sd_device["partition"]."'>".$lsblk["label"]."</a>",
                            "<a id='idfilesystem_uuid:".$partition_of_sd_device["partition"]."'>".$lsblk["uuid"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["mountpoint"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["type"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["options"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["dump"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["pass"]."</a>"
                            
                        ];
                    } else {
                        $table_sd[] = [
                            $buttons,
                            "",
                            "",
                            "<a id='idpartition:".$partition_of_sd_device["partition"]."'>/dev/".$partition_of_sd_device["partition"]."</a>",
                            "<a id='idpartition_size:".$partition_of_sd_device["partition"]."'>".$partition_of_sd_device["partition_size"]."</a>",
                            "<a id='idpartition_id:".$partition_of_sd_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idpartition_type:".$partition_of_sd_device["partition"]."'><i class='fa fa-spinner fa-spin'></a>",
                            "<a id='idfilesystem_fstype:".$partition_of_sd_device["partition"]."'>".$lsblk["fstype"]."</a>",
                            "<a id='idfilesystem_fsver:".$partition_of_sd_device["partition"]."'>".$lsblk["fsver"]."</a>",
                            "<a id='idfilesystem_label:".$partition_of_sd_device["partition"]."'>".$lsblk["label"]."</a>",
                            "<a id='idfilesystem_uuid:".$partition_of_sd_device["partition"]."'>".$lsblk["uuid"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["mountpoint"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["type"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["options"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["dump"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["pass"]."</a>"                            
                        ];
                    }
                $i++;
                }
            }
        }
        return $table_sd;
    }
    
    function ConfigureLocalResourcesRefreshedChildNode($partitions_id_type) {
        $name = $this->UTILITIES->Translate("Devices");
        $header = [
            $this->UTILITIES->Translate("Action"),
            $this->UTILITIES->Translate("Device"),
            $this->UTILITIES->Translate("Device Size"),
            $this->UTILITIES->Translate("Partition"),
            $this->UTILITIES->Translate("Partition Size"),
            $this->UTILITIES->Translate("Partition Id"),
            $this->UTILITIES->Translate("Partition Type"),
            $this->UTILITIES->Translate("fs fstype"),
            $this->UTILITIES->Translate("fs fsver"),
            $this->UTILITIES->Translate("fs label"),
            $this->UTILITIES->Translate("fs uuid"),
            $this->UTILITIES->Translate("fstab mount point"),
            $this->UTILITIES->Translate("fstab type"),
            $this->UTILITIES->Translate("fstab options"),
            $this->UTILITIES->Translate("fstab dump"),
            $this->UTILITIES->Translate("fstab pass")
        ];
        $fstabentries = $this->UTILITIES->RASPBERRY->ReadFstab();
        $table_mmc = $this->GetTableConfigureMmc2($fstabentries, $partitions_id_type);
        $table_sd = $this->GetTableConfigureSd2($fstabentries, $partitions_id_type);
        if(count($table_sd) > 0) {
            $table = array_merge($table_mmc, $table_sd);
        } else {
            $table = $table_mmc;
        }
        $footer = $this->ButtonToDashboard2().$this->HiddenConsolePanels();
        $TABLEOFLOCALRESOURCES = new TABLE($name, $header, $table, $footer);
        $string = "<div id='ConfigureLocalResourcesTableChildNode'>".$TABLEOFLOCALRESOURCES->DisplayTableOnCard("w3-row", "w3-right-align")."</div>";
        return $string;
    }
    
    function ButtonToDashboard2() {
        $string =  $this->BUTTONS->ButtonLinkedTo(
            "IdButtonLinkedToDashboard",
            $this->toroot."/pages/RASPBERRY_Dashboard.php?lang=".$this->UTILITIES->lang.$this->UTILITIES->query_string,   // $link
            "DisableButtonsAndSpinThisButton("
                . json_encode("IdButtonLinkedToDashboard").")",
            "<i class='fa fa-arrow-left'></i>"                                                                 // $button_text
        );
        return $string;
    }
    
    function GetTableConfigureMmc2($fstabentries, $partitions_id_type) {
        $devices_mmc = $this->UTILITIES->RASPBERRY->GetMmcDevices();
        foreach($devices_mmc as $device_mmc) {
            $partitions_of_mmc_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfMmcDevice($device_mmc["device"]);
            if(count($partitions_of_mmc_device) === 0) {
                $table_mmc[] = [
                    "", "<a id='iddevice:".$device_mmc["device"]."'>/dev/".$device_mmc["device"]."</a>",
                    "<a id='iddevice_size:".$device_mmc["device"]."'>".$device_mmc["device_size"]."</a>",
                    "", "", "", "", "", "", "", "", "", "", "", "", "", ""
                        ];
            } else {
                $i = 0;
                foreach($partitions_of_mmc_device as $partition_of_mmc_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_mmc_device["partition"]);
                    $fstab_entry = $this->UTILITIES->GetFstabEntryByPartitionOrUuid($partition_of_mmc_device["partition"], $fstabentries);
                    if($i === 0) {
                        $table_mmc[] = [
                            "(".$this->UTILITIES->Translate("mounted").")",
                            "<a id='iddevice:".$device_mmc["device"]."'>/dev/".$device_mmc["device"]."</a>",
                            "<a id='iddevice_size:".$device_mmc["device"]."'>".$device_mmc["device_size"]."</a>",
                            "<a id='idpartition:".$partition_of_mmc_device["partition"]."'>/dev/".$partition_of_mmc_device["partition"]."</a>",
                            "<a id='idpartition_size:".$partition_of_mmc_device["partition"]."'>".$partition_of_mmc_device["partition_size"]."</a>",
                            "<a id='idpartition_id:".$partition_of_mmc_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_mmc_device["partition"], $partitions_id_type)["id"]."</a>",
                            "<a id='idpartition_type:".$partition_of_mmc_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_mmc_device["partition"], $partitions_id_type)["type"]."</a>",
                            "<a id='idfilesystem_fstype:".$partition_of_mmc_device["partition"]."'>".$lsblk["fstype"]."</a>",
                            "<a id='idfilesystem_fsver:".$partition_of_mmc_device["partition"]."'>".$lsblk["fsver"]."</a>",
                            "<a id='idfilesystem_label:".$partition_of_mmc_device["partition"]."'>".$lsblk["label"]."</a>",
                            "<a id='idfilesystem_uuid:".$partition_of_mmc_device["partition"]."'>".$lsblk["uuid"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["mountpoint"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["type"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["options"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["dump"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["pass"]."</a>"
                        ];
                    } else {
                        $table_mmc[] = [
                            "(".$this->UTILITIES->Translate("mounted").")",
                            "",
                            "",
                            "<a id='idpartition:".$partition_of_mmc_device["partition"]."'>/dev/".$partition_of_mmc_device["partition"]."</a>",
                            "<a id='idpartition_size:".$partition_of_mmc_device["partition"]."'>".$partition_of_mmc_device["partition_size"]."</a>",
                            "<a id='idpartition_id:".$partition_of_mmc_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_mmc_device["partition"], $partitions_id_type)["id"]."</a>",
                            "<a id='idpartition_type:".$partition_of_mmc_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_mmc_device["partition"], $partitions_id_type)["type"]."</a>",
                            "<a id='idfilesystem_fstype:".$partition_of_mmc_device["partition"]."'>".$lsblk["fstype"]."</a>",
                            "<a id='idfilesystem_fsver:".$partition_of_mmc_device["partition"]."'>".$lsblk["fsver"]."</a>",
                            "<a id='idfilesystem_label:".$partition_of_mmc_device["partition"]."'>".$lsblk["label"]."</a>",
                            "<a id='idfilesystem_uuid:".$partition_of_mmc_device["partition"]."'>".$lsblk["uuid"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["mountpoint"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["type"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["options"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["dump"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_mmc_device["partition"]."'>".$fstab_entry["pass"]."</a>"
                        ];
                    }
                    $i++;  
                }
            }
        }
        return $table_mmc;
    }
    
    function GetTableConfigureSd2($fstabentries, $partitions_id_type) {
        $devices_sd = $this->UTILITIES->RASPBERRY->GetSdDevices();
        
        foreach($devices_sd as $device_sd) {
            $partitions_of_sd_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfSdDevice($device_sd["device"]);
            if(count($partitions_of_sd_device) === 0) {
                $table_sd[] = [
                    $this->CreatePartitionButton($device_sd["device"]),
                    "<a id='iddevice:".$device_sd["device"]."'>/dev/".$device_sd["device"]."</a>",
                    "<a id='iddevice_size:".$device_sd["device"]."'>".$device_sd["device_size"]."</a>",
                    "", "", "", "", "", "", "", "", "", "", "", "", ""
                        ];
            } else {
                $i = 0;
                foreach($partitions_of_sd_device as $partition_of_sd_device) {
                    $lsblk = $this->UTILITIES->RASPBERRY->ReadLsblk($partition_of_sd_device["partition"]);
                    $fstab_entry = $this->UTILITIES->GetFstabEntryByPartitionOrUuid($lsblk["uuid"], $fstabentries);
                    $buttons = $this->ManageSdPartitionsButtons(
                            $device_sd["device"],
                            $partition_of_sd_device["partition"],
                            $lsblk["fstype"],
                            $lsblk["uuid"],
                            $lsblk["mountpoint"],
                            $fstab_entry["mountpoint"]);
                    if($i === 0) {
                        $table_sd[] = [
                            $buttons,
                            "<a id='iddevice:".$device_sd["device"]."'>/dev/".$device_sd["device"]."</a>",
                            "<a id='iddevice_size:".$device_sd["device"]."'>".$device_sd["device_size"]."</a>",
                            "<a id='idpartition:".$partition_of_sd_device["partition"]."'>/dev/".$partition_of_sd_device["partition"]."</a>",
                            "<a id='idpartition_size:".$partition_of_sd_device["partition"]."'>".$partition_of_sd_device["partition_size"]."</a>",
                            "<a id='idpartition_id:".$partition_of_sd_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_sd_device["partition"], $partitions_id_type)["id"]."</a>",
                            "<a id='idpartition_type:".$partition_of_sd_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_sd_device["partition"], $partitions_id_type)["type"]."</a>",
                            "<a id='idfilesystem_fstype:".$partition_of_sd_device["partition"]."'>".$lsblk["fstype"]."</a>",
                            "<a id='idfilesystem_fsver:".$partition_of_sd_device["partition"]."'>".$lsblk["fsver"]."</a>",
                            "<a id='idfilesystem_label:".$partition_of_sd_device["partition"]."'>".$lsblk["label"]."</a>",
                            "<a id='idfilesystem_uuid:".$partition_of_sd_device["partition"]."'>".$lsblk["uuid"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["mountpoint"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["type"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["options"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["dump"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["pass"]."</a>"
                        ];
                    } else {
                        $table_sd[] = [
                            "",
                            "",
                            "<a id='idpartition:".$partition_of_sd_device["partition"]."'>/dev/".$partition_of_sd_device["partition"]."</a>",
                            "<a id='idpartition_size:".$partition_of_sd_device["partition"]."'>".$partition_of_sd_device["partition_size"]."</a>",
                            "<a id='idpartition_id:".$partition_of_sd_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_sd_device["partition"], $partitions_id_type)["id"]."</a>",
                            "<a id='idpartition_type:".$partition_of_sd_device["partition"]."'>".$this->UTILITIES_LOCAL_RESOURCES->GetPartitionIdAndTypeByPartition($partition_of_sd_device["partition"], $partitions_id_type)["type"]."</a>",
                            "<a id='idfilesystem_fstype:".$partition_of_sd_device["partition"]."'>".$lsblk["fstype"]."</a>",
                            "<a id='idfilesystem_fsver:".$partition_of_sd_device["partition"]."'>".$lsblk["fsver"]."</a>",
                            "<a id='idfilesystem_label:".$partition_of_sd_device["partition"]."'>".$lsblk["label"]."</a>",
                            "<a id='idfilesystem_uuid:".$partition_of_sd_device["partition"]."'>".$lsblk["uuid"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["mountpoint"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["type"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["options"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["dump"]."</a>",
                            "<a id='idfstabmountpoint:".$partition_of_sd_device["partition"]."'>".$fstab_entry["pass"]."</a>",
                            $buttons
                        ];
                    }
                $i++;
                }
            }
        }
        return $table_sd;
    }
    
    function ManageSdPartitionsButtons($device, $partition, $fstype, $uuid, $mountpoint, $mountpoint_fstab) {
        
        if($mountpoint === $mountpoint_fstab) {
            $string = "(".$this->UTILITIES->Translate("mounted").")"; 
        } elseif($mountpoint_fstab !== null) {
            $string = $this->DeleteFstabMountPointButton($partition, $mountpoint_fstab);
        } elseif($fstype !== "") {
            $string = $this->WipeFileSystemButton($partition)." ".$this->CreateFstabMountPointButton($partition, $uuid, $fstype);
        } else {
            $string = $this->DeletePartitionButton($partition, $device)." ".$this->MakeFileSystemButton($device, $partition);
        }
        return $string;
    }
    
    function DeleteFstabMountPointButton($partition, $mountpoint_fstab) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonDeleteFstabMountPoint:".$partition, "inline", "", "", "", "DeleteFstabMountPoint("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_DeleteFstabMountPoint.php").", "  
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_LoadConfigureLocalResourcesTable.php").", "         // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "                                                            // idbuttonadduser
                . json_encode("IdConsolePanelDeleteFstabMountPoint").", "                                                             // idpanel                                                          // idconfirmbutton
                . json_encode("IdButtonCancelDeleteFstabMountPoint").", "                                                         // idcancelbutton
                . json_encode("IdButtonDeleteFstabMountPointNow").", "  
                . json_encode("IdButtonClearConsoleDeleteFstabMountPoint").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputDeleteFstabMountPoint").", "                                                            // idconsole
                . json_encode("ConfigureLocalResourcesTableParentNode").", "
                . json_encode("ConfigureLocalResourcesTableChildNode").", "
                . json_encode("idpartition:".$partition).", "
                . json_encode($partition).", "
                . json_encode("idfstabmountpoint:".$partition).", "
                . json_encode($mountpoint_fstab).")",                         // 
            "<i class='fa fa-arrow-left w3-margin-right' aria-hidden='true'></i>".$this->UTILITIES->Translate("Delete fstab mount point"));                                                     // $button_text
        return $string;
    }
    
    function CreateFstabMountPointButton($partition, $uuid, $type) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonCreateFstabMountPoint:".$partition, "inline", "", "", "", "CreateFstabMountPoint("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_CreateFstabMountPoint.php").", "  
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_LoadConfigureLocalResourcesTable.php").", "         // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "                                                            // idbuttonadduser
                . json_encode("IdConsolePanelCreateFstabMountPoint").", "                                                             // idpanel                                                          // idconfirmbutton
                . json_encode("IdButtonCancelCreateFstabMountPoint").", "                                                         // idcancelbutton
                . json_encode("IdButtonCreateFstabMountPointNow").", "  
                . json_encode("IdButtonClearConsoleCreateFstabMountPoint").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputCreateFstabMountPoint").", "                                                            // idconsole
                . json_encode("ConfigureLocalResourcesTableParentNode").", "
                . json_encode("ConfigureLocalResourcesTableChildNode").","
                . json_encode("idfilesystem_fstype:".$partition).", "
                . json_encode("idfilesystem_fsver:".$partition).", "
                . json_encode("idfilesystem_label:".$partition).", "
                . json_encode("idfilesystem_uuid:".$partition).", "
                . json_encode($uuid).", "
                . json_encode($type).")",                            // 
            $this->UTILITIES->Translate("Create fstab mount point")."<i class='fa fa-arrow-right w3-margin-left' aria-hidden='true'></i>");                                                     // $button_text
        return $string;
    }
    
    function DeletePartitionButton($partition, $device) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonDeletePartition:".$partition, "inline", "", "", "", "DeletePartition("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_LoadConfigureLocalResourcesTable.php").", "         // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "                                                            // idbuttonadduser
                . json_encode("IdConsolePanelDeletePartition").", "                                                             // idpanel                                                          // idconfirmbutton
                . json_encode("IdButtonCancelDeletePartition").", "                                                         // idcancelbutton
                . json_encode("IdButtonDeletePartitionNow").", "    
                . json_encode("IdButtonClearConsoleDeletePartition").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputDeletePartition").", "                                                            // idconsole
                . json_encode("ConfigureLocalResourcesTableParentNode").", "
                . json_encode("ConfigureLocalResourcesTableChildNode").", "
                . json_encode($device).", "
                . json_encode("idpartition:".$partition).", "
                . json_encode($partition).", "
                . json_encode("idpartition_size:".$partition).","
                . json_encode("idpartition_id:".$partition).","
                . json_encode("idpartition_type:".$partition).")",                         // 
            "<i class='fa fa-arrow-left w3-margin-right' aria-hidden='true'></i>".$this->UTILITIES->Translate("Delete Partition"));                                                     // $button_text
        return $string;
    }
    
    function CreatePartitionButton($device) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonCreatePartition:".$device, "inline", "", "", "", "CreatePartition("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_LoadConfigureLocalResourcesTable.php").", "        // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "                                                            // idbuttonadduser
                . json_encode("IdConsolePanelCreatePartition").", "                                                             // idpanel                                                          // idconfirmbutton
                . json_encode("IdButtonCancelCreatePartition").", "                                                         // idcancelbutton
                . json_encode("IdButtonCreatePartitionNow").", "    
                . json_encode("IdButtonClearConsoleCreatePartition").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputCreatePartition").", "                                                            // idconsole
                . json_encode("ConfigureLocalResourcesTableParentNode").", "
                . json_encode("ConfigureLocalResourcesTableChildNode").", "
                . json_encode("iddevice:".$device).", "
                . json_encode($device).", "
                . json_encode("iddevice_size:".$device).")",                          // 
            $this->UTILITIES->Translate("Create Partition")."<i class='fa fa-arrow-right w3-margin-left' aria-hidden='true'></i>");                                                     // $button_text
        return $string;
    }
    
    function MakeFileSystemButton($device, $partition) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonMakeFileSystem:".$partition, "inline", "", "", "", "MakeFileSystem("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_LoadConfigureLocalResourcesTable.php").", "         // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "                                                            // idbuttonadduser
                . json_encode("IdConsolePanelMakeFileSystem").", "                                                             // idpanel                                                          // idconfirmbutton
                . json_encode("IdButtonCancelMakeFileSystem").", "                                                         // idcancelbutton
                . json_encode("IdButtonMakeFileSystemNow").", "    
                . json_encode("IdButtonClearConsoleMakeFileSystem").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputMakeFileSystem").", "                                                            // idconsole
                . json_encode("ConfigureLocalResourcesTableParentNode").", "
                . json_encode("ConfigureLocalResourcesTableChildNode").","
                . json_encode($device).", "
                . json_encode("idpartition:".$partition).","
                . json_encode($partition).", "
                . json_encode("idpartition_size:".$partition).","
                . json_encode("idpartition_id:".$partition).","
                . json_encode("idpartition_type:".$partition).","
                . json_encode("IdEntryFileSystem").")",                            // 
            $this->UTILITIES->Translate("Make File System")."<i class='fa fa-arrow-right w3-margin-left' aria-hidden='true'></i>");                                                     // $button_text
        return $string;
    }
    
    function WipeFileSystemButton($partition) {
        $string = $this->BUTTONS->ButtonJsFunctionOnClick(
            "IdButtonWipeFileSystem:".$partition, "inline", "", "", "", "WipeFileSystem("                                                       // $button_id, ...display, ...jsfunction_onclick
                . json_encode($this->UTILITIES->toroot."/ajax/ExecuteCommandAsUserRealtimeOutput.php").", "             // AjaxFileExecute
                . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_LoadConfigureLocalResourcesTable.php").", "         // AjaxFileRefresh
                . json_encode($_SESSION["key"]).", ".json_encode("root").", ".json_encode("raspbx").", "                // authorization_key, user, host
                . json_encode("IdButtonLinkedToDashboard").", "                                                            // idbuttonadduser
                . json_encode("IdConsolePanelWipeFileSystem").", "                                                             // idpanel                                                          // idconfirmbutton
                . json_encode("IdButtonCancelWipeFileSystem").", "                                                         // idcancelbutton
                . json_encode("IdButtonWipeFileSystemNow").", "    
                . json_encode("IdButtonClearConsoleWipeFileSystem").", "                                                       // idconsolebutton
                . json_encode("IdConsoleOutputWipeFileSystem").", "                                                            // idconsole
                . json_encode("ConfigureLocalResourcesTableParentNode").", "
                . json_encode("ConfigureLocalResourcesTableChildNode").", "
                . json_encode($partition).", "
                . json_encode("idfilesystem_fstype:".$partition).", "
                . json_encode("idfilesystem_fsver:".$partition).", "
                . json_encode("idfilesystem_label:".$partition).", "
                . json_encode("idfilesystem_uuid:".$partition).")",                            // 
            "<i class='fa fa-arrow-left w3-margin-right' aria-hidden='true'></i>".$this->UTILITIES->Translate("Wipe File System"));                                                     // $button_text
        return $string;
    }
    
    function HiddenConsolePanels() {
        $string = $this->UTILITIES_LOCAL_RESOURCES->HiddenConsolePanelDeleteFstabMountPoint();
        $string .= $this->UTILITIES_LOCAL_RESOURCES->HiddenConsolePanelCreateFstabMountPoint();
        $string .= $this->UTILITIES_LOCAL_RESOURCES->HiddenConsolePanelDeletePartition();
        $string .= $this->UTILITIES_LOCAL_RESOURCES->HiddenConsolePanelCreatePartition();
        $string .= $this->UTILITIES_LOCAL_RESOURCES->HiddenConsolePanelMakeFileSystem();
        $string .= $this->UTILITIES_LOCAL_RESOURCES->HiddenConsolePanelWipeFileSystem();
        return $string;
    }
    
    
    
}
