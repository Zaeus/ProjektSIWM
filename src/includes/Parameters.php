<?php

//Godzina otwarcia gabinet�w
define('OPENING_TIME','7:00');
$officeParameters['openingTime'] = date_create(OPENING_TIME);

//Godzina zamkni�cia gabinet�w
define('CLOSING_TIME','21:00');
$officeParameters['closingTime'] = date_create(CLOSING_TIME);

//Czas wizyty
define('VISIT_DURATION', '20 minutes');
$officeParameters['visitDuration'] = VISIT_DURATION;

//Czas przerwy mi�dzy dzier�awami
define('OFFICE_BREAK', '20 minutes');
$officeParameters['officeBreak'] = OFFICE_BREAK;

//Minimalny czas zaj�cia gabinetu
define('MIN_WORK_DURATION', '2 hours');
$officeParameters['minDurationOfWork'] = MIN_WORK_DURATION;

//Maksymalny czas pracy
define('MAX_WORK_DURATION', '8 hours');
$officeParameters['maxDurationOfWork'] = MAX_WORK_DURATION;

//Ile lat w prz�d wy�wietlanie
define('MAX_DATE', '2 year');
$officeParameters['maxDate'] = MAX_DATE;

//Ile dni przed wizyt� mo�na sie rejestrowa�
define('DAYS_BEFORE_VISIT_MIN', '1 day');
$officeParameters['daysBeforeVisitMin']= DAYS_BEFORE_VISIT_MIN;

define('DAYS_BEFORE_VISIT_MAX', '8 weeks');
$officeParameters['daysBeforeVisitMax']= DAYS_BEFORE_VISIT_MAX;

//Dost�pne specjalizacje lekarzy
define('USG', 'USG');
define('GINEKOLOGIA', 'Ginekologia');
define('INTERNA', 'Interna');
define('CHIRURGIA', 'Chirurgia');

$specialization[] = USG;
$specialization[] = GINEKOLOGIA;
$specialization[] = INTERNA;
$specialization[] = CHIRURGIA;
