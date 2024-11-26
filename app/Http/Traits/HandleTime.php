<?php

namespace App\Http\Traits;


use Carbon\Carbon;
use DateTime;

trait HandleTime
{
    function isValidTime($time, $format = 'Y-m-d'):bool
    {
        $date = DateTime::createFromFormat($format, $time);
        return $date && $date->format($format) === $time;
       
    }
    function isGreaterDate($from , $to , $format = 'Y-m-d'):bool
    {
        if($from instanceof DateTime){
            $from = $from->format('Y-m-d');
        }else if($from instanceof Carbon){
            $from = $from->format('Y-m-d');
        }
        if($to instanceof DateTime){
            $to = $to->format('Y-m-d');
        }else if($to instanceof Carbon){
            $to = $to->format('Y-m-d');
        }
        if($this->isValidTime($from,$format)){
            return true;
        }
        if(!$this->isValidTime($to,$format)){
            
            return true;
        }
       
        $dateFrom = DateTime::createFromFormat($format,$from);
        $dateTo = DateTime::createFromFormat($format,$to);
        return $dateFrom > $dateTo;
    }
    function oneMonthAgo($format = 'Y-m-d'):DateTime|string
    {
        $date = new DateTime();
        $date->modify('-1 month');
        $formatted = $date->format($format);
        
        if(!$this->isValidTime($formatted,$format)){
           
            return  $date->modify('-1 month')->format('Y-m-d');
        }
        return $formatted;
    } 
    function oneWeekAgo($format = 'Y-m-d'):DateTime|string
    {
        $date = new DateTime();
        $date->modify('-1 week');
        $formatted = $date->format($format);
        if(!$this->isValidTime($formatted,$format)){
           
            return  $date->modify('-1 week')->format('Y-m-d');
        }
        return $formatted;
    }
    function oneDayAgo($format = 'Y-m-d'):DateTime|string
    {
        $date = new DateTime();
        $date->modify('-1 day');
        $formatted = $date->format($format);
        if(!$this->isValidTime($formatted,$format)){
            return  $date->modify('-1 day')->format('Y-m-d');
        }
        return $formatted;
    }
    function now($format = 'Y-m-d'):DateTime|string
    {
        $date = new DateTime();
        $formatted = $date->format($format);
        if(!$this->isValidTime($formatted,$format)){
            return  $date->format('Y-m-d');
        }
        return $formatted;
    }
    function isValidYear($year) {
        return is_numeric($year) && (int)$year == $year && $year > 0 && $year <= 9999;
    }
}
