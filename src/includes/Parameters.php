<?php

//Godzina otwarcia gabinetów
define('OPENING_TIME','7:00');
$officeParameters['openingTime'] = date_create('7:00');

//Godzina zamkniêcia gabinetów
define('CLOSING_TIME','21:00');
$officeParameters['closingTime'] = date_create('21:00');

//Czas wizyty
define('VISIT_DURATION', '20 minutes');
$officeParameters['visitDuration'] = '20 minutes';

//Czas przerwy miêdzy dzier¿awami
define('OFFICE_BREAK', '20 minutes');
$officeParameters['officeBreak'] = '20 minutes';

//Minimalny czas zajêcia gabinetu
define('MIN_WORK_DURATION', '2 hours');
$officeParameters['minDurationOfWork'] = '2 hours';

//Maksymalny czas pracy
define('MAX_WORK_DURATION', '8 hours');
$officeParameters['maxDurationOfWork'] = '8 hours';

//Ile lat w przód wy¶wietlanie
define('MAX_DATE', '2 year');
$officeParameters['maxDate'] = '2 year';

