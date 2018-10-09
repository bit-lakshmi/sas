<?php
function biss_hours($start, $end){

    $startDate = new DateTime($start);
    $endDate = new DateTime($end);
    $periodInterval = new DateInterval( "PT1H" );

    $period = new DatePeriod( $startDate, $periodInterval, $endDate );
    $count = 0;

    foreach($period as $date){

    $startofday = clone $date;
    $startofday->setTime(11,00);

    $endofday = clone $date;
    $endofday->setTime(20,30);

        if($date > $startofday && $date <= $endofday && !in_array($date->format('l'), array('Sunday','Saturday'))){

            $count++;
        }

    }
    echo $count;
}
