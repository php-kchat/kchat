<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function index(Request $request){

        $users_count = DB::table('users')->count();
        
        $new_users_this_month = DB::table('users')->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))->count();
        
        $messages_count = DB::table('messages')->count();
        
        $new_messages_this_month = DB::table('messages')->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))->count();
        
        $conversations_count = DB::table('conversations')->count();
        
        $new_conversations_this_month = DB::table('conversations')->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))->count();

        $average_messages_peruser = DB::select('SELECT SUM(`count`)/count(*) as avg FROM (SELECT count(`id`) as count, user_id FROM messages where created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY `user_id`) as tb;');
        
        $average_messages_peruser = $average_messages_peruser[0]->avg;
        
        $dates = $this->date_range();
        $values = array_fill(0, count($dates), 0);
        
        $new_users_perday = DB::table('users')
            ->select(DB::raw('COUNT(*) as count'), DB::raw('DATE(`created_at`) as date'))
            ->groupBy(DB::raw('DATE(`created_at`)'))
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))
            ->orderBy('date')
            ->get()->toArray();
        
        $new_messages_perday = DB::table('messages')
            ->select(DB::raw('COUNT(*) as count'), DB::raw('DATE(`created_at`) as date'))
            ->groupBy(DB::raw('DATE(`created_at`)'))
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))
            ->orderBy('date')
            ->get()->toArray();
        
        $new_conversations_perday = DB::table('conversations')
            ->select(DB::raw('COUNT(*) as count'), DB::raw('DATE(`created_at`) as date'))
            ->groupBy(DB::raw('DATE(`created_at`)'))
            ->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))
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

        //total messages per user per date
        //SELECT COUNT(*),`user_id`,DATE(`created_at`) FROM `messages` GROUP BY DATE(`created_at`), `user_id`;
        
        //,compact('chat','conversation')
        return view('dashboard',compact('new_conversations_this_month','new_messages_this_month','new_users_this_month','users_count','conversations_count','messages_count','new_users_perday','new_messages_perday','new_conversations_perday','average_messages_peruser','dates'));
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
