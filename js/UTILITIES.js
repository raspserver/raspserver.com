/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function SetClassAttribute(id, desired_attribute) {
    let attributes = document.getElementById(id).getAttribute("class");
    if (!attributes.includes(desired_attribute)) {
        let updatedattributes = attributes + " " + desired_attribute;
        document.getElementById(id).setAttribute("class", updatedattributes);
    }
}

function UnsetClassAttribute(id, desired_attribute) {
    let attributes = document.getElementById(id).getAttribute("class");
    if (attributes.includes(desired_attribute)) {
        document.getElementById(id).setAttribute("class", attributes.replace(desired_attribute, "").trim());
    }
}

function DisplaySpinnerWithText(text, idnotification) {
    document.getElementById(idnotification).innerHTML = 
        "<a class='w3-margin-left w3-margin-right'><i class='fa fa-spinner fa-spin'></i><i>" + text + "</i></a>";
    document.getElementById(idnotification).style.display = null;
}

function DisplaySpinnerWithoutText(idnotification) {
    document.getElementById(idnotification).innerHTML = 
        "<a class=''><i class='fa fa-spinner fa-spin'></i><i></i></a>";
    document.getElementById(idnotification).style.display = null;
}

function DisableButtons() {
    const idelements = document.querySelectorAll('*[id]')
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, 5) === 'lang_') {
            document.getElementById(id).style.pointerEvents = 'none';
        }
    }
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, 14) === 'language_token') {
            document.getElementById(id).style.pointerEvents = 'none';
        }
    }
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, 8) === 'IdButton') {
            document.getElementById(id).style.pointerEvents = 'none';
        }
    }
    document.getElementById('openNav').style.pointerEvents = 'none';
    document.getElementById('IdButtonTopbar').style.pointerEvents = 'none';
}

function EnableButtons() {
    const idelements = document.querySelectorAll('*[id]')
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, 5) === 'lang_') {
            document.getElementById(id).style.pointerEvents = null;
        }
    }
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, 8) === 'IdButton') {
            document.getElementById(id).style.pointerEvents = null;
        }
    }
    document.getElementById('openNav').style.pointerEvents = null;
    document.getElementById('IdButtonTopbar').style.pointerEvents = null;
}

function GrayOutButtons(id_identifier) {
    const idelements = document.querySelectorAll('*[id]')
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, id_identifier.length) === id_identifier) {
            SetClassAttribute(id, 'w3-opacity');
        }
    }
}

function UnsetBoldCharacters(id_identifier) {
    const idelements = document.querySelectorAll('*[id]')
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, id_identifier.length) === id_identifier) {
            document.getElementById(id).style.fontWeight = null;
        }
    }
}

function UndoOutGrayingButtons(id_identifier) {
    const idelements = document.querySelectorAll('*[id]')
    for(let IDELEMENT of idelements) {
        let id = IDELEMENT.id;
        if(id.substring(0, id_identifier.length) === id_identifier) {
            UnsetClassAttribute(id, 'w3-opacity');
        }
    }
}

function DisableButtonsAndSpinTopbarButton() {
    DisableButtons();
    DisplaySpinnerWithText('', 'IdButtonTopbar');
}

function DisableButtonsAndSpinThisButton(idbutton) {
    DisableButtons();
    DisplaySpinnerWithoutText(idbutton);
}

function ClearConsole(idconsole, user, host) {
    if(user === 'root') {
        document.getElementById(idconsole).innerHTML = 
            'root@' + host + ':~# ';
    } else {
        document.getElementById(idconsole).innerHTML = 
            '<a style=\'color:green;\'>' + user + "@" + host + '</a>'+
            ':' + 
            '<a style=\'color:blue;\'>' + "~$ " + '</a>';
    }
}

function GetConsolePrompt(user, host) {
    if(user === 'root') {
        $string =   'root@' + host + ':~# ';
    } else {
        $string =   '<a style=\'color:green;\'>' + user + "@" + host + '</a>:'+
                    '<a style=\'color:blue;\'>' + "~$ " + '</a>';
    }
    return $string;
}

function PurgeBufferingSpacesAsterisk(response) {
    let string = '';
    const myArray = response.split("<br>");
    for (let element of myArray) {
        string += "<br>" + element.substring(4096).trim();
    }
    return string;
}

function PurgeBufferingSpacesAsUser(response) {
    let string = '';
    const myArray = response.split("<br>");
    for (let element of myArray) {
        string +=  element.substring(4096).trim() + "<br>";
    }
    return string.substring(0, string.length - 4);
}

function GetLastLineOfResponse(purged_response) {
    const length = purged_response.length;
    const string = purged_response.substring(0, length -4);
    const position = string.lastIndexOf('<br>');
    const last_line = string.substring(position + 4);
    return last_line;
}

function DidErrorOccur(purged_response) {
    const last_line = GetLastLineOfResponse(purged_response);
    if(last_line.substring(0, 9) === "Exit Code") {
        return true;
    } else {
        return false;
    }
}

function SortList(idlist) {
    let list = document.querySelector('#' + idlist);
    [...list.children].sort((a,b)=>a.innerText>b.innerText?1:-1).forEach(node=>list.appendChild(node));
}

