/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function DisableLanguage(lang, toroot, authorization_key) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            location.reload();
        }
    };
    xhttp.open('POST', toroot + '/ajax/DisableLanguage.php');
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('lang=' + lang + '&authorization_key=' + authorization_key);
    document.getElementById('IdButton:' + lang).style.display = 'none';
    DisplaySpinnerWithText('', 'languageconfigurationtablespinner:' + lang);
    DisableButtons();
}

function EnableLanguage(lang, toroot, authorization_key) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            location.reload();
        }
    };
    xhttp.open('POST', toroot + '/ajax/EnableLanguage.php');
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('lang=' + lang + '&authorization_key=' + authorization_key);
    document.getElementById('IdButton:' + lang).style.display = 'none';
    DisplaySpinnerWithText('', 'languageconfigurationtablespinner:' + lang);
    DisableButtons();
}



