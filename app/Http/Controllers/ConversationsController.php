<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use KChat\ActivityLog;
use KChat\NotificationsLog;

class ConversationsController extends Controller
{
    function Conversations(Request $request){
        
        $conversations = DB::table('participants')
            ->select('conversations.id as id','conversations.conversation_name as name','conversations.photo as photo','conversations.created_at as created_at',DB::raw('COUNT(p.user_id) as members'))
            ->rightJoin('conversations', 'participants.conversation_id', '=', 'conversations.id')
            ->rightJoin('participants as p', 'p.conversation_id', '=', 'conversations.id')
            ->where('participants.user_id',Auth()->user()->id)
            ->orderBy('conversations.created_at','DESC')
            ->groupBy('p.conversation_id')
            ->paginate(10);

		$pages = range(1, $conversations->lastPage());
        
        if($request->role == 'admin'){
            return view('common.conversations',compact('conversations','pages'));
        }
        
        return view('common.conversations',compact('conversations','pages'));
    }
    
    function delete(Request $request){
        
        if(isset($request->id)){
            $request->ids = [$request->id];
        }
        
		if(count($request->ids) == 0){
            return json_encode(['error' => 'No conversation is selected']);
        }
        
        //This is to ensure that a user can only delete conversations in which he/she is a participant.
        $conversations = DB::table('participants')
        ->select('conversation_id')
        ->where('user_id',Auth()->user()->id)
        ->whereIn('conversation_id',$request->ids)
        ->get()
        ->toArray();
        
        $ids = [];
        
        foreach($conversations as $k => $conversation){
            $ids[] = $conversation->conversation_id;
        }
        
        $conversations = DB::table('conversations')
        ->select('id','conversation_name')
        ->whereIn('id',$ids)
        ->get()
        ->toArray();
        
        $conversation_name = [];
        
        foreach($conversations as $conversation){
            $conversation_name[$conversation->id] = $conversation->conversation_name;
        }
        
        $participants = DB::table('participants')
        ->select('conversation_id','user_id')
        ->whereIn('conversation_id',$ids)
        ->get()
        ->toArray();
        
        ActivityLog::log()->save('Conversation','You have deleted conversation '.implode(',',$conversation_name).'.');
        
        DB::table('conversations')
        ->whereIn('id',$ids)
        ->delete();
        
        $tmp = [];
        
        foreach($participants as $participant){
            $tmp[$conversation_name[$participant->conversation_id]][] = $participant->user_id;
        }
        
        foreach($tmp as $group => $ids){
            NotificationsLog::log()->save($ids,'Conversation',Auth()->user()->email.' have deleted conversation '.$group.'.');
        }
        
        return json_encode($participants);
	}
}
