<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KchatController extends Controller
{
    function kchat(Request $request){
		
		$data = [];
		
		// If user has opened chat
		if(!empty($request->chat)){
			
			// Insert Message and Update id in conversations
			if(!empty($request->message)){
				
                $type = 0;
                
                if(!empty($request->whiteboard)){
                    $type = 1;
                }
                
				$id = DB::table('messages')->insertGetId([
					'user_id' => Auth()->user()->id,
					'message' => $request->message,
					'conversation_id' => $request->chat,
					'created_at' => now(),
                    'type' => $type,
				]);
				
				DB::table('conversations')->where('id', $request->chat)->update(['message_id' => $id]);
				
			}
			
			// checking if user is participant of conversation also fetching conversation_id
			$tmp = DB::table('participants')->where(['conversation_id' => $request->chat,'user_id' => Auth()->user()->id])->get()->toArray();
			
			if(count($tmp)){
							
                $_tmp = DB::table('messages')
                    ->rightJoin('users', 'messages.user_id', '=', 'users.id')
                    ->rightJoin('conversations', 'messages.conversation_id', '=', 'conversations.id')
                    ->where('conversation_id',$request->chat)
                    ->select('messages.message','messages.type','messages.created_at','users.first_name','users.last_name','users.photo','messages.id','users.id as user_id')
                    ->orderBy('messages.id','DESC');
				
				if (session()->has('message_id')){
                    
                    // This is to load Old Messages
                    if($request->has('previous')){

                        $data['previous'] = true;
                        
                        $_tmp->limit(25);
                        
                        if(session()->has('previous')){
                            session()->put('previous',(session()->get('previous') + 1));
                        }else{
                            session()->put('previous',1);
                        }
                        
                        $_tmp->offset(25*session()->get('previous'));
                        
                    }else{
                        // This is to load New Messages
                        $_tmp->where('messages.id','>',session()->get('message_id'));
                    }
                    
				}else{
                    // limit message on first access
					$_tmp->limit(25);
				}
				
				$data['messages'] = $_tmp->get()->toArray();
				
                // Reverse when its new message only
                if(!$request->has('previous')){
                    $data['messages'] = array_reverse($data['messages']);
                }
			
                foreach($data['messages'] as $i => $v){
                    $data['messages'][$i]->first_name = htmlentities($data['messages'][$i]->first_name);
                    $data['messages'][$i]->last_name = htmlentities($data['messages'][$i]->last_name);
                    $data['messages'][$i]->message = htmlentities($data['messages'][$i]->message);
                }
            
				if(count($data['messages'])){
                    
					if(end($data['messages'])->id > session()->get('message_id')){
                        
                        session()->put('message_id', end($data['messages'])->id);
                        
                        DB::table('participants')->where(['conversation_id' => $request->chat,'user_id' => Auth()->user()->id])->update(['seen' => end($data['messages'])->id]);
                        
                    }
					
				}
				
			}
		}
		
		/*
			This is to show latest messages of all messages
		*/ 
		
		$tmp = DB::table('messages')
			->rightJoin('users', 'messages.user_id', '=', 'users.id')
			->rightJoin('conversations', 'messages.conversation_id', '=', 'conversations.id')
			->rightJoin(DB::raw('(SELECT DISTINCT participants.conversation_id, conversations.* FROM `participants` JOIN conversations ON participants.conversation_id = conversations.id WHERE participants.user_id = '.Auth()->user()->id.') lm'), 'messages.id', '=', 'lm.message_id')
			->select('messages.id as mid','messages.created_at as date','messages.id as mid','messages.message', 'messages.type', 'messages.user_id', 'users.first_name', 'users.last_name', 'users.photo', 'lm.*')
			->orderBy('messages.id','DESC')
            ->orderBy('conversations.id')
            ->limit(50);
        
		if (session()->has('chat_id')){
			$tmp->where('messages.id','>', session()->get('chat_id'));
		}
		
		$data['chats'] = $tmp->get()->toArray();
        
        foreach($data['chats'] as $i => $v){
            $data['chats'][$i]->conversation_name = htmlentities($data['chats'][$i]->conversation_name);
            $data['chats'][$i]->first_name = htmlentities($data['chats'][$i]->first_name);
            $data['chats'][$i]->last_name = htmlentities($data['chats'][$i]->last_name);
            $data['chats'][$i]->message = htmlentities($data['chats'][$i]->message);
        }
		
        $data['chats'] = array_reverse($data['chats']);
        
		if(count($data['chats'])){
            if(end($data['chats'])->mid > session()->get('chat_id')){
                session()->put('chat_id', end($data['chats'])->mid);
            }
		}
        
		//print_r($data);
        
		return json_encode($data);
	}
    
    function getConvo(Request $request){
        
        $tmp = DB::table('conversations')
        ->select('conversations.id','conversations.conversation_name', 'conversations.photo', 'conversations.created_at')
        ->rightJoin('participants', 'participants.conversation_id', '=', 'conversations.id')
        ->where('participants.user_id', Auth()->user()->id)
        ->where('conversations.conversation_name', 'like', '%'.$request->convo_like.'%')
		->limit(25)
        ->get()
        ->toArray();
        
        foreach($tmp as $i => $v){
            $tmp[$i]->conversation_name = htmlentities($tmp[$i]->conversation_name);
        }
        
        return json_encode($tmp);
        
    }
    
    function attachments(Request $request){
        
        // Checking if user is part of conversation
        $tmp = DB::table('participants')->where(['conversation_id' => $request->chat, 'user_id' => Auth()->user()->id])->get()->toArray();

        if(!count($tmp)){
            return false;
        }
        
        $tmp = DB::table('settings')->where(['key' => 'uploadpath'])->get();
        
        $uploadpath = $tmp[0]->value;
        
        $tmp = $request->all();
        
        $json = [];
        
        foreach($tmp['files'] as $file){
            $tmp = [];
            $tmp['Name'] = $file->getClientOriginalName();
            $tmp['uuid'] = Str::uuid()->toString();
            $file->move($uploadpath, $tmp['uuid']);
            $tmp['MimeType'] = explode('/',$file->getClientMimeType());
            $json[] = $tmp;
        }

        $id = DB::table('messages')->insertGetId([
            'user_id' => Auth()->user()->id,
            'message' => json_encode($json),
            'conversation_id' => $request->chat,
            'created_at' => now(),
            'type' => 2,
        ]);
        
        DB::table('conversations')->where('id', $request->chat)->update(['message_id' => $id]);
        
        return true;
    }
}
