

const riderActivForm = document.querySelector("#riderActivationForm");


// si le form est identifié sur la page
if (riderActivForm) {

  // formatage de texte "actif"
  function textToOn(selectorString) {
    let textElems = document.querySelectorAll(selectorString);

    textElems.forEach(elem => {
      elem.classList.remove("text-secondary");
      elem.classList.add("fw-bold");
      elem.classList.add("text-dark");
    });
  }


  // formatage de texte "inactif"
  function textToOff(selectorString) {
    let textElems = document.querySelectorAll(selectorString);

    textElems.forEach(elem => {
      elem.classList.remove("fw-bold");
      elem.classList.remove("text-dark");
      elem.classList.add("text-secondary");
    });
  }


  // on récupère tous les input:checkbox
  const boxes = document.querySelectorAll("input[type=checkbox]");

    boxes.forEach(elem => {

      elem.addEventListener('change', (e) => {

        boxNb = e.target.getAttribute("name");
        selectorString = ".rider-" + boxNb;

        if (e.target.checked) {
          textToOn(selectorString);
        } else {
          textToOff(selectorString);
        }

      });

    });


}



