<?php

function debugPR($info) {
  echo '<pre>';
  print_r($info);
  echo '</pre>';
}

function debugVD($info) {
  echo '<pre>';
  var_dump($info);
  echo '</pre>';
}

// used in api.php
function translateColor($color)
{

  switch ($color) {

    case 'red':
      $trCol = '#e12';
      break;

    case 'orange':
      $trCol = '#e72';
      break;

    case 'orangelight':
      $trCol = '#fa1';
      break;

    case 'yellow':
      $trCol = '#fd3';
      break;

    case 'salmon':
      $trCol = '#f87';
      break;

    case 'purple':
      $trCol = '#929';
      break;

    case 'indigo':
      $trCol = '#76d';
      break;

    case 'blue':
      $trCol = '#26e';
      break;

    case 'bluelight':
      $trCol = '#1be';
      break;

    case 'teal':
      $trCol = '#189';
      break;

    case 'yellow':
      $trCol = '#fd3';
      break;

    case 'green':
      $trCol = '#372';
      break;

    case 'greenlight':
      $trCol = '#8b2';
      break;
  }

  return $trCol;
}

