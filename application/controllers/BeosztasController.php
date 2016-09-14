<?php

class BeosztasController extends Zend_Controller_Action
{
	public function indexAction(){
		$request = $this->getRequest();
		
		if($request->isPost()){
			$this->view->startyear = $request->getParam('startyear');
			$this->view->startmonth = $request->getParam('startmonth');
			$this->view->startday = $request->getParam('startday');
			$this->view->endyear = $request->getParam('endyear');
			$this->view->endmonth = $request->getParam('endmonth');
			$this->view->endday = $request->getParam('endday');
		}
		else{
			$this->view->startyear = date('Y');
			$this->view->startmonth = date('m');
			$this->view->startday = 1;
			$this->view->endyear = date('Y')+1;
			$this->view->endmonth = date('m');
			$this->view->endday = 1;
		}
		
		$startDate = $this->view->startyear.'-'.$this->view->startmonth.'-'.$this->view->startday;
		$endDate = $this->view->endyear.'-'.$this->view->endmonth.'-'.$this->view->endday;
	
		$this->view->calendar = new ScheduleCalendar($startDate, $endDate);
		
		$this->view->monthAbbreviations = array(
			1 => 'jan',
			'feb',
			'mar',
			'apr',
			'may',
			'jun',
			'jul',
			'aug',
			'sep',
			'okt',
			'nov',
			'dec'
		);
		
		$this->view->title = "Beosztások";
	}
}

?>
