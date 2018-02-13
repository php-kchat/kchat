<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class guestlist extends action{
	function action(){
		$users = array();
		
		if(isset($this->data['param'])){
			$hsdjhd = explode(',',base64_decode(urldecode($this->data['param'][0])));
			$offset = $hsdjhd[0];
			$limit  = $hsdjhd[1];
		}else{
			$offset = 0;
			$limit = 10;
		}
		
		$stmt = $this->data['pdo']->prepare("SELECT u.id,u.fname,u.lname,u.uname,g.ip,g.country_code,g.time_zone,g.latitude,g.longitude,u.ctime FROM {$this->dbprefix}users u join {$this->dbprefix}guest g WHERE u.role = 3 and u.id = g.id limit :limit offset :offset;");
		$stmt->execute(array('limit' => $limit,'offset' => $offset));
		while ($row = $stmt->fetch())
		{
			$users[] = array(
				'table' => true,
				'id' => $row['id'],
				'fname' => $row['fname'],
				'lname' => $row['lname'],
				'uname' => $row['uname'],
				'ip' => $row['ip'],
				'country_code' => $row['country_code'],
				'time_zone' => $row['time_zone'],
				'latitude' => $row['latitude'],
				'longitude' => $row['longitude'],
				'date' => $row['ctime'],
			);
		}
		
		
		$stmt = $this->data['pdo']->query("SELECT count(uname) as userno FROM {$this->dbprefix}users WHERE `role` = 3 ");
		$row = $stmt->fetch();
		$users['no'] = $row['userno'];
		
		$offset1 = $offset;
		$offset2 = $offset + $limit;
				
		$oset1 = $offset1 - $limit;
		if($oset1 < 0){
			$oset1 = 0;
		}
		
		$oset2 = $offset2;
		if($oset2 > ($users['no'] - $limit)){
			$oset2 = $users['no'] - $limit;
		}
		
		$users['prev'] = urlencode(base64_encode($oset1.','.$limit));
		$users['post']   = urlencode(base64_encode($oset2.','.$limit));
		$users['start']   = $offset1+1;
		$users['end']   = $offset2;
		
		return $users;
	}
}