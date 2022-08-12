/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function AddSambaShare(Ajax0, Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idbuttonaddsambashare, idpanel, identry1, identry2, idbuttonwritableno, idbuttonwritableyes, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, ipaddress, invalidsharename, invalidpath) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idbuttonaddsambashare).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idbuttonwritableno).style.background = null;
    document.getElementById(idbuttonwritableyes).style.background = "gray";
    document.getElementById(idconsolebutton).style.display = 'none';
    const validusersids = GetValidUsersIds('1');
    for(let validusersid of validusersids) {
        document.getElementById(validusersid).style.background = null;
    }
    SetClassAttribute(idconfirmbutton, 'w3-opacity');
    document.getElementById(idconfirmbutton).style.pointerEvents = 'none';
    document.getElementById(identry1).value = '//' + ipaddress + '/';
    document.getElementById(identry2).value = '/media/';
    window.location.href = '#' + idconsole;
    document.getElementById(idbuttonwritableno).onclick = function () {
        SetWritableNo(idbuttonwritableno, idbuttonwritableyes);
    };
    document.getElementById(idbuttonwritableyes).onclick = function () {
        SetWritableYes(idbuttonwritableno, idbuttonwritableyes);
    };
    for(let validusersid of validusersids) {
        document.getElementById(validusersid).onclick = function () {
            const selected_valid_users = GetSelectedValidUsers(validusersids);
            ToggleValidUsersButton(validusersid, validusersids, idconfirmbutton, selected_valid_users);
        };
    }
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idbuttonaddsambashare).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(idbuttonwritableyes).style.background = "gray";
        document.getElementById(idconsole).innerHTML = GetConsolePrompt(user, host);
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        AddSambaShareNow(Ajax0, Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttonaddsambashare, idpanel, identry1, identry2, idbuttonwritableno, idbuttonwritableyes, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, ipaddress, validusersids, invalidsharename, invalidpath);
    };
}

function AddSambaShareNow(Ajax0, Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttonaddsambashare, idpanel, identry1, identry2, idbuttonwritableno, idbuttonwritableyes, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, ipaddress, validusersids, invalidsharename, invalidpath) {
    const new_share = document.getElementById(identry1).value;
    const new_path = document.getElementById(identry2).value;
    let writable;
    if(document.getElementById(idbuttonwritableno).style.background.includes("gray")) {
        writable = "no";
    } else {
        writable = "yes";
    }
    const selected_valid_users = GetSelectedValidUsers(validusersids);
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let errors = '';
            if(this.responseText !== '1' ) {
                errors += invalidpath + '<br>';
            }
            sambashares = GetSambaShares();
            if(!(new_share.substring(0, ipaddress.length + 3) === '//' + ipaddress + '/'
                    && IsSambaShareNameValid(new_share.substring(ipaddress.length + 3))
                    && new_share.substring(ipaddress.length + 3) !== ''
                    && !sambashares.includes(new_share.substring(ipaddress.length + 3)))) {
                errors += invalidsharename + '<br>';
            }
            if (errors !== '') {
                document.getElementById(idconsole).innerHTML = errors;
                window.location.href = '#' + idconsolebutton;
            } else {
                let writable;
                if(document.getElementById(idbuttonwritableyes).style.background.includes("gray")) {
                    writable = 'yes';
                } else {
                    writable = 'no';
                }
                const selected_valid_users = GetSelectedValidUsers(validusersids);
                const selected_valid_users_string = ConvertArrayToString(selected_valid_users);
//                window.alert('share ' + new_share.substring(ipaddress.length + 3) + ', path ' + new_path + ', writable ' + writable + ' string ' + selected_valid_users_string);
                
                AddShare(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, new_share.substring(ipaddress.length + 3), new_path, writable, selected_valid_users_string);
//                ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
            }
        }
    };
    xhttp.open('POST', Ajax0);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('new_path=' + new_path + '&authorization_key=' + authorization_key);
}



function RemoveSambaShare(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idbuttonaddsambashare, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, idshare, share) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idbuttonaddsambashare).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(idshare).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idbuttonaddsambashare).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(idshare).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        DeleteShare(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, share);
    };
}

