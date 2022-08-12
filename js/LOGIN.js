/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function RestoreLoginForm(text, idnotification) {
    DisableButtons();
    document.getElementById('IdLoginButton').style.pointerEvents = 'none';
    DisplaySpinnerWithText(text, idnotification);
}

function LoadLoginPage(loginpage) {
    DisableButtons();
    DisplaySpinnerWithText('', 'IdButtonTopbar');
    window.location.href = loginpage;
}
