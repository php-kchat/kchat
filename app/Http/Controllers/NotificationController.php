<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use KChat\SetStatus;

class NotificationController extends Controller
{
    function notification(Request $request){
		
		$infos = DB::table('notifications')->orderBy('id', 'desc')->where('uid',Auth()->user()->id)->paginate(10);
		
		$pages = range(1, $infos->lastPage());
		
		$last = $infos->items();
		
		if(isset($last[0])){
			
			$last = $last[0]->id;
			
			$status = $request->get('status');
			
			if($status['notification']){
				
				SetStatus::save()->last('notification',$last);
				
			}
		}
        
        if($request->role == 'admin'){
            return view('admin.notifications',compact('infos','pages'));
        }
        
        return view('user.notifications',compact('infos','pages'));
	}
	
    function delete(Request $request){
		
		if(isset($request->ids)){
			
			DB::table('notifications')
			->whereIn('id',$request->ids)
			->where('uid',Auth()->user()->id)
			->delete();
			
		}else{
			
			DB::table('notifications')
			->where('id',$request->id)
			->where('uid',Auth()->user()->id)
			->delete();
			
		}
	}
}