function ExecuteANumberOfCommandsAsUser(AjaxFileExecute, AjaxFileRefresh, authorization_key, user, host, idcancelbutton, idconfirmbutton, idconsolebutton, idconsole, commands, IdTableParentNode, IdTableChildNode) {
    const xhttp = [];
    for (let i = 0; i < (commands.length); i++) {
        xhttp[i] = new XMLHttpRequest();
    }
    for (let j = 0; j < (commands.length - 1); j++) {
        xhttp[j].onprogress = function () {
            let purged_response = PurgeBufferingSpacesAsUser(this.responseText);
            document.getElementById(idconsole).innerHTML = '<pre>' + console_content + purged_response + '</pre>';
            window.location.href = '#' + idconsole;
        };
        xhttp[j].onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let purged_response = PurgeBufferingSpacesAsUser(this.responseText);
                console_content += purged_response + GetConsolePrompt(user, host) + commands[j + 1].masked;
                document.getElementById(idconsole).innerHTML = '<pre>' + console_content + '</pre>';
                window.location.href = '#' + idconsole;
                xhttp[j + 1].open('POST', AjaxFileExecute);
                xhttp[j + 1].setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp[j + 1].send('command=' + commands[j + 1].command + '&user=' + user + '&authorization_key=' + authorization_key);
            }
        };
    }
    xhttp[commands.length - 1].onprogress = function () {
        let purged_response = PurgeBufferingSpacesAsUser(this.responseText);
        if(purged_response.substring(0, 1) !== '^') {
            document.getElementById(idconsole).innerHTML = '<pre>' + console_content + purged_response + '</pre>';
        }
        window.location.href = '#' + idconsole;
    };
    xhttp[commands.length - 1].onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let purged_response = PurgeBufferingSpacesAsUser(this.responseText);
            if(purged_response.substring(0, 1) !== '^') {
                console_content += purged_response + GetConsolePrompt(user, host);
                document.getElementById(idconsole).innerHTML = '<pre>' + console_content + '</pre>';
                window.location.href = '#' + idconsole;
                ReloadElement(AjaxFileRefresh, authorization_key, IdTableParentNode, IdTableChildNode);
            } else {
                const delay = DisplayResponse(purged_response, console_content, idconsole, idconsolebutton, user, host, 500);
                setTimeout(() => { ReloadElement(AjaxFileRefresh, authorization_key, IdTableParentNode, IdTableChildNode); }, delay);
            }
        }
    };
    xhttp[0].open('POST', AjaxFileExecute);
    xhttp[0].setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp[0].send('command=' + commands[0].command + '&user=' + user + '&authorization_key=' + authorization_key);
    DisableButtons();
    DisplaySpinnerWithText('', idconsolebutton);
    let console_content = GetConsolePrompt(user, host) + commands[0].masked;
    document.getElementById(idconsole).innerHTML = '<pre>' + console_content + '</pre>';
    window.location.href = '#' + idconsole;
}

function DisplayResponse(response, console_content, idconsole, idconsolebutton, user, host, delay) {
    const array = response.split('<br>');
    let i = 0;
    for(let j = 0; j < array.length - 1; j++) {
        setTimeout(() => { document.getElementById(idconsole).innerHTML +=  array[j].substring(1) + '<br>';
                           window.location.href = '#' + idconsolebutton; }, j * delay);
        i = j + 2;
    }
    setTimeout(() => { document.getElementById(idconsole).innerHTML +=  array[i].substring(1);
                           window.location.href = '#' + idconsolebutton; }, i * delay);
    i++;                  
    setTimeout(() => { document.getElementById(idconsole).innerHTML += GetConsolePrompt(user, host);
                           window.location.href = '#' + idconsolebutton; }, i * delay);
    i++;
    return i * delay;
}

function ReloadElement(AjaxFileRefresh, authorization_key, IdTableParentNode, IdTableChildNode) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            ReplaceElement(IdTableParentNode, IdTableChildNode, this.responseText);
            EnableButtons();
        }
    };
    xhttp.open('POST', AjaxFileRefresh);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('authorization_key=' + authorization_key);
}

function ReloadElements(AjaxFileRefresh, authorization_key, IdsTableParentNode, IdsTableChildNode) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const ArrayIdsParentNode = IdsTableParentNode.split('°');
            const ArrayIdsChildNode = IdsTableChildNode.split('°');
            const ArrayResponseText = this.responseText.split('°');
            let i = 0;
            for(let element of ArrayResponseText) {
                ReplaceElement(ArrayIdsParentNode[i], ArrayIdsChildNode[i], element);
                i++;
            }
            EnableButtons();
        }
    };
    xhttp.open('POST', AjaxFileRefresh);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('authorization_key=' + authorization_key);
}

function ReplaceElement(idparent, idchild, innerHTML) {
    const element = document.getElementById(idchild);
    element.parentNode.removeChild(element);
    const refreshed_table = document.createElement(idchild);
    refreshed_table.innerHTML = innerHTML;
    document.getElementById(idparent).appendChild(refreshed_table);
}

function EnableDropdownConfirmationButton(idbutton) {
    document.getElementById(idbutton).style.pointerEvents = null;
    UnsetClassAttribute(idbutton, 'w3-opacity');
}

function UpdateConsolePromptAndEnableDropdownConfirmationButton(idbutton, idconsole, identry1, host) {
    const user = document.getElementById(identry1).value.split(' ')[1];
    document.getElementById(idconsole).innerHTML = GetConsolePrompt(user, host);
    EnableDropdownConfirmationButton(idbutton);
}

function IsDomainNameValid(domain) {
    const re = new RegExp(/^((?:(?:(?:\w[\.\-\+]?)*)\w)+)((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,6})$/);
    return domain.match(re);
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
