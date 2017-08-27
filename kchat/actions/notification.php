<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class notification extends action{
	function action($data){
		
		$users = array();
		
		$stmt = $data['pdo']->prepare("SELECT * FROM `{$this->dbprefix}notification` where user = :user ORDER BY id DESC limit 10;");
		$stmt->execute(array('user' => $data['user']['id']));
		while ($row = $stmt->fetch())
		{
			$users[] = array(
				'seen' => $row['seen'],
				'time' => $row['time'],
				'url' => $row['url'],
				'notification' => $row['notification']
			);
		}
		
		return $users;
	}
}