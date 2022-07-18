<?php

$fecha_inicio_day = '0';//date ( 'd' );
$fecha_inicio_month = '0';//date ( 'm' );
$fecha_inicio_year = '0';//date ( 'Y' );

echo day_dropdown('fecha_inicio_day',$fecha_inicio_day).month_dropdown('fecha_inicio_month',$fecha_inicio_month).year_dropdown('fecha_inicio_year',$fecha_inicio_year,2010,date('Y')+10);?>