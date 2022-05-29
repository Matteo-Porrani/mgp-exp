
const usercodeInput = document.querySelector('#usercode');
const keybdKeys = document.querySelectorAll('.keybd-btn');
const loginButton = document.querySelector('#loginBtn');


keybdKeys.forEach(btn => {
  btn.addEventListener('click', (e) => {

    let currentCode = usercodeInput.value;

    // valeur de la touche
    let keyValue = e.currentTarget.dataset.keybd;

    if (keyValue === 'reset') {
      // code reset
      currentCode = "";
    } else if (keyValue === 'canc') {
      // cancel last digit
      currentCode = currentCode.slice(0, currentCode.length - 1);
    } else {
      // add digit
      if (currentCode.length === 4) {
        // alert('max code length !');
        return; /* si l'input contient déjà 4 chiffres, on sort de la fonction sans action supplémentaire */
      }
      currentCode += keyValue;
    }

    usercodeInput.value = currentCode;
    // console.log(currentCode.length);

    if (currentCode.length === 4) {
      loginButton.classList.remove('disabled');
      loginButton.classList.remove('btn-outline-secondary');
      loginButton.classList.add('btn-outline-success');
    }

  });
});



