/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */


function DeleteFstabMountPoint(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, idpartition, partition, idmountpointfstab, mountpoint) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(idmountpointfstab).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    DeleteFstabMountPointNow(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, mountpoint);
}

function DeleteFstabMountPointNow(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, mountpoint) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let commands = [];
            const part0 = this.responseText.split('°')[0];
            const part1 = this.responseText.split('°')[1];
            const string1 = part0.split(":")[0];
            const string2 = part0.split(":")[1];
            const string3 = part1.split(":")[0];
            const string4 = part1.split(":")[1];
            if(part1 === ':') {
                commands = [ {command:string1,
                               masked:string2}];
            } else {
                commands = [{command:string1,
                              masked:string2},
                            {command:string3,
                              masked:string4}];
            }
                ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
        }
    };
    xhttp.open('POST', Ajax1);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('mountpoint=' + mountpoint + '&authorization_key=' + authorization_key);
}

function CreateFstabMountPoint(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, idfilesystem_fstype, idfilesystem_fsver, idfilesystem_label, idfilesystem_uuid, uuid, type) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(idfilesystem_fstype).style.fontWeight = 'bold';
    document.getElementById(idfilesystem_fsver).style.fontWeight = 'bold';
    document.getElementById(idfilesystem_label).style.fontWeight = 'bold';
    document.getElementById(idfilesystem_uuid).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    CreateFstabMountPointNow(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, uuid, type);
}

function CreateFstabMountPointNow(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, uuid, type) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let commands = [];
            const part0 = this.responseText.split('°')[0];
            const part1 = this.responseText.split('°')[1];
            if(part0 === ':') {
                const string3 = part1.split(":")[0];
                const string4 = part1.split(":")[1];
                commands = [{command:string3,
                              masked:string4}];
            } else {
                const string1 = part0.split(":")[0];
                const string2 = part0.split(":")[1];
                const string3 = part1.split(":")[0];
                const string4 = part1.split(":")[1];
                commands = [{command:string1,
                              masked:string2},
                            {command:string3,
                              masked:string4}];
            }
            ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
        }
    };
    xhttp.open('POST', Ajax1);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('uuid=' + uuid + '&type=' + type + '&authorization_key=' + authorization_key);
}

function DeletePartition(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, device, idpartition, partition, idpartition_size, idpartition_id, idpartition_type) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(idpartition).style.fontWeight = 'bold';
    document.getElementById(idpartition_size).style.fontWeight = 'bold';
    document.getElementById(idpartition_id).style.fontWeight = 'bold';
    document.getElementById(idpartition_type).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(idpartition).style.fontWeight = null;
        document.getElementById(idpartition_size).style.fontWeight = null;
        document.getElementById(idpartition_id).style.fontWeight = null;
        document.getElementById(idpartition_type).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        DeletePartitionNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, device, partition);
    };
}

function DeletePartitionNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, device, partition) {
    const commands = [{command:"echo -e \\\'d /dev/" + partition + "\\\'\\\\\n\\\'w\\\' | fdisk /dev/" + device,
                        masked:"echo -e 'd /dev/" + partition + "'\\\\n'w' | fdisk /dev/" + device}];
    ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);    
}

function CreatePartition(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, iddevice, device, iddevice_size) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(iddevice).style.fontWeight = 'bold';
    document.getElementById(iddevice_size).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(iddevice).style.fontWeight = null;
        document.getElementById(iddevice_size).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        CreatePartitionNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, device);
    };
}

function CreatePartitionNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, device) {
    const commands = [{command:"echo -e \\\'n\\\'\\\\\n\\\'p\\\'\\\\\n\\\'\\\'\\\\\n\\\'\\\'\\\\\n\\\'\\\'\\\\\n\\\'w\\\' | fdisk /dev/" + device,
                        masked:"echo -e 'n'\\\\n'p'\\\\n''\\\\n''\\\\n''\\\\n'w' | fdisk /dev/" + device}];       
    ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}

function MakeFileSystem(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, device, idpartition, partition, idpartition_size, idpartition_id, idpartition_type, identry) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(identry).value = 'ext4';
    document.getElementById(idpartition).style.fontWeight = 'bold';
    document.getElementById(idpartition_size).style.fontWeight = 'bold';
    document.getElementById(idpartition_id).style.fontWeight = 'bold';
    document.getElementById(idpartition_type).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(idpartition).style.fontWeight = null;
        document.getElementById(idpartition_size).style.fontWeight = null;
        document.getElementById(idpartition_id).style.fontWeight = null;
        document.getElementById(idpartition_type).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        MakeFileSystemNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, device, partition, identry);
    };
}

function MakeFileSystemNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, device, partition, identry) {
    let fstype = document.getElementById(identry).value;
    let id;
    
    switch(fstype) {
        case 'fat32':
            fstype = 'vfat';
            id = '7';
            break;
        case 'ntfs':
            fstype = 'ntfs -f';
            id = '7';
            break;
        case 'ext4':
            id = '83';
            break;
    }
    
    
    if(fstype === 'fat32') {
        fstype = 'vfat';
    } else if(fstype === 'ntfs') {
        fstype = 'ntfs -f';
    }
    const commands = [
                      {command:"echo -e \\\'t\\\'\\\\\n\\\'" + id + "\\\'\\\\\n\\\'w\\\' | fdisk /dev/" + device,
                        masked:"echo -e 't'\\\\n'" + id + "'\\\\n'w' | fdisk /dev/" + device},
                      {command:"mkfs -t " + fstype + " /dev/" + partition,
                        masked:"mkfs -t " + fstype + " /dev/" + partition}];
    ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}

function WipeFileSystem(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, partition, idfilesystem_fstype, idfilesystem_fsver, idfilesystem_label, idfilesystem_uuid) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(idfilesystem_fstype).style.fontWeight = 'bold';
    document.getElementById(idfilesystem_fsver).style.fontWeight = 'bold';
    document.getElementById(idfilesystem_label).style.fontWeight = 'bold';
    document.getElementById(idfilesystem_uuid).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(idfilesystem_fstype).style.fontWeight = null;
        document.getElementById(idfilesystem_fsver).style.fontWeight = null;
        document.getElementById(idfilesystem_label).style.fontWeight = null;
        document.getElementById(idfilesystem_uuid).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        WipeFileSystemNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, partition);
    };
}

function WipeFileSystemNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, partition) {
    const commands = [{command:'wipefs -a --force /dev/' + partition,
                        masked:'wipefs -a --force /dev/' + partition}];
    ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}

function LoadPartitionsIdsAndTypes(AjaxFile, authorization_key) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
//            window.alert(this.responseText);
            const array = this.responseText.split("°");
            for(let element of array) {
                partition = element.split(':')[0];
                id = element.split(':')[1];
                type = element.split(':')[2];
                document.getElementById('idpartition_id:' + partition).innerHTML = id;
                document.getElementById('idpartition_type:' + partition).innerHTML = type;
            }
        }
    };
//    window.alert('fff');
    xhttp.open('POST', AjaxFile);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('authorization_key=' + authorization_key);
}


