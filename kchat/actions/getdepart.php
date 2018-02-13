<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class getdepart extends action{
	
	function action(){
		$dept = array();
				
		$stmt = $this->data['pdo']->prepare("SELECT * FROM `{$this->dbprefix}department`;");
		$stmt->execute(array());
		while ($row = $stmt->fetch())
		{
			if(strtolower($row['dept']) == 'admin'){
				continue;
			}
			$dept[] = array(
				'id' => $row['id'],
				'dept' => $row['dept'],
				'discription' => $row['discription']
			);
		}
		return $dept;
	}
}