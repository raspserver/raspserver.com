/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function PingOrTracerouteThisDomain(AjaxFileExecute, AjaxFileAddRefresh, authorization_key, user, host, idbuttontoggle, iddomainlist, identry, idconsole, IdButtonClearConsole, newdomain, command, raspberry_emulated) {
    const xhttp = new XMLHttpRequest();
    xhttp.onprogress = function () {
        if(!raspberry_emulated) {
            let purged_response = PurgeBufferingSpacesAsterisk(this.responseText);
            document.getElementById(idconsole).innerHTML = '<pre>' + console_content + purged_response + '</pre>';
            window.location.href = '#' + IdButtonClearConsole;
        }
    };
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let purged_response = PurgeBufferingSpacesAsterisk(this.responseText);
            if(!raspberry_emulated) {
                console_content += purged_response + GetConsolePrompt(user, host);
                document.getElementById(idconsole).innerHTML = '<pre>' + console_content;
                window.location.href = '#' + IdButtonClearConsole;
                if(!DidErrorOccur(purged_response) && newdomain !== '') {
                    AddItemToListGetRefreshedDomainList(AjaxFileAddRefresh, authorization_key, newdomain, command.split(' ')[0], idconsole, IdButtonClearConsole, iddomainlist, button_clear_console_label, raspberry_emulated, idbuttontoggle); 
                    
                } else {
                    document.getElementById(IdButtonClearConsole).innerHTML = button_clear_console_label + '</pre>';
                    EnableButtons();
                }
            } else {
                FakeDelayedResponse(AjaxFileAddRefresh, authorization_key, user, host, idbuttontoggle, idconsole, IdButtonClearConsole, newdomain, command, purged_response, button_clear_console_label, iddomainlist); 
            }
        }
    };
    xhttp.open("POST", AjaxFileExecute);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('command=' + command + '&authorization_key=' + authorization_key);
    let console_content = GetConsolePrompt(user, host) + command;
    document.getElementById(idconsole).innerHTML = '<pre>' + console_content + '</pre>';
    window.location.href = '#' + IdButtonClearConsole;
    DisableButtons();
    if (newdomain === '') {
        document.getElementById(identry).value = null;
    }
    UnboldDomainList(iddomainlist);
    if(IsDomainOnTheList(command.substring(command.lastIndexOf(' ') + 1))) {
        document.getElementById(iddomainlist + command.split(' ')[0] + ':' + command.substring(command.lastIndexOf(' ') + 1)).style.fontWeight = 'bold';
    }
    const button_clear_console_label = document.getElementById(IdButtonClearConsole).innerHTML;
    DisplaySpinnerWithText('', IdButtonClearConsole);
}

function PingOrTracerouteThisEntry(AjaxFileExecute, AjaxFileAddRefresh, authorization_key, user, host, idbuttontoggle, iddomainlist, identry, idconsole, IdButtonClearConsole, commandwithoutdomain, raspberry_emulated) {
    let newdomain;
    if(!IsDomainOnTheList(document.getElementById(identry).value.toLowerCase())) {
        newdomain = document.getElementById(identry).value.toLowerCase();
    } else {
        newdomain = '';
    }
    const command = commandwithoutdomain + document.getElementById(identry).value.toLowerCase();
    PingOrTracerouteThisDomain(AjaxFileExecute, AjaxFileAddRefresh, authorization_key, user, host, idbuttontoggle, iddomainlist, identry, idconsole, IdButtonClearConsole, newdomain, command, raspberry_emulated);
}

function DeleteItemFromListGetRefreshedDomainList(AjaxFileDeleteRefresh, authorization_key, domain, iddomainlist, idconsole, id_button_toggle_ping) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            
            let element = document.getElementById('ConfigureDomainsTableChildNode');
            element.parentNode.removeChild(element);
            const refreshed_table = document.createElement('ConfigureDomainsTableChildNode');
            refreshed_table.innerHTML = this.responseText;
            document.getElementById('ConfigureDomainsTableParentNode').appendChild(refreshed_table);
            document.getElementById(idconsole).innerHTML = console_content;
            document.getElementById(bold_item_id).style.fontWeight = 'bold';
        }
    };
    const console_content = document.getElementById(idconsole).innerHTML;
    const bold_item_id = GetBoldItemOnDomainList(iddomainlist);
    xhttp.open("POST", AjaxFileDeleteRefresh);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('domain=' + domain + '&ping_or_traceroute=' + IsItPingOrTraceroute(id_button_toggle_ping) + '&authorization_key=' + authorization_key);
}

