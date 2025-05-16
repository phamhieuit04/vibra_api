<?php

namespace Database\Seeders;

use App\Models\Library;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LibrarySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(Library::all());
		for ($i = $from; $i < $from + 3; $i++) {
			DB::table('libraries')->insert([
				'user_id' => rand(1, 10),
				'playlist_id' => rand(1, 10),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
		for ($i = $from + 3; $i < $from + 6; $i++) {
			DB::table('libraries')->insert([
				'user_id' => rand(1, 10),
				'artist_id' => rand(1, 10),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
		for ($i = $from + 6; $i < $from + 10; $i++) {
			DB::table('libraries')->insert([
				'user_id' => rand(1, 10),
				'song_id' => rand(1, 10),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}
