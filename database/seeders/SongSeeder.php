<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SongSeeder extends Seeder
{
	private $names = [
		"Someone Like You",
		"Thriller",
		"Blinding Lights",
		"Let It Be",
		"I Will Always Love You",
		"Despacito",
		"Lose Yourself",
		"Uptown Funk",
		"Shake It Off",
		"Yesterday"
	];

	private $descriptions = [
		"Bản ballad đầy cảm xúc về sự chia ly và nhớ nhung người cũ.",
		"Ca khúc kinh điển với phần nhạc video mang tính cách mạng.",
		"Một bản synth-pop hiện đại với âm thanh retro và giai điệu lôi cuốn.",
		"Bài hát nhẹ nhàng, cổ vũ niềm tin và sự an ủi trong khó khăn.",
		"Một bản ballad kinh điển về tình yêu vĩnh cửu và sự chia xa.",
		"Ca khúc Latin sôi động làm mưa làm gió toàn cầu.",
		"Một bản rap đầy năng lượng khuyến khích nắm lấy cơ hội duy nhất trong đời.",
		"Giai điệu vui tươi, dễ nhớ, pha trộn giữa funk và pop hiện đại.",
		"Một ca khúc tự tin và vui nhộn, khuyến khích bỏ qua chỉ trích.",
		"Bản ballad ngắn gọn nhưng đầy cảm xúc về tình yêu đã qua."
	];

	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(Song::all());
		for ($i = $from; $i < $from + 10; $i++) {
			DB::table('songs')->insert([
				'name' => $this->names[$i],
				'author_id' => rand(1, 10),
				'category_id' => rand(1, 10),
				'lyrics' => '/default.txt',
				'thumbnail' => '/default.jpg',
				'total_played' => rand(0, 10000),
				'status' => 1,
				'price' => rand(0, 10000),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}
