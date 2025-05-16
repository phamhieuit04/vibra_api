<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(User::all());
		for ($i = $from; $i < $from + 10; $i++) {
			DB::table('users')->insert([
				'name' => fake()->name(),
				'email' => fake()->unique()->safeEmail(),
				'password' => Hash::make('12345678'),
				'avatar' => '/default.jpg',
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}
