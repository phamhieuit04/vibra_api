<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PlaylistSeeder extends Seeder
{
	private $names = [
		"21",
		"Thriller",
		"After Hours",
		"Let It Be",
		"The Bodyguard",
		"Vida",
		"8 Mile",
		"Uptown Special",
		"1989",
		"Help!"
	];

	private $descriptions = [
		"Album đột phá của Adele, kết hợp giữa pop và soul, thể hiện nỗi đau và sự trưởng thành trong tình yêu.",
		"Album huyền thoại của Michael Jackson với nhiều bản hit, định hình lại nền âm nhạc pop toàn cầu.",
		"Album đánh dấu sự chuyển hướng phong cách của The Weeknd, kết hợp giữa synth-pop và âm hưởng retro hiện đại.",
		"Album cuối cùng của The Beatles trước khi tan rã, mang đậm chất nhẹ nhàng và đầy tính biểu tượng.",
		"Album nhạc phim nổi tiếng với các bản ballad cảm động, đưa Whitney Houston lên đỉnh cao sự nghiệp.",
		"Album Latin pop với những bản hit sôi động, mở rộng ảnh hưởng của âm nhạc Latin trên toàn thế giới.",
		"Album nhạc phim với sự góp mặt của Eminem, mang đậm tính tự sự và cảm hứng vượt khó.",
		"Album funk-pop hiện đại với âm thanh tươi vui, giúp Bruno Mars đạt được nhiều giải thưởng lớn.",
		"Album chuyển mình của Taylor Swift sang thể loại pop thuần túy, đánh dấu một kỷ nguyên mới trong sự nghiệp.",
		"Một trong những album quan trọng của The Beatles với phong cách acoustic mộc mạc và lời ca sâu lắng."
	];

	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(Playlist::all());
		for ($i = $from; $i < $from + 10; $i++) {
			DB::table('playlists')->insert([
				'name' => $this->names[$i],
				'description' => $this->descriptions[$i],
				'author_id' => rand(1, 10),
				'thumbnail' => '/default.jpg',
				'type' => rand(1, 2),
				'total_song' => rand(1, 50),
				'price' => rand(0, 10000),
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}
