<?php

namespace Database\Seeders;

use App\Helpers\FileHelper;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
	private $names = [
		"Kenshi Yonezu",
		"Yoasobi",
		"Billie Eilish",
	];

	private $emails = [
		"kenshi@gmail.com",
		"yoasobi@gmail.com",
		"billie@gmail.com",
	];
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(User::all());
		for ($i = $from; $i < count($this->names); $i++) {
			$user = new User();
			$user->email = $this->emails[$i];
			DB::table('users')->insert([
				'name' => $this->names[$i],
				'email' => $this->emails[$i],
				'password' => Hash::make('12345678'),
				'avatar' => '/' . FileHelper::getNameFromEmail($user) . '.jpg',
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}