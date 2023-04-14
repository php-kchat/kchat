<?php

use Illuminate\Support\Facades\DB;

class Settings{
	
	static function get($key){
		return DB::table('settings')->where('key',$key)->get();
	}
	
	static function set($key, $value){
		
		$data = [
			'key' => $key,
			'value' => $value,
		];
		
		DB::table('settings')->updateOrInsert(
			['key' => $key], // Check if a record exists with the email
			$data, // If record doesn't exist, insert this data
		);
		
	}
}