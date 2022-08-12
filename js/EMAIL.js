/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function GetEmailAddresses() {
    let email_addresses = [];
    const idelements = document.querySelectorAll('*[id]')
    for (let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if (id.split(":")[0] === "IdColumn0") {
            let debian_user = id.split(":")[1];
            let pop3_server = id.split(":")[2];
            let pop3_user = id.split(":")[3];
            let pop3_server_line_number = id.split(":")[4];
            let pop3_user_line_number = id.split(":")[5];
            let EMAIL_ADDRESS = {debian_user: debian_user, pop3_server: pop3_server, pop3_user: pop3_user, pop3_server_line_number:pop3_server_line_number, pop3_user_line_number:pop3_user_line_number};
            email_addresses.push(EMAIL_ADDRESS);
        }
    }
    return email_addresses;
}

function POP3ServersNumberOfEmails(pop3_server) {
    email_addresses = GetEmailAddresses();
    let number = 0;
    for(let EMAIL_ADDRESS of email_addresses) {
        if(EMAIL_ADDRESS.pop3_server === pop3_server) {
            number++;
        }
    }
    return number;
}

function GetPOP3ServerLineNumber(pop3_server) {
    let pop3_server_line_number;
    email_addresses = GetEmailAddresses();
    for(let EMAIL_ADDRESS of email_addresses) {
        if(EMAIL_ADDRESS.pop3_server === pop3_server) {
            pop3_server_line_number = EMAIL_ADDRESS.pop3_server_line_number;
        }
    }
    return pop3_server_line_number;
}

function DoesThisEmailEntryExist(NEW_EMAIL_ADDRESS) {
    let result = false;
    const email_addresses = GetEmailAddresses();
    for(let EMAIL_ADDRESS of email_addresses) {
        if(
            EMAIL_ADDRESS.debian_user === NEW_EMAIL_ADDRESS.debian_user &&
            EMAIL_ADDRESS.pop3_server === NEW_EMAIL_ADDRESS.pop3_server &&
            EMAIL_ADDRESS.pop3_user === NEW_EMAIL_ADDRESS.pop3_user
                ) {
            result = true;
        }
    }
    return result;
}

function IsEmailUserNameValid(username) {
//    const regex = /^[a-z_]([a-z0-9_-]{0,31}|[a-z0-9_-]{0,30}\\$)$/;
//    if (username.match(regex)) {
//        return true;
//    } else {
//        return false;
//    }
    return true;
}

function IsEmailUserPasswordValid(password) {
//    const regex = /^[a-z_]([a-z0-9_-]{0,31}|[a-z0-9_-]{0,30}\\$)$/;
//    if (password.match(regex)) {
//        return true;
//    } else {
//        return false;
//    }
    return true;
}

function DoesPOP3ServerExist(pop3_server) {
    let result = false;
    const email_addresses = GetEmailAddresses();
    for(let EMAIL_ADDRESS of email_addresses) {
        if(EMAIL_ADDRESS.pop3_server === pop3_server) {
            result = true;
        }
    }
    return result;
}

function AddEmail(Ajax1, Ajax2, Ajax3, Ajax4, authorization_key, user, host, idbuttondashboard, idbuttonaddemail, idpanel, identry1, identry2, identry3, identry4, identry5, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, select, entry_exists, invalid_servername, invalid_email_username, invalid_password, no_match, IdTableParentNode, IdTableChildNode) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idbuttonaddemail).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    SetClassAttribute(idconfirmbutton, 'w3-opacity');
    window.location.href = '#' + idconsole;
    document.getElementById(idcancelbutton).onclick = function() {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idbuttonaddemail).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById(identry1).value = select;
        document.getElementById(identry2).value = null;
        document.getElementById(identry3).value = null;
        document.getElementById(identry4).value = null;
        document.getElementById(identry5).value = null;
        ClearConsole(idconsole, user, host);
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        AddEmailNow(Ajax1, Ajax2, Ajax3, Ajax4, authorization_key, user, host, identry1, identry2, identry3, identry4, identry5, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, entry_exists, invalid_servername, invalid_email_username, invalid_password, no_match, IdTableParentNode, IdTableChildNode);
    }; 
}