function AddItemToListGetRefreshedDomainList(AjaxFileAddRefresh, authorization_key, domain, ping_or_traceroute, idconsole, IdButtonClearConsole, iddomainlist, button_clear_console_label, raspberry_emulated, idbuttontoggle) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let element = document.getElementById('ConfigureDomainsTableChildNode');
            element.parentNode.removeChild(element);
            const refreshed_table = document.createElement('ConfigureDomainsTableChildNode');
            refreshed_table.innerHTML = this.responseText;
            document.getElementById('ConfigureDomainsTableParentNode').appendChild(refreshed_table);
            document.getElementById(idconsole).innerHTML = console_content;
            window.location.href = '#' + IdButtonClearConsole;
            document.getElementById(iddomainlist + ping_or_traceroute + ':' + domain).style.fontWeight = 'bold';
            document.getElementById(IdButtonClearConsole).innerHTML = button_clear_console_label;
            EnableButtons();
            if(raspberry_emulated) {
                document.getElementById(idbuttontoggle).style.pointerEvents = 'none';
            }
        }
    };
    const console_content = document.getElementById(idconsole).innerHTML;
    xhttp.open("POST", AjaxFileAddRefresh);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('domain=' + domain + '&ping_or_traceroute=' + ping_or_traceroute + '&authorization_key=' + authorization_key);
}

function TogglePingOrTraceroute(
            id_button_toggle_ping,
            id_button_toggle_traceroute,
            id_button_domain_ping,
            id_button_domain_traceroute,
            id_entry_ping,
            id_entry_traceroute,
            id_button_ping,
            id_button_traceroute
        ) {
    const domains = GetDomains();
    if(document.getElementById(id_button_toggle_ping).style.display === 'inline') {
        document.getElementById(id_button_toggle_ping).style.display = 'none';
        document.getElementById(id_button_toggle_traceroute).style.display = 'inline';
        document.getElementById(id_entry_ping).style.display = 'none';
        document.getElementById(id_entry_traceroute).style.display = null;
        document.getElementById(id_button_ping).style.display = 'none';
        document.getElementById(id_button_traceroute).style.display = null;
        for (let domain of domains) {
            document.getElementById(id_button_domain_ping + domain).style.display = 'none';
            document.getElementById(id_button_domain_traceroute + domain).style.display = 'inline';
        }
        ClearConsoleClearEntryAndUnbold('', 'asterisk', 'raspbx', 'IdConsoleOutput', 'IdEntry', 'IdButtonDomain');
    } else {
        document.getElementById(id_button_toggle_ping).style.display = 'inline';
        document.getElementById(id_button_toggle_traceroute).style.display = 'none';
        document.getElementById(id_entry_ping).style.display = null;
        document.getElementById(id_entry_traceroute).style.display = 'none';
        document.getElementById(id_button_ping).style.display = null;
        document.getElementById(id_button_traceroute).style.display = 'none';
        for (let domain of domains) {
            document.getElementById(id_button_domain_ping + domain).style.display = 'inline';
            document.getElementById(id_button_domain_traceroute + domain).style.display = 'none';
        }
        ClearConsoleClearEntryAndUnbold('', 'asterisk', 'raspbx', 'IdConsoleOutput', 'IdEntry', 'IdButtonDomain');
    }
}

