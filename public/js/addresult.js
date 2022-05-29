
const addResBtns = document.querySelectorAll('.addResBtn');
const resIdList = document.querySelector('#resIdList');
const resPreview = document.querySelector('#resPreview');
const resReset = document.querySelector('#resResetBtn');



// si on trouve les boutons d'ajout résultat
if (addResBtns.length > 1) {

  resReset.addEventListener('click', (e) => {
    resIdList.value = "";
    resPreview.innerHTML = "";

    addResBtns.forEach(item => {
      item.classList.add('border-dark');
      item.classList.add('bg-warning');
      item.classList.remove('bd-gray2');
      item.classList.remove('text-gray2');
    });
  });



  addResBtns.forEach(item => {
    item.addEventListener('click', (e) => {

      if (!resIdList.value) {
        resIdList.value += `${e.currentTarget.dataset.id}`;
      } else {
        resIdList.value += `, ${e.currentTarget.dataset.id}`;
      }


      e.currentTarget.classList.remove('border-dark');
      e.currentTarget.classList.remove('bg-warning');
      e.currentTarget.classList.add('bd-gray2');
      e.currentTarget.classList.add('text-gray2');




      if (e.currentTarget.dataset.id !== 'emptypos') {
        resPreview.innerHTML += `<li class="fs-5"><span class="f-cont">${e.currentTarget.dataset.numb}</span> - ${e.currentTarget.dataset.name}</li>`;
      }



    });
  });

}


