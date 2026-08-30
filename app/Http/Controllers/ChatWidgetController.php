<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use KChat\ActivityLog;

class ChatWidgetController extends Controller
{
    
    function index(Request $request){
        
        if($request->role != 'admin'){
            return false;
        }
        
        $widgets = DB::table('chat_widgets')->orderBy('id', 'DESC')->get();
        
        $departments = DB::table('departments')->get();
        
        $appUrl = config('app.url');
        
        return view('admin.chat_widget', compact('widgets', 'departments', 'appUrl'));
    }
    
    function store(Request $request){
        
        if($request->role != 'admin'){
            return false;
        }
        
        if(empty($request->widget_title)){
            return json_encode(array('error' => 'Widget title is required'));
        }
        
        if(empty($request->widget_department)){
            return json_encode(array('error' => 'Please select a department'));
        }
        
        $data = [
            'title' => $request->widget_title,
            'icon' => $request->widget_icon ?? 'fa-comments',
            'department' => $request->widget_department,
            'color' => $request->widget_color ?? '#007bff',
            'position' => $request->widget_position ?? 'right',
            'is_active' => 1,
            'updated_at' => now(),
        ];
        
        if(!empty($request->widget_id)){
            // Update existing widget
            DB::table('chat_widgets')->where('id', $request->widget_id)->update($data);
            ActivityLog::log()->save('Chat Widget', 'You have updated chat widget "'.$request->widget_title.'".');
        } else {
            // Create new widget
            $data['token'] = Str::random(48);
            $data['created_at'] = now();
            DB::table('chat_widgets')->insert($data);
            ActivityLog::log()->save('Chat Widget', 'You have created chat widget "'.$request->widget_title.'".');
        }
        
        return json_encode([]);
    }
    
    function delete(Request $request){
        
        if($request->role != 'admin'){
            return false;
        }
        
        if(empty($request->widget_id)){
            return json_encode(array('error' => 'Widget ID is required'));
        }
        
        $widget = DB::table('chat_widgets')->where('id', $request->widget_id)->first();
        
        if($widget){
            DB::table('chat_widgets')->where('id', $request->widget_id)->delete();
            ActivityLog::log()->save('Chat Widget', 'You have deleted chat widget "'.$widget->title.'".');
        }
        
        return json_encode([]);
    }
    
    function widgetChats(Request $request){
        
        $userId = Auth()->user()->id;
        
        // Get widget conversations assigned to the current agent using the shared chat tables.
        $sessions = DB::table('participants as p')
            ->join('conversations as c', 'c.id', '=', 'p.conversation_id')
            ->leftJoin(DB::raw('(SELECT conversation_id, MAX(id) as last_msg_id, MAX(created_at) as last_msg_time FROM messages GROUP BY conversation_id) as lm'), 'c.id', '=', 'lm.conversation_id')
            ->leftJoin('messages as last_msg', 'lm.last_msg_id', '=', 'last_msg.id')
            ->leftJoin(DB::raw('(SELECT conversation_id, user_id FROM participants WHERE user_id != ' . (int) $userId . ') as partic'), 'partic.conversation_id', '=', 'c.id')
            ->leftJoin('users as visitor_user', 'visitor_user.id', '=', 'partic.user_id')
            ->join('chat_widgets as cw', DB::raw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(c.conversation_name, ":", 2), ":", -1) AS UNSIGNED)'), '=', 'cw.id')
            ->where('p.user_id', $userId)
            ->where('c.conversation_name', 'like', 'widget:%')
            ->select(
                'c.id as id',
                DB::raw("COALESCE(visitor_user.first_name, 'Visitor') as visitor_name"),
                DB::raw("COALESCE(visitor_user.last_name, '') as visitor_last_name"),
                DB::raw("'open' as status"),
                'c.created_at as created_at',
                'cw.title as widget_title',
                'last_msg.message as last_message',
                DB::raw("CASE WHEN last_msg.user_id = p.user_id THEN 'agent' ELSE 'visitor' END as last_sender"),
                'lm.last_msg_time'
            )
            ->orderBy('lm.last_msg_time', 'DESC')
            ->limit(50)
            ->get();

