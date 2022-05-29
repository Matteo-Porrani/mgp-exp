const pageMenu = document.querySelector('#pageMenu');

if (pageMenu) {

  const menuOptions = document.querySelectorAll('.opt');
  const dispSections = document.querySelectorAll('.displayOptionSection');



  menuOptions.forEach(item => {
    item.addEventListener('click', (e) => {

      // désactive tous les boutons
      menuOptions.forEach(item => {
        item.classList.remove('opt__active');
        item.classList.add('opt__off');
      }); 

      // active le bouton cliqué
      e.target.classList.remove('opt__off');
      e.target.classList.add('opt__active');


      dispSections.forEach(item => item.classList.add('d-none'));

      document.querySelector(e.target.dataset.target).classList.remove('d-none');

    });
  });


}

