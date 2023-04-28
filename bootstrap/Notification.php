<?php

namespace KChat;

use Illuminate\Support\Facades\DB;
use App\Models\Notifications;
use App\Models\User;

class NotificationsLog{
	
	static function Log(){
		return new self();
	}
	
	function save($uid, $title, $notification){
		
		if(!is_array($uid)){
			$uid = [$uid];
		}
		
		$users = User::select(['id as uid'])->whereIn('id',$uid)->get()->toArray();
		
		foreach ($users as &$user) {
			$user['title'] = $title;
			$user['notification'] = $notification;
			$user['photo'] = Auth()->user()->photo;
			$user['created_at'] = now();
			$user['updated_at'] = now();
		}
		
		Notifications::insert($users);
	}
}