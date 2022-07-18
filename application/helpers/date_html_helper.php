<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    function day_dropdown($name='',$value='')
    {
        $days='1';
        $day['0'] = 'Día'; 
        while ( $days <= '31'){
            $day[]=$days;$days++;
        }
        return form_dropdown($name, $day, $value);
    }
    
    function year_dropdown($name='',$value='',$range_inf='',$range_sup='')
    {
    	if ($value == '') {
			$value = date ( "Y" );
		}
		if ($range_inf == '') {
			$range_inf = date ( "Y" ) - 85;
		}
		if ($range_sup == '') {
			$range_sup = date ( "Y" );
		}
		
		$years = range ( $range_inf, $range_sup );
		$year_list ['0'] = 'Año';
		foreach ( $years as $year ) {
			$year_list [$year] = $year;
		}
		return form_dropdown ( $name, $year_list, $value );
    }
    
    function month_dropdown($name='',$value='')
    {
        $month=array(
        	'0'=>'Mes',
            '01'=>'Enero',
            '02'=>'Febrero',
            '03'=>'Marzo',
            '04'=>'Abril',
            '05'=>'Mayo',
            '06'=>'Junio',
            '07'=>'Julio',
            '08'=>'Agosto',
            '09'=>'Septiembre',
            '10'=>'Octubre',
            '11'=>'Noviembre',
            '12'=>'Diciembre');
        return form_dropdown($name, $month, $value);
    }