<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use KChat\ActivityLog;

class SettingController extends Controller
{
	
    function Setting(Request $request){
		
		$TimeZone = \DateTimeZone::listIdentifiers();
		$departments = DB::table('departments')->get();
        return view('settings',compact('departments','TimeZone'));
	}
	
    function TimeZone(Request $request){
		\Settings::set('Timezone',$request->timezone);
		ActivityLog::log()->save('Timezone','You have successfully updated Timezone to  '.$request->timezone);
	}
	
    function AddDepartment(Request $request){
		
		DB::table('departments')->insert(
			['department' => $request->adddepartment]
		);
		
		ActivityLog::log()->save('Setting','You have successfully Added '.$request->adddepartment.' Department.');
		
	}
	
    function DeleteDepartment(Request $request){
		
		DB::table('departments')->where('department', $request->deletedepartment)->delete();
		
		ActivityLog::log()->save('Setting','You have successfully Deleted '.$request->deletedepartment.' Department.');
	}
	
}
