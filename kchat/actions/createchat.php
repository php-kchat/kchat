<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class createchat extends action{
	function action($data){
		
		if(!isset($_POST['users'])){
			return true;
		}
		
		$users = array_filter(explode(",",$_POST['users']));
		
		if(empty($users)){
			echo json_encode(array('error' => 'No User Selected'));
			return true;
		}
		
		$users[] = $data['user']['id'];
		
		$groupid = array();
		foreach($users as $guser){
			$groupid[$guser] = $guser;
		}
		
		ksort($groupid);
		$gmd5 = md5(serialize($groupid));
		
		$group = kchat_rand();
		
		$stmt = $data['pdo']->prepare("INSERT INTO `{$this->dbprefix}groups` (`id`,`groupid`) VALUES (:id,:groupid)");
		$stmt->execute(
			array(
				'id' => $group,
				'groupid' => $gmd5,
			)
		);
		
		foreach($users as $user){
			$stmt = $data['pdo']->prepare("INSERT INTO `{$this->dbprefix}group_users` (`grupid`,`users`) VALUES (:grupid,:users)");
			$stmt->execute(
				array(
					'grupid' => $group,
					'users' => $user,
				)
			);
		}
		
		$stmt = $data['pdo']->prepare("INSERT INTO `{$this->dbprefix}msgs` (`msg`,`grp_id`,`sender_id`) VALUES (:msg,:grp_id,:sender_id)");
		$stmt->execute(
			array(
				'msg' => 'You are now connected on KChat',
				'grp_id' => $group,
				'sender_id' => $data['user']['id'],
			)
		);
		
		$url = urlencode(base64_encode($group));
		echo json_encode(array('redirect_to' => $url));
	}
}