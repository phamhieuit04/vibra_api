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
		"Lemon",
		"Flamingo",
		"Halzion",
		"Gunjou",
		"My future",
		"Lost cause",
		"Shinunoga E-wa",
		"Send My Love"
	];

	private $descriptions = [
		"Phát hành dưới dạng đĩa đơn vào ngày 14 tháng 3 năm 2018. Bên cạnh đó, đây cũng chính là ca khúc chủ đề của bộ phim truyền hình đình đám Unnatural lên sóng cùng năm",
		"Yonezu bị ảnh hưởng bởi việc nhớ lại những điều từ khi anh ấy uống rượu. Bài hát được kể theo góc nhìn của một người tìm kiếm khoái lạc.",
		"Halzion dựa trên Soredemo, Happy End, một truyện ngắn do Hashizume Shunki viết, đánh dấu lần đầu tiên Yoasobi hợp tác với một tiểu thuyết gia chuyên nghiệp.",
		"Lấy cảm hứng từ bộ truyện tranh Blue Period của Tsubasa Yamaguchi, bài hát được mô tả là 'một bài hát cổ vũ truyền cảm hứng cho người nghe bằng cách đắm chìm vào những gì họ thích và thể hiện những gì họ thấy'.",
		"Một bản ballad R&B và ambient với ảnh hưởng của soul và jazz , lời bài hát đề cập đến một bài ca ngợi tình yêu bản thân và sức mạnh cá nhân. Eilish đã viết bài hát cùng với nhà sản xuất của nó, Finneas O'Connell.",
		"Eilish sử dụng phong cách hát ngân nga. Trong lời bài hát, cô ấy ăn mừng sự chia tay với một người bạn đời cũ kiêu ngạo và thờ ơ, gọi họ là 'lost cause' trong phần điệp khúc .",
		"'Shinunoga E-Wa' là một trong những ca khúc nổi bật nhất trong album HELP EVER HURT NEVER của Fujii Kaze, phát hành năm 2020. Tựa đề bài hát, viết theo kiểu tiếng Nhật cổ, có thể hiểu là 'Thà chết còn hơn' – một cách diễn đạt mãnh liệt về tình yêu.",
		"'Send My Love (To Your New Lover)' là ca khúc thứ ba trong album 25 của Adele, mang màu sắc khác biệt so với những bản ballad trữ tình quen thuộc của cô. Bài hát có giai điệu pop sôi động, mang hơi hướng acoustic và nhịp điệu hiện đại."
	];

	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(Song::all());
		for ($i = $from; $i < $from + 2; $i++) {
			DB::table('songs')->insert([
				'name' => $this->names[$i],
				'author_id' => 1,
				'playlist_id' => 1,
				'category_id' => rand(1, 10),
				'description' => $this->descriptions[$i],
				'lyrics' => '/default.txt',
				'thumbnail' => '/default.jpg',
				'total_played' => 0,
				'status' => 1,
				'price' => 10000,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
		for ($i = $from + 2; $i < $from + 4; $i++) {
			DB::table('songs')->insert([
				'name' => $this->names[$i],
				'author_id' => 2,
				'playlist_id' => 2,
				'category_id' => rand(1, 10),
				'description' => $this->descriptions[$i],
				'lyrics' => '/default.txt',
				'thumbnail' => '/default.jpg',
				'total_played' => 0,
				'status' => 1,
				'price' => 10000,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
		for ($i = $from + 4; $i < $from + 6; $i++) {
			DB::table('songs')->insert([
				'name' => $this->names[$i],
				'author_id' => 3,
				'playlist_id' => 3,
				'category_id' => rand(1, 10),
				'description' => $this->descriptions[$i],
				'lyrics' => '/default.txt',
				'thumbnail' => '/default.jpg',
				'total_played' => 0,
				'status' => 1,
				'price' => 10000,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}

		DB::table('songs')->insert([
			'name' => $this->names[6],
			'author_id' => 4,
			'playlist_id' => 4,
			'category_id' => rand(1, 10),
			'description' => $this->descriptions[6],
			'lyrics' => '/default.txt',
			'thumbnail' => '/default.jpg',
			'total_played' => 0,
			'status' => 1,
			'price' => 10000,
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		]);

		DB::table('songs')->insert([
			'name' => $this->names[7],
			'author_id' => 5,
			'playlist_id' => 5,
			'category_id' => rand(1, 10),
			'description' => $this->descriptions[7],
			'lyrics' => '/default.txt',
			'thumbnail' => '/default.jpg',
			'total_played' => 0,
			'status' => 1,
			'price' => 10000,
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		]);

		for ($i = $from; $i < count($this->names); $i++) {
			$song = Song::find($i + 1);
			$song->thumbnail = '/' . $this->names[$i] . " thumbnail.jpg";
			$song->lyrics = '/' . $this->names[$i] . " lyrics.txt";
			$song->total_played = rand(0, 5000);
			$song->touch();
		}
	}
}
