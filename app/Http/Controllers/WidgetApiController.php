<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WidgetApiController extends Controller
{
    
    /**
     * Initialize widget — validate token and return config
     */
    function init(Request $request){
        
        if(empty($request->token)){
            return response()->json(['error' => 'Token is required'], 400);
        }
        
        $widget = DB::table('chat_widgets')
            ->where('token', $request->token)
            ->where('is_active', 1)
            ->first();
        
        if(!$widget){
            return response()->json(['error' => 'Invalid or inactive widget'], 404);
        }
        
        // Check if any agents are available in this department
        $agentCount = $this->getAvailableAgentsCount($widget->department);
        
        return response()->json([
            'title' => $widget->title,
            'icon' => $widget->icon,
            'color' => $widget->color,
            'position' => $widget->position,
            'language' => $widget->language ?? 'en',
            'agents_online' => $agentCount > 0,
        ]);
    }
    
    /**
     * Start a new chat session — find least-busy agent
     */
    function startChat(Request $request){
        
        if(empty($request->token) || empty($request->visitor_uid)){
            return response()->json(['error' => 'Token and visitor_uid are required'], 400);
        }
        
        $widget = DB::table('chat_widgets')
            ->where('token', $request->token)
            ->where('is_active', 1)
            ->first();
        
        if(!$widget){
            return response()->json(['error' => 'Invalid or inactive widget'], 404);
        }

        $visitorName = $this->cleanVisitorName($request->visitor_name ?? 'Visitor');
        $visitorUserId = $this->ensureVisitorUser($request->visitor_uid, $visitorName);
        $conversationKey = 'widget:' . $widget->id . ':' . $request->visitor_uid;
        $existingConversation = DB::table('conversations')
            ->where(function ($query) use ($conversationKey) {
                $query->where('visitor_key', $conversationKey)
                    ->orWhere('conversation_name', $conversationKey);
            })
            ->first();

        if($existingConversation){
            DB::table('conversations')->where('id', $existingConversation->id)->update([
                'conversation_name' => $visitorName,
                'visitor_key' => $conversationKey,
                'updated_at' => now(),
            ]);

            $agentId = DB::table('participants')
                ->where('conversation_id', $existingConversation->id)
                ->where('user_id', '!=', $visitorUserId)
                ->value('user_id');

            return response()->json([
                'session_id' => $existingConversation->id,
                'agent_name' => $this->getAgentName($agentId),
                'status' => 'existing',
            ]);
        }
        
        // Find least-busy agent in the department
        $agentId = $this->findLeastBusyAgent($widget->department);
        
        if(!$agentId){
            return response()->json([
                'error' => 'No agents are currently available. Please try again later.',
                'agents_online' => false,
            ], 503);
        }

        $sessionId = DB::table('conversations')->insertGetId([
            'conversation_name' => $visitorName,
            'visitor_key' => $conversationKey,
            'photo' => '/logo/KChat_Logo.svg',
            'message_id' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('participants')->insert([
            [
                'user_id' => $agentId,
                'conversation_id' => $sessionId,
                'status' => 'agent',
                'seen' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $visitorUserId,
                'conversation_id' => $sessionId,
                'status' => 'visitor',
                'seen' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        $messageId = DB::table('messages')->insertGetId([
            'user_id' => $agentId,
            'conversation_id' => $sessionId,
            'message' => 'Hello! How can I help you today?',
            'type' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('conversations')->where('id', $sessionId)->update(['message_id' => $messageId]);
        
        return response()->json([
            'session_id' => $sessionId,
            'agent_name' => $this->getAgentName($agentId),
            'status' => 'new',
        ]);
    }
    
    /**
     * Visitor sends a message
     */
    function sendMessage(Request $request){
        
        if(empty($request->session_id) || empty($request->visitor_uid) || empty($request->message)){
            return response()->json(['error' => 'session_id, visitor_uid, and message are required'], 400);
        }

        $visitorUserId = $this->ensureVisitorUser($request->visitor_uid, $request->visitor_name ?? 'Visitor');
        $session = DB::table('participants')
            ->where('conversation_id', $request->session_id)
            ->where('user_id', $visitorUserId)
            ->first();
        
        if(!$session){
            return response()->json(['error' => 'Session not found or closed'], 404);
        }

        $type = !empty($request->whiteboard) ? 1 : 0;
        $msgId = DB::table('messages')->insertGetId([
            'user_id' => $visitorUserId,
            'conversation_id' => $request->session_id,
            'message' => $request->message,
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('conversations')->where('id', $request->session_id)->update(['message_id' => $msgId]);
        
        return response()->json(['id' => $msgId]);
    }

    function sendFile(Request $request){
        if(empty($request->session_id) || empty($request->visitor_uid) || !$request->hasFile('file')){
            return response()->json(['error' => 'session_id, visitor_uid, and file are required'], 400);
        }

        $visitorUserId = $this->ensureVisitorUser($request->visitor_uid, $request->visitor_name ?? 'Visitor');
        $session = DB::table('participants')
            ->where('conversation_id', $request->session_id)
            ->where('user_id', $visitorUserId)
            ->first();

        if(!$session){
            return response()->json(['error' => 'Session not found or closed'], 404);
        }

        $uploadpath = Cache::remember('settings.uploadpath', 3600, function() {
            return DB::table('settings')->where('key', 'uploadpath')->value('value');
        });

        if(!$uploadpath){
            return response()->json(['error' => 'File upload path is not set'], 500);
        }

        $file = $request->file('file');
        $item = [
            'Name' => $file->getClientOriginalName(),
            'uuid' => (string) Str::uuid(),
            'MimeType' => $file->getClientMimeType(),
        ];

        $file->move($uploadpath, $item['uuid']);
        DB::table('files')->insert([
            'Name' => $item['Name'],
            'uuid' => $item['uuid'],
            'MimeType' => $item['MimeType'],
            'conversation_id' => $request->session_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $json = json_encode([$item]);
        $msgId = DB::table('messages')->insertGetId([
            'user_id' => $visitorUserId,
            'conversation_id' => $request->session_id,
            'message' => $json,
            'type' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('conversations')->where('id', $request->session_id)->update(['message_id' => $msgId]);

        return response()->json(['id' => $msgId]);
    }
    
    /**
     * Visitor polls for new messages
     */
    function poll(Request $request){
        
        if(empty($request->session_id) || empty($request->visitor_uid)){
            return response()->json(['error' => 'session_id and visitor_uid are required'], 400);
        }

        $visitorUserId = $this->ensureVisitorUser($request->visitor_uid, $request->visitor_name ?? 'Visitor');
        $session = DB::table('participants')
            ->where('conversation_id', $request->session_id)
            ->where('user_id', $visitorUserId)
            ->first();
        
        if(!$session){
            return response()->json(['error' => 'Session not found'], 404);
        }
        
        $query = DB::table('messages')
            ->where('conversation_id', $request->session_id)
            ->orderBy('id', 'ASC');
        
        if(!empty($request->after_id)){
            $query->where('id', '>', $request->after_id);
        }
        
        $messages = $query->get()->toArray();
        
        foreach($messages as $i => $v){
            // For whiteboard (type=1) and file (type=2) messages, preserve raw JSON
            if ($v->type == 1 || $v->type == 2) {
                $messages[$i]->raw_message = $v->message;
            }
            $messages[$i]->message = htmlentities($messages[$i]->message);
            if ($v->user_id == $visitorUserId) {
                $messages[$i]->sender = 'visitor';
            } else {
                $messages[$i]->sender = 'agent';
            }
        }
        
        return response()->json([
            'messages' => $messages,
            'session_status' => 'open',
        ]);
    }
    
    /**
     * Find the least-busy active agent in a given department
     * "Least busy" = fewest open widget_sessions assigned
     */
    private function findLeastBusyAgent($department){
        
        // Users store department as JSON array like ["Sales","Support"]
        // We need users whose department JSON contains the widget's department
        $agents = DB::table('users')
            ->where('status', 'Active')
            ->whereIn('role', [0, 2]) // admin roles (0=superadmin, 2=admin) and regular users can be agents
            ->where(function($q) use ($department) {
                $q->where('department', 'like', '%"'.$department.'"%')
                  ->orWhere('department', 'like', '%'.$department.'%');
            })
            ->select('users.id')
            ->get()
            ->pluck('id')
            ->toArray();
        
        if(empty($agents)){
            // Also try regular users (role=1) in the department
            $agents = DB::table('users')
                ->where('status', 'Active')
                ->where(function($q) use ($department) {
                    $q->where('department', 'like', '%"'.$department.'"%')
                      ->orWhere('department', 'like', '%'.$department.'%');
                })
                ->select('users.id')
                ->get()
                ->pluck('id')
                ->toArray();
        }
        
        if(empty($agents)){
            return null;
        }
        
        // Count widget conversations for each agent using the shared conversations/messages model.
        $sessionCounts = DB::table('participants as p')
            ->join('conversations as c', 'c.id', '=', 'p.conversation_id')
            ->whereIn('p.user_id', $agents)
            ->where('c.conversation_name', 'like', 'widget:%')
            ->selectRaw('p.user_id as agent_id, COUNT(*) as session_count')
            ->groupBy('p.user_id')
            ->pluck('session_count', 'agent_id')
            ->toArray();
        
        // Find agent with fewest sessions
        $leastBusy = null;
        $minSessions = PHP_INT_MAX;
        
        foreach($agents as $agentId){
            $count = $sessionCounts[$agentId] ?? 0;
            if($count < $minSessions){
                $minSessions = $count;
                $leastBusy = $agentId;
            }
        }
        
        return $leastBusy;
    }
    
    /**
     * Get count of available agents for a department
     */
    private function getAvailableAgentsCount($department){
        return DB::table('users')
            ->where('status', 'Active')
            ->where(function($q) use ($department) {
                $q->where('department', 'like', '%"'.$department.'"%')
                  ->orWhere('department', 'like', '%'.$department.'%');
            })
            ->count();
    }
    
    /**
     * Get agent display name
     */
    private function getAgentName($agentId){
        if(!$agentId) return 'Agent';
        $user = DB::table('users')->where('id', $agentId)->first();
        if(!$user) return 'Agent';
        return $user->first_name . ' ' . $user->last_name;
    }

    /**
     * Create a synthetic visitor user record so the widget uses the normal messages/conversations flow.
     */
    private function ensureVisitorUser($visitorUid, $visitorName = 'Visitor'){
        $email = 'widget-visitor-' . md5($visitorUid) . '@local.invalid';
        $user = DB::table('users')->where('email', $email)->first();

        if ($user) {
            $visitorName = $this->cleanVisitorName($visitorName);
            if ($visitorName !== 'Visitor' && $user->first_name !== $visitorName) {
                DB::table('users')->where('id', $user->id)->update([
                    'first_name' => $visitorName,
                    'updated_at' => now(),
                ]);
            }
            return (int) $user->id;
        }

        return DB::table('users')->insertGetId([
            'first_name' => $this->cleanVisitorName($visitorName),
            'last_name' => 'Guest',
            'email' => $email,
            'password' => bcrypt($visitorUid),
            'status' => 'Active',
            'department' => json_encode([]),
            'role' => 1,
            'photo' => '/logo/KChat_Logo.svg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function cleanVisitorName($visitorName)
    {
        $visitorName = trim(strip_tags((string) $visitorName));
        return mb_substr($visitorName ?: 'Visitor', 0, 120);
    }
    
}
