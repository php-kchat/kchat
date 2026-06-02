<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    function index(Request $request){
        
        $dates = $this->date_range();
        $values = array_fill(0, count($dates), 0);
        $oneMonthAgo = Carbon::now()->subMonth();
        $userId = Auth()->user()->id;
        
        $current_user_messages_count = DB::table('messages')->where('user_id', $userId)->count();
        
        $current_user_messages_count_this_month = DB::table('messages')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $oneMonthAgo)
            ->count();
        
        $current_user_conversations_count = DB::table('participants')->where('user_id', $userId)->count();
        
        $current_user_new_conversations_this_month = DB::table('participants')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $oneMonthAgo)
            ->count();
        
        $current_user_new_messages_perday = DB::table('messages')
            ->selectRaw('COUNT(*) as count, DATE(`created_at`) as date')
            ->groupByRaw('DATE(`created_at`)')
            ->where('created_at', '>=', $oneMonthAgo)
            ->where('user_id', $userId)
            ->orderBy('date')
            ->get()->toArray();
            
        $current_user_new_conversations_perday = DB::table('messages')
            ->selectRaw('COUNT(DISTINCT `conversation_id`) as count, DATE(`created_at`) as date')
            ->groupByRaw('DATE(`created_at`)')
            ->where('created_at', '>=', $oneMonthAgo)
            ->where('user_id', $userId)
            ->orderBy('date')
            ->get()->toArray();
        
        // For Users
        $tmp = $values;
        foreach($current_user_new_messages_perday as $value){
            $tmp[array_search($value->date,$dates)] = $value->count;
        }
        $current_user_new_messages_perday = $tmp;
        
        // For Messages
        $tmp = $values;
        foreach($current_user_new_conversations_perday as $value){
            $tmp[array_search($value->date,$dates)] = $value->count;
        }
        $current_user_new_conversations_perday = $tmp;

        if($request->role == 'user'){
            return view('user.dashboard',compact('current_user_new_messages_perday','current_user_new_conversations_perday','current_user_new_conversations_this_month','current_user_messages_count_this_month','current_user_messages_count','current_user_conversations_count','dates')); 
        }
        
        $users_count = DB::table('users')->count();
        
        $new_users_this_month = DB::table('users')->where('created_at', '>=', $oneMonthAgo)->count();
        
        $messages_count = DB::table('messages')->count();
        
        $new_messages_this_month = DB::table('messages')->where('created_at', '>=', $oneMonthAgo)->count();
        
        $conversations_count = DB::table('conversations')->count();
        
        $new_conversations_this_month = DB::table('conversations')->where('created_at', '>=', $oneMonthAgo)->count();

        $average_messages_peruser = DB::table('messages')
            ->selectRaw('COUNT(id) as count')
            ->where('created_at', '>=', $oneMonthAgo)
            ->groupBy('user_id');
        
        $average_messages_peruser = DB::table(DB::raw("({$average_messages_peruser->toSql()}) as tb"))
            ->mergeBindings($average_messages_peruser)
            ->selectRaw('SUM(count)/COUNT(*) as avg')
            ->value('avg');
        
        $new_users_perday = DB::table('users')
            ->selectRaw('COUNT(*) as count, DATE(`created_at`) as date')
            ->groupByRaw('DATE(`created_at`)')
            ->where('created_at', '>=', $oneMonthAgo)
            ->orderBy('date')
            ->get()->toArray();
        
        $new_messages_perday = DB::table('messages')
            ->selectRaw('COUNT(*) as count, DATE(`created_at`) as date')
            ->groupByRaw('DATE(`created_at`)')
            ->where('created_at', '>=', $oneMonthAgo)
            ->orderBy('date')
            ->get()->toArray();
        
        $new_conversations_perday = DB::table('conversations')
            ->selectRaw('COUNT(*) as count, DATE(`created_at`) as date')
            ->groupByRaw('DATE(`created_at`)')
            ->where('created_at', '>=', $oneMonthAgo)
            ->orderBy('date')
            ->get()->toArray();
        
        // For Users
        $tmp = $values;
        foreach($new_users_perday as $value){
            $tmp[array_search($value->date,$dates)] = $value->count;
        }
        $new_users_perday = $tmp;
        
        // For Messages
        $tmp = $values;
        foreach($new_messages_perday as $value){
            $tmp[array_search($value->date,$dates)] = $value->count;
        }
        $new_messages_perday = $tmp;
        
        // For Conversions
        $tmp = $values;
        foreach($new_conversations_perday as $value){
            $tmp[array_search($value->date,$dates)] = $value->count;
        }
        
        $new_conversations_perday = $tmp;
        
        return view('admin.dashboard',compact('current_user_new_messages_perday','current_user_new_conversations_perday','current_user_new_conversations_this_month','current_user_messages_count_this_month','current_user_messages_count','current_user_conversations_count','new_conversations_this_month','new_messages_this_month','new_users_this_month','users_count','conversations_count','messages_count','new_users_perday','new_messages_perday','new_conversations_perday','average_messages_peruser','dates'));
    }
    
    function date_range(){
        // Start and end dates
        $start_date = date('Y-m-d',strtotime("-1 Months"));
        $end_date = date('Y-m-d');

        // Initialize empty array to hold dates
        $range = array();

        // Loop through each day and add to date range
        for ($i = strtotime($start_date); $i <= strtotime($end_date); $i += 86400) {
            $range[] = date('Y-m-d', $i);
        }
        
        return $range;
    }
}
