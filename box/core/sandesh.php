<?php

class KChat{
	
	var $global = array();
	var $data = array();
	
	function __construct($data){
		
		$this->data = $data;
		
		if(isset($data['db_host'])){
			
			$opt = array(
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false,
				PDO::MYSQL_ATTR_FOUND_ROWS => TRUE
			);
			
			$this->global['pdo'] = new PDO("mysql:host=".$data['db_host'].";dbname=".$data['db_db'].";port=".$data['db_port'].";charset=utf8", $data['db_user'], $data['db_pass'],$opt);
			
			$guest = getUniqe();
			$stmt = $this->global['pdo']->prepare("SELECT `id`,`group_id`,`guest_id` FROM `{$data['db_prefix']}guest` where guest_id = :guest_id;");
			$stmt->execute(array('guest_id' => $guest));
			$row = $stmt->fetch();

			if(!empty($row['id'])){
				$this->global['id'] = $row['id'];
				$this->global['group_id'] = $row['group_id'];
				$this->global['guest_id'] = $row['guest_id'];
				
				$stmt = $this->global['pdo']->prepare("SELECT * FROM `{$data['db_prefix']}users` where id = :id;");
				$stmt->execute(array('id' => $this->global['id']));
				$row = $stmt->fetch();
				$this->global = array_merge($this->global,$row);
			}
		}
	}
	
	function start($data){
		
		if(
			empty($_POST['kchat_start']) &&
			empty($_POST['kchat_fname']) &&
			empty($_POST['kchat_lname']) &&
			empty($_POST['kchat_email']) &&
			empty($_POST['kchat_dept']) &&
			empty($_POST['kchat_msg'])
		){
			return false;
		}
		
		$guest = getUniqe();
		$id = kchat_rand(); //creating new user id
		$group = kchat_rand(); //creating new group id
		$stmt = $this->global['pdo']->prepare("SELECT `id` FROM `{$data['db_prefix']}guest` where guest_id = :guest_id;");
		$stmt->execute(array('guest_id' => $guest));
		$row = $stmt->fetch();
		if(!empty($row['id'])){
			echo $row['id'];
		}else{
			// INSERT GROUP
			$users = array();
			$users[] = $this->getSupportid($_POST['kchat_dept']);
			$users[] = $id;
			$groupid = array();
			foreach($users as $guser){
				$groupid[$guser] = $guser;
			}
			ksort($groupid);
			$gmd5 = md5(serialize($groupid));
			$stmt = $this->global['pdo']->prepare("INSERT INTO `{$data['db_prefix']}groups` (`id`,`groupid`) VALUES (:id,:groupid);");
			$stmt->execute(
				array(
					'id' => $group,
					'groupid' => $gmd5,
				)
			);
			// INSERT GROUP END
			// INSERT USERS
			$stmt = $this->global['pdo']->prepare("INSERT INTO {$data['db_prefix']}users (`id`,`fname`,`lname`,`uname`,`password`,`role`,`dept`,`email`) VALUES (:id,:fname,:lname,:uname,:password,:role,:dept,:email);");
			$stmt->execute(
				array(
					'id' => $id,
					'uname' => $id,
					'fname' => $_POST['kchat_fname'],
					'lname' => $_POST['kchat_lname'],
					'password' => kchat_rand(),//random password for guest
					'role' => 3,
					'dept' => null,
					'email' => $_POST['kchat_email'],
				)
			);
			// INSERT USERS END
			// INSERT GROUP_USERS
			
			foreach($users as $user){
				$stmt = $this->global['pdo']->prepare("INSERT INTO `{$data['db_prefix']}group_users` (`grupid`,`users`) VALUES (:grupid,:users);");
				$stmt->execute(
					array(
						'grupid' => $group,
						'users' => $user,
					)
				);
			}
			// INSERT GROUP_USERS END
			// INSERT GUEST
			$stmt = $this->global['pdo']->prepare("INSERT INTO {$data['db_prefix']}guest (`id`,`guest_id`,`email`,`ip`,`country_code`,`time_zone`,`latitude`,`longitude`,`group_id`) VALUES (:id,:guest_id,:email,:ip,:country_code,:time_zone,:latitude,:longitude,:group_id);");
			$stmt->execute(
				array(
					'id' => $id,
					'guest_id' => $guest,
					'email' => $_POST['kchat_email'],
					'ip' => getip(),
					'country_code' => 'Unknown',
					'time_zone' => 'Unknown',
					'latitude' => 0,
					'longitude' => 0,
					'group_id' => $group
				)
			);
			// INSERT GUEST END
			// INSERT MSGS
			$stmt = $this->global['pdo']->prepare("INSERT INTO `{$data['db_prefix']}msgs` (`msg`,`grp_id`,`sender_id`, `mid`) VALUES (:msg,:grp_id,:sender_id, 1);");
			$stmt->execute(
				array(
					'msg' => $_POST['kchat_msg'],
					'grp_id' => $group,
					'sender_id' => $id,
				)
			);
			// INSERT MSGS END
			// INSERT PLOTLY
			$x = date('Y-m-d H:00:00');
			$stmt = $this->global['pdo']->prepare("INSERT INTO `{$data['db_prefix']}plotly` (`y`,`x`) VALUES (1,:x) ON DUPLICATE KEY UPDATE y = y + 1;");
			$stmt->execute(array('x' => $x));
			// INSERT PLOTLY END
		}
		
	}
	
