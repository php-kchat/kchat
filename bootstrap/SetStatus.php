<?php

namespace KChat;

use Illuminate\Support\Facades\DB;
use App\Models\Notifications;
use App\Models\User;

class SetStatus{
	
	static function save(){
		return new self();
	}
	
	function last($status, $last){
		if(!(empty($last) || empty($status))){
			DB::table('status')
			->where('uid',Auth()->user()->id)
			->where('status',$status)
			->update([
				'seen' => $last,
				'updated_at' => now(),
			]);
		}
	}
}