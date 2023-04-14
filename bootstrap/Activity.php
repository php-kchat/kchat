<?php

namespace KChat;

use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class ActivityLog{
	
	static function Log(){
		return new self();
	}
	
	function save($title, $notification){
        Activity::create([
            'uid'  =>  Auth()->user()->id,
            'title'  =>  $title,
            'notification'  =>  $notification,
            'created_at' =>  now(),
        ]);
	}
}