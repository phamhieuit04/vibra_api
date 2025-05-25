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
				'user_id' => rand(1, 3),
				'playlist_id' => rand(1, 3),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
		for ($i = $from + 3; $i < $from + 6; $i++) {
			$userId = rand(1, 3);
			$possibleArtistIds = array_diff([1, 2, 3], [$userId]);
			$possibleArtistIds = array_values($possibleArtistIds);

			$artistId = $possibleArtistIds[array_rand($possibleArtistIds)];
			DB::table('libraries')->insert([
				'user_id' => $userId,
				'artist_id' => $artistId,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
		for ($i = $from + 6; $i < $from + 10; $i++) {
			DB::table('libraries')->insert([
				'user_id' => rand(1, 3),
				'song_id' => rand(1, 6),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}
