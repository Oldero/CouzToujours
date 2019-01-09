<?php
/**
* Calendar Generation Class
*
* This class provides a simple reuasable means to produce month calendars in valid html
*
* @version 2.6
* @author Jim Mayes <jim.mayes@gmail.com>
* @link http://style-vs-substance.com
* @copyright Copyright (c) 2008, Jim Mayes
* @license http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons Attribution-Share Alike 3.0 License

MODIF : in french, highlight events.
*/

class Calendar{
	var $date;
	var $year;
	var $month;
	var $day;
	
	var $week_start_on = FALSE;
	var $week_start = 1;// monday
	
	var $link_days = FALSE;
	var $link_to;
	var $formatted_link_to;
	
	var $mark_today = TRUE;
	var $today_date_class = 'today';
	
	var $mark_selected = TRUE;
	var $selected_date_class = 'selected';
	
	var $mark_passed = TRUE;
	var $passed_date_class = 'passed';

	//événements
	//tableaux 2-dimension d'infobulles -> tableau d'événements;
	var $info_private;
	var $info_official;
	var $info_normal;
	//classe de case
	//event privatisé
	var $privatised_class = 'privatised';
	//event officiel de l'asso
	var $official_class = 'official';	
	//event normal, nuances de rouge
	var $event_04_class = 'red_light';
	var $event_48_class = 'red_medium';
	var $event_8plus_class = 'red_dark';
	
	
	/* CONSTRUCTOR */
	function Calendar($date = NULL, $year = NULL, $month = NULL){
		setlocale(LC_TIME, "fr_FR");
		$self = htmlspecialchars($_SERVER['PHP_SELF']);
		$this->link_to = $self;
		
		if( is_null($year) || is_null($month) ){
			if( !is_null($date) ){
				//-------- strtotime the submitted date to ensure correct format
				$this->date = date("Y-m-d", strtotime($date));
			} else {
				//-------------------------- no date submitted, use today's date
				$this->date = date("Y-m-d");
			}
			$this->set_date_parts_from_date($this->date);
		} else {
			$this->year		= $year;
			$this->month	= str_pad($month, 2, '0', STR_PAD_LEFT);
		}	
	}
	
	function set_date_parts_from_date($date){
		$this->year		= date("Y", strtotime($date));
		$this->month	= date("m", strtotime($date));
		$this->day		= date("d", strtotime($date));
	}
	
