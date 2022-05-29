/**
 * AFFICHE LA MODALE SI LE COOKIE 'modalCookie' EXISTE
 */


// on crée un array qui contient tous les cookies


// FIXME -- code doublon avec countdown.js

cookies = document.cookie.split('; ');
allCookies = [];

cookies.forEach(cookie => {
  cookie = cookie.trim();
  let formatCookie = cookie.split('=');
  key = decodeURIComponent(formatCookie[0]);
  val = decodeURIComponent(formatCookie[1]);
  decodCookie = [key, val];
  allCookies.push(decodCookie);
});



let modalContent = document.querySelector("#modalContent");

let message = "";
for (i = 0; i < allCookies.length; i++) {


  // if (allCookies[i][0] == 'modalCookie' || allCookies[i][0] == ' modalCookie') {
  if (allCookies[i][0] == 'modalCookie') {
    console.log("j'ai trouvé modalCookie");
    message = allCookies[i][1];
  }
}

// identification de la modale
var messModal = new bootstrap.Modal(document.getElementById('messMod'), {
  keyboard: false
})

// affichage de la modale
if (message != "") {
  modalContent.innerText = message;
  messModal.show();
}