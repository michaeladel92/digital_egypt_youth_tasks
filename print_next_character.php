<?php
/*
TASK
Write a PHP function to print the next character of a specific character.
input : 'a'
Output : 'b'
input : 'z'
Output : 'a'

*/ 

function nextCharacter($letter){
  $alphabet = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
  $letter = strtolower($letter);

  if(in_array($letter,$alphabet)){
      $lastChar = count($alphabet) - 1;
      $key = array_search($letter,$alphabet);
       if($key === $lastChar){
        echo $alphabet[0];
       }else{
          $nextChar = $key + 1;
          echo $alphabet[$nextChar];
        }
  }else{
    echo "character not found please write 1 letter only from a-z";
  }
}

nextCharacter('m');