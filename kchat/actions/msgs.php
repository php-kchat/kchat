<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class msgs extends action{
		
	var $qfired = 0;
	
	function action($data){
			
			if(isset($_POST['msg'])){
				$chats = $this->msg_insert($data,$_POST['msg']);
			}
			
			$this->lastaccess($data);
			
			if(isReq('kchatchats')){
				$message = $this->message($data);
			}
			if(isReq('messages')){
				$chats = $this->chats($data);
			}
			if(isReq('online')){
				$online = $this->getstatus($data);
			}
			if(isReq('unread')){
				$unread = $this->unread($data);
			}
			$output = array();
			if(!empty($message)){
				$output["message"] = $message;
			}
			if(!empty($online)){
				$output = array_merge($output,$online);
			}
			if(!empty($chats)){
				$output["chats"] = $chats;
			}
			if(!empty($unread)){
				$output["unread"] = $unread;
			}
			if(!empty($typing)){
				$output["typing"] = $typing;
			}
			if(alertify::is_Ajax_alert()){
				$output["error"] = alertify::get_Ajax_alert();
			}
			
			$output["timestamp"] = time();
			
			$output["rq_time"] = round((microtime(true) - $data['_start']) , 3 );
			
			
			if(isset($_SESSION['offset'])){
				$output["offset"] = $_SESSION['offset'];
			}
			
			//$output["reqps"] = @file_get_contents('cache/reqps.cache');
			
			if(cclear()){
				$sql = "DELETE FROM `{$this->dbprefix}cache`WHERE time < ( UNIX_TIMESTAMP() - 12 );";
				$stmt = $data['pdo']->prepare($sql);
				$this->qfired++;
				$stmt->execute(array());	
			}
			
			$output["qfired"] = $this->qfired;
			
			echo json_encode($output);
		
	}

	function getstatus($data){
		$return = array();
		$output = array();
		$stmt = $data['pdo']->prepare("select * from `{$this->dbprefix}cache` where (`time` > (unix_timestamp() - 5)) and `group` = :group;");
		$this->qfired++;
		$stmt->execute(array(
			'group' => getGroup($data)
		));
		$online = array();
		$session = array();
		while ($row = $stmt->fetch())
		{
			if($row['uname'] != $data['user']['uname']){
				//typing
				if($row['process'] == 3){
					$output["typing"][] = $row['fname'];
				}
				//online
				if($row['process'] == 1){
					$online[] = array(
						'id' => $row['uname'],
						'time' => $row['time'],
						'username' => $row['fname'].' '.$row['lname']
					);
					$session[] = $row['uname'];
				}
			}
		}
		//online
		if(!isset($_SESSION['online'])){
			$_SESSION['online'] = array();//$session;
		}
		
		$add_online = array_diff($session,$_SESSION['online']);
		$rem_online = array_diff($_SESSION['online'],$session);
		
		// add to online list
		foreach($add_online as $key => $value){
			if(isset($online[$key])){
				$output["online"][] = $online[$key];
			}
		}
		
		// remove from online list
		foreach($rem_online as $key => $value){
			$output["offline"][] = $value;
		}
		
	
		$_SESSION['online'] = $session;
		
		//typing
		if(isset($output["typing"])){
			$output["typing"] = array_values($output["typing"]);
			$count = count($output["typing"]);
			switch($count){
				case 0: 
					$out = '';
				break;
				case 1:
					$out = $output["typing"][0].' is typing...';
				break;
				case 2:
					$out = $output["typing"][0].' and '.$output["typing"][1].' are typing...';
				break;
				case 3:
					$out = $output["typing"][0].', '.$output["typing"][1].' and '.$output["typing"][2].' are typing...';
				break;
				default:
					$out = $output["typing"][0].', '.$output["typing"][1].' and other '.(count($output["typing"])- 2).' are typing...';
			}
			$output["typing"] = $out;
		}
		return $output;
	}
	
	function chats($data){
		
		$msg = array();
		
		if(isset($_POST['timestamp'])){
			$timestamp = @date("Y-m-d H:i:s",($_POST['timestamp'] - 1));
		}else{
			$timestamp = @date("Y-m-d H:i:s",'0000000000');
		}
		
		$stmt = $data['pdo']->prepare("SELECT `id`,`mid`,`msg`,`time`,`grp_id`,(SELECT concat(`fname`,' ',`lname`) FROM {$this->dbprefix}users WHERE `id` = `sender_id` limit 1) as username , (SELECT concat(`seens`,'|',`notify`) FROM {$this->dbprefix}group_users WHERE `grupid` = `grp_id` and `users` = `sender_id` limit 1) as status FROM `{$this->dbprefix}msgs` WHERE `id` IN (SELECT max(`id`) FROM `{$this->dbprefix}msgs` WHERE `grp_id` IN (SELECT DISTINCT `grupid` FROM `{$this->dbprefix}group_users` WHERE `users` = :current_user) and time > :time GROUP BY `grp_id`);");
		$this->qfired++;
		$stmt->execute(array(
			'current_user' => $data['user']['id'],
			'time' => $timestamp
		));
		while ($row = $stmt->fetch())
		{
			$sueen = explode('|',$row['status']);
			
			if($sueen[0] != $sueen[1]){
				//unseen
				$status = '<!--spam style="color:#000000;">&#10004;</spam-->';
			}else{
				//seen
				$status = '<!--spam style="color:#3EDB2D;">&#10004;</spam-->';
			}
			$msg[] = array(
				'id' => $row['grp_id'],
				'message' => msgdecode($data,$row['msg']),
				'username' => ucfirst($row['username']),
				'sent_on' => ago($row['time']),
				'status' => $status,
				'url' => urlencode(base64_encode($row['grp_id'])),
			);
			$lastseen = $row['mid'];
		}
		
		//updating message status
		if(isset($lastseen)){
			$stmt = $data['pdo']->prepare("UPDATE `{$this->dbprefix}group_users` SET `notify` = :notify where users = :users and grupid = :grupid;");
			$this->qfired++;
			$stmt->execute(
				array(
					'notify' => $lastseen,
					'users' => $data['user']['id'],
					'grupid' => getGroup($data),
					)
			);
		}
		
		return $msg;
	}
	
	function msg_insert($data,$msg){
		$msg = msgencode($data,$msg);
		$msg = trim($msg);
		$grp_id = getGroup($data);
		if(empty($msg) && empty($grp_id)){
			return false;
		}
		
		// getting group id
		$stmt = $data['pdo']->prepare("SELECT IFNULL(MAX(`mid`) + 1, 0) as mid FROM `{$this->dbprefix}msgs` WHERE `grp_id` = :grp_id;");
		$this->qfired++;
		$stmt->execute(
			array(
				'grp_id' => $grp_id
				)
		);
		while ($row = $stmt->fetch())
		{
			$mid = $row['mid'];
		}
		
		$stmt = $data['pdo']->prepare("INSERT INTO `{$this->dbprefix}msgs` (`msg`,`grp_id`,`sender_id`,`mid`) VALUES (:msg, :grp_id, :sender_id, :mid);");
		$this->qfired++;

		$stmt->execute(
			array(
				'msg' => $msg,
				'grp_id' => $grp_id,
				'sender_id' => $data['user']['id'],
				'mid' => $mid,
			)
		);
		
		$grp = getGroup($data);
		
		if($grp){
			$sql = "UPDATE `{$this->dbprefix}cache` 
			SET `time` = UNIX_TIMESTAMP() 
			WHERE uname = :uname AND
			process = 2;";
			$sql_array = array(
				'uname' => $data['user']['uname']
			);
			$stmt = $data['pdo']->prepare($sql);
			$this->qfired++;
			$stmt->execute($sql_array);	
			$count = $stmt->rowCount();
			if($count == 0){
				// process 2 new msg
				$sql = "INSERT INTO `{$this->dbprefix}cache` (`fname`,`lname`,`time`,`uname`,`group`,`process`,`value`,`dept`,`support_id`)
				VALUES (:fname,:lname,UNIX_TIMESTAMP(),:uname,:group,2,1,:dept,:support_id);";
				$sql_array = array(
					'fname' => $data['user']['fname'],
					'lname' => $data['user']['lname'],
					'uname' => $data['user']['uname'],
					'group' => $grp,
					'dept' => $data['user']['dept'],
					'support_id' => $data['user']['id'],
				);
				$stmt = $data['pdo']->prepare($sql);
				$stmt->execute($sql_array);
			}
		}
	}
	
	function message($data){
		
		$grp_id = getGroup($data);
		if(isset($_POST['first_run'])){
			
			if($_POST['first_run'] == 'true'){
				//runing at first time
				$sql = "SELECT `id`,(select concat(fname,' ',lname) as username from {$this->dbprefix}users where id = sender_id limit 1) as username,`msg`,`time`,`sender_id`,`mid` from {$this->dbprefix}msgs WHERE mid >= 0 and `grp_id` = :grp_id2 and (select count(`id`) FROM `{$this->dbprefix}group_users` WHERE `users` = :user AND `grupid` = :grp_id) != 0 ORDER BY id DESC limit 25;";
				
				$sql_array = array(
					'grp_id2' => $grp_id,
					'user' => $data['user']['id'],
					'grp_id' => $grp_id
				);
				
			}else{
				//runing at all time
				$sql = "SELECT `id`,(select concat(fname,' ',lname) as username from {$this->dbprefix}users where id = sender_id limit 1) as username,`msg`,`time`,`sender_id`,`mid` from {$this->dbprefix}msgs WHERE mid > (select `seens` from `{$this->dbprefix}group_users` where grupid = :grp_id0 and users = :user0 limit 1) and `grp_id` = :grp_id1 and (select count(`id`) FROM `{$this->dbprefix}group_users` WHERE `users` = :user1 AND `grupid` = :grp_id2) != 0 ORDER BY id DESC;";
				$sql_array = array(
					'grp_id0' => $grp_id,
					'user0' => $data['user']['id'],
					'grp_id1' => $grp_id,
					'user1' => $data['user']['id'],
					'grp_id2' => $grp_id,
				);
			}
		}
		
		$stmt = $data['pdo']->prepare($sql);
		$this->qfired++;
		$stmt->execute($sql_array);
		
		$msg = array();
		while ($row = $stmt->fetch())
		{
			if($row['sender_id'] == $data['user']['id']){
				$align = 1;
			}else{
				$align = 0;
			}
			$msg[] = array(
				'offset' => $row['mid'],
				'id' => $row['id'],
				'message' => msgdecode($data,$row['msg']),
				'sent_on' => ago($row['time']),
				'align' => $align,
				'username' => ucfirst($row['username']),
				'dir' => 'u2b',
			);
			
			if(!isset($lastseen)){
				$lastseen = 0;
			}
			
			if($_POST['first_run'] == 'true'){
				$_SESSION['offset'] = $row['mid'];
			}
			
			$lastseen = ($lastseen > $row['mid'])?$lastseen:$row['mid'];
		}
		
		// lastseen is set first time for limit 25 and sets seens to last row witch is selected at first run hance second time msgs are fetch because seen in not equals to last msg id 
		//updating message status
		if(isset($lastseen)){
			$sql = "UPDATE `{$this->dbprefix}group_users` SET `seens` = :seens where users = :users and grupid = :grupid;";
			$sql_array = array(
				'seens' => $lastseen,
				'users' => $data['user']['id'],
				'grupid' => $grp_id,
			);
			$stmt = $data['pdo']->prepare($sql);
			$this->qfired++;
			$stmt->execute($sql_array);
		}
		
		//Get old message
		if(isset($_POST['offset']) && isReq('premsg')){
			$offset = $_POST['offset'];
			if($offset != 'none'){
				//run to get old msgs
				$sql = "SELECT `id`,(select concat(fname,' ',lname) as username from {$this->dbprefix}users where id = sender_id limit 1) as username,`msg`,`time`,`sender_id`,`mid` from {$this->dbprefix}msgs WHERE mid >= 0 and mid < :mid and `grp_id` = :grp_id1 and (select count(`id`) FROM `{$this->dbprefix}group_users` WHERE `users` = :users AND `grupid` = :grp_id2) != 0 ORDER BY id DESC limit 10;";
								
				$sql_array = array(
					'mid' => $offset,
					'grp_id1' => $grp_id,
					'users' => $data['user']['id'],
					'grp_id2' => $grp_id
				);
				
				$stmt = $data['pdo']->prepare($sql);
				$this->qfired++;
				$stmt->execute($sql_array);
				$oldmsg = array();
				while ($row = $stmt->fetch())
				{
					if($row['sender_id'] == $data['user']['id']){
						$align = 1;
					}else{
						$align = 0;
					}
					$oldmsg[] = array(
						'id' => $row['id'],
						'message' => $row['msg'],
						'sent_on' => ago($row['time']),
						'align' => $align,
						'username' => ucfirst($row['username']),
						'dir' => 'b2u',
					);
					$_SESSION['offset'] = $row['mid'];
				}
				$oldmsg = reverse($oldmsg);
				$msg =  array_merge($oldmsg,$msg);
			}
		}
		//===================================================================
		return array_reverse($msg);
	}
	
	function unread($data){
		$sql = "select sum( (select max(mid) from {$this->dbprefix}msgs where `grp_id` = `grupid`) - seens ) as unread from {$this->dbprefix}group_users where users = :user;";
		$stmt = $data['pdo']->prepare($sql);
		$this->qfired++;
		$stmt->execute(
			array(
				'user' => $data['user']['id']
				)
		);
		while ($row = $stmt->fetch())
		{
			return $row['unread'];
		}
	}
	
	function getOffset($data){
		if(isset($data['param'][1])){
			return unserialize(base64_decode(urldecode($data['param'][1])));
		}else{
			return 0;
		}
	}
		
	function lastaccess($data){
		
  		$sql = "UPDATE `{$this->dbprefix}cache` 
		SET `time` = UNIX_TIMESTAMP(),
		`group` = :group
		WHERE uname = :uname AND
		process = 1;";
		$sql_array = array(
			'group' => getGroup($data),
			'uname' => $data['user']['uname']
		);
		$stmt = $data['pdo']->prepare($sql);
		$this->qfired++;
		try {
			$stmt->execute($sql_array);	
		} catch (PDOException $e) {
			if($e->getCode() == 23000){
				alertify::alert("No Such Chat Room Exist");
			}
		}
		$count = $stmt->rowCount();
		if($count == 0){
			$sql = "INSERT INTO `{$this->dbprefix}cache` (`fname`,`lname`,`time`,`uname`,`group`,`process`,`value`,`dept`,`support_id`)
			VALUES (:fname,:lname,UNIX_TIMESTAMP(),:uname,:group,1,1,:dept,:support_id);";
			$sql_array = array(
				'fname' => $data['user']['fname'],
				'lname' => $data['user']['lname'],
				'uname' => $data['user']['uname'],
				'group' => getGroup($data),
				'dept' => $data['user']['dept'],
				'support_id' => $data['user']['id'],
			);
			$stmt = $data['pdo']->prepare($sql);
			$this->qfired++;
			try {
				$stmt->execute($sql_array);	
			} catch (PDOException $e) {
				if($e->getCode() == 23000){
					alertify::alert("Invalid URL");
				}
			}
		} 
	}
	
}