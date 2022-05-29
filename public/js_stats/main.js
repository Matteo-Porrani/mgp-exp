
import dom from './dom.js';
import maker from './maker.js';


// A*A -- for localhost : '/mgpbattle/stats.php'
if (location.pathname === '/stats.php' || location.pathname === '/mgpbattle/stats.php') {

  // event listener on <select>
  mainStatsSelector.addEventListener('change', changeStatsDisplay);
  // show/hide players & riders
  function changeStatsDisplay(e) {
    if (e.target.value === 'players') {
      statsPlayersSection.classList.remove('d-none');
      statsRidersSection.classList.add('d-none');
    } else {
      statsPlayersSection.classList.add('d-none');
      statsRidersSection.classList.remove('d-none');
    }
  }

  const targetUrl1 = 'http://localhost:8888/mgpbattle/api.php?data=users';
  const targetUrl2 = 'https://mgpbattle.alwaysdata.net/api.php?data=users';
  const fetchUrl = location.hostname === 'localhost' ? targetUrl1 : targetUrl2;

  fetch(fetchUrl)
    .then(res => res.json())
    .then(data => {

      // sort functions
      const sortFuncs = [
        // sortByRank
        arr => arr.sort((a, b) => a.rank - b.rank),
        // sortByPrecision
        arr => arr.sort((a, b) => b.prec - a.prec),
        // sortByRegularity
        arr => arr.sort((a, b) => b.regul - a.regul),
      ];

      // first display
      refreshCards(data);

      // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
      function refreshCards(players) {
        const [binA, binB] = [getVisibleItems(players), getInvisibleItems(players)];
        try {
          reorderCards(binA, params.activeOrder);
        } catch (error) {

        }
        displayCards(binA, dom.bin1);
        displayCards(binB, dom.bin2);
      }

      function displayCards(players, bin) {
        bin.innerHTML = players.reduce((content, player) => content += maker.createCard(player), '');
        addListenerToToggles();
      }

      // visible & invisible items
      function getVisibleItems(arr) {
        return arr.filter(item => item.show === true);
      }

      function getInvisibleItems(arr) {
        return arr.filter(item => item.show === false);
      }

      // call to sort functions
      function reorderCards(arr, cryt) {
        sortFuncs[cryt](arr);
      }

      // change property in the params object
      function changeDisplayOrder(cryt) {
        params.activeOrder = cryt;
      }

      // event listener for toggle buttons on cards
      function addListenerToToggles() {
        document.querySelectorAll('.toggler').forEach(item => item.addEventListener('click', toggleHandler));
      }

      function toggleHandler(e) {
        e.currentTarget.classList.toggle('active');
        // using 'engine' instead of 'this'
        togglePlayerVisibility(data, e.currentTarget.dataset.id);

        setTimeout(() => refreshCards(data), 300);
      }

      function togglePlayerVisibility(players, playerID) {
        players[playerID - 1].show = !players[playerID - 1].show;
      }

      // buttons for choosing display order
      const labelClickHandler = e => {
        changeDisplayOrder(e.target.dataset.cryt);
        refreshCards(data);
      };

      // event listener on order buttons
      dom.labels.forEach(item => item.addEventListener('click', labelClickHandler));



      // MK -- boutons 'Tout monter' / 'Tout masquer'
      const activButtonsClickHandler = e => {

        if (parseInt(e.target.dataset.action) === 1) {

          // activer
          data.forEach(player => {
            if (player.show === false) togglePlayerVisibility(data, player.id);
          });

        } else {
          // désactiver
          data.forEach(player => {
            if (player.show) togglePlayerVisibility(data, player.id);
          });
        }

        refreshCards(data);
      }

      dom.activButtons.forEach(item => item.addEventListener('click', activButtonsClickHandler));

    });

}