	function output_calendar($year = NULL, $month = NULL, $calendar_class = 'calendar'){
		setlocale(LC_TIME, "fr_FR");
		if( $this->week_start_on !== FALSE ){
			echo "The property week_start_on is replaced due to a bug present in version before 2.6. of this class! Use the property week_start instead!";
			exit;
		}
		
		//--------------------- override class methods if values passed directly
		$year = ( is_null($year) )? $this->year : $year;
		$month = ( is_null($month) )? $this->month : str_pad($month, 2, '0', STR_PAD_LEFT);
		//--------------------------------- extraction des tableaux d'événements
		//------------------------------------ privatisé + officiel + normal * 3
		$privatised_event = array_column($this->info_private, 0);
		$official_event = array_column($this->info_official, 0);
		if (is_array($this->info_normal)) {
		$top = sizeof($this->info_normal);
    	$bottom = 0;
    	//---------------------------------- répartition dans les trois tableaux
     	while($bottom <= $top){
	        if ($this->info_normal[$bottom][1] <=4) {
	            $event_04[] = $this->info_normal[$bottom][0];
	        }
	        elseif ($this->info_normal[$bottom][1] <= 8 ) {
	            $event_48[] = $this->info_normal[$bottom][0];
	        }
	        else {
	            $event_8plus[] = $this->info_normal[$bottom][0];
	        }
	        $bottom++;
	    }
		}
		//------------------------------------------- create first date of month
		$month_start_date = strtotime($year . "-" . $month . "-01");
		//------------------------- first day of month falls on what day of week
		$first_day_falls_on = date("N", $month_start_date);
		//----------------------------------------- find number of days in month
		$days_in_month = date("t", $month_start_date);
		//-------------------------------------------- create last date of month
		$month_end_date = strtotime($year . "-" . $month . "-" . $days_in_month);
		//----------------------- calc offset to find number of cells to prepend
		$start_week_offset = $first_day_falls_on - $this->week_start;
		$prepend = ( $start_week_offset < 0 )? 7 - abs($start_week_offset) : $first_day_falls_on - $this->week_start;
		//-------------------------- last day of month falls on what day of week
		$last_day_falls_on = date("N", $month_end_date);

		//------------------------------------------------- start table, caption
		//----------------------------------------------------------noms de mois
		$output  = "<table class=\"" . $calendar_class . "\">\n";
		$output .= "<caption>" . ucfirst(strftime("%B %Y", $month_start_date)) . "</caption>\n";
		
		$col = '';
		$th = '';
		//----------------------------------------------------------noms de jours
		for( $i=1,$j=$this->week_start,$t=(3+$this->week_start)*86400; $i<=7; $i++,$j++,$t+=86400 ){
			$localized_day_name = gmstrftime('%A',$t);
			$col .= "<col class=\"" . strtolower($localized_day_name) ."\" />\n";
			$th .= "\t<th title=\"" . "ucfirst($localized_day_name)" ."\">" . strtoupper($localized_day_name{0}) ."</th>\n";
			$j = ( $j == 7 )? 0 : $j;
		}
		
		//------------------------------------------------------- markup columns
		$output .= $col;
		
		//----------------------------------------------------------- table head
		$output .= "<thead>\n";
		$output .= "<tr>\n";
		
		$output .= $th;
		
		$output .= "</tr>\n";
		$output .= "</thead>\n";
		
		//---------------------------------------------------------- start tbody
		$output .= "<tbody>\n";
		$output .= "<tr>\n";
		
		//---------------------------------------------- initialize week counter
		$weeks = 1;
		
		//--------------------------------------------------- pad start of month
		
		//------------------------------------ adjust for week start on saturday
		for($i=1;$i<=$prepend;$i++){
			$output .= "\t<td class=\"pad\">&nbsp;</td>\n";
		}
		
		//--------------------------------------------------- loop days of month
		for($day=1,$cell=$prepend+1; $day<=$days_in_month; $day++,$cell++){
			/*if this is first cell and not also the first day, end previous row*/
			if( $cell == 1 && $day != 1 ){
				$output .= "<tr>\n";
			}
			
			//-------------- zero pad day and create date string for comparisons
			$day = str_pad($day, 2, '0', STR_PAD_LEFT);
			$day_date = $year . "-" . $month . "-" . $day;
			
			//-------------------------- compare day and add classes for matches
			if( $this->mark_today == TRUE && $day_date == date("Y-m-d") ){
				$classes[] = $this->today_date_class;
			}
			
			if( $this->mark_selected == TRUE && $day_date == $this->date ){
				$classes[] = $this->selected_date_class;
			}
			
			if( $this->mark_passed == TRUE && $day_date < date("Y-m-d") ){
				$classes[] = $this->passed_date_class;
			}
			//Changed here to add five colors of events
			if( is_array($privatised_event) ){
				if( in_array($day_date, $privatised_event) ){
					$classes[] = $this->privatised_class;
				}
			}
			if( is_array($official_event) ){
				if( in_array($day_date, $official_event) ){
					$classes[] = $this->official_class;
				}
			}
			if( is_array($event_04) ){
				if( in_array($day_date, $event_04) ){
					$classes[] = $this->event_04_class;
				}
			}
			if( is_array($event_48) ){
				if( in_array($day_date, $event_48) ){
					$classes[] = $this->event_48_class;
				}
			}
			if( is_array($event_8plus) ){
				if( in_array($day_date, $event_8plus) ){
					$classes[] = $this->event_8plus_class;
				}
			}
			
			//----------------- loop matching class conditions, format as string
			if( isset($classes) ){
				$day_class = ' class="';
				foreach( $classes AS $value ){
					$day_class .= $value . " ";
				}
				$day_class = substr($day_class, 0, -1) . '"';
			} else {
				$day_class = '';
			}
			
			//---------------------------------- start table cell, apply classes
			//---------------------------------- avec l'infobulle !
			$jour_compare = $year . "-" . $month ."-" . $day;
			$infobulle = strftime("%A %e %B %Y", strtotime($day_date));
			$infobulle .= " \n";
			if (is_array($this->info_private)) {
				if (in_array($jour_compare, array_column($this->info_private,0))) {
					$key = array_search($jour_compare, array_column($this->info_private, 0));
					$infobulle .= "séjour privatisé \n" . $this->info_private[$key][2];
				}
			}
			if (is_array($this->info_official)) {
				if (in_array($jour_compare, array_column($this->info_official,0))) {
					$key = array_search($jour_compare, array_column($this->info_official, 0));
					$infobulle .= $this->info_official[$key][2];
				}
			}
			if (is_array($this->info_normal)) {
				if (in_array($jour_compare, array_column($this->info_normal,0))) {
					$key = array_search($jour_compare, array_column($this->info_normal, 0));
					$infobulle .= $this->info_normal[$key][1] . " personnes \n" . $this->info_normal[$key][2];
				}
			}
			$output .= "\t<td" . $day_class . " title=\"" . $infobulle . "\">";
			
			//----------------------------------------- unset to keep loop clean
			unset($day_class, $classes);
			
			//-------------------------------------- conditional, start link tag 
			//-------------------------------------- A TRAVAILLER AVEC LES LIENS link_days = TRUE
			switch( $this->link_days ){
				case 0 :
					$output .= $day;
					//Avec infobulle :
					//$output .= '<span class="infobulle" aria-label ="' . $infobulle . '">' . $day . '<span>';
				break;
				
				case 1 :
					if( empty($this->formatted_link_to) ){
						$output .= "<a href=\"" . $this->link_to . "?date=" . $day_date . "\">" . $day . "</a>";
					} else {
						$output .= "<a href=\"" . strftime($this->formatted_link_to, strtotime($day_date)) . "\">" . $day . "</a>";
					}
				break;
				
				case 2 :
					if( is_array($this->privatised_event) ){
						if( in_array($day_date, $this->privatised_event) ){
							if( empty($this->formatted_link_to) ){
								$output .= "<a href=\"" . $this->link_to . "?date=" . $day_date . "\">";
							} else {
								$output .= "<a href=\"" . strftime($this->formatted_link_to, strtotime($day_date)) . "\">";
							}
						}
					}
					
					$output .= $day;
					
					if( is_array($this->privatised_event) ){
						if( in_array($day_date, $this->privatised_event) ){
							if( empty($this->formatted_link_to) ){
								$output .= "</a>";
							} else {
								$output .= "</a>";
							}
						}
					}
				break;
			}
			
			//------------------------------------------------- close table cell
			$output .= "</td>\n";
			
			//------- if this is the last cell, end the row and reset cell count
			if( $cell == 7 ){
				$output .= "</tr>\n";
				$cell = 0;
			}
			
		}
		
		//----------------------------------------------------- pad end of month
		if( $cell > 1 ){
			for($i=$cell;$i<=7;$i++){
				$output .= "\t<td class=\"pad\">&nbsp;</td>\n";
			}
			$output .= "</tr>\n";
		}
		
		//--------------------------------------------- close last row and table
		$output .= "</tbody>\n";
		$output .= "</table>\n";
		
		//--------------------------------------------------------------- return
		return $output;
		
	}
	
}
?>