function AddEmailNow(Ajax1, Ajax2, Ajax3, Ajax4, authorization_key, user, host, identry1, identry2, identry3, identry4, identry5, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, entry_exists, invalid_servername, invalid_email_username, invalid_password, no_match, IdTableParentNode, IdTableChildNode) {
    const new_email_debian_user = document.getElementById(identry1).value;
    const new_email_pop3_server = escapeHtml(document.getElementById(identry2).value);
    const new_email_pop3_user = escapeHtml(document.getElementById(identry3).value);
    const new_email_pop3_password = escapeHtml(document.getElementById(identry4).value);
    const new_email_pop3_password_confirmation = escapeHtml(document.getElementById(identry5).value);
    const NEW_EMAIL_ADDRESS = {debian_user: new_email_debian_user, pop3_server: new_email_pop3_server, pop3_user: new_email_pop3_user};
    let errors = '';
    if(DoesThisEmailEntryExist(NEW_EMAIL_ADDRESS)) {
        errors +=  entry_exists + '<br>';
    }
    if(!IsDomainNameValid(new_email_pop3_server)) {
        errors +=  invalid_servername + '<br>';
    }
    if (!IsEmailUserNameValid(new_email_pop3_user)) {
        errors += invalid_email_username + '<br>';
    }
    if (!IsEmailUserPasswordValid(new_email_pop3_password)) {
        errors += invalid_password + '<br>';
    }
    if (new_email_pop3_password !== new_email_pop3_password_confirmation) {
        errors += no_match + '<br>';
    }
    if (errors !== '') {
        document.getElementById(idconsole).innerHTML = errors;
        window.location.href = '#' + idconsolebutton;
    } else {
        let commands = [];
        if(!DoesPOP3ServerExist(new_email_pop3_server)) {
            commands = [
                {command:"sed -i -e \\\'$\\\'a\\\'poll " + new_email_pop3_server + " with protocol POP3:\\\' /etc/fetchmailrc",
                  masked:"sed -i -e '$'a'poll " + new_email_pop3_server + " with protocol POP3:' /etc/fetchmailrc"},
                {command:"sed -i -e \\\'/poll  *" + new_email_pop3_server + "  *with  *protocol  *POP3:/\\\'a\\\'user \\\'\\\\\\'" + new_email_pop3_user + "\\\\\\'\\\', password \\\'\\\\\\'" + new_email_pop3_password + "\\\\\\'\\\', is " + new_email_debian_user + " here ssl;\\\' /etc/fetchmailrc",
                  masked:"sed -i -e '/poll  *" + new_email_pop3_server + "  *with  *protocol  *POP3:/'a'user '" + new_email_pop3_user + "', password '***', is " + new_email_debian_user + " here ssl;' /etc/fetchmailrc"}];
        } else {
            const pop3_server_line_number = GetPOP3ServerLineNumber(new_email_pop3_server);
            commands = [{command:"sed -i -e \\\'/poll  *" + new_email_pop3_server + "  *with  *protocol  *POP3:/\\\'a\\\'user \\\'\\\\\\'" + new_email_pop3_user + "\\\\\\'\\\', password \\\'\\\\\\'" + new_email_pop3_password + "\\\\\\'\\\', is " + new_email_debian_user + " here ssl;\\\' /etc/fetchmailrc",
                          masked:"sed -i -e '/poll  *" + new_email_pop3_server + "  *with  *protocol  *POP3:/'a'user '" + new_email_pop3_user + "', password '***', is " + new_email_debian_user + " here ssl;' /etc/fetchmailrc"}];
        }
        ExecuteANumberOfCommandsAsUser(Ajax1, Ajax2, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
        GetEmailAddressesAndInitiateConnectionTests(Ajax3, Ajax4, authorization_key);
    }
}

function DeleteEmail(Ajax1, Ajax2, Ajax3, Ajax4, authorization_key, user, host, idbuttondashboard, idbuttonaddemail, idpanel, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, debian_user, pop3_server, pop3_user, pop3_server_line_number, pop3_user_line_number, IdTableParentNode, IdTableChildNode) {
    DisableButtons();
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById(idbuttonaddemail).style.display = 'none';
    document.getElementById(idpanel).style.display = null;
    document.getElementById(idcancelbutton).style.pointerEvents = null;
    document.getElementById(idconfirmbutton).style.pointerEvents = null;
    document.getElementById(idconsolebutton).style.display = 'none';
    document.getElementById('IdColumn0:' + debian_user + ':' + pop3_server + ':' + pop3_user + ':' + pop3_server_line_number + ':' + pop3_user_line_number).style.fontWeight = 'bold';
    window.location.href = '#' + idconsole;
    
    document.getElementById(idcancelbutton).onclick = function() {
        EnableButtons();
        document.getElementById(idbuttondashboard).style.display = null;
        document.getElementById(idbuttonaddemail).style.display = null;
        document.getElementById(idpanel).style.display = 'none';
        document.getElementById('IdColumn0:' + debian_user + ':' + pop3_server + ':' + pop3_user + ':' + pop3_server_line_number + ':' + pop3_user_line_number).style.fontWeight = null;
        window.location.href = '#' + '';
    };
    document.getElementById(idconfirmbutton).onclick = function () {
        DeleteEmailNow(Ajax1, Ajax2, Ajax3, Ajax4, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, pop3_server, pop3_server_line_number, pop3_user_line_number, IdTableParentNode, IdTableChildNode);
    }; 
}

function DeleteEmailNow(Ajax1, Ajax2, Ajax3, Ajax4, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, pop3_server, pop3_server_line_number, pop3_user_line_number, IdTableParentNode, IdTableChildNode) {
    let commands = [];
    if(POP3ServersNumberOfEmails(pop3_server) < 2) {
        commands = [{command:"sed -i -e \\\'" + pop3_server_line_number + "d\\\' -e \\\'" + pop3_user_line_number + "d\\\' /etc/fetchmailrc",
                      masked:"sed -i -e '" + pop3_server_line_number + "d' -e '" + pop3_user_line_number + "d' /etc/fetchmailrc"}];
    } else {
        commands = [{command:"sed -i -e \\\'" + pop3_user_line_number + "d\\\' /etc/fetchmailrc",
                      masked:"sed -i -e '" + pop3_user_line_number + "d' /etc/fetchmailrc"}];
    }
    ExecuteANumberOfCommandsAsUser(Ajax1, Ajax2, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode);
    GetEmailAddressesAndInitiateConnectionTests(Ajax3, Ajax4, authorization_key);
}

function GetEmailAddressesAndInitiateConnectionTests(Ajax3, Ajax4, authorization_key) {
   setTimeout(() => {GetEmailAddressesAndInitiateConnectionTests2(Ajax3, Ajax4, authorization_key);}, 5000); 
}

function GetEmailAddressesAndInitiateConnectionTests2(Ajax3, Ajax4, authorization_key) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            const email_addresses = [];
            const array = this.responseText.split(' ');
            for(let element of array) {
                let EMAIL_ADDRESS = {
                    debian_user: element.split(':')[0], 
                    pop3_server: element.split(':')[1], 
                    pop3_user: element.split(':')[2],
                    pop3_password: element.split(':')[3]};
                email_addresses.push(EMAIL_ADDRESS);
            }
            for(let EMAIL_ADDRESS of email_addresses) {
                TestEmailConnection(Ajax4, authorization_key, EMAIL_ADDRESS);
            }
        }
    };
    xhttp.open('POST', Ajax3);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('authorization_key=' + authorization_key);
}

function TestEmailConnection(Ajax4, authorization_key, EMAIL_ADDRESS) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            const id = 'IdColumn4:' + 
                    EMAIL_ADDRESS.debian_user + ':' +
                    EMAIL_ADDRESS.pop3_server + ':' +
                    EMAIL_ADDRESS.pop3_user;
            document.getElementById(id).innerHTML = this.responseText;
            if(this.responseText === '+OK') {
                document.getElementById(id).style.color = 'green';
            } else if (this.responseText === '-ERR') {
                document.getElementById(id).style.color = 'red';
            }
        }
    };
    xhttp.open('POST', Ajax4);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('pop3_server=' + EMAIL_ADDRESS.pop3_server + '&pop3_user=' + EMAIL_ADDRESS.pop3_user + '&pop3_password=' + EMAIL_ADDRESS.pop3_password + '&authorization_key=' + authorization_key);
}


