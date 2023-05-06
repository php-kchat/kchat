<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use KChat\ActivityLog;

class SettingController extends Controller
{
	
    function Setting(Request $request){
        
		if($request->role != 'admin'){
			return false;
        }
        
		$TimeZone = \DateTimeZone::listIdentifiers();
        
		$departments = DB::table('departments')->get();
        
        $settings = DB::table('settings')->get()->toArray();
        
        $tmp = [
            'uploadpath' => '',
            'Timezone' => 'Asia/Kolkata',
        ];
        
        foreach($settings as $setting){
            $tmp[$setting->key] = $setting->value;
        }
        
        $settings = $tmp;
        
        return view('admin.settings',compact('departments','TimeZone','settings'));
	}
	
    function TimeZone(Request $request){
        
		if($request->role != 'admin'){
			return false;
        }
        
		\Settings::set('Timezone',$request->timezone);
		ActivityLog::log()->save('Timezone','You have successfully updated Timezone to  '.$request->timezone);
	}
	
    function AddDepartment(Request $request){
        
		if($request->role != 'admin'){
			return false;
        }
		
        if($request->adddepartment == null){
            return json_encode(array('error' => 'Department field is empty'));
        }
        
		DB::table('departments')->insert(
			['department' => $request->adddepartment]
		);
		
		ActivityLog::log()->save('Setting','You have successfully Added '.$request->adddepartment.' Department.');
		
        return json_encode([]);
	}
	
    function DeleteDepartment(Request $request){
        
		if($request->role != 'admin'){
			return false;
        }
		
		DB::table('departments')->where('department', $request->deletedepartment)->delete();
		
		ActivityLog::log()->save('Setting','You have successfully Deleted '.$request->deletedepartment.' Department.');
	}
	
    function uploadpath(Request $request){
        
		if($request->role != 'admin'){
			return false;
        }
		
        if($request->uploadpath == null){
            return json_encode(array('error' => 'Upload path field is empty'));
        }
        
		\Settings::set('uploadpath',$request->uploadpath);
		
		ActivityLog::log()->save('Setting','You have set upload path to '.$request->uploadpath.'.');
        
        return json_encode([]);
	}
	
}