function ConfigureSambaShare(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idbuttonaddsambashare, idpanel, idbuttonwritableno, idbuttonwritableyes, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, idshare, share, idwritable, idvalidusers, idpath) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idbuttonaddsambashare).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    SetClassAttribute(idconfirmbutton, 'w3-opacity');
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(idshare).style.fontWeight = 'bold';
    if(document.getElementById(idwritable).innerHTML === 'no') {
        document.getElementById(idbuttonwritableno).style.background = "gray";
        document.getElementById(idbuttonwritableyes).style.background = null;
    } else {
        document.getElementById(idbuttonwritableno).style.background = null;
        document.getElementById(idbuttonwritableyes).style.background = "gray";
    }
    window.location.href = '#' + idconsole;
    const selected_valid_users_conf = document.getElementById(idvalidusers).innerHTML.split(',');
    const validusersids = GetValidUsersIds('2');
    for(let validusersid of validusersids) {
        document.getElementById(validusersid).style.background = null;
    }
    for(let validusersid of validusersids) {
        if(selected_valid_users_conf.includes(validusersid.split(':')[1])) {
            document.getElementById(validusersid).style.background = "gray";
        }
    }
    document.getElementById(idbuttonwritableno).onclick = function () {
        SetWritableNo(idbuttonwritableno, idbuttonwritableyes);
        DisableEnableSubmitButton(selected_valid_users_conf, idconfirmbutton, idbuttonwritableno, idbuttonwritableyes, idwritable, validusersids);
    };
    document.getElementById(idbuttonwritableyes).onclick = function () {
        SetWritableYes(idbuttonwritableno, idbuttonwritableyes);
        DisableEnableSubmitButton(selected_valid_users_conf, idconfirmbutton, idbuttonwritableno, idbuttonwritableyes, idwritable, validusersids);
    };
    for(let validusersid of validusersids) {
        document.getElementById(validusersid).onclick = function () {
            ToggleValidUsersButton(validusersid, validusersids, idconfirmbutton);
            DisableEnableSubmitButton(selected_valid_users_conf, idconfirmbutton, idbuttonwritableno, idbuttonwritableyes, idwritable, validusersids);
        };
    }
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idbuttonaddsambashare).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(idshare).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        const path = document.getElementById(idpath).innerHTML;
        let writable;
        if(document.getElementById(idbuttonwritableno).style.background.includes("gray")) {
            writable = "no";
        } else {
            writable = "yes";
        }
        const selected_valid_users = GetSelectedValidUsers(validusersids);
        const selected_valid_users_string = ConvertArrayToString(selected_valid_users);
        DeleteShareAndCreateNew(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, share, path, writable, selected_valid_users_string)
//        window.alert(share + 'writable ' + writable + ' path ' + path + ' valid users ' + selected_valid_users_string);
    };
}

function AddShare(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, share, path, writable, selected_valid_users_string) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
//            window.alert(this.responseText);
            const commands = [{command:this.responseText.split('°')[0].split(':')[0],
                                masked:this.responseText.split('°')[0].split(':')[1]},
                              {command:this.responseText.split('°')[1].split(':')[0],
                                masked:this.responseText.split('°')[1].split(':')[1]}];
//            window.alert(this.responseText.split('°')[0].split(':')[0]);
            ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
        }
    };
    xhttp.open('POST', Ajax1);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('share=' + share + '&path=' + path + '&writable=' + writable + '&selected_valid_users_string=' + selected_valid_users_string + '&authorization_key=' + authorization_key);
}

function DeleteShare(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, share) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const commands = [{command:this.responseText.split('°')[0].split(':')[0],
                                masked:this.responseText.split('°')[0].split(':')[1]},
                              {command:this.responseText.split('°')[1].split(':')[0],
                                masked:this.responseText.split('°')[1].split(':')[1]}];
            ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
        }
    };
    xhttp.open('POST', Ajax1);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('share=' + share + '&authorization_key=' + authorization_key);
}