        foreach($sessions as $session){
            $session->visitor_name = trim(($session->visitor_name ?? 'Visitor') . ' ' . ($session->visitor_last_name ?? '')) ?: 'Visitor';
        }
        
        $activeSession = null;
        $messages = [];
        
        if($request->has('session') && $request->session){
            $activeSession = DB::table('participants as p')
                ->join('conversations as c', 'c.id', '=', 'p.conversation_id')
                ->join('chat_widgets as cw', DB::raw('CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(c.conversation_name, ":", 2), ":", -1) AS UNSIGNED)'), '=', 'cw.id')
                ->leftJoin(DB::raw('(SELECT conversation_id, user_id FROM participants WHERE user_id != ' . (int) $userId . ') as partic'), 'partic.conversation_id', '=', 'c.id')
                ->leftJoin('users as visitor_user', 'visitor_user.id', '=', 'partic.user_id')
                ->where('p.user_id', $userId)
                ->where('c.id', $request->session)
                ->where('c.conversation_name', 'like', 'widget:%')
                ->select('c.*', 'cw.title as widget_title', DB::raw("COALESCE(visitor_user.first_name, 'Visitor') as visitor_name"))
                ->first();
            
            if($activeSession){
                $messages = DB::table('messages as m')
                    ->leftJoin('users as u', 'u.id', '=', 'm.user_id')
                    ->where('m.conversation_id', $activeSession->id)
                    ->select('m.*', DB::raw("CASE WHEN m.user_id = " . (int) $userId . " THEN 'agent' ELSE 'visitor' END as sender"))
                    ->orderBy('m.id', 'ASC')
                    ->get()
                    ->toArray();
            }
        }
        
        return view('admin.widget_chats', compact('sessions', 'activeSession', 'messages'));
    }
    
    function widgetChatMessages(Request $request){
        
        $userId = Auth()->user()->id;
        
        if(empty($request->session_id)){
            return json_encode(['error' => 'Session ID is required']);
        }
        
        // Verify agent owns this session via the shared conversation schema.
        $session = DB::table('participants as p')
            ->where('p.user_id', $userId)
            ->where('p.conversation_id', $request->session_id)
            ->first();
        
        if(!$session){
            return json_encode(['error' => 'Session not found']);
        }
        
        // If agent is sending a message
        if(!empty($request->message)){
            $type = !empty($request->whiteboard) ? 1 : 0;
            $msgId = DB::table('messages')->insertGetId([
                'user_id' => $userId,
                'conversation_id' => $request->session_id,
                'message' => $request->message,
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('conversations')->where('id', $request->session_id)->update(['message_id' => $msgId]);
        }
        
        // Fetch messages (optionally after a given ID for polling)
        $query = DB::table('messages as m')
            ->leftJoin('users as u', 'u.id', '=', 'm.user_id')
            ->where('m.conversation_id', $request->session_id)
            ->select('m.*', DB::raw("CASE WHEN m.user_id = " . (int) $userId . " THEN 'agent' ELSE 'visitor' END as sender"))
            ->orderBy('m.id', 'ASC');
        
        if(!empty($request->after_id)){
            $query->where('m.id', '>', $request->after_id);
        }
        
        $messages = $query->get()->toArray();
        
        foreach($messages as $i => $v){
            $messages[$i]->message = htmlentities($messages[$i]->message);
        }
        
        return json_encode(['messages' => $messages, 'session_status' => 'open']);
    }
    
    function closeSession(Request $request){
        
        $userId = Auth()->user()->id;
        
        if(empty($request->session_id)){
            return json_encode(['error' => 'Session ID is required']);
        }
        
        DB::table('conversations')
            ->where('id', $request->session_id)
            ->whereExists(function($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('participants')
                    ->whereColumn('participants.conversation_id', 'conversations.id')
                    ->where('participants.user_id', $userId);
            })
            ->update(['updated_at' => now()]);
        
        return json_encode([]);
    }
    
}
