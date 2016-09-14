<?php

class CalendarDay{
	public $year;
	public $month;
	public $day;
	public $timeStamp;
	
	public function __construct($year, $month, $day){
		$this->year = $year;
		$this->month = $month;
		$this->day = $day;
		
		$this->timeStamp = strtotime($year.'-'.$month.'-'.$day.' 00:00:00');
	}
}

class Calendar{

	protected $weeks;
	
	protected $startDate;
	protected $endDate;
	
	public function __construct($startDate, $endDate, $dayClassName='CalendarDay'){
		$this->startDate = $startDate;
		$this->endDate = $endDate;
		$startDayTimeStamp = strtotime($startDate);
		$endDayTimeStamp = strtotime($endDate);
		$firstDayIndex = date('N', $startDayTimeStamp);
		
		$firstCalendarDayTimeStamp = strtotime('-'.($firstDayIndex-1).' day', $startDayTimeStamp);
		
		$daysInCalendar = (($endDayTimeStamp-$firstCalendarDayTimeStamp)/60/60/24);
		
		$this->weeks = array();
		
		$weekIndex = 0;
		for($row=0; $row<ceil($daysInCalendar/7); $row++){
			for($day=0; $day<7; $day++){
				$actualTimeStamp = strtotime('+'.$row*7+$day.' day', $firstCalendarDayTimeStamp);
				$actualYear = date('Y', $actualTimeStamp);
				$actualMonth = date('n', $actualTimeStamp);
				$actualDay = date('j', $actualTimeStamp);
				if($day == 0){
					$weekIndex++;
				}
				$this->weeks[$weekIndex] []= new $dayClassName($actualYear, $actualMonth, $actualDay);
			}
		}
	}
	
	public function getData(){
		return $this->weeks;
	}
}

?>
