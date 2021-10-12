<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class userslist extends action{
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
		
		$stmt = $this->data['pdo']->prepare("SELECT (select `dept` from {$this->dbprefix}department where id = role) as role,id,fname,lname,uname,ctime FROM {$this->dbprefix}users WHERE `role` != 3 limit :l_imit offset :o_ffset;");
		$stmt->execute(array('l_imit' => $limit,'o_ffset' => $offset));
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
		
		
		$stmt = $this->data['pdo']->query("SELECT count(uname) as userno FROM {$this->dbprefix}users WHERE `role` != 3 ");
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