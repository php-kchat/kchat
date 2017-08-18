<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class userslist extends action{
	function action($data){
		$users = array();
		
		if(isset($data['param'])){
			$hsdjhd = explode(',',base64_decode(urldecode($data['param'][0])));
			$offset = $hsdjhd[0];
			$limit  = $hsdjhd[1];
		}else{
			$offset = 0;
			$limit = 10;
		}
		
		$stmt = $data['pdo']->prepare("SELECT (select `dept` from {$this->dbprefix}department where id = role) as role,id,fname,lname,uname,ctime FROM {$this->dbprefix}users WHERE `role` != 3 limit :limit offset :offset");
		$stmt->execute(array('limit' => $limit,'offset' => $offset));
		while ($row = $stmt->fetch())
		{
			$users[] = array(
				'table' => true,
				'role' => $row['role'],
				'id' => $row['id'],
				'fname' => $row['fname'],
				'lname' => $row['lname'],
				'uname' => $row['uname'],
				'date' => $row['ctime'],
			);
		}
		
		
		$stmt = $data['pdo']->query("SELECT count(uname) as userno FROM {$this->dbprefix}users WHERE `role` != 3 ");
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