<?php
class Ia_Date extends DateTime{
    
    public function __construct($time){
        $this->setTimezone(new DateTimeZone(date_default_timezone_get()));
        parent::__construct($time);        
    }
    
    public static function createFromFormat($format, $time=null, $timezone=null){
        if($timezone){
             return \DateTime::createFromFormat($format, $time, $timezone);
        } else {
            $endDateObj = \DateTime::createFromFormat($format, $time);//, $timezone);
            $endDateObj->setTimezone(new DateTimeZone(date_default_timezone_get()));    
            return $endDateObj;
        }
    }
    
}