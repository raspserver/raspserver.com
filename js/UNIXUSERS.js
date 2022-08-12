/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function GetUnixUsers() {
    let unixusers = [];
    const idelements = document.querySelectorAll('*[id]')
    for (let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if (id.split(":")[0] === "UnixUser") {
            let name = id.split(":")[3];
            let uid = id.split(":")[1];
            let gid = id.split(":")[2];
            let UNIXUSER = {name: name, uid: uid, gid: gid};
            unixusers.push(UNIXUSER);
        }
    }
    return unixusers;
}

function GetMaxUnixUsers() {
    let name = document.getElementById('ConfigureUnixUsersTableName').innerHTML;
    let pos1 = name.lastIndexOf("/");
    let pos2 = name.lastIndexOf(")");
    let maxusers = name.substring(pos1 + 1, pos2);
    return maxusers;
}

function DoesThisUnixUserExist(user) {
    result = false;
    const unixusers = GetUnixUsers();
    for (let UNIXUSER of unixusers) {
        if (UNIXUSER.name === user) {
            return true;
        }
    }
    return result;
}

function AddUnixUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idbuttonadduser, idpanel, identry1, identry2, identry3, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, new_uid, new_gid, user_exists, invalid_username, invalid_password, no_match, IdTableParentNode, IdTableChildNode) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idbuttonadduser).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idbuttonadduser).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(identry1).value = null;
        document.getElementById(identry2).value = null;
        document.getElementById(idconsole).innerHTML = GetConsolePrompt(user, host);
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        AddUnixUserNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, identry1, identry2, identry3, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, new_uid, new_gid, user_exists, invalid_username, invalid_password, no_match, IdTableParentNode, IdTableChildNode);
    };
}

function AddUnixUserNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, identry1, identry2, identry3, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, new_uid, new_gid, user_exists, invalid_username, invalid_password, no_match, IdTableParentNode, IdTableChildNode) {
    const new_username = document.getElementById(identry1).value;
    const new_password = document.getElementById(identry2).value;
    const new_password_confirmation = document.getElementById(identry3).value;
    let errors = '';
    if (DoesThisUnixUserExist(new_username)) {
        errors += user_exists + '<br>';
    }
    if (!IsRaspberryUserNameValid(new_username)) {
        errors += invalid_username + '<br>';
    }
    if (!IsRaspberryUserPasswordValid(new_password)) {
        errors += invalid_password + '<br>';
    }
    if (new_password !== new_password_confirmation) {
        errors += no_match + '<br>';
    }
    if (errors !== '') {
        document.getElementById(idconsole).innerHTML = errors;
        window.location.href = '#' + idconsolebutton;
    } else {
        const commands = [
            {command:'useradd -u ' + new_uid + ' -d /home/' + new_username + ' -m ' + new_username,
              masked:'useradd -u ' + new_uid + ' -d /home/' + new_username + ' -m ' + new_username},
            {command:'groupmod -g ' + new_gid + ' ' + new_username,
              masked:'groupmod -g ' + new_gid + ' ' + new_username},
            {command:"echo -e \\\'" + new_password + "\\\'\\\\\n\\\'" + new_password + "\\\' | passwd " + new_username,
              masked:"echo -e '***'\\\\n'***' | passwd " + new_username},
            {command:"echo -e \\\'" + new_password + "\\\'\\\\\n\\\'" + new_password + "\\\' | smbpasswd -a " + new_username,
              masked:"echo -e '***'\\\\n'***' | smbpasswd -a " + new_username},
            
            {command:"sed -i -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\[" + new_username + "\\\]\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ path = /home/" + new_username + "\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ valid users = " + new_username + "\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ writable = yes\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ browsable = yes\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\\\ \\\\ \\\\ guest ok = no\\\' -e \\\'/\\\\[printers\\\\]/\\\'i\\\'\\r\\\'  /etc/samba/smb.conf",
              masked:"sed -i -e '/\\[printers\\]/'i'\[" + new_username + "\]' -e '/\\[printers\\]/'i'\\ \\ \\ path = /home/" + new_username + "' -e '/\\[printers\\]/'i'\\ \\ \\ valid users = " + new_username + "' -e '/\\[printers\\]/'i'\\ \\ \\ writable = yes' -e '/\\[printers\\]/'i'\\ \\ \\ browsable = yes' -e '/\\[printers\\]/'i'\\ \\ \\ guest ok = no' -e '/\\[printers\\]/'i'\\r'  /etc/samba/smb.conf"},
            {command:"service smbd restart",
              masked:"service smbd restart"}
        ];
        ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
    }
}

function ChangeUidGid(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idbuttonadduser, idpanel, identry1, identry2, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, userid, username, useruid, usergid, select, IdTableParentNode, IdTableChildNode) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    if(GetUnixUsers().length < GetMaxUnixUsers()) {
        document.getElementById(idbuttonadduser).style.display = 'none';
    }
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    SetClassAttribute(idconfirmbutton, 'w3-opacity');
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(userid).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        if(GetUnixUsers().length < GetMaxUnixUsers()) {
            document.getElementById(idbuttonadduser).style.display = null;
        }
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(userid).style.fontWeight = null;
        document.getElementById(identry1).value = select;
        document.getElementById(identry2).value = select;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        ChangeUidGidNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, identry1, identry2, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, username, useruid, usergid, select, IdTableParentNode, IdTableChildNode);
    };
}

function ChangeUidGidNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, identry1, identry2, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, username, useruid, usergid, select, IdTableParentNode, IdTableChildNode) {
    const uid_new = document.getElementById(identry1).value;
    const gid_new = document.getElementById(identry2).value;
    let commands = [];
    if (uid_new !== select && gid_new !== select) {
        commands = [
                {command:'usermod -u ' + uid_new + ' ' + username,
                  masked:'usermod -u ' + uid_new + ' ' + username},
                {command:'groupmod -g ' + gid_new + ' ' + username,
                  masked:'groupmod -g ' + gid_new + ' ' + username},
          {command:"service smbd restart",
              masked:"service smbd restart"}];
    } else if (uid_new !== select) {
        commands = [
                {command:'usermod -u ' + uid_new + ' ' + username,
                  masked:'usermod -u ' + uid_new + ' ' + username},
          {command:"service smbd restart",
              masked:"service smbd restart"}];
    } else {
        commands = [
                {command:'groupmod -g ' + gid_new + ' ' + username,
                  masked:'groupmod -g ' + gid_new + ' ' + username},
          {command:"service smbd restart",
              masked:"service smbd restart"}];
    }
    ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
}

function DeleteUser(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idbuttonadduser, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, userid, username, IdTableParentNode, IdTableChildNode) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    if(GetUnixUsers().length < GetMaxUnixUsers()) {
        document.getElementById(idbuttonadduser).style.display = 'none';
    }
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(userid).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        if(GetUnixUsers().length < GetMaxUnixUsers()) {
            document.getElementById(idbuttonadduser).style.display = null;
        }
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(userid).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        DeleteUserNow(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, username, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode);
    };
}

function DeleteUserNow(Ajax1, AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, username, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, IdTableParentNode, IdTableChildNode) {
    let commands = [];
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
//            window.alert(this.responseText);
            const array = this.responseText.split("°");
            let commands = [];
            for(let element of array) {
                commands.push({command:element.split(":")[0], masked:element.split(":")[1]});
            }
            ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
        }
    };
    xhttp.open('POST', Ajax1);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('user=' + username + '&authorization_key=' + authorization_key);
    DisableButtons();
    DisplaySpinnerWithText('', idconsolebutton);
    window.location.href = '#' + idconsole;
}

function ChangePassword(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idbuttondashboard, idbuttonadduser, idpanel, identry1, identry2, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, userid, username, no_match, invalid_password, IdTableParentNode, IdTableChildNode) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    if(GetUnixUsers().length < GetMaxUnixUsers()) {
        document.getElementById(idbuttonadduser).style.display = 'none';
    }
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById(userid).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function () {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        if(GetUnixUsers().length < GetMaxUnixUsers()) {
            document.getElementById(idbuttonadduser).style.display = null;
        }
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(userid).style.fontWeight = null;
        document.getElementById(identry1).value = null;
        document.getElementById(identry2).value = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        ChangePasswordNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, identry1, identry2, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, username, no_match, invalid_password, IdTableParentNode, IdTableChildNode)
    };
}

function ChangePasswordNow(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, identry1, identry2, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, username, no_match, invalid_password, IdTableParentNode, IdTableChildNode) {
    const new_password = document.getElementById(identry1).value;
    const new_password_confirmation = document.getElementById(identry2).value;
    let errors = '';
    if (new_password !== new_password_confirmation) {
        errors = no_match + '<br>';
    }
    if (!IsRaspberryUserPasswordValid(new_password)) {
        errors += invalid_password + '<br>';
    }
    if (errors !== '') {
        document.getElementById(idconsole).innerHTML = errors;
        window.location.href = '#' + idconsolebutton;
    } else {
        const commands = [
            {command:"echo -e \\\'" + new_password + "\\\'\\\\\n\\\'" + new_password + "\\\' | passwd " + username,
              masked:"echo -e '***'\\\\n'***' | passwd " + username},
            {command:"echo -e \\\'" + new_password + "\\\'\\\\\n\\\'" + new_password + "\\\' | smbpasswd -a " + username,
              masked:"echo -e '***'\\\\n'***' | smbpasswd -a " + username},
      {command:"service smbd restart",
              masked:"service smbd restart"}];
        ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
    }
}

function IsRaspberryUserNameValid(username) {
    const regex = /^[a-z_]([a-z0-9_-]{0,31}|[a-z0-9_-]{0,30}\\$)$/;
    if (username.match(regex)) {
        return true;
    } else {
        return false;
    }
}

function IsRaspberryUserPasswordValid(password) {
    const regex = /^[a-z_]([a-z0-9_-]{0,31}|[a-z0-9_-]{0,30}\\$)$/;
    if (password.match(regex)) {
        return true;
    } else {
        return false;
    }
}
