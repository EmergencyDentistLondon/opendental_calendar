<?php

require("Calendar.php");

class Schedule{
	public $doctor;
	public $employee;
	public $startTime;
	public $endTime;
	
	public function __construct($doctor, $employee, $startTime, $endTime){
		$this->doctor = $doctor;
		$this->employee = $employee;
		$this->startTime = $startTime;
		$this->endTime = $endTime;
	}
}

class ScheduleCalendarDay extends CalendarDay{
	public $schedules;
	
	public function __construct($year, $month, $day){
		parent::__construct($year, $month, $day);
		$this->schedules = array();
	}
	
	public function addSchedule($schedule){
		$this->schedules []= $schedule;
	}
}

class ScheduleCalendar extends Calendar{
	protected $events;
	
	public function __construct($startDate, $endDate){
		parent::__construct($startDate, $endDate, 'ScheduleCalendarDay');
		
		$table = new DBTable('schedule');
		$select = $table->select()->from($table, array('date'=>'SchedDate', 'starttime'=>'StartTime', 'endtime'=>'StopTime'))->setIntegrityCheck(false);
		$select->joinLeft('provider', 'schedule.ProvNum = provider.ProvNum', array('doctor'=>'provider.Abbr'));
		$select->joinLeft('employee', 'schedule.EmployeeNum = employee.EmployeeNum', array('employee'=>'employee.FName'));
		$select->where('SchedDate >= ?', $startDate);
		$select->where('SchedDate <= ?', $endDate);
		$select->order('SchedDate ASC');
		$select->order('StartTime ASC');
		$rows = $table->fetchAll($select);
		
		foreach($this->weeks as $week){
			foreach($week as $day){
				foreach($rows as $row){
					if(sprintf('%02d-%02d-%02d', $day->year, $day->month, $day->day) == $row->date){
						$day->addSchedule(new Schedule($row->doctor, $row->employee, $row->starttime, $row->endtime));
					}
				}
			}
		}
	}
	
	public function getData(){
		return $this->weeks;
	}
}

?>
