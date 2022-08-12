/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function StartDemo(lang, toroot) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            window.location.href = toroot + '/pages/RASPBERRY_Dashboard.php?lang=' + lang;
        }
    };
    xhttp.open('GET', toroot + '/ajax/DemoStart.php?lang=' + lang, true);
    xhttp.send();
    DisableButtons();
//    DisplaySpinnerWithText('', 'IdButtonTopbar');
    DisplaySpinnerWithText('', 'IdButtonLogin');
}

function QuitDemo(lang, toroot) {
    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            window.location.href = toroot + '/pages/RASP_Home.php?lang=' + lang;
        }
    };
    xhttp.open('GET', toroot + '/ajax/DemoQuit.php', true);
    xhttp.send();
    DisableButtons();
    DisplaySpinnerWithText('', 'IdButtonTopbar');
}
