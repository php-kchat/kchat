<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    function messages(Request $request){
		
		$chat = $request->chat;
		
		$conversation[0] = false;
		
		if(empty($chat)){
			
			$chat = '';
			
		}else{
			
			// Checking if user is part of conversation
			$tmp = DB::table('participants')->where(['conversation_id' => $chat,'user_id' => Auth()->user()->id])->get()->toArray();

			if(count($tmp)){

				$conversation = DB::table('conversations')
				->where('id',$chat)
				->get();
				
			}
		}
		
		$conversation = $conversation[0];
		
		return view('msg',compact('chat','conversation'));
	}
    
    function UpdateConversation(Request $request){
        
        // checking if user is participant of conversation also fetching conversation_id
        $tmp = DB::table('participants')->where(['conversation_id' => $request->group_id,'user_id' => Auth()->user()->id])->get()->toArray();
        
        if(count($tmp)){
                
            $data = [];
            
            $file = $request->file('photo');
            
            if(!empty($file)){
                $image_path = $file->getClientOriginalName();
                $path = '/images/' . $image_path;
                $file->move(public_path('/images'), $image_path);
                $data['photo'] = $path;
            }
            
            $data['updated_at'] = now();
            
            if(!empty($request->grpname)){
                $data['conversation_name'] = $request->grpname;
            }
            
            DB::table('conversations')
            ->where('id',$request->group_id)
            ->limit(1)
            ->update($data);
            
        }
        
    }
}