function DeleteShareAndCreateNew(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode, share, path, writable, selected_valid_users_string) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const commands = [{command:this.responseText.split('°')[0].split(':')[0],
                                masked:this.responseText.split('°')[0].split(':')[1]},
                              {command:this.responseText.split('°')[1].split(':')[0],
                                masked:this.responseText.split('°')[1].split(':')[1]},
                              {command:this.responseText.split('°')[2].split(':')[0],
                                masked:this.responseText.split('°')[2].split(':')[1]}];
            ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
        }
    };
    xhttp.open('POST', Ajax1);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('share=' + share + '&path=' + path + '&writable=' + writable + '&selected_valid_users_string=' + selected_valid_users_string + '&authorization_key=' + authorization_key);
}

function ConvertArrayToString(array) {
    let string = '';
    for(let element of array) {
        string += ',' + element;
    }
    return string.substring(1);
}

function IsSambaShareNameValid(string) {
    const regex = /^[a-zA-Z0-9_]+$/;
    if (string.match(regex)) {
        return true;
    } else {
        return false;
    }
}

function GetSambaShares() {
    let sambashares = [];
    const idelements = document.querySelectorAll('*[id]')
    for (let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if (id.split(":")[0] === 'idshare') {
            sambashares.push(id.split(":")[1]);
        }
    }
    return sambashares;
}

function GetSelectedValidUsers(validusersids) {
    let selected_valid_users = [];
    for(let validusersid of validusersids) {
//        window.alert(validusersid);
        if(document.getElementById(validusersid).style.background.includes("gray")){
            selected_valid_users.push(validusersid.split(':')[1]);
        }
    }
    return selected_valid_users;
}

function ToggleValidUsersButton(validusersid, validusersids, idconfirmbutton) {
    if(document.getElementById(validusersid).style.background.includes("gray")) {
        document.getElementById(validusersid).style.background = null;
    } else {
        document.getElementById(validusersid).style.background = "gray";
    }
   const selected_valid_users = GetSelectedValidUsers(validusersids);
    if(selected_valid_users.length === 0) {
        SetClassAttribute(idconfirmbutton, 'w3-opacity');
        document.getElementById(idconfirmbutton).style.pointerEvents = 'none';
    } else {
        UnsetClassAttribute(idconfirmbutton, 'w3-opacity');
        document.getElementById(idconfirmbutton).style.pointerEvents = null;;
    }
}

function GetValidUsersIds(prefix) {
    validusersids = [];
    const idelements = document.querySelectorAll('*[id]')
    for (let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if (id.split(":")[0] === "ValidUser" + prefix) {
            validusersids.push(id);
        }
    }
    return validusersids;
}

function SetWritableNo(idbuttonwritableno, idbuttonwritableyes) {
    document.getElementById(idbuttonwritableyes).style.background = null;
    document.getElementById(idbuttonwritableno).style.background = "gray";
}

function SetWritableYes(idbuttonwritableno, idbuttonwritableyes) {
    document.getElementById(idbuttonwritableyes).style.background = "gray";
    document.getElementById(idbuttonwritableno).style.background = null;
}

function DisableEnableSubmitButton(selected_valid_users_conf, idconfirmbutton, idbuttonwritableno, idbuttonwritableyes, idwritable, validusersids) {
    const selected_valid_users = GetSelectedValidUsers(validusersids);
    selected_valid_users.sort();
    selected_valid_users_conf.sort();
    let valid_users_unchanged;
    if(JSON.stringify(selected_valid_users) === JSON.stringify(selected_valid_users_conf)) {
        valid_users_unchanged = true;
//        window.alert('true');
    } else {
        valid_users_unchanged = false; 
//        window.alert('false');
    }
    let writable_unchanged;
    if((document.getElementById(idbuttonwritableyes).style.background.includes("gray")
            && document.getElementById(idwritable).innerHTML === 'yes') 
            || (document.getElementById(idbuttonwritableno).style.background.includes("gray")
            && document.getElementById(idwritable).innerHTML === 'no')) {
        writable_unchanged = true;
//        window.alert('true');
    } else {
        writable_unchanged = false;
//        window.alert('false');
    }
    if((valid_users_unchanged && writable_unchanged) || selected_valid_users.length === 0) {
        document.getElementById(idconfirmbutton).style.pointerEvents = 'none';
        SetClassAttribute(idconfirmbutton, 'w3-opacity');
    } else {
        document.getElementById(idconfirmbutton).style.pointerEvents = null;
        UnsetClassAttribute(idconfirmbutton, 'w3-opacity');
    }
}

