<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Hash;
use KChat\ActivityLog;
use KChat\NotificationsLog;

class UserController extends Controller
{
	
    function members(Request $request){
		
		$users = DB::table('users');
		
        if(!empty($request->ms)){
            $ms = $request->ms;
            $users->where('email', 'like', '%'.$request->ms.'%');
        }
        
        $users = $users->paginate(10);
        
		$keys = array_column($users->items(),'id');
		
		$values = array_map([$this,"SecrurePass"],$users->items());
		
		$jsonusers = array_combine($keys, $values);
		
		$pages = range(1, $users->lastPage());
		
        $ms = '';
        
        if(!empty($request->ms)){
            $ms = $request->ms;
        }
        
        return view('members',compact('users','pages','jsonusers','ms'));
	}
	
    function delete_users(Request $request){
		
		if($request->ids == null){
			return false;
		}
		
		if(DB::table('users')->whereIn('id', $request->ids)->delete()){
			
			$emails = implode(", ",array_column(User::select(['email'])->whereIn('id', $request->ids)->get()->toArray(), 'email'));
			
			ActivityLog::log()->save('Deleted','You have Deleted '.$emails.'.');
		}
	}
	
    function set_inactive_users(Request $request){
		
		if($request->ids == null){
			return false;
		}
		
		if(DB::table('users')->whereIn('id', $request->ids)->update(['status' => 'Inactive'])){
			
			$emails = implode(", ",array_column(User::select(['email'])->whereIn('id', $request->ids)->get()->toArray(), 'email'));
			
			ActivityLog::log()->save('Set InActive','You have Set InActive '.$emails.'.');
			
			NotificationsLog::log()->save($request->ids, 'Set InActive',Auth()->user()->email.' Changed your status to InActive');
		}
	}
	
    function set_active_users(Request $request){
		
		if($request->ids == null){
			return false;
		}
		
		if(DB::table('users')->whereIn('id', $request->ids)->update(['status' => 'Active'])){
			
			$emails = implode(", ",array_column(User::select(['email'])->whereIn('id', $request->ids)->get()->toArray(), 'email'));
			
			ActivityLog::log()->save('Set Active','You have Set Active '.$emails.'.');
			
			NotificationsLog::log()->save($request->ids, 'Set Active',Auth()->user()->email.' Changed your status to Active');
		}
	}
	
    function block_users(Request $request){
		
		if($request->ids == null){
			return false;
		}
		
		if(DB::table('users')->whereIn('id', $request->ids)->update(['status' => 'Blocked'])){
			
			$emails = implode(", ",array_column(User::select(['email'])->whereIn('id', $request->ids)->get()->toArray(), 'email'));
			
			ActivityLog::log()->save('Blocked','You have Blocked '.$emails.'.');
			
			NotificationsLog::log()->save($request->ids, 'Blocked',Auth()->user()->email.' Blocked you');
		}
	}
	
    function unblock_users(Request $request){
		
		if($request->ids == null){
			return false;
		}
		
		if(DB::table('users')->whereIn('id', $request->ids)->update(['status' => 'Active'])){
			
			$emails = implode(", ",array_column(User::select(['email'])->whereIn('id', $request->ids)->get()->toArray(), 'email'));
			
			ActivityLog::log()->save('UnBlocked','You have unblocked '.$emails.'.');
			
			NotificationsLog::log()->save($request->ids, 'UnBlocked',Auth()->user()->email.' UnBlocked you');
		}
	}
	
    function Profile(Request $request){
		
        $profile = DB::table('users')->where('id',Auth()->user()->id)->get();
		
        $departments = DB::table('departments')->get();
		
		$profile = $profile[0];
		
        return view('profile',compact('profile','departments'));
		
	}
	
    function NewConversation(Request $request){
		
        $data = [
			'message_id' => '0',
			'created_at' => now(),
			'updated_at' => now(),
		];
        
        if(!empty($request->grpname)){
            $data['conversation_name'] = $request->grpname;
        }
        
		$conversation_id  = DB::table('conversations')->insertGetId($data);
		
		$ids = $request->ids;
		
		$ids[] = Auth()->user()->id;
		
		$ids = array_unique($ids);
		
		if(count($ids) == 1){
			return false;
		}
		
		$data = [];
		
		foreach($ids as $id){
			$data[] = [
				'user_id' => $id,
				'conversation_id' => $conversation_id,
				'created_at' => now(),
				'updated_at' => now(),
			];
		}
		
		DB::table('participants')->insert($data);
		
	}
	
    function SaveProfile(Request $request){
		
		$data = $request->all();
		
        $request->validate([
            'first_name'         =>   'required',
            'last_name'         =>   'required',
            'email'        =>   'required|email',
        ]);
		
		$file = $request->file('photo');
		
		if(!empty($file)){
			$image_path = $file->getClientOriginalName();
			$path = '/images/' . $image_path;
			$file->move(public_path('/images'), $image_path);
			$data['photo'] = $path;
		}else{
			unset($data['photo']);
		}
		
		if(!empty($data['password'])){
			$request->validate([
				'password'     =>   'required|min:6',
			]);
			$data['password'] = Hash::make($data['password']);
			unset($data['repassword'],$data['_token'],$data['email']);
		}else{
			unset($data['password'],$data['repassword'],$data['_token'],$data['email']);
		}
		
		$data['updated_at'] = now();
		
		DB::table('users')
        ->where('id',Auth()->user()->id)
        ->limit(1)
        ->update($data);
		
		ActivityLog::log()->save('Profile','You have successfully updated your profile.');
		
	}
	
	function SecrurePass($UserDetail){
		unset(
			$UserDetail->password, 
			$UserDetail->email_verified_at, 
			$UserDetail->remember_token
		);
		return $UserDetail;
	}
	
}
