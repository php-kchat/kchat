<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    function activity(Request $request){
        
		$infos = DB::table('activities')->orderBy('id', 'desc')->where('uid',Auth()->user()->id)->paginate(10);
		
		$pages = range(1, $infos->lastPage());
        
        if($request->role == 'admin'){
            return view('admin.activities',compact('infos','pages'));
        }
        
        return view('user.activities',compact('infos','pages'));
	}
	
    function delete(Request $request){
        
        if(isset($request->id)){
            $request->ids = [$request->id];
        }
        
		if(count($request->ids) == 0){
            return json_encode(['error' => 'No activity is selected']);
        }
        
        DB::table('activities')
        ->whereIn('id',$request->ids)
        ->where('uid',Auth()->user()->id)
        ->delete();
        
        return json_encode([]);
	}
}
