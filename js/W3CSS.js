/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

//  W3CSS Side navigation bar
function w3_open() {
    document.getElementById('main').style.marginLeft = '25%';
    document.getElementById('mySidebar').style.width = '25%';
    document.getElementById('mySidebar').style.display = 'block';
    document.getElementById('openNav').style.display = 'none';
}
function w3_close() {
    document.getElementById('main').style.marginLeft = '0%';
    document.getElementById('mySidebar').style.display = 'none';
    document.getElementById('openNav').style.display = 'inline-block';
}

function myAccFunc() {
  var x = document.getElementById("Accordion");
  if (x.className.indexOf("w3-show") === -1) {
    x.className += " w3-show";
    x.previousElementSibling.className += " w3-gray";
  } else { 
    x.className = x.className.replace(" w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-gray", "");
  }
}