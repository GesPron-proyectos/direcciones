<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class EMP_Profiler extends CI_Profiler {

   function EMP_Profiler()
   {
       $this->__construct ();
   }

   function _compile_queries()
	{
		
		$dbs = array();

		// Let's determine which databases are currently connected to
		foreach (get_object_vars($this->CI) as $CI_object)
		{
			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
			{
				$dbs[] = $CI_object;
			}
		}

		if (count($dbs) == 0)
		{
			$output  = "\n\n";
			$output .= '<fieldset id="ci_profiler_queries" style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
			$output .= "\n";
			$output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_queries').'&nbsp;&nbsp;</legend>';
			$output .= "\n";
			$output .= "\n\n<table style='border:none; width:100%'>\n";
			$output .="<tr><td style='width:100%;color:#0000FF;font-weight:normal;background-color:#eee;padding:5px'>".$this->CI->lang->line('profiler_no_db')."</td></tr>\n";
			$output .= "</table>\n";
			$output .= "</fieldset>";

			return $output;
		}

		// Load the text helper so we can highlight the SQL
		$this->CI->load->helper('text');

		// Key words we want bolded
		$highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

		$output  = "\n\n";

		foreach ($dbs as $db)
		{
			$output .= '<fieldset style="border:1px solid #0000FF;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
			$output .= "\n";
			$output .= '<legend style="color:#0000FF;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_database').':&nbsp; '.$db->database.'&nbsp;&nbsp;&nbsp;'.$this->CI->lang->line('profiler_queries').': '.count($db->queries).'&nbsp;&nbsp;&nbsp;</legend>';
			$output .= "\n";
			$output .= "\n\n<table style='width:100%;'>\n";

			if (count($db->queries) == 0)
			{
				$output .= "<tr><td style='width:100%;color:#0000FF;font-weight:normal;background-color:#eee;padding:5px;'>".$this->CI->lang->line('profiler_no_queries')."</td></tr>\n";
			}
			else
			{
				foreach ($db->queries as $key => $val)
				{
					$time = number_format($db->query_times[$key], 4);
					$explain = $val;
					$val = highlight_code($val, ENT_QUOTES);

					foreach ($highlight as $bold)
					{
						$val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);
					}
					//write_file('./log.txt', $val,"r+");
					$output .= "<tr><td style='padding:5px; vertical-align: top;width:1%;color:#900;font-weight:normal;background-color:#ddd;'>".$time."&nbsp;&nbsp;</td><td style='padding:5px; color:#000;font-weight:normal;background-color:#ddd;'>".$val."</td></tr>\n";
				
				
				if (strpos($explain, 'SELECT') !== false) {
				    $r = $this->CI->db->query('EXPLAIN ' . $explain);
				    $r = $r->result_array();
				    
				    $output .= "<tr><td width='1%' style='font-weight:normal;'>&nbsp;</td><td style='color:#000;font-weight:normal;'>";
				    
				    foreach ($r as $explain)
				    {
				        $output .= "<table cellpadding='3' width='100%' style='border-top: 1px solid #ddd;margin-bottom: 10px'>";
				        next($explain); // pop id off, because who cares?
				        while (list($key, $val) = each($explain))
				        {
				            if (!empty($val)) // only display non-empty elements
				            {
				                $output .= "<tr><td style='color:#990000;' width='100'>" . $key . "</td><td>" . $val . "</td></tr>";
				            }
				        }
				        $output .= "</table>\n";
				    }
				    
				    $output .= "</td></tr>\n";
				}
				
				}
			}

			$output .= "</table>\n";
			$output .= "</fieldset>";
		}
		
		return $output;
	}
}  
?>