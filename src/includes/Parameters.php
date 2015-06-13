<?php

//Godzina otwarcia gabinetów
define('OPENING_TIME','7:00');
$officeParameters['openingTime'] = date_create('7:00');

//Godzina zamkniêcia gabinetów
define('CLOSING_TIME','21:00');
$officeParameters['closingTime'] = date_create('21:00');

//Czas wizyty
define('VISIT_DURATION', '30 minutes');
$officeParameters['visitDuration'] = '30 minutes';

//Minimalny czas zajêcia gabinetu
define('MIN_WORK_DURATION', '2 hours');
$officeParameters['minDurationOfWork'] = '2 hours';

//Maksymalny czas pracy
define('MAX_WORK_DURATION', '8 hours');
$officeParameters['maxDurationOfWork'] = '8 hours';
