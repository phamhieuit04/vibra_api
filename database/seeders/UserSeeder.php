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

	private $descriptions = [
		'Kenshi Yonezu (米津玄師) là một nghệ sĩ đa tài người Nhật Bản: ca sĩ, nhạc sĩ, nhà sản xuất âm nhạc và họa sĩ minh họa. Sinh ngày 10 tháng 3 năm 1991 tại Tokushima, Nhật Bản, anh bắt đầu sự nghiệp âm nhạc vào năm 2009 dưới nghệ danh Hachi, chuyên sản xuất nhạc Vocaloid với phần mềm Hatsune Miku. Năm 2012, Yonezu ra mắt với tên thật và giọng hát thật qua album Diorama, đánh dấu bước chuyển mình từ nghệ sĩ ẩn danh trên mạng sang ngôi sao nhạc pop nổi tiếng tại Nhật Bản.',
		'YOASOBI là một nhóm nhạc pop Nhật Bản gồm hai thành viên: Ayase (nhạc sĩ, nhà sản xuất) và Ikura (ca sĩ, tên thật là Lilas Ikuta). Ra mắt vào năm 2019, họ nổi bật với mô hình "chuyển thể tiểu thuyết thành âm nhạc", tạo ra các ca khúc dựa trên truyện ngắn từ nền tảng Monogatary.com và các tác phẩm văn học khác.',
		'Billie Eilish Pirate Baird O’Connell, sinh ngày 18 tháng 12 năm 2001 tại Los Angeles, California, là một ca sĩ kiêm nhạc sĩ người Mỹ. Cô nổi tiếng với phong cách âm nhạc độc đáo, kết hợp giữa pop, alternative và electropop, cùng với hình ảnh nghệ thuật đậm chất cá nhân.'
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
				'description' => $this->descriptions[$i],
				'password' => Hash::make('12345678'),
				'avatar' => '/' . FileHelper::getNameFromEmail($user) . '.jpg',
				'email_verified_at' => Carbon::now(),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}