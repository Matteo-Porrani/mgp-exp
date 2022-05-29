
// import { params } from "./data.js";
// import engine from "./engine.js";


function convertToPercent(val, tot) {
  return ((100 / tot) * val).toFixed(1);
}

function calcStepAverage(steps) {
  return (steps.reduce((tot, step) => tot += parseInt(step), 0) / steps.length).toFixed(1);
}

function removeDecimalIfZero(num) {
  if (num.toString().split('.')[1] === '0') {
    return Math.floor(num);
  } else {
    return num;
  }
}



const maker = {

  createCard(player) {

    const percentWp = removeDecimalIfZero(convertToPercent(player.placed.wp, params.totalBets));
    const percentBpx = removeDecimalIfZero(convertToPercent(player.placed.bpx, params.totalBets));
    const percentBps = removeDecimalIfZero(convertToPercent(player.placed.bps, params.totalBets));
    const percentRegul = removeDecimalIfZero((parseFloat(percentWp) + parseFloat(percentBpx) + parseFloat(percentBps)).toFixed(1));
    const average = removeDecimalIfZero(calcStepAverage(player.steps));

    return `
      <div class="stats-card ${player.show ? '' : 'stats-card--dark rounded rounded-3'}">
        
        <div class="rounded rounded-3 p-1 mb-1" style="background-color: ${player.col}"></div>
        <div class="stats-card-header">
          <h5 class="player-name f-cont">${player.name}</h5>
          <div class="stats-card-header-right">
            <span class="f-cont">${player.score}</span>
            <div class="toggler ${player.show ? 'active' : ''}" data-id="${player.id}"><div class="toggler__btn"></div></div>
          </div>
        </div>

        <div class="stats-card__content" style="display: ${player.show ? 'block' : 'none'}">

          <div class="stats-card__main">

            <div class="stats-prec__wrapper">
              <div class="stats-prec__bar stats-prec__wp" style="width: ${percentWp}%">${percentWp > 3 ? percentWp + '%' : ''}</div>
              <div class="stats-prec__bar stats-prec__bpx" style="width: ${percentBpx}%">${percentBpx > 3 ? percentBpx + '%' : ''}</div>
              <div class="stats-prec__bar stats-prec__bps" style="width: ${percentBps}%">${percentBps > 3 ? percentBps + '%' : ''}</div>
            </div>

            <div class="stats-regul__wrapper" style="background-color: transparent;">
              <div class="stats-regul__bar" style="width: ${percentRegul}%">${percentRegul}%</div>
            </div>

          </div>


          <div class="box1__title">
            <p class="m-0">Détails</p>
            <label class="stats-box-toggler btn btn-outline-dark bd-gray2" for="player${player.id}box1">
              <i class="fas fa-angle-down text-gray2"></i>
            </label>
          </div>

          <input id="player${player.id}box1" class="stats-box-toggler-input" type="checkbox">
          <div class="box1__content">

            <p class="perf-display-score">
              Victoires : <strong>${player.wins}</strong><br>
              Min : <strong>${Math.min(...player.steps)}</strong><br>
              Max : <strong>${Math.max(...player.steps)}</strong><br>
              Moy : <strong>${average}</strong><br>
            </p>  
            
            <div class="perf__wrapper squared-bg">
              ${this.makeSteps(player.steps, player.col)}
            </div>
            
          </div>

        </div>

      </div>
    `;
  },


  makeSteps(steps, bgColor) {

    return steps.reduce((content, step) => {
      return content += `
        <div class="step-bar" style="height: ${step * 2}px; background-color: ${bgColor}; color: ${bgColor === '#fd3' ? '#212529' : '#f8f9fa'}">
          <span class="step-bar-score">${step}</span> 
        </div>
      `;

    }, '');

  }

}


export default maker;