	function msg($data){
	
		if(empty($_POST['msg'])){
			return false;
		}
		
		$msg = trim($_POST['msg']);
		
		if(empty($msg)){
			return false;
		}
		
		$grp_id = $this->global['group_id'];
		
		$stmt = $this->global['pdo']->prepare("SELECT IFNULL(MAX(`mid`) + 1, 0) as mid FROM `{$data['db_prefix']}msgs` WHERE `grp_id` = :grp_id;");
		$stmt->execute(
			array(
				'grp_id' => $grp_id
				)
		);
		while ($row = $stmt->fetch())
		{
			$mid = $row['mid'];
		}
		
		$stmt = $this->global['pdo']->prepare("INSERT INTO `{$data['db_prefix']}msgs` (`msg`,`grp_id`,`sender_id`,`mid`) VALUES (:msg, :grp_id,:sender_id,:mid);");
		$stmt->execute(
			array(
				'msg' => msgencode($msg),
				'grp_id' => $grp_id,
				'sender_id' => $this->global['id'],
				'mid' => $mid,
			)
		);
	}
	
	function getmsg($post,$data){
		
		$grp_id = $this->global['group_id'];
		
		//online
		if($grp_id){
			$sql = "UPDATE `{$data['db_prefix']}cache` 
			SET `time` = UNIX_TIMESTAMP() 
			WHERE uname = :uname AND
			process = 1;";
			$sql_array = array(
				'uname' => $this->global['id']
			);
			$stmt = $this->global['pdo']->prepare($sql);
			
			$stmt->execute($sql_array);	
			$count = $stmt->rowCount();
			if($count == 0){
				// process 2 new msg
				$sql = "INSERT INTO `{$data['db_prefix']}cache` (`fname`,`lname`,`time`,`uname`,`group`,`process`,`value`,`dept`,`support_id`)
				VALUES (:fname,:lname,UNIX_TIMESTAMP(),:uname,:group,1,1,:dept,:support_id);";
				$sql_array = array(
					'fname' => $this->global['fname'],
					'lname' => $this->global['lname'],
					'uname' => $this->global['uname'],
					'group' => $grp_id,
					'dept' => $this->global['dept'],
					'support_id' => $this->global['id'],
				);
				$stmt = $this->global['pdo']->prepare($sql);
				$stmt->execute($sql_array);
			}
		}
		
		if(isset($post['first_run'])){
			
			if($post['first_run'] == 'true'){
				//runing at first time
				$sql = "SELECT `id`,(select concat(fname,' ',lname) as username from {$data['db_prefix']}users where id = sender_id limit 1) as username,`msg`,`time`,`sender_id`,`mid` from {$data['db_prefix']}msgs WHERE mid >= 0 and `grp_id` = :grp_id2 and (select count(`id`) FROM `{$data['db_prefix']}group_users` WHERE `users` = :user AND `grupid` = :grp_id) != 0 ORDER BY id DESC limit 25;";
				
				$sql_array = array(
					'grp_id2' => $grp_id,
					'user' => $this->global['id'],
					'grp_id' => $grp_id
				);
				
			}else{
				//runing at all time
				$sql = "SELECT `id`,(select concat(fname,' ',lname) as username from {$data['db_prefix']}users where id = sender_id limit 1) as username,`msg`,`time`,`sender_id`,`mid` from {$data['db_prefix']}msgs WHERE mid > (select `seens` from `{$data['db_prefix']}group_users` where grupid = :grp_id0 and users = :user0 limit 1) and `grp_id` = :grp_id1 and (select count(`id`) FROM `{$data['db_prefix']}group_users` WHERE `users` = :user1 AND `grupid` = :grp_id2) != 0 ORDER BY id DESC;";
				$sql_array = array(
					'grp_id0' => $grp_id,
					'user0' => $this->global['id'],
					'grp_id1' => $grp_id,
					'user1' => $this->global['id'],
					'grp_id2' => $grp_id,
				);
			}
		}
		
		$stmt = $this->global['pdo']->prepare($sql);
		$stmt->execute($sql_array);
		
		$json = array();
		while($row = $stmt->fetch())
		{
			if($row['sender_id'] == $this->global['id']){
				$align = 1;
			}else{
				$align = 0;
			}
			$json[] = array(
				'id' => $row['id'],
				'message' => msgdecode($row['msg']),
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
		
		//updating message status
		if(isset($lastseen)){
			$stmt = $this->global['pdo']->prepare("UPDATE `{$data['db_prefix']}group_users` SET `seens` = :seens where users = :users and grupid = :grupid;");
			$stmt->execute(
				array(
					'seens' => $lastseen,
					'users' => $this->global['id'],
					'grupid' => $grp_id,
					)
			);
		}
		
		//Get old message
		if(isset($_POST['offset']) && isReq('premsg')){
			$offset = $_POST['offset'];
			if($offset != 'none'){
				//run to get old msgs
				$sql = "SELECT `id`,(select concat(fname,' ',lname) as username from {$data['db_prefix']}users where id = sender_id limit 1) as username,`msg`,`time`,`sender_id`,`mid` from {$data['db_prefix']}msgs WHERE mid >= 0 and mid < :mid and `grp_id` = :grp_id1 and (select count(`id`) FROM `{$data['db_prefix']}group_users` WHERE `users` = :users AND `grupid` = :grp_id2) != 0 ORDER BY id DESC limit 10;";
								
				$sql_array = array(
					'mid' => $offset,
					'grp_id1' => $grp_id,
					'users' => $this->global['id'],
					'grp_id2' => $grp_id
				);
				
				$stmt = $this->global['pdo']->prepare($sql);
				$stmt->execute($sql_array);
				$oldmsg = array();
				while ($row = $stmt->fetch())
				{
					if($row['sender_id'] == $this->global['id']){
						$align = 1;
					}else{
						$align = 0;
					}
					$oldmsg[] = array(
						'id' => $row['id'],
						'message' => msgdecode($row['msg']),
						'sent_on' => ago($row['time']),
						'align' => $align,
						'username' => ucfirst($row['username']),
						'dir' => 'b2u',
					);
					$_SESSION['offset'] = $row['mid'];
				}
				$oldmsg = reverse($oldmsg);
				$json =  array_merge($oldmsg,$json);
			}
		}
		
		$response['msg'] = array_reverse($json);
		
		if(isset($_SESSION['offset'])){
			$response["offset"] = $_SESSION['offset'];
		}
		
		return $response;
	}
	
	function js($data){
		$guest = getUniqe();
		$stmt = $this->global['pdo']->prepare("SELECT `id` FROM `{$data['db_prefix']}guest` where guest_id = :guest_id;");
		$stmt->execute(array('guest_id' => $guest));
		$row = $stmt->fetch();
		$global_guest = 'false';
		if(!empty($row['id'])){
			$global_guest = 'true';
		}
		$stmt = $this->global['pdo']->prepare("SELECT `fname`,`lname` FROM `{$data['db_prefix']}users` WHERE `id` = :guest_id;");
		$stmt->execute(array('guest_id' => $row['id']));
		$row = $stmt->fetch();
		if(!empty($row['fname'])){
			$global_name = $row['fname'].' '.$row['lname'];
		}else{
			$global_name = 'KChat';
		}
		_p("global = new Object();\n");
		_p("global.guest = ".$global_guest.";\n");
		_p("global.name = \"".$global_name."\";\n");
		_p("global.heading = \"KChat\";\n");
		_p("global.dept = ");
		$dept = array();
		$stmt = $this->global['pdo']->prepare("SELECT `id`,`dept` FROM `{$data['db_prefix']}department`;");
		$stmt->execute(array());
		$row = $stmt->fetchAll();
		_p(json_encode($row).";\n");	
	}
	
	function css($data){
		$stmt = $this->global['pdo']->prepare("SELECT `selecter`,`value`,`type`,`css` FROM `{$data['db_prefix']}setting`;");
		$stmt->execute(array());
		$css = array();
		$row = $stmt->fetchAll();
		$dcss = array();
		foreach($row as $css){
			$value = '';
			switch($css['type']){
				case 'color':
					$value = '#'.$css['value'];
				break;
				case 'pixel':
					$value = $css['value'].'px';
				break;
			}
			$dcss[$css['selecter']][$css['css']] = $value;
		}
		foreach($dcss as $key => $css){
			_p($key."{\n");
			foreach($css as $k => $v){
				_p("\t$k : $v;\n");
			}
			_p("}\n");
		}
	}
	/*
	return msg id of least active online user of last 20 minut
	*/
	function getSupportid($depart){ //{$data['db_prefix']}
		// supportid user_id
		if(!empty($_SESSION['supportid'])){
			return $_SESSION['supportid'];
		}
		
		$stmt = $this->global['pdo']->prepare("SELECT `sender_id`,count(`msg`) as `msg_count` FROM `{$this->data['db_prefix']}msgs` where `sender_id` IN (select `support_id` from `{$this->data['db_prefix']}temp` where `process` = 1 and `dept` = :depart) order by `sender_id`;");
		
		$stmt->execute(array(
			'depart' => $depart
		));
		
		$ids = array();
		
		while($row = $stmt->fetch()){
			$ids[$row['sender_id']] = $row['msg_count'];
		}
		
		$rids = array_flip($ids);
		
		$_SESSION['supportid'] = $rids[min($ids)];
		
		if(empty($_SESSION['supportid'])){
			return 'KkEtq2SNzvl02OR';
		}
		
		return $_SESSION['supportid'];
	}
	
}

