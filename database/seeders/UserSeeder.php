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
	private $names = [
		"Kenshi Yonezu",
		"Yoasobi",
		"Billie Eilish",
		// "Jack",
		// "Hoàng Dũng",
		// "Wanuka",
		// "Natori",
		// "Billie Eilish",
		// "Aurora",
		// "Tuấn Ngọc"
	];

	private $emails = [
		"kenshi@gmail.com",
		"yoasobi@gmail.com",
		"billie@gmail.com",
		// "jack@gmail.com",
		// "hoangdung@gmail.com",
		// "wanuka@gmail.com",
		// "natori@gmail.com",
		// "billie@gmail.com",
		// "aurora@gmail.com",
		// "tuanngoc@gmail.com"
	];
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(User::all());
		for ($i = $from; $i < count($this->names); $i++) {
			DB::table('users')->insert([
				'name' => $this->names[$i],
				'email' => $this->emails[$i],
				'password' => Hash::make('12345678'),
				'avatar' => '/default.jpg',
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}