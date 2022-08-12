/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */


function ChangeAdministratorPassword(idbuttondashboard) {
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById('IdChangePasswordHiddenParts').style.display = null;
    DisableButtons();
}

function ChangeAdministratorPasswordCancel(idbuttondashboard) {
    document.getElementById(idbuttondashboard).style.display = null;
    document.getElementById('ChangeAdministratorPasswordNotificationId').innerHTML = null;
    document.getElementById('ChangeAdministratorPasswordNotificationId').style.display = 'none';
    document.getElementById('IdChangePasswordHiddenParts').style.display = 'none';
    document.getElementById('IdChangePasswordEntryPassword').value = null;
    document.getElementById('IdChangePasswordEntryPasswordConfirmation').value = null;
    EnableButtons();
}

function ChangeAdministratorPasswordNow(toroot, authorization_key, button_cancel_id, button_confirmation_id, notification_id, no_entry, no_match, invalid_password) {
    const entry_password = document.getElementById('IdChangePasswordEntryPassword').value;
    const entry_password_confirmation = document.getElementById('IdChangePasswordEntryPasswordConfirmation').value;
    if(entry_password === '' && entry_password_confirmation === '') {
        document.getElementById('ChangeAdministratorPasswordNotificationId').innerHTML = no_entry;
        document.getElementById('ChangeAdministratorPasswordNotificationId').style.display = null;
    } else if(entry_password !== entry_password_confirmation) {
        document.getElementById('ChangeAdministratorPasswordNotificationId').innerHTML = no_match;
        document.getElementById('ChangeAdministratorPasswordNotificationId').style.display = null;
    } else if(!IsRaspserverPasswordValid()) {
        document.getElementById('ChangeAdministratorPasswordNotificationId').innerHTML = invalid_password;
        document.getElementById('ChangeAdministratorPasswordNotificationId').style.display = null;
    } else {
        AjaxChangeAdministratorPasswordNow(toroot, authorization_key, button_cancel_id, button_confirmation_id, notification_id, entry_password);
    }
}

function AjaxChangeAdministratorPasswordNow(toroot, authorization_key, button_cancel_id, button_confirmation_id, notification_id, password) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            location.reload();
        }
    };
    DisableButtons();
    document.getElementById(button_cancel_id).style.pointerEvents = 'none';
    document.getElementById(button_confirmation_id).style.pointerEvents = 'none';
    DisplaySpinnerWithText('', button_confirmation_id);
    document.getElementById(notification_id).innerHTML = null;
    xhttp.open("POST", toroot + '/ajax/ChangeAdministratorPassword.php');
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('password=' + password + '&authorization_key=' + authorization_key);
}

function DisableAdministratorPassword(idbuttondashboard) {
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById('IdDisablePasswordHiddenParts').style.display = null;
    DisableButtons();
}

function DisableAdministratorPasswordCancel(idbuttondashboard) {
    document.getElementById(idbuttondashboard).style.display = null;
    document.getElementById('IdDisablePasswordHiddenParts').style.display = 'none';
    EnableButtons();
}

function AjaxDisableAdministratorPasswordNow(toroot, authorization_key, button_cancel_id, button_confirmation_id) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            location.reload();
        }
    };
    DisableButtons();
    document.getElementById(button_cancel_id).style.pointerEvents = 'none';
    document.getElementById(button_confirmation_id).style.pointerEvents = 'none';
    DisplaySpinnerWithText('', button_confirmation_id);
    xhttp.open("POST", toroot + '/ajax/DisableAdministratorPassword.php');
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('authorization_key=' + authorization_key);
}

function EnableAdministratorPassword(idbuttondashboard) {
    document.getElementById(idbuttondashboard).style.display = 'none';
    document.getElementById('IdEnablePasswordHiddenParts').style.display = null;
    DisableButtons();
}

function EnableAdministratorPasswordCancel(idbuttondashboard) {
    document.getElementById(idbuttondashboard).style.display = null;
    document.getElementById('EnableAdministratorPasswordNotificationId').innerHTML = null;
    document.getElementById('EnableAdministratorPasswordNotificationId').style.display = 'none';
    document.getElementById('IdEnablePasswordHiddenParts').style.display = 'none';
    document.getElementById('IdEnablePasswordEntryPassword').value = null;
    document.getElementById('IdEnablePasswordEntryPasswordConfirmation').value = null;
    EnableButtons();
}

function EnableAdministratorPasswordNow(toroot, authorization_key, button_cancel_id, button_confirmation_id, notification_id, no_entry, no_match, invalid_password) {
    const entry_password = document.getElementById('IdEnablePasswordEntryPassword').value;
    const entry_password_confirmation = document.getElementById('IdEnablePasswordEntryPasswordConfirmation').value;
    if(entry_password === '' && entry_password_confirmation === '') {
        document.getElementById('EnableAdministratorPasswordNotificationId').innerHTML = no_entry;
        document.getElementById('EnableAdministratorPasswordNotificationId').style.display = null;
    } else if(entry_password !== entry_password_confirmation) {
        document.getElementById('EnableAdministratorPasswordNotificationId').innerHTML = no_match;
        document.getElementById('EnableAdministratorPasswordNotificationId').style.display = null;
    } else if(!IsRaspserverPasswordValid()) {
        document.getElementById('EnableAdministratorPasswordNotificationId').innerHTML = invalid_password;
        document.getElementById('EnableAdministratorPasswordNotificationId').style.display = null;
    } else {
        AjaxEnableAdministratorPasswordNow(toroot, authorization_key, button_cancel_id, button_confirmation_id, notification_id, entry_password);
    }
}

function AjaxEnableAdministratorPasswordNow(toroot, authorization_key, button_cancel_id, button_confirmation_id, notification_id, password) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            location.reload();
        }
    };
    DisableButtons();
    document.getElementById(button_cancel_id).style.pointerEvents = 'none';
    document.getElementById(button_confirmation_id).style.pointerEvents = 'none';
    DisplaySpinnerWithText('', button_confirmation_id);
    document.getElementById(notification_id).innerHTML = null;
    xhttp.open("POST", toroot + '/ajax/EnableAdministratorPassword.php');
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('password=' + password + '&authorization_key=' + authorization_key);
}

function IsRaspserverPasswordValid() {
    
    return true;
}