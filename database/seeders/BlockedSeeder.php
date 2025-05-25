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
		for ($i = $from; $i < $from + 3; $i++) {
			DB::table('blocked')->insert([
				'user_id' => rand(1, 3),
				'song_id' => rand(1, 6),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
		for ($i = $from; $i < $from + 3; $i++) {
			$userId = $i + 1;
			$possibleArtistIds = array_diff([1, 2, 3], [$userId]);
			$possibleArtistIds = array_values($possibleArtistIds);

			$artistId = $possibleArtistIds[array_rand($possibleArtistIds)];

			DB::table('blocked')->insert([
				'user_id' => $userId,
				'artist_id' => $artistId,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}
