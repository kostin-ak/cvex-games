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

    function printArrayFormatted($array) {
        // Преобразуем массив в строку с нужным форматом
        $formattedArray = array_map(function($item) {
            // Если элемент является строкой, добавляем кавычки
            return is_string($item) ? '"' . $item . '"' : $item;
        }, $array);

        $formattedString = '[' . implode(', ', $formattedArray) . ']';
        echo $formattedString;
    }

    function _group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
    function group_by_date($array) {
        foreach($array as $kay => $val) {
            $date = strtotime($val['date']);
            $date = date("d.m.Y",$date);
            $array[$kay]['date_wot'] = $date;
        }

        return _group_by($array, 'date_wot');
    }