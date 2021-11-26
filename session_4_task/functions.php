<?php
//Remove all HTML tags from a string
function clean($variable){
  $clean = trim($variable);
  $clean = strtolower($clean);
  $clean = filter_var($clean, FILTER_SANITIZE_STRING);
  return $clean;
}

//Remove all illegal characters from an email address:
function cleanEmail($variable){
  $clean = trim($variable);
  $clean = strtolower($clean);
  $clean = filter_var($clean, FILTER_SANITIZE_EMAIL);
  return $clean;
}

//removes all illegal URL characters from a string
function cleanUrl($variable){
  $clean = trim($variable);
  $clean = strtolower($clean);
  $clean = filter_var($clean, FILTER_SANITIZE_URL);
  return $clean;
}

// PARAM input value | gender[male/female]
function selectedGender($value,$gender){
  $final = '';
  if(strtolower($value) === strtolower($gender)){
    $final = 'selected';
  }
  return $final;
}
