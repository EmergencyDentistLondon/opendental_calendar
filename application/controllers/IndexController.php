<?php

class IndexController extends Zend_Controller_Action
{
	public function indexAction(){
		$request = $this->getRequest();
		
		if($request->isPost()){
			$this->view->year = $request->getParam('year');
			$this->view->month = $request->getParam('month');
			$this->view->day = $request->getParam('day');
		}
		else{
			$this->view->year = date('Y');
			$this->view->month = date('m');
			$this->view->day = date('d');
		}
	
		$table = new DBTable('operatory');
		$select = $table->select()->from($table, array('id'=>'OperatoryNum', 'title'=>'OpName'));
		$this->view->operatories = $table->fetchAll($select);
		
		foreach($this->view->operatories as $operatory){
			$appointments[$operatory->id] = array();
		}
	
		$table = new DBTable('appointment');
		
		$select = $table->select()->from($table, array('id'=>'AptNum', 'time'=>'AptDateTime', 'note'=>'Note', 'operatoryid'=>'Op', 'hour'=>'HOUR(AptDateTime)', 'minute'=>'MINUTE(AptDateTime)'))->setIntegrityCheck(false);
		$select->joinLeft('patient', 'patient.PatNum = appointment.PatNum', array('firstname'=>"patient.FName", 'lastname'=>'patient.LName'));
		$select->joinLeft('provider', 'provider.ProvNum = appointment.ProvNum', array('doctor'=>"provider.Abbr"));
		$select->joinLeft('operatory', 'operatory.OperatoryNum = appointment.Op', array('operatory'=>"operatory.OpName"));
		$select->where('appointment.AptStatus IN ( 1, 2, 4, 5, 7, 8 )');
		$select->where('appointment.AptDateTime >= ?', $this->view->year.'-'.$this->view->month.'-'.$this->view->day);
		$select->where('appointment.AptDateTime <= ?', $this->view->year.'-'.$this->view->month.'-'.$this->view->day.' 23:59:59');
		$select->group('appointment.AptNum');
		$select->order('appointment.AptDateTime ASC');
		
		$rows = $table->fetchAll($select);
		
		foreach($rows as $appointment){
			if(!isset($appointments[$appointment->operatoryid][$appointment->hour])){
				$appointments[$appointment->operatoryid][$appointment->hour] = array();
			}
			$appointments[$appointment->operatoryid][$appointment->hour] []= $appointment;
		}
		
		
		$this->view->appointments = $appointments;
	
		$this->view->title = "Kezelések";
		
		$this->view->minHour = 6;
		$this->view->maxHour = 22;
		
		$role = Zend_Auth::getInstance()->getIdentity()->role;
		if($role !== 'admin' && $role !== 'superadmin'){
			$this->_helper->viewRenderer->setRender('index-guest');
		}
	}
	
	public function md5bin( $target ) {
		$md5 = md5( $target );
		$ret = '';

		for ( $i = 0; $i < 32; $i += 2 ) {
			$ret .= chr( hexdec( $md5{ $i + 1 } ) + hexdec( $md5{ $i } ) * 16 );
		}

		return $ret;
	}
	
	public function md5Action(){
		$this->_helper->viewRenderer->setNoRender();
		
		$str = 'sali108';
		$digeststr = md5($str);
		$digestbytes = md5($str, TRUE);
		
		$lofasz = base64_decode('KgCs5Vt/gHikBq7TL5PLCg==');
		
		//$digestbytes = $this->md5bin(md5('sali108'));
		
		for($i = 0; $i < strlen($digestbytes); $i++){
			echo(sprintf("%02x - %02x<br />", ord($digestbytes[$i]), ord($lofasz[$i])));
		}
		
		var_dump(base64_encode($digestbytes));
		var_dump(mb_convert_encoding($digestbytes, "BASE64"));
	}
}

?>
