<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class notification extends action{
	function action(){
		
		$users = array();
		
		$offset = 0;
		
		if(isset($this->data['param'][0])){
			$offset = base64_decode($this->data['param'][0]);
		}
		
		$stmt = $this->data['pdo']->prepare("SELECT * FROM `{$this->dbprefix}notification` where user = :user ORDER BY id DESC limit 10 offset :offset;");
		$stmt->execute(array('user' => $this->data['user']['id'],'offset' => $offset));
		while ($row = $stmt->fetch())
		{
			$users[] = array(
				'id' => $row['id'],
				'seen' => $row['seen'],
				'time' => $row['time'],
				'url' => $row['url'],
				'notification' => $row['notification']
			);
		}
		
		return $users;
	}
}