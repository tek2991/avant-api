<?php

if (!function_exists("getGradeForMark")) {

    function getGradeForMark($score)
    {
        if ($score > 90)  return "A1";
        elseif ($score > 80)  return "A2";
        elseif ($score > 70)  return "B1";
        elseif ($score > 60)  return "B2";
        elseif ($score > 50)  return "C1";
        elseif ($score > 40)  return "C2";
        elseif ($score > 32)  return "D";
        elseif ($score > 20)  return "E1";
        else                   return "E2";
    }
}
