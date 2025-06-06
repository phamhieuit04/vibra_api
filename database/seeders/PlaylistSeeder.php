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
		"Stray Sheep",
		"The book",
		"Happier than ever",
		"HELP EVER HURT NEVER",
		"25"
	];

	private $descriptions = [
		"Được phát hành vào ngày 5 tháng 8 năm 2020. Tên của album được lấy cảm hứng từ New Testament.",
		"The Book là EP đầu tay của Yoasobi. Phát hành vào ngày 6 tháng 1 năm 2021, thông qua Sony Music Entertainment Japan, cùng ngày với đĩa đơn Kaibutsu, đi kèm với mùa thứ hai của Beastars .",
		"Là album phòng thu thứ hai của Billie Eilish được ra mắt vào ngày 30 tháng 7 năm 2021 bởi hãng đĩa Darkroom và Interscope Records.",
		"HELP EVER HURT NEVER là album phòng thu đầu tay của Fujii Kaze, phát hành vào ngày 20 tháng 5 năm 2020. Album gồm 11 ca khúc do chính anh sáng tác, thể hiện phong cách âm nhạc pha trộn độc đáo giữa pop, R&B, soul và funk.",
		"25 là album phòng thu thứ ba của Adele, phát hành vào ngày 20 tháng 11 năm 2015. Đây là một tác phẩm đánh dấu sự trở lại mạnh mẽ của cô sau thời gian vắng bóng để tập trung cho cuộc sống cá nhân."
	];

	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(Playlist::all());
		for ($i = $from; $i < count($this->names); $i++) {
			DB::table('playlists')->insert([
				'name' => $this->names[$i],
				'description' => $this->descriptions[$i],
				'author_id' => $i + 1,
				'thumbnail' => '/' . $this->names[$i] . ' thumbnail.jpg',
				'type' => 1,
				'total_song' => 2,
				'price' => 10000,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
		for ($i = 4; $i < 6; $i++) {
			$playlist = Playlist::find($i);
			$playlist->total_song = 1;
			$playlist->touch();
		}
	}
}
