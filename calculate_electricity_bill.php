<?php
/* Task
Write a PHP program to calculate electricity bill .
Conditions:
For first 50 units – 3.50/unit
For next 100 units – 4.00/unit
For units above 150 – 6.50/unit
You can use conditional statements.
*/



  $unit = 10;

  if($unit <= 50){
    $unit *= 3.50;
    echo "Cost per unit is <b>3.50/unit</b>,<br>Total Bill Is <b>$unit</b> LE";
  }
  elseif($unit > 50 && $unit <= 150){
    //first 50 will cost 3.50/unit
    $phaseOneUnits = 50;
    //rest units will cost 4.00/unit
    $phaseTwoUnits = $unit - $phaseOneUnits;
    
    $totalPhaseOneUnits = $phaseOneUnits * 3.50;
    $totalPhaseTwoUnits = $phaseTwoUnits * 4;
    //final cost
    $unit = $totalPhaseOneUnits + $totalPhaseTwoUnits;
    echo "
    - Cost of  $phaseOneUnits units: <b>[$phaseOneUnits X 3.50/unit]</b><br>
    - Cost of  $phaseTwoUnits units: <b>[$phaseTwoUnits X 4/unit]</b><br>
     Total Bill Is $unit LE
    ";

  }else{
    // 50 units will cost 3.50/unit
    $phaseOneUnits = 50;
    $phaseTwoUnits = $unit - $phaseOneUnits;
    // above 150 units will cost 6.50/unit
    $phaseThreeUnits = $phaseTwoUnits - 100;
    // 100 units will cost 4/unit
    $phaseTwoUnits -= $phaseThreeUnits;

    $totalPhaseOneUnits   = $phaseOneUnits * 3.50;
    $totalPhaseTwoUnits   = $phaseTwoUnits * 4;
    $totalPhaseThreeUnits = $phaseThreeUnits * 6.50;
    // final cost
    $unit = $totalPhaseOneUnits + $totalPhaseTwoUnits + $totalPhaseThreeUnits;
    echo "
    - Cost of  $phaseOneUnits units:<b>[$phaseOneUnits X 3.50/unit]</b><br>
    - Cost of  $phaseTwoUnits units: <b>[$phaseTwoUnits X 4/unit]</b><br>
    - Cost of  $phaseThreeUnits units:<b>[$phaseThreeUnits X 6.50/unit]</b><br>
     Total Bill Is $unit LE
    ";
  }