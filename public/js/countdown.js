



// A*A -- création d'un array qui contient tous les cookies
let cookies = document.cookie.split('; ');
let allCookies = [];
cookies.forEach(cookie => {
  cookie = cookie.trim();
  let formatCookie = cookie.split('=');
  key = decodeURIComponent(formatCookie[0]);
  val = decodeURIComponent(formatCookie[1]);
  decodCookie = [key, val];
  allCookies.push(decodCookie);
});




// A*A -- fonction de calcul des différents éléments du compte à rebours
function calcTimerValues(distance) {
  let timerValues = [];

  let days = Math.floor(distance / (1000 * 60 * 60 * 24));
  let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  let seconds = Math.floor((distance % (1000 * 60)) / 1000);


  timerValues = [days, hours, minutes, seconds];
  return timerValues;
}






let countdownString = "";
for (i = 0; i < allCookies.length; i++) {

  // A*A -- si un cookie avec name='countdownCk' existe...
  if (allCookies[i][0] == 'countdownCk') {
    // on récupère sa valeur dans countdownString
    countdownString = allCookies[i][1];
  }
}






// On vérifie si la section '#countdownSection' est présente sur la page
if (document.querySelector("#countdownSection")) {


  // RPR -- si un countdown existe
  if (countdownString !== "") {


    // var countdownDate = new Date('2021-08-29T14:00:00'.replace(/\s/, 'T')).getTime();


    // on calcule le timestamp "limite" à partir de countdownString
    let countdownDate = new Date(countdownString.replace(/\s/, 'T')).getTime();


    // A*A -- affichage au chargement +++++++++++++++++++++++++++++++++++++++++++++++++
    let now = new Date().getTime();


    // on calcule la différence entre now et le timestamp limite
    let distance = countdownDate - now;


    // on récupère les éléments du DOM
    let countdownSection = document.querySelector("#countdownSection");

    // let countdownOutput = document.querySelector("#timeCount");

    const timeD = document.querySelector('#timeD');
    const timeH = document.querySelector('#timeH');
    const timeM = document.querySelector('#timeM');
    const timeS = document.querySelector('#timeS');







    // on cache le compteur si on est à 00:00:00
    if (distance < 0) {
      countdownSection.classList.add('d-none');
    }


    // si l'élément du DOM a été reconnu
    if (countdownSection) {

      let [days, hours, minutes, seconds] = calcTimerValues(distance);

      // MK -- premier affichage
      // countdownOutput.innerHTML = days + " : " + hours + " : " + minutes + " : " + seconds;
      timeD.textContent = days;
      timeH.textContent = hours;
      timeM.textContent = minutes;
      timeS.textContent = seconds;


      // RPR -- mise à jour du compteur chaque seconde
      let x = setInterval(function () {

        // calcule la nouvelle valeur de now
        let now = new Date().getTime();


        // Find the distance between now and the count down date
        let distance = countdownDate - now;


        [days, hours, minutes, seconds] = calcTimerValues(distance);


        // countdownOutput.innerHTML = days + " : " + hours + " : " + minutes + " : " + seconds;
        timeD.textContent = days;
        timeH.textContent = hours;
        timeM.textContent = minutes;
        timeS.textContent = seconds;
  


        // If the count down is finished, write some text
        if (distance < 0) {
          clearInterval(x);
          countdownOutput.innerHTML = "0h : 0m : 0s";
        }
      }, 1000);


    }


  }


}







