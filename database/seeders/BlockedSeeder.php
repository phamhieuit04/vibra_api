<?php

namespace Database\Seeders;

use App\Models\Blocked;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BlockedSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(Blocked::all());
		for ($i = $from; $i < $from + 5; $i++) {
			DB::table('blocked')->insert([
				'user_id' => rand(1, 10),
				'song_id' => rand(1, 10),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
		for ($i = $from + 5; $i < $from + 10; $i++) {
			DB::table('blocked')->insert([
				'user_id' => rand(1, 10),
				'artist_id' => rand(1, 10),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}
