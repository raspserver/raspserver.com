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
class UTILITIES_LOCAL_RESOURCES {
    
    function __construct (
            $UTILITIES,
            $BUTTONS
    ) {
        $this->UTILITIES = $UTILITIES;
        $this->BUTTONS = $BUTTONS;
    }
    
    function HiddenConsolePanelDeleteFstabMountPoint() {
        $UmountPartitionPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelDeleteFstabMountPoint", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                       // $name_display, $name  

            $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelDeleteFstabMountPoint", "none", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonDeleteFstabMountPointNow",    "none", "", "", "", "", $this->UTILITIES->Translate("Delete fstab mount point")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputDeleteFstabMountPoint",                                                                    // $idconsole
            "IdButtonClearConsoleDeleteFstabMountPoint",                                                                // $idclearconsolebutton  
            "");                                                                                          // $JSfunctionclearconsolebutton                                                                     
        $string = $UmountPartitionPanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelCreateFstabMountPoint() {
        $UmountPartitionPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelCreateFstabMountPoint", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                       // $name_display, $name  

            $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelCreateFstabMountPoint", "none", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonCreateFstabMountPointNow",    "none", "", "", "", "", $this->UTILITIES->Translate("Create fstab mount point")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputCreateFstabMountPoint",                                                                    // $idconsole
            "IdButtonClearConsoleCreateFstabMountPoint",                                                                // $idclearconsolebutton  
            "");                                                                                          // $JSfunctionclearconsolebutton                                                                     
        $string = $UmountPartitionPanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelDeletePartition() {
        $DeletePartitionPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelDeletePartition", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                       // $name_display, $name  

            $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelDeletePartition", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonDeletePartitionNow",    "", "", "", "", "", $this->UTILITIES->Translate("Delete Partition")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputDeletePartition",                                                                    // $idconsole
            "IdButtonClearConsoleDeletePartition",                                                                // $idclearconsolebutton  
            "");                                                                                          // $JSfunctionclearconsolebutton                                                                     
        $string = $DeletePartitionPanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelCreatePartition() {
        $CreatePartitionPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelCreatePartition", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                       // $name_display, $name  

            $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelCreatePartition", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonCreatePartitionNow",    "", "", "", "", "", $this->UTILITIES->Translate("Create Partition")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputCreatePartition",                                                                    // $idconsole
            "IdButtonClearConsoleCreatePartition",                                                                // $idclearconsolebutton  
            "");                                                                                          // $JSfunctionclearconsolebutton                                                                     
        $string = $CreatePartitionPanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelMakeFileSystem() {
        $MakeFileSystemPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelMakeFileSystem", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                       // $name_display, $name  
            $this->BUTTONS->Input_Dropdown(
                  "IdEntryFileSystem", // $entry_id, 
                  array("fat32", "ntfs", "ext4"), // $entry_valuse, 
                  $this->UTILITIES->Translate("File System Type"), // $entry_label, 
                  $this->UTILITIES->Translate("select"), // $select, 
                  "") // $JsFunctionOnChange)
          . $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelMakeFileSystem", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonMakeFileSystemNow",    "", "", "", "", "", $this->UTILITIES->Translate("Make File System")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputMakeFileSystem",                                                                    // $idconsole
            "IdButtonClearConsoleMakeFileSystem",                                                                // $idclearconsolebutton  
            "");                                                                                          // $JSfunctionclearconsolebutton                                                                     
        $string = $MakeFileSystemPanel->ConsolePanel();
        return $string;
    }
    
    function HiddenConsolePanelWipeFileSystem() {
        $WipeFileSystemPanel = new CONSOLE_PANEL(
            $this->UTILITIES,                                                                                   // $UTILITIES
            "IdConsolePanelWipeFileSystem", "none",                                                                    // $console_panel_id, $console_panel_display
            "none", "",                                                                                       // $name_display, $name  
            
            $this->BUTTONS->TwinButtons(                                                                        // $field_left continuation
                "IdButtonCancelWipeFileSystem", "", "", "", "", "", $this->UTILITIES->Translate("Cancel"),                         // $button_cancel_id, ...display, ...jsfunction_onclick, ...text
                "IdButtonWipeFileSystemNow",    "", "", "", "", "", $this->UTILITIES->Translate("Wipe File System")),                      // $button_confirmation_id, ...display, ...jsfunction_onclick, ...text  
            "root",                                                                                             // $user
            "raspbx",                                                                                           // $host
            "IdConsoleOutputWipeFileSystem",                                                                    // $idconsole
            "IdButtonClearConsoleWipeFileSystem",                                                                // $idclearconsolebutton  
            "");                                                                                          // $JSfunctionclearconsolebutton                                                                     
        $string = $WipeFileSystemPanel->ConsolePanel();
        return $string;
    }
    
    function CreateCommandsDeleteFstabMountPoint($mountpoint) {
        $string1 = "sed -i -e \\'/".str_replace("/", "\\\\/", $mountpoint)."/\\'d /etc/fstab";
        $string2 = "sed -i -e '/".str_replace("/", "\/", $mountpoint)."/'d /etc/fstab";
        if($this->UTILITIES->RASPBERRY->DoesDirectoryExist($mountpoint)) {
            $string3 = "rm -r ".$mountpoint;
            $string4 = "rm -r ".$mountpoint;
        }
        $commands_delete_fstab_mount_point = $string1.":".$string2."°".$string3.":".$string4;
        return $commands_delete_fstab_mount_point;
    }
    
    function CreateCommandsCreateFstabMountPoint($uuid, $type) {
        switch ($type) {
            case "vfat":
                $options = "umask=0000";
                break;
            case "ntfs":
                $options = "umask=0000";
                break;
            case "ext4":
                $options = "defaults";
                break;
        }
        if(!$this->UTILITIES->RASPBERRY->DoesDirectoryExist("/media/".$uuid)) {
            $string1 = "mkdir /media/".$uuid;
            $string2 = "mkdir /media/".$uuid;
            $string3 = "sed -i -e \\'$\\'a\\'UUID=".$uuid." /media/".$uuid." ".$type." ".$options." 0 2\\' /etc/fstab";
            $string4 = "sed -i -e '$'a'UUID=".$uuid." /media/".$uuid." ".$type." ".$options." 0 2' /etc/fstab";
        } else {
            $string3 = "sed -i -e \\'$\\'a\\'UUID=".$uuid." /media/".$uuid." ".$type." ".$options." 0 2\\' /etc/fstab";
            $string4 = "sed -i -e '$'a'UUID=".$uuid." /media/".$uuid." ".$type." ".$options." 0 2' /etc/fstab";
        }
        $commands_create_fstab_mount_point = $string1.":".$string2."°".$string3.":".$string4;
        return $commands_create_fstab_mount_point;
    }
    
    function GetPartitionIdsAndTypes() {
        $output_fdisk = $this->UTILITIES->RASPBERRY->GetFdiskOutput();
        $devices_mmc = $this->UTILITIES->RASPBERRY->GetMmcDevices();
        foreach($devices_mmc as $device_mmc) {
            $partitions_of_mmc_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfMmcDevice($device_mmc["device"]);
            foreach($partitions_of_mmc_device as $partition_of_mmc_device) {
                $partitions_mmc[] = $this->UTILITIES->RASPBERRY->GetPartitionIdAndType($partition_of_mmc_device["partition"], $output_fdisk);
            }
        }
        $devices_sd = $this->UTILITIES->RASPBERRY->GetSdDevices();
        foreach($devices_sd as $device_sd) {
            $partitions_of_sd_device = $this->UTILITIES->RASPBERRY->GetPartitionsOfSdDevice($device_sd["device"]);
            foreach($partitions_of_sd_device as $partition_of_sd_device) {
                $partitions_sd[] = $this->UTILITIES->RASPBERRY->GetPartitionIdAndType($partition_of_sd_device["partition"], $output_fdisk);
            }
        }
        if(count($partitions_sd) > 0) {
            $partitions_id_type = array_merge($partitions_mmc, $partitions_sd);
        } else {
            $partitions_id_type = $partitions_mmc;
        }
        return $partitions_id_type;
    }
    
    function GetPartitionIdAndTypeByPartition($partition, $partitions_id_type) {
        foreach($partitions_id_type as $partition_id_type) {
            if($partition_id_type["partition"] === $partition) {
                return $partition_id_type;
            }
        }
    }
    
    function LoadDisplayLocalResourcesTable() {
        $string = "<script>"
                    . "ReloadElement("
                        . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_LoadDisplayLocalResourcesTable.php").", " // AjaxFileExecut
                        . json_encode($_SESSION["key"]).","
                        . json_encode("DisplayLocalResourcesParentNode").","
                        . json_encode("DisplayLocalResourcesChildNode").");"
            . "</script>";
        return $string;
    }
    
    function LoadConfigureLocalResourcesTable() {
        $string = "<script>"
                    . "ReloadElement("
                        . json_encode($this->UTILITIES->toroot."/ajax/LOCAL_RESOURCES_LoadConfigureLocalResourcesTable.php").", " // AjaxFileExecut
                        . json_encode($_SESSION["key"]).","
                        . json_encode("ConfigureLocalResourcesTableParentNode").","
                        . json_encode("ConfigureLocalResourcesTableChildNode").");"
            . "</script>";
        return $string;
    }
    
}
