/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function RegenerateHostKeys(Ajax1, Ajax2, authorization_key, user, host, idbutton_ssh_keys_summary, idbutton_regenerate_hostkeys, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode) {
    DisableButtons();
    document.getElementById(idbutton_ssh_keys_summary).style.display = 'none';
    document.getElementById(idbutton_regenerate_hostkeys).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function() {
        EnableButtons();
        document.getElementById(idbutton_ssh_keys_summary).style.display = null;
        document.getElementById(idbutton_regenerate_hostkeys).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        RegenerateHostKeysNow(Ajax1, Ajax2, authorization_key, user, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode);
    };
}

function RegenerateHostKeysNow(Ajax1, Ajax2, authorization_key, user, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode) {
    const commands = [
                    {command:"rm /etc/ssh/ssh_host_*",
                      masked:"rm /etc/ssh/ssh_host_*"},
                    {command:"ssh-keygen -A",
                      masked:"ssh-keygen -A"},
                    {command:"service ssh restart",
                      masked:"service ssh restart"}
                ];
    ExecuteANumberOfCommandsAsUser(Ajax1, Ajax2, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}

function DeleteAuthorizedKey(Ajax1, Ajax2, authorization_key, host, idbutton_ssh_keys_summary, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, username, line_number_in_authorized_keys) {
    DisableButtons();
    document.getElementById(idconsole).innerHTML = GetConsolePrompt(username, host);
    document.getElementById(idbutton_ssh_keys_summary).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById('IdButtonDeleteAuthorizedKey:' + username + ':' + line_number_in_authorized_keys).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function() {
        EnableButtons();
        document.getElementById(idbutton_ssh_keys_summary).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById('IdButtonDeleteAuthorizedKey:' + username + ':' + line_number_in_authorized_keys).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        DeleteAuthorizedKeyNow(Ajax1, Ajax2, authorization_key, username, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, username, line_number_in_authorized_keys);
    };
}

function DeleteAuthorizedKeyNow(Ajax1, Ajax2, authorization_key, user, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, username, line_number_in_authorized_keys) {
    let path;
    if(username === 'root') {
        path = "/root/.ssh/authorized_keys";
    } else {
        path = "/home/" + username + "/.ssh/authorized_keys";
    }
    const commands = [{command:"sed -i -e \\\'" + line_number_in_authorized_keys + "d\\\' " + path,
                        masked:"sed -i -e '" + line_number_in_authorized_keys + "d' " + path}];
//                     [{command:"cat " + path + " | sed " + line_number_in_authorized_keys + "d -i " + path,
//                        masked:"cat " + path + " | sed " + line_number_in_authorized_keys + "d -i " + path}];
    ExecuteANumberOfCommandsAsUser(Ajax1, Ajax2, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}

function DeleteKnownHost(Ajax1, Ajax2, authorization_key, host, idbutton_ssh_keys_summary, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, username, line_number_in_known_hosts) {
    DisableButtons();
    document.getElementById(idconsole).innerHTML = GetConsolePrompt(username, host);
    document.getElementById(idbutton_ssh_keys_summary).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById('IdButtonDeleteKnownHost:' + username + ':' + line_number_in_known_hosts).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function() {
        EnableButtons();
        document.getElementById(idbutton_ssh_keys_summary).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById('IdButtonDeleteKnownHost:' + username + ':' + line_number_in_known_hosts).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        DeleteKnownHostNow(Ajax1, Ajax2, authorization_key, username, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, username, line_number_in_known_hosts);
    };
}

function DeleteKnownHostNow(Ajax1, Ajax2, authorization_key, user, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, username, line_number_in_known_hosts) {
    let path;
    if(username === 'root') {
        path = "/root/.ssh/known_hosts";
    } else {
        path = "/home/" + username + "/.ssh/known_hosts";
    }
    const commands = [{command:"sed -i -e \\\'" + line_number_in_known_hosts + "d\\\' " + path,
                        masked:"sed -i -e '" + line_number_in_known_hosts + "d' " + path}];
    ExecuteANumberOfCommandsAsUser(Ajax1, Ajax2, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}

function RemoveUserKeys(Ajax1, Ajax2, authorization_key, host, idbutton_ssh_keys_summary, idbutton_generate_userkeys, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, username, path, number_unixuserswithoutkeys) {
    DisableButtons();
    document.getElementById(idconsole).innerHTML = GetConsolePrompt(username, host);
    if(number_unixuserswithoutkeys !== 0) {
        document.getElementById(idbutton_generate_userkeys).style.display = 'none'; 
    }
    document.getElementById(idbutton_ssh_keys_summary).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById('IdButtonRemoveUserKeys:' + username + ':' + path).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function() {
        EnableButtons();
        if(number_unixuserswithoutkeys !== 0) {
            document.getElementById(idbutton_generate_userkeys).style.display = null;
        }
        document.getElementById(idbutton_ssh_keys_summary).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById('IdButtonRemoveUserKeys:' + username + ':' + path).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        DeleteUserKeysNow(Ajax1, Ajax2, authorization_key, username, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, username, path);
    };
}

function DeleteUserKeysNow(Ajax1, Ajax2, authorization_key, user, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, username, path) {
//    window.alert(path);
    const commands = [{command:"rm " + path.substring(0, path.length - 4) + "*",
                        masked:"rm " + path.substring(0, path.length - 4) + "*"}];
    ExecuteANumberOfCommandsAsUser(Ajax1, Ajax2, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}

function GenerateUserKeys(Ajax1, Ajax2, authorization_key, user, host, idbutton_ssh_keys_summary, idbutton_generate_userkeys, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, identry1, identry2, select, root_has_a_ssh_folder) {
//function GenerateUserKeys() {
//window.alert('ddd');
    DisableButtons();
    document.getElementById(idbutton_generate_userkeys).style.display = 'none';
    document.getElementById(idbutton_ssh_keys_summary).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    SetClassAttribute(idconfirmbutton, 'w3-opacity');
    document.getElementById(identry1).value = select;
    document.getElementById(identry2).value = 'ed25519';
    document.getElementById(idconsole).innerHTML = GetConsolePrompt(user, host);
    document.getElementById(idconsolebutton).style.display = 'none';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function() {
        EnableButtons();
        document.getElementById(idbutton_generate_userkeys).style.display = null;
        document.getElementById(idbutton_ssh_keys_summary).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        GenerateUserKeysNow(Ajax1, Ajax2, authorization_key, user, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, identry1, identry2, root_has_a_ssh_folder);
    };
}

function GenerateUserKeysNow(Ajax1, Ajax2, authorization_key, user, host, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, identry1, identry2, root_has_a_ssh_folder) {
    const username = document.getElementById(identry1).value.split(" ")[1];
    const keytype = document.getElementById(identry2).value;
    let homedirectory;
    if(username === 'root') {
        homedirectory = '/root/';
    } else {
        homedirectory = '/home/' + username + '/';
    }
    let key_filename;
    switch(keytype) {
        case 'rsa -b 4096':
            key_filename = 'id_rsa';
            break;
        case 'dsa':
            key_filename = 'id_dsa';
            break;
        case 'ecdsa -b 521':
            key_filename = 'id_ecdsa';
            break;
        case 'ed25519':
            key_filename = 'id_ed25519';
            break;
    }
    let commands; // missing .ssh directory marked by white space behind username therefore split.length = 3
    if((username !== 'root' && document.getElementById(identry1).value.split(" ").length === 3)
            || (username === 'root' && !root_has_a_ssh_folder)
            ) { 
        commands = [{command:"mkdir " + homedirectory + '.ssh',
                      masked:"mkdir " + homedirectory + '.ssh'},
                    {command:"chmod 700 " + homedirectory + '.ssh',
                      masked:"chmod 700 " + homedirectory + '.ssh'},
                    {command:"ssh-keygen -t " + keytype + " -N \\\'\\\' -f " + homedirectory + '.ssh/' + key_filename,
                      masked:"ssh-keygen -t " + keytype + " -N '' -f " + homedirectory + '.ssh/' + key_filename}];
    } else {
        commands = [{command:"ssh-keygen -t " + keytype + " -N \\\'\\\' -f " + homedirectory + '.ssh/' + key_filename,
                      masked:"ssh-keygen -t " + keytype + " -N '' -f " + homedirectory + '.ssh/' + key_filename}];
    } 
    ExecuteANumberOfCommandsAsUser(Ajax1, Ajax2, authorization_key, username, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}

function OpenConfigureSshSettingPanel(Ajax1, Ajax2, authorization_key, username, host, idsetting ,idbuttondashbaord, idbutton_ssh_keys_summary, idpanel, idbuttonsetting1, idbuttonsetting2, idbuttonsetting3, idbuttonsetting4, valuesetting1, valuesetting2, valuesetting3, valuesetting4, value, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, configuration_file, setting, idnote) {
    DisableButtons();
    document.getElementById(idbuttondashbaord).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idsetting).style.fontWeight = 'bold';
    document.getElementById(idbutton_ssh_keys_summary).style.display = 'none';
    document.getElementById(idnote).innerHTML = setting;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(idbuttonsetting1).style.pointerEvents = null;
    document.getElementById(idbuttonsetting2).style.pointerEvents = null;
    document.getElementById(idbuttonsetting3).style.pointerEvents = null;
    window.location.href = '#' + idconsole;
//    document.getElementById(idbuttonsetting4).style.pointerEvents = null;
    SetClassAttribute(idbuttonsetting4, "w3-opacity");
    if(value === valuesetting1) {
        document.getElementById(idbuttonsetting1).style.background = "gray";
        document.getElementById(idbuttonsetting2).style.background = null;
    } else if(value === valuesetting2) {
        document.getElementById(idbuttonsetting1).style.background = null;
        document.getElementById(idbuttonsetting2).style.background = "gray";
    } else {
        document.getElementById(idbuttonsetting1).style.background = null;
        document.getElementById(idbuttonsetting2).style.background = null;
    }
    document.getElementById(idbuttonsetting1).onclick = function() {
        SetButtonBackgroundColor(idbuttonsetting1, idbuttonsetting1, idbuttonsetting2, idbuttonsetting4, value, valuesetting1, valuesetting2);
    };
    document.getElementById(idbuttonsetting2).onclick = function() {
        SetButtonBackgroundColor(idbuttonsetting2, idbuttonsetting1, idbuttonsetting2, idbuttonsetting4, value, valuesetting1, valuesetting2);
    };
    document.getElementById(idbuttonsetting3).onclick = function() {
        EnableButtons();
        document.getElementById(idbuttondashbaord).style.display = null;
        document.getElementById(idsetting).style.fontWeight = null;
        document.getElementById(idbutton_ssh_keys_summary).style.display = null;
        if(value === valuesetting1) {
            document.getElementById(idbuttonsetting1).style.background = "gray";
            document.getElementById(idbuttonsetting2).style.background = null;
        } else if(value === valuesetting2) {
            document.getElementById(idbuttonsetting1).style.background = null;
            document.getElementById(idbuttonsetting2).style.background = "gray";
        } else {
            document.getElementById(idbuttonsetting1).style.background = null;
            document.getElementById(idbuttonsetting2).style.background = null;
        }
        document.getElementById(idbuttonsetting4).style.pointerEvents = 'none';
        SetClassAttribute(idbuttonsetting4, "w3-opacity");
        document.getElementById(idpanel).style.display = 'none';
        window.location.href = '#' + '';
    };
    document.getElementById(idbuttonsetting4).onclick = function() {
        if(document.getElementById(idbuttonsetting1).style.background.includes("gray") && value !== valuesetting1) {
            SetSshSettingNow(Ajax1, Ajax2, authorization_key, username, host, idbuttonsetting1, idbuttonsetting2, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, configuration_file, setting, valuesetting1, value);
        } else if(document.getElementById(idbuttonsetting2).style.background.includes("gray") && value !== valuesetting2) {
            SetSshSettingNow(Ajax1, Ajax2, authorization_key, username, host, idbuttonsetting1, idbuttonsetting2, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, configuration_file, setting, valuesetting2, value);
        }
    };
}

function SetButtonBackgroundColor(idbuttonsetting, idbuttonsetting1, idbuttonsetting2, idbuttonsetting4, value, valuesetting1, valuesetting2) {
    if(idbuttonsetting === idbuttonsetting1) {
        document.getElementById(idbuttonsetting1).style.background = 'gray';
        document.getElementById(idbuttonsetting2).style.background = null;
        if(document.getElementById(idbuttonsetting1).style.background.includes("gray") && value !== valuesetting1) {
            document.getElementById(idbuttonsetting4).style.pointerEvents = null;
            UnsetClassAttribute(idbuttonsetting4, "w3-opacity");
        } else if(document.getElementById(idbuttonsetting1).style.background.includes("gray") && value === valuesetting1) {
            document.getElementById(idbuttonsetting4).style.pointerEvents = 'none';
            SetClassAttribute(idbuttonsetting4, "w3-opacity");
        }
    } else if (idbuttonsetting === idbuttonsetting2) {
        document.getElementById(idbuttonsetting1).style.background = null;
        document.getElementById(idbuttonsetting2).style.background = 'gray';
        if(document.getElementById(idbuttonsetting2).style.background.includes("gray") && value !== valuesetting2) {
            document.getElementById(idbuttonsetting4).style.pointerEvents = null;
            UnsetClassAttribute(idbuttonsetting4, "w3-opacity");
        } else if(document.getElementById(idbuttonsetting2).style.background.includes("gray") && value === valuesetting2) {
            document.getElementById(idbuttonsetting4).style.pointerEvents = 'none';
            SetClassAttribute(idbuttonsetting4, "w3-opacity");
        }
    }
}

function SetSshSettingNow(Ajax1, Ajax2, authorization_key, username, host, idbuttonsetting1, idbuttonsetting2, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, configuration_file, setting, valuesetting, value) {
    let commands;
    commands = [{command:"sed -i -e \\\'s/^ *" + setting + "  *" + value + "/" + setting + " " + valuesetting + "/\\\' " + configuration_file,
                  masked:"sed -i -e 's/^ *" + setting + "  *" + value + "/" + setting + " " + valuesetting + "/' " + configuration_file},
                {command:"service ssh restart",
                  masked:"service ssh restart"}];
    
    ExecuteANumberOfCommandsAsUser(Ajax1, Ajax2, authorization_key, username, host, idbuttonsetting1, idbuttonsetting2, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}