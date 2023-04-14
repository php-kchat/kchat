<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		/*
        DB::table('status')->insert([
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
		]);
		*/
    }
}
