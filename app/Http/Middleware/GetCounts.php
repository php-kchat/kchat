<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
use App\Models\Notifications;

class GetCounts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {		

		session()->forget('chat_id');
		session()->forget('message_id');
		session()->forget('previous');

		$status = Status::select(['status','seen'])->where('uid', Auth()->user()->id)->get()->toArray();
	
		if(count($status) == 0){

			$status = [
				[
					'status' => 'message',
					'seen' => 0,
					'uid' => Auth()->user()->id,
				],
				[
					'status' => 'notification',
					'seen' => 0,
					'uid' => Auth()->user()->id,
				]
			];
			
			DB::table('status')->insert($status);
			
		}
		
		$status = array_combine(array_column($status,'status'),array_column($status,'seen'));
		
		$status['notification'] = Notifications::where('id', '>', $status['notification'])->where('uid', Auth()->user()->id)->count();
		
		//Sharing variable with view
		view()->share('status', $status);
		
		//Setting Attribute to use in Controller
		$request->attributes->set('status', $status);
		
        return $next($request);
    }
}
