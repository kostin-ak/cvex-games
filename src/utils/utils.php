<?php

    function get_time_delta(string $time){
        $date1 = new DateTime($time);
        $date2 = new DateTime();

        $interval = $date1->diff($date2);

        return $interval;

    }

    function number($n, $titles) {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
    }

    function time_interval_to_string($interval){
        if($interval->y != 0){
            return $interval->y." ".number($interval->y, array('год', 'года', "лет"));
        }
        return $interval->days." ".number($interval->days, array('день', 'дня', 'дней'));
    }