function FakeDelayedResponse(AjaxFileAddRefresh, authorization_key, user, host, idbuttontoggle, idconsole, IdButtonClearConsole, newdomain, command, purged_response, button_clear_console_label, iddomainlist) {
    if(purged_response.indexOf('Exit Code') === -1) {
        const pos1 = purged_response.indexOf("64 bytes"); const string1 = purged_response.substring(pos1 + 8);
        const pos2 = string1.indexOf("64 bytes"); const string2 = purged_response.substring(0, pos1 + 8 + pos2);
        document.getElementById(idconsole).innerHTML = GetConsolePrompt(user, host) + command + string2;
        window.location.href = '#' + IdButtonClearConsole;
        const string3 = purged_response.replace(string2, ''); const string4 = string3.substring(8);
        const pos4 = string4.indexOf("64 bytes"); const string5 = string3.substring(0, pos4 + 8);
        setTimeout(() => { document.getElementById(idconsole).innerHTML += string5;
                           window.location.href = '#' + IdButtonClearConsole; }, 1000);
        const string6 = string3.replace(string5, ''); const string7 = string6.substring(8);
        const pos5 = string7.indexOf("64 bytes"); const string8 = string6.substring(0, 8 + pos5);
        setTimeout(() => { document.getElementById(idconsole).innerHTML += string8;
                           window.location.href = '#' + IdButtonClearConsole; }, 2000);
        const string9 = string6.replace(string8, '');
        setTimeout(() => { document.getElementById(idconsole).innerHTML += string9 + GetConsolePrompt(user, host);
                           window.location.href = '#' + IdButtonClearConsole;
                           if(!DidErrorOccur(purged_response) && newdomain !== '') {
                               AddItemToListGetRefreshedDomainList(AjaxFileAddRefresh, authorization_key, newdomain, command.split(' ')[0], idconsole, IdButtonClearConsole, iddomainlist, button_clear_console_label, true, idbuttontoggle);
                           }
                           document.getElementById(IdButtonClearConsole).innerHTML = button_clear_console_label;
                           EnableButtons(); document.getElementById(idbuttontoggle).style.pointerEvents = 'none';}, 3000);
    } else {
        document.getElementById(idconsole).innerHTML = 
                GetConsolePrompt(user, host) + command + purged_response + GetConsolePrompt(user, host);
        window.location.href = '#' + IdButtonClearConsole;
        document.getElementById(IdButtonClearConsole).innerHTML = button_clear_console_label;
        EnableButtons(); document.getElementById(idbuttontoggle).style.pointerEvents = 'none';
    }
}

function GetDomains() {
    const domains = [];
    const idelements = document.querySelectorAll('*[id]');
    for (let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if (id.substring(0, 19) === 'IdButtonDomainping:') {
            domains.push(id.substring(19));
        }
    }
    return domains;
}

function IsDomainOnTheList(domain)  {
    const domains = GetDomains();
    if(domains.indexOf(domain) === -1) {
        return false;
    } else {
        return true;
    }
}

function IsItPingOrTraceroute(id_button_toggle_ping) {
    if(document.getElementById(id_button_toggle_ping).style.display === 'inline') {
        return 'ping';
    } else {
        return 'traceroute';
    }
}

function UnboldDomainList(iddomainlist) {
    const idelements = document.querySelectorAll('*[id]')
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, iddomainlist.length) === iddomainlist) {
            document.getElementById(id).style.fontWeight = null;
        }
    }
}

function ClearBothPingAndTracerouteEntries(identry) {
    
    const idelements = document.querySelectorAll('*[id]')
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, identry.length) === identry) {
            document.getElementById(id).value = null;
        }
    }
}

function ClearConsoleClearEntryAndUnbold(href, user, host, idconsole, identry, iddomainlist) {
    if(user === 'root') {
        document.getElementById(idconsole).innerHTML = 
            'root@' + host + ':~# ';
    } else {
        document.getElementById(idconsole).innerHTML = 
            '<a style=\'color:green;\'>' + user + "@" + host + '</a>'+
            ':' + 
            '<a style=\'color:blue;\'>' + "~$ " + '</a>';
    }
    window.location.href = '#' + href;
    ClearBothPingAndTracerouteEntries(identry);
    UnboldDomainList(iddomainlist);
}

function GetBoldItemOnDomainList(iddomainlist) {
    let bold_item_id = false;
    const domains = GetDomains();
    const idelements = document.querySelectorAll('*[id]')
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, iddomainlist.length) === iddomainlist) {
            if(document.getElementById(id).style.fontWeight === 'bold') {
                bold_item_id = id;
            }
        }
    }
    return bold_item_id;
}
